<?php

// phpcs:ignoreFile

declare(strict_types=1);

if (!class_exists(\PhpCsFixer\Config::class) || !class_exists(\PhpCsFixer\Finder::class)) {
    return;
}

$finder = \PhpCsFixer\Finder::create()
    ->in([__DIR__ . '/app', __DIR__ . '/public'])
    ->exclude('vendor');

return (new \PhpCsFixer\Config())
    ->setRules([
        '@PSR12' => true,
        'array_syntax' => ['syntax' => 'short'],
        'no_unused_imports' => true,
        'single_quote' => true,
    ])
    ->setFinder($finder);
