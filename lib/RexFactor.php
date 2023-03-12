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
            'PHP Migration' =>
            [
                'PHP_72' => 'PHP 7.2',
                'PHP_73' => 'PHP 7.3',
                'PHP_74' => 'PHP 7.4',
                'PHP_80' => 'PHP 8.0',
                'PHP_81' => 'PHP 8.1',
                'PHP_82' => 'PHP 8.2',
            ]
        ];
    }

    public static function runRector(string $addonName, string $setName, bool $preview):string {
        $configPath = self::writeRectorConfig($setName);
        $rectorBin = self::rectorBinpath();


        $processPath = \rex_path::addon($addonName);
        if (!is_dir($processPath)) {
            throw new \InvalidArgumentException('Unknown addon name: ' . $addonName);
        }

        $output = RexCmd::execCmd($rectorBin.' process '. escapeshellarg($processPath) .' -c ' . escapeshellarg($configPath) . ($preview ? ' --dry-run' : ''), $stderrOutput, $exitCode);
        return $output;
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

    private static function writeRectorConfig(string $setName):string {
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
