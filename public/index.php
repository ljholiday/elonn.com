<?php

declare(strict_types=1);

use Elonn\Site\ApiClient;
use Elonn\Site\Response;
use Elonn\Site\Router;
use Elonn\Site\View;
use Dotenv\Dotenv;

define('BASE_PATH', dirname(__DIR__));

require BASE_PATH . '/vendor/autoload.php';

Dotenv::createImmutable(BASE_PATH)->safeLoad();
$config = require BASE_PATH . '/config/config.php';

redirectToHttps();

$apiBaseUrl = $config['services']['api_base_url'];
$cookieDomain = $config['auth']['cookie_domain'];
$worldUrl = $config['products']['world_url'];
$api = new ApiClient($apiBaseUrl);
$router = new Router();

$router->get('/', static function () use ($api, $worldUrl): void {
    renderPage('Elonn', 'home.php', [
        'identity' => currentIdentity($api),
        'worldUrl' => $worldUrl,
        'error' => errorMessage($_GET['error'] ?? null),
    ]);
});

$router->get('/account/login', static function () use ($api, $worldUrl): void {
    $returnTo = allowedReturnTo($_GET['return_to'] ?? null);
    $identity = currentIdentity($api);
    if ($identity !== null) {
        Response::redirect($returnTo ?? $worldUrl);
        return;
    }

    renderPage('Log in', 'account/login.php', [
        'identity' => null,
        'worldUrl' => $worldUrl,
        'error' => errorMessage($_GET['error'] ?? null),
        'return_to' => $returnTo,
        'old' => [],
    ]);
});

$router->get('/login', static function (): void {
    $query = $_SERVER['QUERY_STRING'] ?? '';
    Response::redirect('/account/login' . (is_string($query) && $query !== '' ? '?' . $query : ''));
});

$router->post('/account/login', static function () use ($api, $cookieDomain, $worldUrl): void {
    $email = normalizeEmail($_POST['email'] ?? null);
    $password = is_string($_POST['password'] ?? null) ? $_POST['password'] : '';
    $returnTo = allowedReturnTo($_POST['return_to'] ?? null);

    if ($email === null || $password === '') {
        renderPage('Log in', 'account/login.php', [
            'identity' => null,
            'worldUrl' => $worldUrl,
            'error' => 'Email and password are required.',
            'return_to' => $returnTo,
            'old' => formOld(['email']),
        ], 400);
        return;
    }

    $result = $api->login($email, $password);
    if (!$result['ok'] || !isset($result['token'], $result['expires_at'])) {
        renderPage('Log in', 'account/login.php', [
            'identity' => null,
            'worldUrl' => $worldUrl,
            'error' => 'Invalid email or password.',
            'return_to' => $returnTo,
            'old' => formOld(['email']),
        ], 401);
        return;
    }

    setAuthCookie($result['token'], $result['expires_at'], $cookieDomain);
    Response::redirect($returnTo ?? $worldUrl);
});

$router->post('/login', static function () use ($api, $cookieDomain, $worldUrl): void {
    $email = normalizeEmail($_POST['email'] ?? null);
    $password = is_string($_POST['password'] ?? null) ? $_POST['password'] : '';
    $returnTo = allowedReturnTo($_POST['return_to'] ?? null);

    if ($email === null || $password === '') {
        renderPage('Log in', 'account/login.php', [
            'identity' => null,
            'worldUrl' => $worldUrl,
            'error' => 'Email and password are required.',
            'return_to' => $returnTo,
            'old' => formOld(['email']),
        ], 400);
        return;
    }

    $result = $api->login($email, $password);
    if (!$result['ok'] || !isset($result['token'], $result['expires_at'])) {
        renderPage('Log in', 'account/login.php', [
            'identity' => null,
            'worldUrl' => $worldUrl,
            'error' => 'Invalid email or password.',
            'return_to' => $returnTo,
            'old' => formOld(['email']),
        ], 401);
        return;
    }

    setAuthCookie($result['token'], $result['expires_at'], $cookieDomain);
    Response::redirect($returnTo ?? $worldUrl);
});

