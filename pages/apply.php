<?php

use rexfactor\DiffHtml;
use rexfactor\RexFactor;

$addon = rex_get('addon', 'string');
$setList = rex_get('set-list', 'string');

$backToStartUrl = rex_url::backendPage('rexfactor');

$result = RexFactor::runRector($addon, $setList, false);

$html = '';
$total = $result->getTotals();
if ($total['changed_files'] > 0) {
    $headline = 'Successfully migrated '. $total['changed_files'] .' files';
} else {
    $headline = 'No changes';
}

echo '<h2>'. $headline .'</h2>';
echo '<a class="btn btn-info" href="'. $backToStartUrl .'">Start next migration</a>';
