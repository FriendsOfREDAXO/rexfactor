<?php

namespace rexfactor;

final class DiffHtml {
    const FORMAT_SIDE_BY_SIDE = 'side-by-side';
    const FORMAT_LINE_BY_LINE = 'line-by-line';

    /**
     * @var RectorResult
     */
    private $result;

    /**
     * @var string
     */
    private $outputFormat;

    /**
     * @param self::FORMAT_* $outputFormat
     */
    public function __construct(RectorResult $result, $outputFormat = self::FORMAT_SIDE_BY_SIDE)
    {
        $this->result = $result;
        $this->outputFormat = $outputFormat;
    }

    public function renderHtml(): string
    {
        return "
        <script>
        const diffString = `{$this->getDiffString()}`;

        document.addEventListener('DOMContentLoaded', function () {
          var targetElement = document.getElementById('my-diff-view');
          var configuration = {
            drawFileList: true,
            fileListToggle: false,
            fileListStartVisible: false,
            fileContentToggle: false,
            matching: 'lines',
            outputFormat: '{$this->outputFormat}',
            synchronisedScroll: true,
            highlight: true,
            renderNothingWhenEmpty: false,
          };
          var diff2htmlUi = new Diff2HtmlUI(targetElement, diffString, configuration);
          diff2htmlUi.draw();
          diff2htmlUi.highlightCode();
        });
        </script>
        <div id='my-diff-view'></div>";
    }

    static public function getHead() {
        return '
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/10.7.1/styles/github.min.css" />
            <link
                rel="stylesheet"
                type="text/css"
                href="https://cdn.jsdelivr.net/npm/diff2html/bundles/css/diff2html.min.css"
            />
            <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/diff2html/bundles/js/diff2html-ui.min.js"></script>
        ';
    }

    private function getDiffString():string {
        $diffString = '';
        foreach ($this->result->getFileDiffs() as $fileDiff) {
            $diffString .= "
diff --git a/{$fileDiff['file']} b/{$fileDiff['file']}
--- a/{$fileDiff['file']}
+++ b/{$fileDiff['file']}
{$fileDiff['diff']}
            ";
        }
        return $diffString;
    }
}
