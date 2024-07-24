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

namespace Service\Class\Documentation\Page;

use ArrayIterator;
use Collection\ClassCollection;
use Collection\DocPageCollection;
use Contract\Formatter\DokuWikiFormatInterface;
use Contract\Generator\Documentation\Class\Page\Class\ClassPageGeneratorInterface;
use Contract\Service\Class\Documentation\Page\ClassPageServiceInterface;
use Contract\Service\Class\Documentation\Page\MethodPageServiceInterface;
use Contract\Service\File\DocFileServiceInterface;
use Dto\Class\ClassDto;
use Dto\Common\Modifier;
use Dto\Documentation\DocPage;
use Dto\Method\Method;
use Illuminate\Support\Collection;
use Webmozart\Assert\Assert;

final readonly class ClassPageService implements ClassPageServiceInterface, DokuWikiFormatInterface
{
    private const string FILE_EXTENSION_SUFFIX = 'FILE_EXTENSION';

    public function __construct(
        private ClassPageGeneratorInterface $classPageGenerator,
        private DocFileServiceInterface $docFileService,
        private MethodPageServiceInterface $methodPageService
    ) {}

    /**
     * @param Collection<int, ClassDto> $classes
     */
    public function dumpPages(Collection $classes, string $destDirectory, string $lang, string $format): void
    {
        Assert::stringNotEmpty($format);
        Assert::stringNotEmpty($lang);

        $this->docFileService->dumpDocFiles($this->getPages($classes, $lang, $format), $destDirectory);
    }

    /**
     * @psalm-param non-empty-string $lang
     * @psalm-param non-empty-string $format
     * @param Collection<int, ClassDto> $classes
     * @return Collection<int, DocPage>
     */
    private function getPages(Collection $classes, string $lang, string $format): Collection
    {
        /** @var Collection<int, DocPage> $pages */
        $pages = Collection::make();
        $fileExtension = $this->getFileExtension($format);
        /** @var ArrayIterator $iterator */
        $iterator = $classes->getIterator();

        while ($iterator->valid()) {
            /** @var ClassDto $class */
            $class = $iterator->current();

            $classPageContent = $this->classPageGenerator->generate($class, $format, $lang);
            $className = $class->getName();
            $classNamespace = empty($class->getNamespace()) ? '' : $class->getNamespace() . '_';
            $fileName = sprintf('%s%s', $classNamespace, $className);

            Assert::stringNotEmpty($classPageContent);
            Assert::stringNotEmpty($fileName);
            Assert::stringNotEmpty($fileExtension);

            $page = DocPage::create(
                $classPageContent,
                $className,
                $fileName,
                $fileExtension
            );
            $pages->push($page);

            $methods = $class->getMethods();
            /** @var Collection<int, DocPage> $methodPages */
            $methodPages = $this->methodPageService->getPages($methods, $lang, $format);
            $pages = $pages->merge($methodPages);

            $iterator->next();
        }

        return Collection::make($pages->toArray());
    }

    private function getFileExtension(string $format): string
    {
        return self::{sprintf('%s_%s', strtoupper($format), self::FILE_EXTENSION_SUFFIX)};
    }
}
