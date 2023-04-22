<?php
// rexfactor auto generated file - do not edit, delete, rename

use Rector\CodingStyle\Rector\FuncCall\ConsistentPregDelimiterRector;
use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\SetList;
use Rector\PHPUnit\Set\PHPUnitSetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->sets([
        %%RECTOR_SETS%%
    ]);
    $rectorConfig->rules([
        %%RECTOR_RULES%%
    ]);

    $rectorConfig->phpVersion(%%TARGET_PHP_VERSION%%);

    $rectorConfig->skip([
        // by personal preference, I don't want rector to fiddle with my regex patterns
        ConsistentPregDelimiterRector::class,

        %%SKIP_LIST%%
    ]);
};
