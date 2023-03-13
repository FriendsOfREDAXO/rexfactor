<?php

namespace rexfactor;

use Rector\PHPUnit\Set\PHPUnitSetList;
use Rector\Set\ValueObject\SetList;
use rex_path;
use rexstan\RexCmd;

final class RexFactor {
    public const PHP_MIGRATIONS = 'PHP Version Migrations';
    public const PHPUNIT_MIGRATIONS = 'PHPUnit Version Migrations';
    public const MISC_MIGRATIONS = 'Misc';
    public const REX_CODE_STYLE_SETNAME = 'REX_CODE_STYLE';
    private const USE_CASES = [
        self::PHP_MIGRATIONS =>
        [
            'PHP_72' => 'PHP 7.2',
            'PHP_73' => 'PHP 7.3',
            'PHP_74' => 'PHP 7.4',
            'PHP_80' => 'PHP 8.0',
            'PHP_81' => 'PHP 8.1',
            'PHP_82' => 'PHP 8.2',
        ],
        self::PHPUNIT_MIGRATIONS =>
        [
            'PHPUNIT_60' => 'PHPUnit 6.0',
            'PHPUNIT_70' => 'PHPUnit 7.0',
            'PHPUNIT_80' => 'PHPUnit 8.0',
            'PHPUNIT_90' => 'PHPUnit 9.0',
            'PHPUNIT_91' => 'PHPUnit 9.1',
            'PHPUNIT_100' => 'PHPUnit 10',
            'PHPUNIT_CODE_QUALITY' => 'Unify test-code quality',
            'PHPUNIT_EXCEPTION' => 'Refactor exception expectations',
            'REMOVE_MOCKS' => 'Reduce mock usage',
            'PHPUNIT_SPECIFIC_METHOD' => 'Use specific assert*() methods',
            'PHPUNIT_YIELD_DATA_PROVIDER' => 'Use yield over array return in data providers',
            'ANNOTATIONS_TO_ATTRIBUTES' => 'Annotations to Attributes',
        ],
        self::MISC_MIGRATIONS =>
        [
             self::REX_CODE_STYLE_SETNAME => 'Redaxo specific code style',
            'CODE_QUALITY' => 'Unify code quality',
            'CODING_STYLE' => 'More explicit coding style',
            'DEAD_CODE' => 'Remove dead code',
            'TYPE_DECLARATION' => 'Infer type declarations',
            'PRIVATIZATION' => 'Reduce symbol visibility (privatization)',
            'EARLY_RETURN' => 'Use early returns',
        ]
    ];

    /**
     * @return array<string, array<string, string>>
     */
    public static function getUseCases():array {
        $useCases = self::USE_CASES;

        // verfiy the config is valid
        foreach($useCases as $groupLabel => $groupSetLists) {
            foreach ($groupSetLists as $setList => $label) {
                // rex code style is not a rector set. skip it from validation.
                if ($setList === self::REX_CODE_STYLE_SETNAME) {
                    continue;
                }

                // will throw on invalid config class
                self::getSetListFqcn($setList);
            }
        }

        return $useCases;
    }

    /**
     * @param TargetVersion::* $targetVersion
     *
     * @return RectorResult|CsFixerResult
     */
    public static function runRexFactor(string $addonName, string $setName, string $targetVersion, bool $preview) {
        $addonPath = rex_path::addon($addonName);
        if (!is_dir($addonPath)) {
            throw new \InvalidArgumentException('Unknown addon name: ' . $addonName);
        }

        $processPath = [];
        $processPath[] = $addonPath;
        if ($addonName === 'developer') {
            $modulesDir = DeveloperAddonIntegration::getModulesDir();
            if ($modulesDir !== null) {
                $processPath[] = $modulesDir;
            }

            $templatesDir = DeveloperAddonIntegration::getTemplatesDir();
            if ($templatesDir !== null) {
                $processPath[] = $templatesDir;
            }
        }
        $processPath = array_map('escapeshellarg', $processPath);

        if ($setName === self::REX_CODE_STYLE_SETNAME) {
            $csfixerBinPath = self::csfixerBinpath();
            $configPath = realpath(__DIR__.'/../.php-cs-fixer.php');

            $cmd = $csfixerBinPath .' fix '. escapeshellarg($addonPath) . ' --config='. escapeshellarg($configPath). ($preview ? ' --dry-run --diff' : '') .' --format=json';
            $output = RexCmd::execCmd($cmd, $stderrOutput, $exitCode);
            return new CsFixerResult($addonName, $output);
        }

        $configPath = self::writeRectorConfig($setName, $targetVersion);
        $rectorBin = self::rectorBinpath();

        $cmd = $rectorBin.' process '. implode(' ', $processPath) .' -c ' . escapeshellarg($configPath) . ($preview ? ' --dry-run' : ' --no-diffs') . ' --clear-cache --output-format=json';
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

    private static function csfixerBinpath(): string {
        if ('WIN' === strtoupper(substr(PHP_OS, 0, 3))) {
            $path = realpath(__DIR__.'/../vendor/bin/php-cs-fixer.bat');
        } else {
            $path = RexCmd::phpExecutable().' '.realpath(__DIR__.'/../vendor/bin/php-cs-fixer');
        }

        if (false === $path) {
            throw new RuntimeException('php-cs-fixer binary not found');
        }

        return $path;
    }

    /**
     * @param TargetVersion::* $targetVersion
     */
    private static function writeRectorConfig(string $setName, string $targetVersion):string {
        $setListClass = self::getSetListFqcn($setName);

        $tplPath = __DIR__.'/../rector.php.tpl';
        $configPath = __DIR__.'/../rector.php';

        $tpl = file_get_contents($tplPath);
        if ($tpl === false) {
            throw new \Exception('Unable to read rector config template');
        }

        $tpl = str_replace('%%RECTOR_SETS%%', $setListClass, $tpl);
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
     * @return class-string
     */
    static private function getSetListFqcn(string $setName): string {
        if (self::constantExists(SetList::class, $setName)) {
            return SetList::class.'::'.$setName;
        }
        if (self::constantExists(PHPUnitSetList::class, $setName)) {
            return PHPUnitSetList::class.'::'.$setName;
        }
        throw new \InvalidArgumentException('Unknown set name: ' . $setName);
    }

    /**
     * @param class-string $class
     */
    private static function constantExists(string $class, string $name):bool{
        return defined("$class::$name");
    }

}
