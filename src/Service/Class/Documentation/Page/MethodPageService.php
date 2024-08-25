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
use Contract\Pipeline\MethodPageMarkerPipelineInterface;
use Contract\Service\Class\Documentation\Page\MethodPageServiceInterface;
use Contract\Service\File\DocFileServiceInterface;
use Contract\Service\File\Template\TemplateServiceProviderInterface;
use Dto\Documentation\DocPage;
use Dto\Method\Method;
use Illuminate\Support\Collection;
use Service\Class\Filter\PageTitleFilter;
use Webmozart\Assert\Assert;

final readonly class MethodPageService implements MethodPageServiceInterface
{
    private const string METHODPAGE_TEMPLATE_SERVICE_KEY = 'method';

    public function __construct(
        private MethodPageMarkerPipelineInterface $methodPageMPipeline,
        private TemplateServiceProviderInterface $tmplServiceProvider,
        private DocFileServiceInterface $docFileService
    ) {}

    /**
     * @psalm-param non-empty-string $format
     * @psalm-param non-empty-string $lang
     */
    public function generateMethodPage(Method $method, string $format, string $lang): DocPage
    {
        Assert::stringNotEmpty($format);
        Assert::stringNotEmpty($lang);

        $markers = $this->methodPageMPipeline->handlePipeline($method, $format, $lang);
        $pageContent = $this->tmplServiceProvider
            ->getService(self::METHODPAGE_TEMPLATE_SERVICE_KEY)
            ->fillTemplate($markers);

        Assert::stringNotEmpty($pageContent);

        return DocPage::create(
            $pageContent,
            $method->getName(),
            sprintf('%s_%s', $method->getClass(), $method->getName()),
            $this->docFileService->getFileExtensionByFormat($format)
        );
    }

    /**
     * @param Collection<int, Method> $methods
     * @return Collection<int, DocPage>
     */
    public function getPages(Collection $methods, string $lang, string $format): Collection
    {
        Assert::stringNotEmpty($format);
        Assert::stringNotEmpty($lang);

        /** @var Collection<int, DocPage> $docPageCollection */
        $docPageCollection = Collection::make();
        $fileExtension = $this->getFileExtension($format);
        /** @var ArrayIterator $iterator */
        $iterator = $methods->getIterator();

        while ($iterator->valid()) {
            /** @var Method $method */
            $method = $iterator->current();
            $methodPage = $this->methodPageGenerator->generate($method, $format, $lang);
            $methodName = $method->getName();
            $fileName = sprintf('%s_%s', $method->getClass(), $methodName);

            Assert::stringNotEmpty($methodPage);
            Assert::stringNotEmpty($fileName);
            Assert::stringNotEmpty($fileExtension);

            $dto = DocPage::create(
                $methodPage,
                $methodName,
                $fileName,
                $fileExtension
            );

            $docPageCollection->push($dto);
            $iterator->next();
        }

        return Collection::make($docPageCollection->filter(function ($value) {
            return (new PageTitleFilter('__construct'))->hasNotPageTitle($value);
        })->toArray());
    }
}
