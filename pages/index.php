<?php

/** @var rex_addon $this */

echo '<style>
.nav-tabs {
    margin-top: -65px;
}
</style>';

echo rex_view::title('rexfactor');

rex_be_controller::includeCurrentPageSubPath();

echo rex_view::info('This AddOn is created by Markus Staab in his free time. <a href="https://github.com/sponsors/staabm">Support rexfactor with your sponsoring 💕</a>');
