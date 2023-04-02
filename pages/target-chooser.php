<?php

use rexfactor\RexCmd;

$useCaseUrl = rex_url::backendPage('rexfactor/use-case');
$content = '';

$content .= rex_view::warning("It's recommended to rexfactor only AddOns which are under version control and don't contain uncommitted changes.");

$content .= '<ul class="list-group">';
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
        if (0 !== $exitCode) {
            $batches[] = '<span class="label label-warning">uncommitted changes</span>';
        }
    }

    $buttonLabel = $availableAddon->getName();
    if ('developer' === $buttonLabel) {
        $buttonLabel .= ': modules/templates';
    }

    $content .= '<li class="list-group-item">
    <div class="pull-right">
        '.implode(' ', $batches).'</div>
        <a class="button" href="'.$useCaseUrl.'&addon='.$availableAddon->getName().'"><h4 class="list-group-item-heading">'.$buttonLabel.'</h4>
        </a>
    </li>';
}
$content .= '</ul>';

$fragment = new rex_fragment();
$fragment->setVar('title', 'Select an AddOn');
$fragment->setVar('body', $content, false);
echo $fragment->parse('core/page/section.php');
