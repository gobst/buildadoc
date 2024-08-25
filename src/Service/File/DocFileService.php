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

use Contract\Service\File\DocFileServiceInterface;
use Contract\Service\File\FileExtensionInterface;
use Contract\Service\File\FileServiceInterface;
use Dto\Documentation\DocPage;
use Illuminate\Support\Collection;
use Webmozart\Assert\Assert;
use Webmozart\Assert\InvalidArgumentException;

final readonly class DocFileService implements DocFileServiceInterface, FileExtensionInterface
{
    private const string FORMAT_DOKUWIKI_KEY = 'dokuwiki';

    public function __construct(
        public FileServiceInterface $fileService
    )
    {
    }

    /**
     * @param Collection<int, DocPage> $pages
     */
    public function dumpDocFiles(Collection $pages, string $destDirectory): void
    {
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

    /**
     * @psalm-param non-empty-string $format
     * @psalm-return non-empty-string
     * @throws InvalidArgumentException
     */
    public function getFileExtensionByFormat(string $format): string
    {
        Assert::stringNotEmpty($format);

        return match ($format) {
            self::FORMAT_DOKUWIKI_KEY => self::DOKUWIKI_FILE_EXTENSION,
            default => throw new InvalidArgumentException("Unknown format '$format'"),
        };
    }
}
