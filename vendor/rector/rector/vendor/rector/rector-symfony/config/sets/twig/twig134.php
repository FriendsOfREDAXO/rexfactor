<?php

declare (strict_types=1);
namespace RectorPrefix202303;

use Rector\Config\RectorConfig;
use Rector\Symfony\Rector\Return_\SimpleFunctionAndFilterRector;
return static function (RectorConfig $rectorConfig) : void {
    $rectorConfig->rule(SimpleFunctionAndFilterRector::class);
};
