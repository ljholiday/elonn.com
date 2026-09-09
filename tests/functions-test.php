<?php

declare(strict_types=1);

// Pure-function tests for elonn.local (public information pages only).
// Functions reproduced from public/index.php for isolation.

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

// ---------------------------------------------------------------------------
// Test runner
// ---------------------------------------------------------------------------

$passed = 0;
$failed = 0;

function test(string $name, callable $fn): void
{
    global $passed, $failed;
    try {
        $result = $fn();
        if ($result === true) {
            echo "PASS: $name\n";
            $passed++;
        } else {
            $detail = is_string($result) ? " — $result" : '';
            echo "FAIL: $name$detail\n";
            $failed++;
        }
    } catch (\Throwable $e) {
        echo "FAIL: $name — threw " . $e->getMessage() . "\n";
        $failed++;
    }
}

echo "Running elonn.local function tests...\n\n";

// normalizeEmail
test('normalizeEmail: valid lowercased', fn () => normalizeEmail('Hello@Example.COM') === 'hello@example.com');
test('normalizeEmail: trims whitespace', fn () => normalizeEmail('  a@b.com  ') === 'a@b.com');
test('normalizeEmail: invalid returns null', fn () => normalizeEmail('notanemail') === null);
test('normalizeEmail: null returns null', fn () => normalizeEmail(null) === null);
test('normalizeEmail: integer returns null', fn () => normalizeEmail(1) === null);
test('normalizeEmail: empty string returns null', fn () => normalizeEmail('') === null);

// cleanOptionalString
test('cleanOptionalString: null returns null', fn () => cleanOptionalString(null) === null);
test('cleanOptionalString: blank returns null', fn () => cleanOptionalString('  ') === null);
test('cleanOptionalString: trims value', fn () => cleanOptionalString('  hello  ') === 'hello');
test('cleanOptionalString: non-string returns null', fn () => cleanOptionalString(true) === null);
test('cleanOptionalString: integer returns null', fn () => cleanOptionalString(0) === null);

// isLocalRedirectPath
test('isLocalRedirectPath: relative path allowed', fn () => isLocalRedirectPath('/contact') === true);
test('isLocalRedirectPath: root allowed', fn () => isLocalRedirectPath('/') === true);
test('isLocalRedirectPath: protocol-relative blocked', fn () => isLocalRedirectPath('//evil.com') === false);
test('isLocalRedirectPath: backslash blocked', fn () => isLocalRedirectPath('/\\evil.com') === false);
test('isLocalRedirectPath: non-absolute blocked', fn () => isLocalRedirectPath('contact') === false);

// safeHttpsRedirectTarget
test('safeHttpsRedirectTarget: allowed production host preserved', function () {
    $_SERVER['HTTP_HOST'] = 'elonn.com';
    $_SERVER['REQUEST_URI'] = '/contact?x=1';

    return safeHttpsRedirectTarget('https://elonn.com', ['elonn.com']) === 'https://elonn.com/contact?x=1';
});

test('safeHttpsRedirectTarget: localhost host preserved', function () {
    $_SERVER['HTTP_HOST'] = 'localhost:8080';
    $_SERVER['REQUEST_URI'] = '/contact';

    return safeHttpsRedirectTarget('https://elonn.com', ['elonn.com']) === 'https://localhost:8080/contact';
});

test('safeHttpsRedirectTarget: unknown host falls back to canonical', function () {
    $_SERVER['HTTP_HOST'] = 'preview.example.test';
    $_SERVER['REQUEST_URI'] = '/contact';

    return safeHttpsRedirectTarget('https://elonn.com', ['elonn.com']) === 'https://elonn.com/contact';
});

test('safeHttpsRedirectTarget: unsafe request URI falls back to root path', function () {
    $_SERVER['HTTP_HOST'] = 'elonn.com';
    $_SERVER['REQUEST_URI'] = '//evil.com/path';

    return safeHttpsRedirectTarget('https://elonn.com', ['elonn.com']) === 'https://elonn.com/';
});

// contactMessage
test('contactMessage: sent', fn () => contactMessage('sent') === 'Thanks — we\'ll be in touch soon.');
test('contactMessage: unknown key returns null', fn () => contactMessage('unknown') === null);
test('contactMessage: null returns null', fn () => contactMessage(null) === null);


echo "\nPassed: $passed  Failed: $failed\n";
exit($failed > 0 ? 1 : 0);
