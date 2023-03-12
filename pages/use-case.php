<?php

$addon = rex_get('addon', 'string');

$backUrl = rex_url::backendPage('rexfactor/target-chooser');
$previewUrl = rex_url::backendPage('rexfactor/preview').'&addon='.rex_escape($addon, 'url');

echo 'AddOn <code>'.rex_escape($addon). '</code> selected. Select the migration use-case:';

echo '<ul>';
foreach(\rexfactor\RexFactor::getUseCases() as $groupLabel => $groupSetLists) {
    echo '<li>'.rex_escape($groupLabel).'</li>';
    echo '<ul>';
    foreach($groupSetLists as $setList => $label) {
        echo '<li><a class="btn btn-save" href="'.$previewUrl.'&set-list='.rex_escape($setList, 'url').'">'.rex_escape($label).'</a></li>';
    }
    echo '</ul>';
}
echo '</ul>';

echo '<a class="btn btn-info" href="'. $backUrl .'">back</a>';
