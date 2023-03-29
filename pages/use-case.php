<?php

$addon = rex_get('addon', 'string');

$backUrl = rex_url::backendPage('rexfactor/target-chooser');
$previewUrl = rex_url::backendPage('rexfactor/preview').'&addon='.rex_escape($addon, 'url');

$rexAddOn = rex_addon::get($addon);
$hasTests = is_dir($rexAddOn->getPath().'/tests');

echo '<h2>Select the migration use-case</h2>';
echo '<p>AddOn: '. rex_escape($addon) .'</p>';

echo '<ul>';
foreach(\rexfactor\RexFactor::getUseCases() as $groupLabel => $groupSetLists) {

    echo '<li>'.rex_escape($groupLabel).'</li>';

    $buttonType = 'btn-save';
    if ($groupLabel === \rexfactor\RexFactor::PHPUNIT_MIGRATIONS && !$hasTests) {
        $buttonType = 'btn-default';
    }

    echo '<ul>';
    foreach($groupSetLists as $setList => $label) {
        $loader = \rexfactor\ViewHelpers::jsLoader();
        echo '<li><a class="btn '. $buttonType .'" href="'.$previewUrl.'&set-list='.rex_escape($setList, 'url').'" onclick="'.$loader.'">'.rex_escape($label).'</a></li>';
    }
    echo '</ul>';
}
echo '</ul>';

echo '<a class="btn btn-info" href="'. $backUrl .'">back</a>';
