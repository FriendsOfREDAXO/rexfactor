<?php

$addon = rex_get('addon', 'string');
$setName = rex_get('set-list', 'string');

\rexfactor\RexFactor::runRector($addon, $setName, true);
