<?php

use rexfactor\DiffHtml;
use rexfactor\RexFactor;
use rexfactor\TargetVersion;

$addon = rex_get('addon', 'string');
$setList = rex_get('set-list', 'string');
$outputFormat = rex_get('format', 'string', DiffHtml::FORMAT_LINE_BY_LINE);
$targetVersion = rex_get('target-version', 'string', TargetVersion::PHP7_2_COMPAT);

if ($addon === '') {
    throw new rex_exception('Missing addon parameter');
}

$backUrl = rex_url::backendPage('rexfactor/use-case').'&addon='.rex_escape($addon, 'url');;
$formatToggleUrl = rex_url::backendPage('rexfactor/preview').'&addon='.rex_escape($addon, 'url') .'&set-list='.rex_escape($setList, 'url');
$versionToggleUrl = rex_url::backendPage('rexfactor/preview').'&addon='.rex_escape($addon, 'url') .'&set-list='.rex_escape($setList, 'url');
$applyUrl = rex_url::backendPage('rexfactor/apply').'&addon='.rex_escape($addon, 'url') .'&set-list='.rex_escape($setList, 'url');

if ($outputFormat === DiffHtml::FORMAT_LINE_BY_LINE) {
    $formatToggleUrl .= '&format='.DiffHtml::FORMAT_SIDE_BY_SIDE;
    $formatToggleLabel = 'side-by-side';
} else {
    $outputFormat = DiffHtml::FORMAT_SIDE_BY_SIDE;
    $formatToggleUrl .= '&format='.DiffHtml::FORMAT_LINE_BY_LINE;
    $formatToggleLabel = 'line-by-line';
}

if ($targetVersion === TargetVersion::PHP8_1) {
    $versionToggleUrl .= '&target-version='.rex_escape(TargetVersion::PHP7_2_COMPAT, 'url');
    $versionToggleLabel = TargetVersion::PHP7_2_COMPAT;
} else {
    $versionToggleUrl .= '&target-version='.rex_escape(TargetVersion::PHP8_1, 'url');
    $versionToggleLabel = TargetVersion::PHP8_1;
    $targetVersion = TargetVersion::PHP7_2_COMPAT;
}

// append other configs, so we don't loose config state
$formatToggleUrl .= '&target-version='.rex_escape($targetVersion, 'url');
$versionToggleUrl .= '&format='.$outputFormat;

$result = RexFactor::runRexFactor($addon, $setList, $targetVersion, true);

$html = '';
$total = $result->getTotals();
if ($total['changed_files'] > 0) {

    $diffHtml = new DiffHtml($result, $outputFormat);
    $diff = DiffHtml::getHead();
    $diff .= $diffHtml->renderHtml();

    echo '<h2>Migration preview</h2>';

    echo '<p>AddOn: '. rex_escape($addon) .'</p>';
    echo '<p>Target Version: '. rex_escape($targetVersion) .'</p>';
    echo '<p>Diff Format: '. $outputFormat .'</p>';

    echo '<a class="btn btn-info" href="'. $backUrl .'">back</a>';
    echo '<a class="btn btn-default" href="'. $formatToggleUrl .'">Change Format: '. $formatToggleLabel .'</a>';
    echo '<a class="btn btn-default" href="'. $versionToggleUrl .'">Change Target-Version: '. $versionToggleLabel .'</a>';
    echo '<a class="btn btn-save" href="'. $applyUrl .'" data-confirm="Source files will be overwritten. continue?">Apply changes</a>';

    echo '<div style="margin-top: 10px"></div>';
    echo '<div style="background: unset; color: unset;">'.$diff.'</div>';


} else {
    echo '<h2>Code is shiny. Nothing todo for this migration - move along.</h2>';

    echo '<a class="btn btn-info" href="'. $backUrl .'">back</a>';
}
