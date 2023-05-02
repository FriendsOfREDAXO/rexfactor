<?php

use rexfactor\RexFactor;

$addon = rex_get('addon', 'string');

$addonLabel = RexFactor::getAddonLabel($addon);

$backUrl = rex_url::backendPage('rexfactor/target-chooser');
$previewUrl = rex_url::backendPage('rexfactor/preview').'&addon='.rex_escape($addon, 'url');

$rexAddOn = rex_addon::get($addon);
$hasTests = is_dir($rexAddOn->getPath().'/tests');

echo '<h2>AddOn: '. $addonLabel .'</h2><hr>';
$content = '';
$content .= '<ul class="list-group">';
foreach (RexFactor::getUseCases() as $groupLabel => $groupSetLists) {
    if (in_array($groupLabel, [RexFactor::PHPUNIT_MIGRATIONS, RexFactor::TESTS_QUALITY], true) && !$hasTests) {
        continue;
    }

    $content .= '<li class="list-group-item panel-title"><h3 class="list-group-item-heading">'.rex_escape($groupLabel).'</h3></li>';

    $content .= '<ul class="list-group">';
    foreach ($groupSetLists as $setList => $label) {
        if ('developer' === $addon && 'DEAD_CODE' === $setList) {
            continue;
        }
        $loader = \rexfactor\ViewHelpers::jsLoader();
        $content .= '<li class="list-group-item"><a class="list-group-item-heading" href="'.$previewUrl.'&set-list='.rex_escape($setList, 'url').'" onclick="'.$loader.'">'.rex_escape($label).'</a></li>';
    }
    $content .= '</ul>';
}
$content .= '</ul>';

$content .= '<a class="btn btn-info" href="'. $backUrl .'">back</a>';

$fragment = new rex_fragment();
$fragment->setVar('title', 'Select the migration use-case');
$fragment->setVar('body', $content, false);
echo $fragment->parse('core/page/section.php');
