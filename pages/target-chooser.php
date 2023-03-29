<?php

use rexfactor\RexCmd;

$useCaseUrl = rex_url::backendPage('rexfactor/use-case');

echo '<h2>Select an AddOn</h2>';

echo rex_view::warning("It's recommended to rexfactor only AddOns which are under version control and don't contain uncommitted changes.");

echo '<ul>';
foreach (rex_addon::getAvailableAddons() as $availableAddon) {
    $addonPath = $availableAddon->getPath();

    // system packages are maintained within the redaxo/redaxo codebase with more advanced tooling then rexfactor
    if ($availableAddon->isSystemPackage()) {
        continue;
    }

    $batches = [];
    if (!is_dir($addonPath.'/.git')) {
        $batches[] = '<span class="label label-danger">unversioned sources</span>';
    } else {
        RexCmd::execCmd('cd '. escapeshellarg($addonPath) .' && git diff --quiet', $stdErr, $exitCode);
        if ($exitCode !== 0) {
            $batches[] = '<span class="label label-warning">uncommitted changes</span>';
        }
    }

    $buttonType = 'btn-save';
    $buttonLabel = $availableAddon->getName();
    if ($buttonLabel === 'developer') {
        $buttonLabel .= ' (incl. modules/templates)';
    }

    echo '<li>
        <a class="btn '. $buttonType .'" href="'.$useCaseUrl.'&addon='.$availableAddon->getName().'">'.$buttonLabel.'</a>
        '.implode(' ', $batches).'
    </li>';
}
echo '</ul>';
