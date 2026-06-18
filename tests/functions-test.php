<?php

declare(strict_types=1);

// Pure-function tests for elonn.local.
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
    $host   = strtolower((string) ($parts['host'] ?? ''));
    if (!in_array($scheme, ['http', 'https'], true)) {
        return null;
    }
    return in_array($host, [
        'elonn.local', 'api.elonn.local', 'time.elonn.local',
        'web.elonn.local', 'world.elonn.local', 'social.elonn.local', 'admin.elonn.local',
        'elonn.com', 'api.elonn.com', 'time.elonn.com', 'web.elonn.com',
        'world.elonn.com', 'social.elonn.com', 'admin.elonn.com',
    ], true) ? $value : null;
}

function errorMessage(mixed $error): ?string
{
    return match ($error) {
        'missing_fields'  => 'Email and password are required.',
        'invalid_login'   => 'Invalid email or password.',
        'user_exists'     => 'An account already exists for that email.',
        'register_failed' => 'Unable to create account.',
        'login_failed'    => 'Unable to log in.',
        default           => null,
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

// allowedReturnTo
test('allowedReturnTo: null returns null', fn () => allowedReturnTo(null) === null);
test('allowedReturnTo: empty string returns null', fn () => allowedReturnTo('') === null);
test('allowedReturnTo: whitespace returns null', fn () => allowedReturnTo('  ') === null);
test('allowedReturnTo: relative path allowed', fn () => allowedReturnTo('/dashboard') === '/dashboard');
test('allowedReturnTo: root path allowed', fn () => allowedReturnTo('/') === '/');
test('allowedReturnTo: elonn.local host allowed', fn () => allowedReturnTo('https://elonn.local/') === 'https://elonn.local/');
test('allowedReturnTo: api.elonn.local allowed', fn () => allowedReturnTo('https://api.elonn.local/path') === 'https://api.elonn.local/path');
test('allowedReturnTo: time.elonn.com allowed', fn () => allowedReturnTo('https://time.elonn.com/path') === 'https://time.elonn.com/path');
test('allowedReturnTo: external host blocked', fn () => allowedReturnTo('https://evil.com/') === null);
test('allowedReturnTo: ftp scheme blocked', fn () => allowedReturnTo('ftp://elonn.com/') === null);
test('allowedReturnTo: http scheme allowed', fn () => allowedReturnTo('http://elonn.local/') === 'http://elonn.local/');

// errorMessage
test('errorMessage: missing_fields', fn () => errorMessage('missing_fields') === 'Email and password are required.');
test('errorMessage: invalid_login', fn () => errorMessage('invalid_login') === 'Invalid email or password.');
test('errorMessage: user_exists', fn () => errorMessage('user_exists') === 'An account already exists for that email.');
test('errorMessage: register_failed', fn () => errorMessage('register_failed') === 'Unable to create account.');
test('errorMessage: login_failed', fn () => errorMessage('login_failed') === 'Unable to log in.');
test('errorMessage: unknown key returns null', fn () => errorMessage('unknown') === null);
test('errorMessage: null returns null', fn () => errorMessage(null) === null);
test('errorMessage: integer returns null', fn () => errorMessage(0) === null);


echo "\nPassed: $passed  Failed: $failed\n";
exit($failed > 0 ? 1 : 0);
