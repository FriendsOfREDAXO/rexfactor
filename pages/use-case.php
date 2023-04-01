<?php

use rexfactor\RexFactor;

$addon = rex_get('addon', 'string');

$backUrl = rex_url::backendPage('rexfactor/target-chooser');
$previewUrl = rex_url::backendPage('rexfactor/preview').'&addon='.rex_escape($addon, 'url');

$rexAddOn = rex_addon::get($addon);
$hasTests = is_dir($rexAddOn->getPath().'/tests');

echo '<h2>Select the migration use-case</h2>';
echo '<h3>AddOn: '. rex_escape($addon) .'</h3><hr>';

echo '<ul class="list-group">';
foreach(RexFactor::getUseCases() as $groupLabel => $groupSetLists) {
    if (in_array($groupLabel, [RexFactor::PHPUNIT_MIGRATIONS, RexFactor::TESTS_QUALITY], true) && !$hasTests) {
        continue;
    }

    echo '<li class="list-group-item panel-title"><h2>'.rex_escape($groupLabel).'</h2></li>';

    echo '<ul class="list-group">';
    foreach($groupSetLists as $setList => $label) {
        $loader = \rexfactor\ViewHelpers::jsLoader();
        echo '<li class="list-group-item"><a class="list-group-item-heading" href="'.$previewUrl.'&set-list='.rex_escape($setList, 'url').'" onclick="'.$loader.'">'.rex_escape($label).'</a></li>';
    }
    echo '</ul>';
}
echo '</ul>';

echo '<a class="btn btn-info" href="'. $backUrl .'">back</a>';
