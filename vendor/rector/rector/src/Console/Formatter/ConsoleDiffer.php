<?php

declare (strict_types=1);
namespace Rector\Core\Console\Formatter;

use RectorPrefix202304\SebastianBergmann\Diff\Differ;
use RectorPrefix202304\SebastianBergmann\Diff\Output\StrictUnifiedDiffOutputBuilder;
use RectorPrefix202304\SebastianBergmann\Diff\Output\UnifiedDiffOutputBuilder;
final class ConsoleDiffer
{
    /**
     * @readonly
     * @var \SebastianBergmann\Diff\Differ
     */
    private $differ;
    /**
     * @readonly
     * @var \Rector\Core\Console\Formatter\ColorConsoleDiffFormatter
     */
    private $colorConsoleDiffFormatter;
    public function __construct(\Rector\Core\Console\Formatter\ColorConsoleDiffFormatter $colorConsoleDiffFormatter)
    {
        $this->colorConsoleDiffFormatter = $colorConsoleDiffFormatter;
        // @see https://github.com/sebastianbergmann/diff#strictunifieddiffoutputbuilder
        // @see https://github.com/sebastianbergmann/diff/compare/4.0.4...5.0.0#diff-251edf56a6344c03fa264a4926b06c2cee43c25f66192d5f39ebee912b7442dc for upgrade
        $unifiedDiffOutputBuilder = new UnifiedDiffOutputBuilder();
        $this->differ = new Differ($unifiedDiffOutputBuilder);
    }
    public function diff(string $old, string $new) : string
    {
        $diff = $this->differ->diff($old, $new);
        return $this->colorConsoleDiffFormatter->format($diff);
    }
}
