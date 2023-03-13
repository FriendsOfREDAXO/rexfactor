<?php

namespace rexfactor;

use rex_path;

final class CsFixerResult {
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
        $changedFiles = 0;

        foreach($this->json['files'] as $files) {
            $changedFiles++;
        }

        return ['changed_files' => $changedFiles, 'errors' => 0];
    }

    /**
     * @return list<array{file: string, diff: string}>
     */
    public function getFileDiffs(): array {
        $addonsPath = rex_path::src();

        $fileDiffs = [];
        foreach($this->json['files'] as $files) {
            $files['diff'] = str_replace($addonsPath, 'src/', $files['diff']);

            $fileDiffs[] = ['file' => $files['name'], 'diff' => $files['diff']];
        }
        return $fileDiffs;
    }
}
