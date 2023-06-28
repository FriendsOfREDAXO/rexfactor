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
        'yoda_style' => ['equal' => false, 'identical' => false, 'less_and_greater' => false]
    ])
    ->setFinder($finder)
;
