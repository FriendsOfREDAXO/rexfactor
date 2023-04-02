<?php

namespace rexfactor;

use Exception;
use InvalidArgumentException;
use Rector\PHPUnit\Set\PHPUnitSetList;
use Rector\Set\ValueObject\SetList;
use rex_path;
use RuntimeException;

use function defined;

final class RexFactor
{
    public const PHPUNIT_MIGRATIONS = 'PHPUnit Version Migrations';
    public const TESTS_QUALITY = 'Improve Test-Code Quality';
    private const PHP_MIGRATIONS = 'PHP Version Migrations';
    private const CODE_QUALITY = 'Improve Code Quality';
    private const MISC_MIGRATIONS = 'Misc';
    private const REX_CODE_STYLE_SETNAME = 'REX_CODE_STYLE';
    private const USE_CASES = [
        self::PHP_MIGRATIONS => [
            'PHP_72' => 'PHP 7.2',
            'PHP_73' => 'PHP 7.3',
            'PHP_74' => 'PHP 7.4',
            'PHP_80' => 'PHP 8.0',
            'PHP_81' => 'PHP 8.1',
            'PHP_82' => 'PHP 8.2',
        ],
        self::CODE_QUALITY => [
            'CODE_QUALITY' => 'Unify code quality',
            'DEAD_CODE' => 'Remove dead code',
            'TYPE_DECLARATION' => 'Infer type declarations',
            'PRIVATIZATION' => 'Reduce symbol visibility (privatization)',
            'EARLY_RETURN' => 'Use early returns',
        ],
        self::PHPUNIT_MIGRATIONS => [
            'PHPUNIT_60' => 'PHPUnit 6',
            'PHPUNIT_70' => 'PHPUnit 7',
            'PHPUNIT_80' => 'PHPUnit 8',
            'PHPUNIT_90' => 'PHPUnit 9',
            'PHPUNIT_91' => 'PHPUnit 9.1',
            'PHPUNIT_100' => 'PHPUnit 10',
        ],
        self::TESTS_QUALITY => [
            'PHPUNIT_CODE_QUALITY' => 'Unify test-code quality',
            'PHPUNIT_EXCEPTION' => 'Refactor exception expectations',
            'REMOVE_MOCKS' => 'Reduce mock usage',
            'PHPUNIT_SPECIFIC_METHOD' => 'Use specific assert*() methods',
            'PHPUNIT_YIELD_DATA_PROVIDER' => 'Use yield over array return in data providers',
            'ANNOTATIONS_TO_ATTRIBUTES' => 'Annotations to Attributes',
        ],
        self::MISC_MIGRATIONS => [
            self::REX_CODE_STYLE_SETNAME => 'Redaxo specific code style',
            'CODING_STYLE' => 'More explicit coding style',
        ],
    ];

    /**
     * @return array<string, array<string, string>>
     */
    public static function getUseCases(): array
    {
        $useCases = self::USE_CASES;

        // verfiy the config is valid
        foreach ($useCases as $groupLabel => $groupSetLists) {
            foreach ($groupSetLists as $setList => $label) {
                // rex code style is not a rector set. skip it from validation.
                if (self::REX_CODE_STYLE_SETNAME === $setList) {
                    continue;
                }

                // will throw on invalid config class
                self::getSetListFqcn($setList);
            }
        }

        return $useCases;
    }

    /**
     * Get the use case and value for a given key.
     *
     * @param string $key the key to search for
     *
     * @return array{0: string, 1: string}|null the use case and value for the given key, or null if not found
     */
    public static function getUseCase(string $key): ?array
    {
        foreach (self::USE_CASES as $useCase => $options) {
            if (isset($options[$key])) {
                return [$useCase, $options[$key]];
            }
        }
        return null;
    }

