<?php

declare (strict_types=1);
namespace RectorPrefix202310\Symplify\EasyParallel\FileSystem;

use RectorPrefix202310\Symplify\SmartFileSystem\SmartFileInfo;
/**
 * @api
 */
final class FilePathNormalizer
{
    /**
     * @param SmartFileInfo[] $fileInfos
     * @return string[]
     */
    public function resolveFilePathsFromFileInfos(array $fileInfos) : array
    {
        $filePaths = [];
        foreach ($fileInfos as $fileInfo) {
            $filePaths[] = $fileInfo->getRelativeFilePathFromCwd();
        }
        return $filePaths;
    }
}
