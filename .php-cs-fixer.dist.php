<?php

declare(strict_types=1);

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

return (new Config())
    ->setRiskyAllowed(true)
    ->setRules([
        '@PER-CS' => true,
        'declare_strict_types' => true,
        'no_unused_imports' => true,
        'ordered_imports' => true,
        'new_expression_parentheses' => ['use_parentheses' => true],
        'global_namespace_import' => [
            'import_classes' => true,
            'import_constants' => null,
            'import_functions' => null,
        ],
    ])
    ->setFinder(Finder::create()->in([__DIR__ . '/src', __DIR__ . '/tests'])->append([__FILE__]))
    ->setCacheFile(__DIR__ . '/.php-cs-fixer.cache');
