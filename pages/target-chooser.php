<?php

use rexfactor\RexCmd;

$useCaseUrl = rex_url::backendPage('rexfactor/use-case');

echo '<h2>Select an AddOn</h2>';

echo rex_view::warning("It's recommended to rexfactor only AddOns which are under version control and doesn't contain uncommitted changes.");

echo '<ul>';
foreach (rex_addon::getAvailableAddons() as $availableAddon) {
    $addonPath = $availableAddon->getPath();

    $label = '';
    if (!is_dir($addonPath.'/.git')) {
        $label .= ' <span class="label label-danger">unversioned sources</span>';
    } else {
        RexCmd::execCmd('cd '. escapeshellarg($addonPath) .' && git diff --quiet', $stdErr, $exitCode);
        if ($exitCode !== 0) {
            $label .= ' <span class="label label-warning">uncommitted changes</span>';
        }
    }

    $buttonType = 'btn-save';
    if ($availableAddon->isSystemPackage()) {
        $buttonType = 'btn-default';
        $label .= ' <span class="label label-info">system package</span>';
    }

    echo '<li>
        <a class="btn '. $buttonType .'" href="'.$useCaseUrl.'&addon='.$availableAddon->getName().'">'.$availableAddon->getName().'</a>
        '.$label.'
    </li>';
}
echo '</ul>';