    /**
     * @param non-empty-string $addonName
     *
     * @return RectorResult|CsFixerResult
     */
    public static function runRexFactor(string $addonName, string $setName, string $targetVersion, bool $preview)
    {
        $addonPath = rex_path::addon($addonName);
        if (!is_dir($addonPath)) {
            throw new InvalidArgumentException('Unknown addon name: ' . $addonName);
        }

        $processPath = [];
        if ('developer' === $addonName) {
            $modulesDir = DeveloperAddonIntegration::getModulesDir();
            if (null !== $modulesDir) {
                $processPath[] = $modulesDir;
            }

            $templatesDir = DeveloperAddonIntegration::getTemplatesDir();
            if (null !== $templatesDir) {
                $processPath[] = $templatesDir;
            }
        } else {
            // don't process the developer addon itself
            $processPath[] = $addonPath;
        }
        $processPath = array_map('escapeshellarg', $processPath);

        if (self::REX_CODE_STYLE_SETNAME === $setName) {
            $csfixerBinPath = self::csfixerBinpath();
            $configPath = realpath(__DIR__.'/../.php-cs-fixer.php');
            if (false === $configPath) {
                throw new RuntimeException('php-cs-fixer config not found');
            }

            $cmd = $csfixerBinPath .' fix '. implode(' ', $processPath) . ' --config='. escapeshellarg($configPath). ($preview ? ' --dry-run --diff' : '') .' --format=json';
            $output = RexCmd::execCmd($cmd, $stderrOutput, $exitCode);
            return new CsFixerResult($output);
        }

        $configPath = self::writeRectorConfig($setName, $targetVersion);
        $rectorBin = self::rectorBinpath();

        $cmd = $rectorBin.' process '. implode(' ', $processPath) .' -c ' . escapeshellarg($configPath) . ($preview ? ' --dry-run' : ' --no-diffs') . ' --clear-cache --output-format=json';
        $json = RexCmd::execCmd($cmd, $stderrOutput, $exitCode);

        return new RectorResult($json);
    }

    private static function rectorBinpath(): string
    {
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

    private static function csfixerBinpath(): string
    {
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

    private static function writeRectorConfig(string $setName, string $targetVersion): string
    {
        $setListClass = self::getSetListFqcn($setName);

        $tplPath = __DIR__.'/../rector.php.tpl';
        $configPath = __DIR__.'/../rector.php';

        $tpl = file_get_contents($tplPath);
        if (false === $tpl) {
            throw new Exception('Unable to read rector config template');
        }

        $tpl = str_replace('%%RECTOR_SETS%%', $setListClass, $tpl);
        if (TargetVersion::PHP8_1 === $targetVersion) {
            $tpl = str_replace('%%TARGET_PHP_VERSION%%', '80100', $tpl);
        } elseif (TargetVersion::PHP7_2_COMPAT === $targetVersion) {
            $tpl = str_replace('%%TARGET_PHP_VERSION%%', '70200', $tpl);
        } else {
            throw new InvalidArgumentException('Unknown target version: ' . $targetVersion);
        }

        $skipList = [];
        $skipList[] = "'*/vendor/*'";
        if (!self::constantExists(PHPUnitSetList::class, $setName)) {
            $skipList[] = "'*/tests/*'";
        }
        $tpl = str_replace('%%SKIP_LIST%%', implode(',', $skipList), $tpl);

        if (false === file_put_contents($configPath, $tpl)) {
            throw new Exception('Unable to write rector config file');
        }

        $realpath = realpath($configPath);
        if (false === $realpath) {
            throw new Exception('Unable to get realpath of rector config file');
        }
        return $realpath;
    }

    private static function getSetListFqcn(string $setName): string
    {
        if (self::constantExists(SetList::class, $setName)) {
            return SetList::class.'::'.$setName;
        }
        if (self::constantExists(PHPUnitSetList::class, $setName)) {
            return PHPUnitSetList::class.'::'.$setName;
        }
        throw new InvalidArgumentException('Unknown set name: ' . $setName);
    }

    /**
     * @param class-string $class
     */
    private static function constantExists(string $class, string $name): bool
    {
        return defined("$class::$name");
    }
}
