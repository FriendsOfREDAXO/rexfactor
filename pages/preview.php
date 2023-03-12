<?php

use rexfactor\DiffHtml;
use rexfactor\RexFactor;

$addon = rex_get('addon', 'string');
$setList = rex_get('set-list', 'string');
$outputFormat = rex_get('format', 'string', DiffHtml::FORMAT_LINE_BY_LINE);

$backUrl = rex_url::backendPage('rexfactor/use-case').'&addon='.rex_escape($addon, 'url');;
$formatToggleUrl = rex_url::backendPage('rexfactor/preview').'&addon='.rex_escape($addon, 'url') .'&set-list='.rex_escape($setList, 'url');
$applyUrl = rex_url::backendPage('rexfactor/apply').'&addon='.rex_escape($addon, 'url') .'&set-list='.rex_escape($setList, 'url');

$result = RexFactor::runRector($addon, $setList, true);

$html = '';
$total = $result->getTotals();
if ($total['changed_files'] > 0) {
    $diffHtml = new DiffHtml($result, $outputFormat);
    $diff = DiffHtml::getHead();
    $diff .= $diffHtml->renderHtml();

    echo '<h2>Migration preview</h2>';

    echo '<a class="btn btn-info" href="'. $backUrl .'">back</a>';
    if ($outputFormat === DiffHtml::FORMAT_LINE_BY_LINE) {
        $formatToggleUrl .= '&format='.DiffHtml::FORMAT_SIDE_BY_SIDE;
        $formatToggleLabel = 'side-by-side';
    } else {
        $formatToggleUrl .= '&format='.DiffHtml::FORMAT_LINE_BY_LINE;
        $formatToggleLabel = 'line-by-line';
    }
    echo '<a class="btn btn-info" href="'. $formatToggleUrl .'">Format: '. $formatToggleLabel .'</a>';
    echo '<a class="btn btn-save" href="'. $applyUrl .'" data-confirm="Source files will be overwritten. continue?">Apply changes</a>';

    echo '<div style="background: unset; color: unset;">'.$diff.'</div>';


} else {
    echo '<h2>Code is shiny. Nothing todo for this migration - move along.</h2>';

    echo '<a class="btn btn-info" href="'. $backUrl .'">back</a>';
}
