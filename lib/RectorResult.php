<?php

namespace rexfactor;

use rex_path;

final class RectorResult {
    /**
     * @var array<string, mixed>
     */
    private $json;

    public function __construct(string $json) {
        $this->json = json_decode($json, true);
        if (!is_array($this->json)) {
            throw new \InvalidArgumentException('Invalid json: '.json_last_error_msg());
        }
    }

    /**
     * @return array{changed_files: int, errors: int}
     */
    public function getTotals(): array {
        return $this->json['totals'];
    }

    /**
     * @return list<array{file: string, diff: string}>
     */
    public function getFileDiffs(): array {
        foreach ($this->json['file_diffs'] as &$fileDiff) {
            // strip file indicators rendered by rector
            $fileDiff['diff'] = str_replace('--- Original', '', $fileDiff['diff']);
            $fileDiff['diff'] = str_replace('+++ New', '', $fileDiff['diff']);

            $fileDiff['diff'] = "
diff --git a/{$fileDiff['file']} b/{$fileDiff['file']}
--- a/{$fileDiff['file']}
+++ b/{$fileDiff['file']}
{$fileDiff['diff']}
            ";

        }

        return $this->json['file_diffs'];
    }
}
