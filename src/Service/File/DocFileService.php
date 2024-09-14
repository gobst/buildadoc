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
declare(strict_types=1);

namespace Service\File;

use Contract\Formatter\DokuWikiFormatInterface;
use Contract\Service\File\DocFileServiceInterface;
use Contract\Service\File\FileExtensionInterface;
use Contract\Service\File\FileServiceInterface;
use Dto\Documentation\DocPage;
use Illuminate\Support\Collection;
use Webmozart\Assert\Assert;
use Webmozart\Assert\InvalidArgumentException;

final readonly class DocFileService implements DocFileServiceInterface, FileExtensionInterface, DokuWikiFormatInterface
{
    public function __construct(
        private FileServiceInterface $fileService
    )
    {
    }

    /**
     * @param Collection<int, DocPage> $pages
     * @throws InvalidArgumentException
     */
    public function dumpClassDocFiles(Collection $pages, string $destDirectory, string $mainDir): void
    {
        Assert::stringNotEmpty($destDirectory);
        Assert::stringNotEmpty($mainDir);

        $mainDirPath = sprintf('%s%s', $destDirectory, $mainDir);
        if (!$this->fileService->directoryExists($mainDirPath)) {
            $this->fileService->makeDirectory($mainDirPath);
        }

        /** @var DocPage $classPage */
        $classPage = $pages->first();
        $pageDir = sprintf('%s/%s', $mainDirPath, strtolower($classPage->getTitle()));
        if (!$this->fileService->directoryExists($pageDir)) {
            $this->fileService->makeDirectory($pageDir);
        }

        $iterator = $pages->getIterator();
        while ($iterator->valid()) {
            /** @var DocPage $page */
            $page = $iterator->current();

            $pageFile = sprintf(
                '%s/%s.%s',
                $pageDir,
                strtolower($page->getFileName()),
                $page->getFileExtension()
            );

            if(!$this->fileService->directoryExists($pageFile)){
                $this->fileService->dumpFile(
                    $pageFile,
                    $page->getContent()
                );
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
            self::DOKUWIKI_FORMAT_KEY => self::DOKUWIKI_FILE_EXTENSION,
            default => throw new InvalidArgumentException("Unknown format '$format'"),
        };
    }
}
