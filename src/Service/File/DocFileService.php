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

namespace Service\File;

use ArrayIterator;
use Collection\DocPageCollection;
use Contract\Service\File\DocFileServiceInterface;
use Contract\Service\File\FileServiceInterface;
use Dto\Documentation\DocPage;

final readonly class DocFileService implements DocFileServiceInterface
{
    public function __construct(
        private FileServiceInterface $fileService
    ) {}

    public function dumpDocFiles(DocPageCollection $pages, string $destDirectory): void
    {
        /** @var ArrayIterator $iterator */
        $iterator = $pages->getIterator();
        while ($iterator->valid()) {
            /** @var DocPage $page */
            $page = $iterator->current();
            $pageFile = sprintf('%s/%s.%s', $destDirectory, $page->getFileName(), $page->getFileExtension());
            if (!file_exists($pageFile)) {
                $this->fileService->dumpFile($pageFile, $page->getContent());
            }
            $iterator->next();
        }
    }
}
