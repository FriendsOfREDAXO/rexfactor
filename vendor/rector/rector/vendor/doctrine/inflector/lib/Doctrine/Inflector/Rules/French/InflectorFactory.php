<?php

declare (strict_types=1);
namespace RectorPrefix202306\Doctrine\Inflector\Rules\French;

use RectorPrefix202306\Doctrine\Inflector\GenericLanguageInflectorFactory;
use RectorPrefix202306\Doctrine\Inflector\Rules\Ruleset;
final class InflectorFactory extends GenericLanguageInflectorFactory
{
    protected function getSingularRuleset() : Ruleset
    {
        return Rules::getSingularRuleset();
    }
    protected function getPluralRuleset() : Ruleset
    {
        return Rules::getPluralRuleset();
    }
}
