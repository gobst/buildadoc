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

namespace Service\Class\Documentation;

use ArrayIterator;
use Contract\Service\Class\Data\ClassDataServiceInterface;
use Contract\Service\Class\Documentation\ClassDocumentationServiceInterface;
use Contract\Service\Class\Documentation\Page\ClassPageServiceInterface;
use Contract\Service\File\DocFileServiceInterface;
use Contract\Service\File\FileServiceInterface;
use Dto\Class\ClassDto;
use Dto\Common\File;
use Illuminate\Support\Collection;
use Webmozart\Assert\Assert;

final readonly class ClassDocumentationService implements ClassDocumentationServiceInterface
{
    public function __construct(
        private ClassDataServiceInterface $classDataService,
        private ClassPageServiceInterface $classPageService,
        private DocFileServiceInterface $docFileService,
        private FileServiceInterface $fileService
    )
    {
    }

    public function buildDocumentation(
        string $sourceDir,
        string $destDir,
        string $name,
        string $lang,
        string $format
    ): void
    {
        Assert::stringNotEmpty($sourceDir);
        Assert::stringNotEmpty($destDir);
        Assert::stringNotEmpty($name);
        Assert::stringNotEmpty($lang);
        Assert::stringNotEmpty($format);

        $classes = $this->fetchClasses($sourceDir);

        /** @var ArrayIterator $iterator */
        $iterator = $classes->getIterator();
        while ($iterator->valid()) {
            /** @var ClassDto $class */
            $class = $iterator->current();
            $docPages = $this->classPageService->generateClassPageIncludingMethodPages($class, $format, $lang, $name);
            $this->docFileService->dumpDocFiles($docPages, $destDir, $name);
            $iterator->next();
        }
    }

    /**
     * @psalm-param non-empty-string $sourceDir
     * @return Collection<int, ClassDto>
     */
    private function fetchClasses(string $sourceDir): Collection
    {
        return $this->classDataService->getAllClasses($this->fetchFiles($sourceDir));
    }

    /**
     * @psalm-param non-empty-string $sourceDir
     * @return Collection<int, File>
     */
    private function fetchFiles(string $sourceDir): Collection
    {
        return $this->fileService->getAllFilesWithinDir($sourceDir, Collection::make());
    }
}
