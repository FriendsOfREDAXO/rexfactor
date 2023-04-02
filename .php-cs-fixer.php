<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__.'/../')
    ->exclude('tests')
;

return (new Redaxo\PhpCsFixerConfig\Config())
    ->setFinder($finder)
;
