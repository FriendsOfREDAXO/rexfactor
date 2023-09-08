<?php

declare (strict_types=1);
namespace Rector\VersionBonding;

use Rector\Core\Contract\Rector\RectorInterface;
use Rector\Core\Php\PhpVersionProvider;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
final class PhpVersionedFilter
{
    /**
     * @readonly
     * @var \Rector\Core\Php\PhpVersionProvider
     */
    private $phpVersionProvider;
    public function __construct(PhpVersionProvider $phpVersionProvider)
    {
        $this->phpVersionProvider = $phpVersionProvider;
    }
    /**
     * @param array<RectorInterface> $rectors
     * @return array<RectorInterface>
     */
    public function filter(iterable $rectors) : array
    {
        $minProjectPhpVersion = $this->phpVersionProvider->provide();
        $activeRectors = [];
        foreach ($rectors as $rector) {
            if (!$rector instanceof MinPhpVersionInterface) {
                $activeRectors[] = $rector;
                continue;
            }
            // does satisfy version? → include
            if ($rector->provideMinPhpVersion() <= $minProjectPhpVersion) {
                $activeRectors[] = $rector;
            }
        }
        return $activeRectors;
    }
}
