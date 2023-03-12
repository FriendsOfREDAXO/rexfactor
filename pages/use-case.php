<?php

$addon = rex_get('addon', 'string');

$backUrl = rex_url::backendPage('rexfactor/target-chooser');
$previewUrl = rex_url::backendPage('rexfactor/preview').'&addon='.rex_escape($addon, 'url');

echo '<ul>';
foreach(\rexfactor\RexFactor::getUseCases() as $groupLabel => $groupSetLists) {
    echo '<li>'.rex_escape($groupLabel).'</li>';
    echo '<ul>';
    foreach($groupSetLists as $setList => $label) {
        echo '<li><a href="'.$previewUrl.'&set-list='.rex_escape($setList, 'url').'">'.rex_escape($label).'</a></li>';
    }
    echo '</ul>';
}
echo '</ul>';

