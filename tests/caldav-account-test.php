<?php

declare(strict_types=1);

$index = file_get_contents(dirname(__DIR__) . '/public/index.php') ?: '';
$template = file_get_contents(dirname(__DIR__) . '/templates/account/account.php') ?: '';
$rewrite = file_get_contents(dirname(__DIR__) . '/public/.htaccess') ?: '';
$checks = [
    'Account provides unified CalDAV connection settings' =>
        str_contains($template, 'Server URL')
        && str_contains($template, 'Username')
        && str_contains($template, 'normal Elonn account password'),
    'Account provides generated credential controls' =>
        str_contains($index, '/account/caldav-tokens')
        && str_contains($template, 'Create CalDAV password')
        && str_contains($template, 'Revoke'),
    'CalDAV discovery redirects to unified services origin' =>
        str_contains($rewrite, 'services.elonn.com/caldav/')
        && str_contains($rewrite, 'services.elonn.local/caldav/'),
];

$failed = 0;
foreach ($checks as $label => $passed) {
    echo ($passed ? 'PASS' : 'FAIL') . ': ' . $label . PHP_EOL;
    $failed += $passed ? 0 : 1;
}

exit($failed === 0 ? 0 : 1);
