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

use Dto\Documentation\DocPage;
use Illuminate\Support\Collection;

interface DocFileServiceInterface
{
    /**
     * @param Collection<int, DocPage> $pages
     * @psalm-param non-empty-string $destDirectory
     * @psalm-param non-empty-string $mainDir
     */
    public function dumpClassDocFiles(Collection $pages, string $destDirectory, string $mainDir): void;

    /**
     * @psalm-param non-empty-string $format
     * @psalm-return non-empty-string
     */
    public function getFileExtensionByFormat(string $format): string;
}
