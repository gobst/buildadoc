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

namespace Service\Class\Documentation\Page;

use ArrayIterator;
use Contract\Pipeline\ClassPageMarkerPipelineInterface;
use Contract\Service\Class\Documentation\Page\ClassPageServiceInterface;
use Contract\Service\Class\Documentation\Page\MethodPageServiceInterface;
use Contract\Service\File\DocFileServiceInterface;
use Contract\Service\File\FileExtensionInterface;
use Contract\Service\File\Template\TemplateServiceProviderInterface;
use Dto\Class\ClassDto;
use Dto\Documentation\DocPage;
use Dto\Method\Method;
use Illuminate\Support\Collection;
use Webmozart\Assert\Assert;
use Webmozart\Assert\InvalidArgumentException;

final readonly class ClassPageService implements ClassPageServiceInterface, FileExtensionInterface
{
    private const string CLASSPAGE_TEMPLATE_SERVICE_KEY = 'class';

    public function __construct(
        private DocFileServiceInterface          $docFileService,
        private MethodPageServiceInterface       $methodPageService,
        private ClassPageMarkerPipelineInterface $classPageMPipeline,
        private TemplateServiceProviderInterface $tmplServiceProvider
    ) {
    }

    /**
     * @psalm-param non-empty-string $format
     * @psalm-param non-empty-string $lang
     * @return Collection<int, DocPage>
     */
    public function generateClassPageIncludingMethodPages(
        ClassDto $class,
        string $format,
        string $lang,
        string $mainDirectory
    ): Collection {
        Assert::stringNotEmpty($format);
        Assert::stringNotEmpty($lang);

        /** @var Collection<int, DocPage> $docPages */
        $docPages = Collection::make();

        $markers = $this->classPageMPipeline->handlePipeline($class, $format, $lang, $mainDirectory);
        $pageContent = $this->tmplServiceProvider
            ->getService(self::CLASSPAGE_TEMPLATE_SERVICE_KEY)
            ->fillTemplate($markers);

        Assert::stringNotEmpty($pageContent);

        $docPages->push(
            DocPage::create(
                $pageContent,
                $class->getName(),
                $class->getName(),
                $this->docFileService->getFileExtensionByFormat($format)
            )
        );

        return $this->fetchMethodPages($class, $format, $lang, $docPages);
    }

    /**
     * @param Collection<int, DocPage> $docPages
     * @return Collection<int, DocPage>
     * @throws InvalidArgumentException
     */
    private function fetchMethodPages(ClassDto $class, string $format, string $lang, Collection $docPages): Collection
    {
        Assert::stringNotEmpty($format);
        Assert::stringNotEmpty($lang);

        $methods = $class->getMethods();
        /** @var ArrayIterator $iterator */
        $iterator = $methods->getIterator();
        while ($iterator->valid()) {
            /** @var Method $method */
            $method = $iterator->current();
            $docPages->push($this->methodPageService->generateMethodPage($method, $format, $lang));
            $iterator->next();
        }

        return $docPages;
    }
}
