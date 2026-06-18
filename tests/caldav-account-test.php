<?php

declare(strict_types=1);

$index = file_get_contents(dirname(__DIR__) . '/public/index.php') ?: '';
$client = file_get_contents(dirname(__DIR__) . '/src/ApiClient.php') ?: '';
$template = file_get_contents(dirname(__DIR__) . '/templates/account/account.php') ?: '';
$checks = [
    'Account loads DAV token metadata' =>
        str_contains($index, "'davTokens' => \$api->davTokens(\$token)")
        && str_contains($client, "public function davTokens("),
    'Account creates one-time CalDAV passwords' =>
        str_contains($index, "\$router->post('/account/caldav-tokens'")
        && str_contains($client, "public function createDavToken(")
        && str_contains($template, 'shown only once'),
    'Account revokes CalDAV passwords' =>
        str_contains($index, "\$router->post('/account/caldav-tokens/revoke'")
        && str_contains($client, "public function revokeDavToken("),
    'Account provides connection settings' =>
        str_contains($template, 'Server URL')
        && str_contains($template, 'Username')
        && str_contains($template, 'generated CalDAV password'),
];

$failed = 0;
foreach ($checks as $label => $passed) {
    echo ($passed ? 'PASS' : 'FAIL') . ': ' . $label . PHP_EOL;
    $failed += $passed ? 0 : 1;
}

exit($failed === 0 ? 0 : 1);
