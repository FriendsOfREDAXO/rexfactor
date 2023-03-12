<?php

namespace rexfactor;

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
     * @return list<string>
     */
    public function getChangedFiles(): array {
        return $this->json['changed_files'];
    }

    /**
     * @return list<array{file: string, diff: string, applied_rectors: list<string>}>
     */
    public function getFileDiffs(): array {
        return $this->json['file_diffs'];
    }
}
