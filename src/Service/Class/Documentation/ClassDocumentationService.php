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

use Collection\ClassCollection;
use Collection\FileCollection;
use Contract\Service\Class\Data\ClassDataServiceInterface;
use Contract\Service\Class\Documentation\ClassDocumentationServiceInterface;
use Contract\Service\Class\Documentation\Page\ClassPageServiceInterface;
use Contract\Service\File\FileServiceInterface;
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

        $this->classPageService->dumpPages($this->getClasses($sourceDir), $destDir, $lang, $format);
    }

    /**
     * @psalm-param non-empty-string $sourceDir
     */
    private function getClasses(string $sourceDir): ClassCollection
    {
        return $this->classDataService->getAllClasses($this->getFiles($sourceDir));
    }

    /**
     * @psalm-param non-empty-string $sourceDir
     */
    private function getFiles(string $sourceDir): FileCollection
    {
        return $this->fileService->getAllFilesWithinDir($sourceDir, new FileCollection());
    }
}
