<?php

declare(strict_types=1);

$local = str_contains((string) ($_SERVER['HTTP_HOST'] ?? ''), 'elonn.local');

// Public information pages only. No identity/member config -- elonn.com does not talk to
// api.elonn (decision.member_surface_consolidation_20260908); it links out to the Runtimes.
return [
    'app' => [
        'environment' => site_string_config('APP_ENV', 'production'),
        'debug' => site_bool_config('APP_DEBUG', false),
        'url' => rtrim(site_string_config('APP_URL', $local ? 'https://elonn.local' : 'https://elonn.com'), '/'),
    ],
    'contact' => [
        'to' => site_string_config('ELONN_CONTACT_EMAIL', 'hello@elonn.com'),
    ],
    'products' => [
        'web_url' => site_string_config('ELONN_WEB_URL', $local ? 'https://web.elonn.local/' : 'https://web.elonn.com/'),
    ],
];

function site_string_config(string $key, string $default = ''): string
{
    $value = $_SERVER[$key] ?? $_ENV[$key] ?? $default;
    return trim((string) $value);
}

function site_bool_config(string $key, bool $default): bool
{
    $value = $_SERVER[$key] ?? $_ENV[$key] ?? $default;
    if (is_bool($value)) {
        return $value;
    }

    return filter_var($value, FILTER_VALIDATE_BOOL);
}
