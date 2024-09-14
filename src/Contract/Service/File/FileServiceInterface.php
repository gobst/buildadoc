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

use Dto\Common\File;
use Illuminate\Support\Collection;

interface FileServiceInterface
{
    /**
     * @psalm-param non-empty-string $directory
     * @psalm-param non-empty-string $extension
     * @param Collection<int, File> $files
     * @return Collection<int, File>
     */
    public function getAllFilesWithinDir(
        string $directory,
        Collection $files,
        array $excludeFiles = [],
        string $extension = 'php'
    ): Collection;

    /**
     * @psalm-param non-empty-string $filename
     */
    public function dumpFile(string $filename, string $content): void;

    /**
     * @psalm-param non-empty-string $phpFile
     * @param Collection<int, File> $files
     */
    public function getSingleFile(string $phpFile, Collection $files): ?File;

    /**
     * @psalm-param non-empty-string $dir
     */
    public function makeDirectory(string $dir): void;

    /**
     * @psalm-param non-empty-string $dir
     */
    public function directoryExists(string $dir): bool;
}
