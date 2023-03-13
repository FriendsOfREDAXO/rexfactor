<?php

declare(strict_types=1);

namespace Redaxo\PhpCsFixerConfig;

use PhpCsFixer\ConfigInterface;
use PhpCsFixer\Fixer\Import\OrderedImportsFixer;

class Config extends \PhpCsFixer\Config
{
    private const REDAXO_RULES = [
        '@Symfony' => true,
        '@Symfony:risky' => true,
        '@PHP73Migration' => true,
        '@PHP71Migration:risky' => true,
        '@PHPUnit84Migration:risky' => true,
        'array_indentation' => true,
        'blank_line_before_statement' => false,
        'braces' => ['allow_single_line_closure' => false],
        'comment_to_phpdoc' => true,
        'concat_space' => false,
        'declare_strict_types' => false,
        'echo_tag_syntax' => false,
        'empty_loop_condition' => false,
        'global_namespace_import' => [
            'import_constants' => true,
            'import_functions' => true,
            'import_classes' => true,
        ],
        'heredoc_to_nowdoc' => true,
        'list_syntax' => ['syntax' => 'short'],
        'method_argument_space' => ['on_multiline' => 'ignore'],
        'native_constant_invocation' => false,
        'no_alternative_syntax' => false,
        'no_blank_lines_after_phpdoc' => false,
        'no_null_property_initialization' => true,
        'no_superfluous_elseif' => true,
        'no_unreachable_default_argument_value' => true,
        'no_useless_else' => true,
        'no_useless_return' => true,
        'ordered_class_elements' => ['order' => [
            'use_trait',
            'constant_public',
            'constant_protected',
            'constant_private',
            'property',
            'construct',
            'phpunit',
            'method',
        ]],
        'ordered_imports' => ['imports_order' => [
            OrderedImportsFixer::IMPORT_TYPE_CLASS,
            OrderedImportsFixer::IMPORT_TYPE_CONST,
            OrderedImportsFixer::IMPORT_TYPE_FUNCTION,
        ]],
        'php_unit_internal_class' => true,
        'php_unit_test_case_static_method_calls' => true,
        'phpdoc_align' => false,
        'phpdoc_no_package' => false,
        'phpdoc_order' => true,
        'phpdoc_separation' => false,
        'phpdoc_to_comment' => false,
        'phpdoc_types_order' => false,
        'phpdoc_var_annotation_correct_order' => true,
        'psr_autoloading' => false,
        'semicolon_after_instruction' => false,
        'static_lambda' => true,
        'void_return' => false,
    ];

    public function __construct(string $name = 'default')
    {
        parent::__construct($name);

        $this->setUsingCache(true);
        $this->setRiskyAllowed(true);
        $this->setRules([]);
    }

    public function setRules(array $rules): ConfigInterface
    {
        return parent::setRules(array_merge(self::REDAXO_RULES, $rules));
    }
}
