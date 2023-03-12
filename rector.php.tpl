<?php
// rexfactor auto generated file - do not edit, delete, rename

use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\SetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->sets([
        %%RECTOR_SETS%%
    ]);

    $rectorConfig->phpVersion(%%TARGET_PHP_VERSION%%);

    $rectorConfig->skip([
        '*/vendor/*'
    ]);
};