$router->get('/account/register', static function () use ($api, $worldUrl): void {
    $returnTo = allowedReturnTo($_GET['return_to'] ?? null);
    $identity = currentIdentity($api);
    if ($identity !== null) {
        Response::redirect($returnTo ?? $worldUrl);
        return;
    }

    renderPage('Create account', 'account/register.php', [
        'identity' => null,
        'worldUrl' => $worldUrl,
        'error' => errorMessage($_GET['error'] ?? null),
        'return_to' => $returnTo,
        'old' => [],
    ]);
});

$router->post('/account/register', static function () use ($api, $cookieDomain, $worldUrl): void {
    $email = normalizeEmail($_POST['email'] ?? null);
    $password = is_string($_POST['password'] ?? null) ? $_POST['password'] : '';
    $displayName = cleanOptionalString($_POST['display_name'] ?? null);
    $returnTo = allowedReturnTo($_POST['return_to'] ?? null);

    if ($email === null || $password === '') {
        renderPage('Create account', 'account/register.php', [
            'identity' => null,
            'worldUrl' => $worldUrl,
            'error' => 'Email and password are required.',
            'return_to' => $returnTo,
            'old' => formOld(['display_name', 'email']),
        ], 400);
        return;
    }

    $register = $api->register($email, $password, $displayName);
    if (!$register['ok']) {
        renderPage('Create account', 'account/register.php', [
            'identity' => null,
            'worldUrl' => $worldUrl,
            'error' => $register['status'] === 409 ? 'An account already exists for that email.' : 'Unable to create account.',
            'return_to' => $returnTo,
            'old' => formOld(['display_name', 'email']),
        ], $register['status'] === 409 ? 409 : 500);
        return;
    }

    $login = $api->login($email, $password);
    if (!$login['ok'] || !isset($login['token'], $login['expires_at'])) {
        Response::redirect('/account/login');
        return;
    }

    setAuthCookie($login['token'], $login['expires_at'], $cookieDomain);
    Response::redirect($returnTo ?? $worldUrl);
});

$router->get('/start', static function () use ($api, $config, $worldUrl): void {
    $identity = currentIdentity($api);
    if ($identity === null) {
        Response::redirect('/account/login?return_to=' . rawurlencode('/start'));
        return;
    }

    renderPage('Elonn Start', 'start.php', [
        'identity' => $identity,
        'worldUrl' => $worldUrl,
        'timeUrl' => productUrl($config, 'time'),
        'error' => null,
    ]);
});

$router->get('/account', static function () use ($api, $worldUrl): void {
    $identity = currentIdentity($api);
    if ($identity === null) {
        Response::redirect('/account/login');
        return;
    }

    renderPage('Account', 'account/account.php', [
        'identity' => $identity,
        'worldUrl' => $worldUrl,
        'error' => null,
    ]);
});

$router->get('/privacy', static function () use ($api, $worldUrl): void {
    renderPage('Privacy', 'legal.php', [
        'identity' => currentIdentity($api),
        'worldUrl' => $worldUrl,
        'heading' => 'Privacy',
        'body' => [
            'Elonn uses account information to operate identity, account access, and connected product services.',
            'Authentication cookies are used to keep you signed in across Elonn services. Do not share account credentials or tokens.',
            'For privacy questions, contact hello@elonn.com.',
        ],
    ]);
});

$router->get('/terms', static function () use ($api, $worldUrl): void {
    renderPage('Terms', 'legal.php', [
        'identity' => currentIdentity($api),
        'worldUrl' => $worldUrl,
        'heading' => 'Terms',
        'body' => [
            'Use Elonn services responsibly and only with accounts you control.',
            'Preview services may change as the platform develops. Production services should preserve account and identity boundaries.',
            'For service questions, contact hello@elonn.com.',
        ],
    ]);
});

$router->post('/account/logout', static function () use ($api, $cookieDomain): void {
    $token = authToken();
    if ($token !== null) {
        $api->logout($token);
    }

    clearAuthCookie($cookieDomain);
    Response::redirect('/account/login');
});

$router->dispatch(
    $_SERVER['REQUEST_METHOD'] ?? 'GET',
    parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/'
);

