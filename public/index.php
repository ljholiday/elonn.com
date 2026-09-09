<?php

declare(strict_types=1);

use Elonn\Site\Response;
use Elonn\Site\Router;
use Elonn\Site\View;
use Dotenv\Dotenv;

define('BASE_PATH', dirname(__DIR__));

require BASE_PATH . '/vendor/autoload.php';

Dotenv::createImmutable(BASE_PATH)->safeLoad();
$config = require BASE_PATH . '/config/config.php';

redirectToHttps($config['app']['url']);

// elonn.com is public information pages only -- home, investors, contact, privacy, terms -- and
// links out to the Runtimes. It owns no identity or member state: no login, no register, no
// account, no ApiClient (decision.member_surface_consolidation_20260908). Members sign in from
// a Runtime, which renders api.elonn's login screen.
$webUrl = $config['products']['web_url'];
$contactTo = $config['contact']['to'];
$router = new Router();

$router->get('/', static function () use ($webUrl): void {
    renderPage('Elonn', 'home.php', [
        'webUrl' => $webUrl,
    ]);
});

$router->get('/investors', static function () use ($webUrl): void {
    renderPage('For Investors', 'investors.php', [
        'webUrl' => $webUrl,
    ]);
});

$router->get('/contact', static function () use ($webUrl): void {
    renderPage('Contact', 'contact.php', [
        'webUrl' => $webUrl,
        'notice' => contactMessage($_GET['notice'] ?? null),
        'error' => contactMessage($_GET['error'] ?? null),
        'topic' => cleanOptionalString($_GET['topic'] ?? null) ?? 'general',
        'old' => [],
    ]);
});

$router->post('/contact', static function () use ($webUrl, $contactTo): void {
    // Honeypot: real visitors never fill this hidden field.
    if (cleanOptionalString($_POST['company'] ?? null) !== null) {
        Response::redirect('/contact?notice=sent');
        return;
    }

    $name = cleanOptionalString($_POST['name'] ?? null);
    $email = normalizeEmail($_POST['email'] ?? null);
    $topic = cleanOptionalString($_POST['topic'] ?? null) ?? 'general';
    $message = cleanOptionalString($_POST['message'] ?? null);

    if ($name === null || $email === null || $message === null) {
        renderPage('Contact', 'contact.php', [
            'webUrl' => $webUrl,
            'notice' => null,
            'error' => 'Name, email, and a message are all required.',
            'topic' => $topic,
            'old' => formOld(['name', 'email', 'message']),
        ], 400);
        return;
    }

    $sent = sendContactMessage($contactTo, $name, $email, $topic, $message);
    if (!$sent) {
        renderPage('Contact', 'contact.php', [
            'webUrl' => $webUrl,
            'notice' => null,
            'error' => 'Something went wrong sending that. Please try again, or email hello@elonn.com directly.',
            'topic' => $topic,
            'old' => formOld(['name', 'email', 'message']),
        ], 500);
        return;
    }

    Response::redirect('/contact?notice=sent');
});

$router->get('/privacy', static function () use ($webUrl): void {
    renderPage('Privacy', 'legal.php', [
        'webUrl' => $webUrl,
        'heading' => 'Privacy',
        'body' => [
            'Elonn uses account information to operate identity, account access, and connected product services.',
            'Authentication cookies are used to keep you signed in across Elonn services. Do not share account credentials or tokens.',
            'For privacy questions, contact hello@elonn.com.',
        ],
    ]);
});

$router->get('/terms', static function () use ($webUrl): void {
    renderPage('Terms', 'legal.php', [
        'webUrl' => $webUrl,
        'heading' => 'Terms',
        'body' => [
            'Use Elonn services responsibly and only with accounts you control.',
            'Preview services may change as the platform develops. Production services should preserve account and identity boundaries.',
            'For service questions, contact hello@elonn.com.',
        ],
    ]);
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
        'webUrl' => $data['webUrl'] ?? '#',
    ], $status);
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

function isHttps(): bool
{
    return ($_SERVER['HTTPS'] ?? '') === 'on'
        || ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https';
}

function redirectToHttps(string $canonicalUrl): void
{
    if (isHttps()) {
        return;
    }

    $target = safeHttpsRedirectTarget($canonicalUrl, ['elonn.local', 'www.elonn.local', 'elonn.com', 'www.elonn.com']);
    if ($target === null) {
        return;
    }

    http_response_code(308);
    header('Location: ' . $target);
    exit;
}

function isLocalRedirectPath(string $value): bool
{
    if ($value === '' || $value[0] !== '/') {
        return false;
    }

    $decoded = rawurldecode($value);
    if (str_starts_with($value, '//') || str_starts_with($decoded, '//')) {
        return false;
    }

    return !str_contains($value, '\\') && !str_contains($decoded, '\\');
}

/**
 * @param array<int, string> $allowedHosts
 */
function safeHttpsRedirectTarget(string $canonicalUrl, array $allowedHosts): ?string
{
    $uri = $_SERVER['REQUEST_URI'] ?? '/';
    $path = is_string($uri) && isLocalRedirectPath($uri) ? $uri : '/';
    $authority = $_SERVER['HTTP_HOST'] ?? '';
    $authority = is_string($authority) ? strtolower(trim($authority)) : '';
    $host = parse_url('http://' . $authority, PHP_URL_HOST);
    $host = is_string($host) ? strtolower($host) : '';

    if (in_array($host, $allowedHosts, true)) {
        return 'https://' . $host . $path;
    }

    if (isLoopbackHost($host) && preg_match('/\A(?:localhost|127(?:\.[0-9]{1,3}){3}|\[::1\])(?::[0-9]{1,5})?\z/', $authority) === 1) {
        return 'https://' . $authority . $path;
    }

    $canonical = rtrim($canonicalUrl, '/');
    return $canonical === '' ? null : $canonical . $path;
}

function isLoopbackHost(string $host): bool
{
    $isLoopbackIpv4 = filter_var($host, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) !== false
        && str_starts_with($host, '127.');

    return $host === 'localhost'
        || $host === '::1'
        || $isLoopbackIpv4;
}

function contactMessage(mixed $message): ?string
{
    return match ($message) {
        'sent' => 'Thanks — we\'ll be in touch soon.',
        default => null,
    };
}

function sendContactMessage(string $to, string $name, string $email, string $topic, string $message): bool
{
    $topicLabel = match ($topic) {
        'investing' => 'Investing',
        'volunteering' => 'Volunteering',
        'press' => 'Press',
        default => 'General',
    };

    $subject = 'Elonn contact form: ' . $topicLabel;
    $body = "From: {$name} <{$email}>\nTopic: {$topicLabel}\n\n{$message}\n";
    $headers = "From: Elonn Contact Form <noreply@elonn.com>\r\nReply-To: {$email}\r\n";

    return mail($to, $subject, $body, $headers);
}
