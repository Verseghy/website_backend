<?php

$finder = PhpCsFixer\Finder::create()
    ->exclude('vendor')
    ->exclude('bootstrap/cache')
    ->exclude('storage')
    ->notPath(['.phpstorm.meta.php', '_ide_helper.php'])
    ->in(__DIR__)
;

return PhpCsFixer\Config::create()
    ->setRules([
        '@PSR1' => true,
        '@PSR2' => true,
        '@Symfony' => true,
        'line_ending' => false,
        'ordered_imports' => false,
    ])
    ->setFinder($finder)
;
