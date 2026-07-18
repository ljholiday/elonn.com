<?php

declare(strict_types=1);

$glossary = file_get_contents(dirname(__DIR__, 2) . '/docs.elonn.local/content/terminology/glossary.xml') ?: '';
$checks = [
    'Glossary defines service in the service-runtime section' =>
        str_contains($glossary, '<section name="service-runtime" authority="mixed">')
        && str_contains($glossary, '<term name="service" authority="varies">'),
    'Service definition is canonical and product-bounded' =>
        str_contains($glossary, 'An independently deployed Elonn application boundary')
        && str_contains($glossary, 'Examples include API, Time, Social, Find, Maps, World, Surface, and Docs.'),
];

$failed = 0;
foreach ($checks as $label => $passed) {
    echo ($passed ? 'PASS' : 'FAIL') . ': ' . $label . PHP_EOL;
    $failed += $passed ? 0 : 1;
}

exit($failed === 0 ? 0 : 1);
