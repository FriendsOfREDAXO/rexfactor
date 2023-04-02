<?php

// make sure the binaries are executable
$binaries = glob(__DIR__.'/vendor/bin/*', GLOB_NOSORT);
if (false !== $binaries) {
    foreach ($binaries as $binaryPath) {
        @chmod($binaryPath, 0775);
    }
}
