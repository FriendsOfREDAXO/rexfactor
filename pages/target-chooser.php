<?php

$useCaseUrl = rex_url::backendPage('rexfactor/use-case');

echo '<ul>';
foreach (rex_addon::getAvailableAddons() as $availableAddon) {
    echo '<li><a href="'.$useCaseUrl.'&addon='.$availableAddon->getName().'">'.$availableAddon->getName().'</a></li>';
}
echo '</ul>';
