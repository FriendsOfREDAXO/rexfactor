<?php
// rexfactor auto generated file - do not edit, delete, rename

use Rector\CodingStyle\Rector\FuncCall\ConsistentPregDelimiterRector;
use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\SetList;
use Rector\PHPUnit\Set\PHPUnitSetList;
use Rector\Php80\Rector\Identical\StrEndsWithRector;
use Rector\Php80\Rector\Identical\StrStartsWithRector;
use Rector\Php80\Rector\NotIdentical\StrContainsRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->sets([
        %%RECTOR_SETS%%
    ]);

    // redaxo core ships with polyfills, so we can always apply these rectors
    $rectorConfig->rules([
        StrEndsWithRector::class,
        StrStartsWithRector::class,
        StrContainsRector::class
    ]);

    $rectorConfig->phpVersion(%%TARGET_PHP_VERSION%%);

    $rectorConfig->skip([
        // by personal preference, I don't want rector to fiddle with my regex patterns
        ConsistentPregDelimiterRector::class,

        %%SKIP_LIST%%
    ]);
};
