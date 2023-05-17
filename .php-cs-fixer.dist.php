<?php

// command = ./vendor/bin/php-cs-fixer fix --allow-risky=yes

$finder = PhpCsFixer\Finder::create()
    ->exclude('somedir')
    ->notPath('src/Symfony/Component/Translation/Tests/fixtures/resources.php')
    ->in(__DIR__);

$config = new PhpCsFixer\Config();
return $config->setRules([
    '@PSR12' => true,
    '@PSR12:risky' => true,
    'strict_param' => true,
    'single_quote' => true,
    'no_unused_imports' => true,
    'no_useless_else' => true,
    'no_useless_return' => true,
    'array_indentation' => true,
    'no_extra_blank_lines' => ['tokens' => ['attribute', 'case', 'continue', 'curly_brace_block', 'default', 'extra', 'parenthesis_brace_block', 'square_brace_block', 'switch', 'throw', 'use']],
    'array_syntax' => ['syntax' => 'short'],
])
    ->setRiskyAllowed(true)
    ->setFinder($finder);