/**
 * @param array<string, mixed> $data
 */
function renderPage(string $title, string $template, array $data, int $status = 200): void
{
    View::render('layout.php', [
        'title' => $title,
        'template' => $template,
        'data' => $data,
        'identity' => $data['identity'] ?? null,
        'worldUrl' => $data['worldUrl'] ?? '#',
    ], $status);
}

/**
 * @param array{products: array<string, string>} $config
 */
function productUrl(array $config, string $product): string
{
    return match ($product) {
        'time' => $config['products']['time_url'] ?? '#',
        default => '#',
    };
}

function authToken(): ?string
{
    $token = $_COOKIE['elonn_api_token'] ?? null;
    if (!is_string($token)) {
        return null;
    }

    $token = trim($token);
    return $token === '' ? null : $token;
}

/**
 * @return array{id: string, email: string, display_name: string|null}|null
 */
function currentIdentity(ApiClient $api): ?array
{
    $token = authToken();
    return $token === null ? null : $api->me($token);
}

function normalizeEmail(mixed $email): ?string
{
    if (!is_string($email)) {
        return null;
    }

    $email = strtolower(trim($email));
    return filter_var($email, FILTER_VALIDATE_EMAIL) === false ? null : $email;
}

function cleanOptionalString(mixed $value): ?string
{
    if (!is_string($value)) {
        return null;
    }

    $value = trim($value);
    return $value === '' ? null : $value;
}

/**
 * @param array<int, string> $keys
 * @return array<string, string>
 */
function formOld(array $keys): array
{
    $old = [];
    foreach ($keys as $key) {
        $value = $_POST[$key] ?? '';
        $old[$key] = is_string($value) ? $value : '';
    }

    return $old;
}

function setAuthCookie(string $token, string $expiresAt, string $domain): void
{
    setcookie('elonn_api_token', $token, [
        'expires' => strtotime($expiresAt) ?: 0,
        'path' => '/',
        'domain' => $domain,
        'secure' => isHttps(),
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
}

function clearAuthCookie(string $domain): void
{
    setcookie('elonn_api_token', '', [
        'expires' => time() - 3600,
        'path' => '/',
        'domain' => $domain,
        'secure' => isHttps(),
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
}

function allowedReturnTo(mixed $value): ?string
{
    if (!is_string($value) || trim($value) === '') {
        return null;
    }

    $value = trim($value);
    if (str_starts_with($value, '/')) {
        return $value;
    }

    $parts = parse_url($value);
    if (!is_array($parts)) {
        return null;
    }

    $scheme = strtolower((string) ($parts['scheme'] ?? ''));
    $host = strtolower((string) ($parts['host'] ?? ''));
    if (!in_array($scheme, ['http', 'https'], true)) {
        return null;
    }

    return in_array($host, [
        'elonn.local',
        'api.elonn.local',
        'time.elonn.local',
        'web.elonn.local',
        'world.elonn.local',
        'social.elonn.local',
        'admin.elonn.local',
        'elonn.com',
        'api.elonn.com',
        'time.elonn.com',
        'web.elonn.com',
        'world.elonn.com',
        'social.elonn.com',
        'admin.elonn.com',
    ], true) ? $value : null;
}

function isHttps(): bool
{
    return ($_SERVER['HTTPS'] ?? '') === 'on'
        || ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https';
}

function redirectToHttps(): void
{
    if (isHttps()) {
        return;
    }

    $host = $_SERVER['HTTP_HOST'] ?? '';
    if (!is_string($host) || $host === '') {
        return;
    }

    $uri = $_SERVER['REQUEST_URI'] ?? '/';
    http_response_code(308);
    header('Location: https://' . $host . (is_string($uri) ? $uri : '/'));
    exit;
}

function errorMessage(mixed $error): ?string
{
    return match ($error) {
        'missing_fields' => 'Email and password are required.',
        'invalid_login' => 'Invalid email or password.',
        'user_exists' => 'An account already exists for that email.',
        'register_failed' => 'Unable to create account.',
        'login_failed' => 'Unable to log in.',
        default => null,
    };
}
