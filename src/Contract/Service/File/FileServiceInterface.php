<?php
/**
 * This file is part of BuildADoc.
 *
 * (c) Guido Obst
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

declare(strict_types = 1);

namespace Contract\Service\File;

use Collection\FileCollection;
use Dto\Common\File;

interface FileServiceInterface
{
    /**
     * @psalm-param non-empty-string $directory
     * @psalm-param non-empty-string $extension
     */
    public function getAllFilesWithinDir(string $directory, FileCollection $files, array $excludeFiles = [], string $extension = 'php'): FileCollection;

    /**
     * @psalm-param non-empty-string $filename
     */
    public function dumpFile(string $filename, string $content): void;

    /**
     * @psalm-param non-empty-string $phpFile
     */
    public function getSingleFile(string $phpFile, FileCollection $files): File;
}
