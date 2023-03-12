<?php

$useCaseUrl = rex_url::backendPage('rexfactor/use-case');

echo '<h2>Select an AddOn</h2>';

echo '<ul>';
foreach (rex_addon::getAvailableAddons() as $availableAddon) {
    $addonPath = $availableAddon->getPath();

    $label = '';
    if (!is_dir($addonPath.'/.git')) {
        $label .= '<span class="label label-warning">unversioned sources</span>';
    }

    echo '<li>
        <a class="btn btn-save" href="'.$useCaseUrl.'&addon='.$availableAddon->getName().'">'.$availableAddon->getName().'</a>
        '.$label.'
    </li>';
}
echo '</ul>';
