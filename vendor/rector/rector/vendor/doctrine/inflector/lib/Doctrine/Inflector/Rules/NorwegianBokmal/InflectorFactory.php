<?php

declare (strict_types=1);
namespace RectorPrefix202311\Doctrine\Inflector\Rules\NorwegianBokmal;

use RectorPrefix202311\Doctrine\Inflector\GenericLanguageInflectorFactory;
use RectorPrefix202311\Doctrine\Inflector\Rules\Ruleset;
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
