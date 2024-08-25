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

use Contract\Service\Class\Data\ClassDataServiceInterface;
use Contract\Service\Class\Documentation\ClassDocumentationServiceInterface;
use Contract\Service\Class\Documentation\Page\ClassPageServiceInterface;
use Contract\Service\File\FileServiceInterface;
use Dto\Class\ClassDto;
use Dto\Common\File;
use Illuminate\Support\Collection;
use Webmozart\Assert\Assert;

final readonly class ClassDocumentationService implements ClassDocumentationServiceInterface
{
    public function __construct(
        private ClassDataServiceInterface $classDataService,
        private FileServiceInterface $fileService,
        private ClassPageServiceInterface $classPageService
    ) {}

    public function buildDocumentation(string $sourceDir, string $destDir, string $lang, string $format): void
    {
        Assert::stringNotEmpty($sourceDir);
        Assert::stringNotEmpty($destDir);
        Assert::stringNotEmpty($lang);
        Assert::stringNotEmpty($format);

        #$this->classPageService->dumpPages($this->getClasses($sourceDir), $destDir, $lang, $format);
    }

    /**
     * @psalm-param non-empty-string $sourceDir
     * @return Collection<int, ClassDto>
     */
    private function getClasses(string $sourceDir): Collection
    {
        return $this->classDataService->getAllClasses($this->getFiles($sourceDir));
    }

    /**
     * @psalm-param non-empty-string $sourceDir
     * @return Collection<int, File>
     */
    private function getFiles(string $sourceDir): Collection
    {
        return $this->fileService->getAllFilesWithinDir($sourceDir, Collection::make());
    }
}
