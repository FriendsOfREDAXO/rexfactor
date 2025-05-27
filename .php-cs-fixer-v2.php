<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__.'/../')
    ->exclude('tests')
    ->exclude('node_modules')
;

return (new Redaxo\PhpCsFixerConfig\Config())
    ->setRules([
        // redaxo core ships with polyfills, so we can always apply these rectors, see https://github.com/redaxo/php-cs-fixer-config/issues/5
        'modernize_strpos' => true,
        // personal preference - I don't like yoda style, see https://github.com/redaxo/php-cs-fixer-config/issues/4
        'yoda_style' => ['equal' => false, 'identical' => false, 'less_and_greater' => false],
        
        // PHP 8.1+ specific rules
        'type_declaration_spaces' => true,
        'nullable_type_declaration' => true,
        'nullable_type_declaration_for_default_null_value' => true,
        'compact_nullable_type_declaration' => true,
        'phpdoc_to_param_type' => true,
        'phpdoc_to_return_type' => true,
        'phpdoc_to_property_type' => true,
        'trailing_comma_in_multiline' => ['elements' => ['arrays', 'arguments', 'parameters']],
        'fully_qualified_strict_types' => true,
        'ordered_imports' => ['sort_algorithm' => 'alpha'],
        'single_trait_insert_per_statement' => true,
        'no_trailing_comma_in_singleline' => true,
    ])
    ->setFinder($finder)
;
