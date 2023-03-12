<?php

namespace rexfactor;

use Rector\Set\ValueObject\SetList;
use rexstan\RexCmd;

final class RexFactor {
    /**
     * @return array<string, array<string, string>>
     */
    public static function getUseCases():array {
        return [
            'PHP Version Migrations' =>
            [
                'PHP_72' => 'PHP 7.2',
                'PHP_73' => 'PHP 7.3',
                'PHP_74' => 'PHP 7.4',
                'PHP_80' => 'PHP 8.0',
                'PHP_81' => 'PHP 8.1',
                'PHP_82' => 'PHP 8.2',
            ],
            'Misc' =>
            [
                'CODE_QUALITY' => 'Unify code quality',
                'CODING_STYLE' => 'More explicit coding style',
                'DEAD_CODE' => 'Remove dead code',
                'TYPE_DECLARATION' => 'Infer type declarations',
                'PRIVATIZATION' => 'Reduce symbol visibility (privatization)',
                'EARLY_RETURN' => 'Use early returns',
            ]
        ];
    }

    /**
     * @param TargetVersion::* $targetVersion
     */
    public static function runRector(string $addonName, string $setName, string $targetVersion, bool $preview):RectorResult {
        $configPath = self::writeRectorConfig($setName, $targetVersion);
        $rectorBin = self::rectorBinpath();

        $processPath = \rex_path::addon($addonName);
        if (!is_dir($processPath)) {
            throw new \InvalidArgumentException('Unknown addon name: ' . $addonName);
        }

        $cmd = $rectorBin.' process '. escapeshellarg($processPath) .' -c ' . escapeshellarg($configPath) . ($preview ? ' --dry-run' : ' --no-diffs') . ' --clear-cache --output-format=json';
        $json = RexCmd::execCmd($cmd, $stderrOutput, $exitCode);
        return new RectorResult($json);
    }

    private static function rectorBinpath(): string {
        if ('WIN' === strtoupper(substr(PHP_OS, 0, 3))) {
            $path = realpath(__DIR__.'/../vendor/bin/rector.bat');
        } else {
            $path = RexCmd::phpExecutable().' '.realpath(__DIR__.'/../vendor/bin/rector');
        }

        if (false === $path) {
            throw new RuntimeException('rector binary not found');
        }

        return $path;
    }

    /**
     * @param TargetVersion::* $targetVersion
     */
    private static function writeRectorConfig(string $setName, string $targetVersion):string {
        if (!self::constantExists(SetList::class, $setName)) {
            throw new \InvalidArgumentException('Unknown set name: ' . $setName);
        }

        $tplPath = __DIR__.'/../rector.php.tpl';
        $configPath = __DIR__.'/../rector.php';

        $tpl = file_get_contents($tplPath);
        if ($tpl === false) {
            throw new \Exception('Unable to read rector config template');
        }

        $tpl = str_replace('%%RECTOR_SETS%%', 'SetList::'.$setName, $tpl);
        if ($targetVersion === TargetVersion::PHP8_1) {
            $tpl = str_replace('%%TARGET_PHP_VERSION%%', '80100', $tpl);
        } elseif ($targetVersion === TargetVersion::PHP7_2_COMPAT) {
            $tpl = str_replace('%%TARGET_PHP_VERSION%%', '70200', $tpl);
        } else {
            throw new \InvalidArgumentException('Unknown target version: ' . $targetVersion);
        }

        if (file_put_contents($configPath, $tpl) === false) {
            throw new \Exception('Unable to write rector config file');
        }

        $realpath = realpath($configPath);
        if ($realpath === false) {
            throw new \Exception('Unable to get realpath of rector config file');
        }
        return $realpath;
    }

    /**
     * @param class-string $class
     */
    private static function constantExists(string $class, string $name):bool{
        return defined("$class::$name");
    }

}
