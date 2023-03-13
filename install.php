<?php

use rexstan\RexCmd;
use rexstan\RexStanUserConfig;

// make sure the binaries are executable
$binaries = glob(__DIR__.'/vendor/bin/*', GLOB_NOSORT);
if ($binaries !== false) {
    foreach ($binaries as $binaryPath) {
        @chmod($binaryPath, 0775);
    }
}
