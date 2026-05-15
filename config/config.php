<?php

declare(strict_types=1);

$local = str_contains((string) ($_SERVER['HTTP_HOST'] ?? ''), 'elonn.local');

return [
    'app' => [
        'environment' => site_string_config('APP_ENV', 'production'),
        'debug' => site_bool_config('APP_DEBUG', false),
        'url' => rtrim(site_string_config('APP_URL', $local ? 'https://elonn.local' : 'https://elonn.com'), '/'),
    ],
    'auth' => [
        'cookie_domain' => site_string_config('ELONN_COOKIE_DOMAIN', $local ? '.elonn.local' : '.elonn.com'),
    ],
    'services' => [
        'api_base_url' => rtrim(site_string_config('ELONN_API_BASE_URL', $local ? 'https://api.elonn.local' : 'https://api.elonn.com'), '/'),
    ],
    'products' => [
        'world_url' => site_string_config('ELONN_WEB_URL', $local ? 'https://web.elonn.local/' : 'https://web.elonn.com/'),
        'time_url' => site_string_config('ELONN_TIME_URL', $local ? 'https://time.elonn.local/' : 'https://time.elonn.com/'),
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
