<?php

// make sure the binaries are executable
$binaries = glob(__DIR__.'/vendor/bin/*', GLOB_NOSORT);
if ($binaries !== false) {
    foreach ($binaries as $binaryPath) {
        @chmod($binaryPath, 0775);
    }
}
$addon = rex_addon::get('rexfactor');

// kommt mit be_style addon
if (class_exists('rex_scss_compiler')) {
    $compiler = new rex_scss_compiler();

    $compiler->setRootDir(rex_path::addon('rexfactor/scss'));
    $compiler->setScssFile([$addon->getPath('scss/styles.scss')]);

    // Compile in backend assets dir
    $compiler->setCssFile($addon->getPath('assets/styles.css'));
    $compiler->compile();
}
