<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__.'/../')
    ->exclude('tests')
;

return (new Redaxo\PhpCsFixerConfig\Config())
    ->setRules([
        'yoda_style' => ['equal' => false, 'identical' => false, 'less_and_greater' => false]
    ])
    ->setFinder($finder)
;
