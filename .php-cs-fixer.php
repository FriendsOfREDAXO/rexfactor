<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__)
;

return (new Redaxo\PhpCsFixerConfig\Config())
    ->setFinder($finder)
    ;
