<?php

namespace rexfactor;

final class ViewHelpers
{
    /**
     * Returns javascript, which renders a blocking loader when invoced.
     */
    public static function jsLoader(): string
    {
        // in the future this should instead use the native redaxo core loader api
        // https://github.com/redaxo/redaxo/pull/5664
        return "document.querySelector('#rex-js-ajax-loader').classList.add('rex-visible');";
    }
}
