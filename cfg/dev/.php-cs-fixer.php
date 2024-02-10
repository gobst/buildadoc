<?php

$finder = PhpCsFixer\Finder::create()
    ->in([
        'src',
        'tests'
    ]);

$config = new PhpCsFixer\Config();
return $config
    ->setRiskyAllowed(true)
    ->setRules([
        '@Symfony' => true,
        'yoda_style' => false,
        'single_line_throw' => false,
        'single_import_per_statement' => true,
        'concat_space' => [
            'spacing' => 'one'
        ],
        'operator_linebreak' => [
            'position' => 'beginning'
        ],
        'ordered_imports' => [
            'imports_order' => [
                'const',
                'class',
                'function',
            ],
        ],
        'global_namespace_import' => [
            'import_classes' => true,
            'import_constants' => true,
            'import_functions' => true,
        ],
        'method_argument_space' => [
            'on_multiline' => 'ensure_fully_multiline'
        ],
        'class_definition' => [
            'inline_constructor_arguments' => false,
            'multi_line_extends_each_single_line' => true,
        ],
        'declare_equal_normalize' => [
            'space' => 'single'
        ],
        'class_attributes_separation' => [
            'elements' => [
                'method' => 'one',
                'property' => 'only_if_meta',
                'trait_import' => 'only_if_meta'
            ]
        ],
        'group_import' => false,
        'no_useless_else' => true,
        'no_useless_return' => true,
        'return_assignment' => true,
        'single_line_empty_body' => true,
        '@PHP81Migration' => true,
        '@PHP80Migration:risky' => true
    ])
    ->setFinder($finder);
