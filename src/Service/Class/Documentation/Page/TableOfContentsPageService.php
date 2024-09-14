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

use Contract\Pipeline\TableOfContentsPageMarkerPipelineInterface;
use Contract\Service\Class\Documentation\Page\TableOfContentsPageServiceInterface;
use Contract\Service\File\DocFileServiceInterface;
use Contract\Service\File\Template\TemplateServiceProviderInterface;
use Dto\Documentation\DocPage;
use Illuminate\Support\Collection;
use Webmozart\Assert\Assert;
use Webmozart\Assert\InvalidArgumentException;

final readonly class TableOfContentsPageService implements TableOfContentsPageServiceInterface
{
    private const string TABLEOFCONTENTS_TEMPLATE_SERVICE_KEY = 'tableofcontents';
    private const string PAGE_TITLE = 'Table of contents';
    private const string PAGE_FILENAME = 'tableofcontents';

    public function __construct(
        private DocFileServiceInterface                    $docFileService,
        private TableOfContentsPageMarkerPipelineInterface $tableOfContPMPipe,
        private TemplateServiceProviderInterface           $tmplServiceProvider
    )
    {
    }

    /**
     * @psalm-param non-empty-string $format
     * @psalm-param non-empty-string $lang
     * @throws InvalidArgumentException
     */
    public function generateTableOfContentsPage(
        Collection $classes,
        string $format,
        string $lang,
        string $mainDirectory
    ): DocPage
    {
        Assert::stringNotEmpty($format);
        Assert::stringNotEmpty($lang);

        $markers = $this->tableOfContPMPipe->handlePipeline($classes, $format, $lang, $mainDirectory);
        $pageContent = $this->tmplServiceProvider
            ->getService(self::TABLEOFCONTENTS_TEMPLATE_SERVICE_KEY)
            ->fillTemplate($markers);

        Assert::stringNotEmpty($pageContent);

        return DocPage::create(
            $pageContent,
            self::PAGE_TITLE,
            self::PAGE_FILENAME,
            $this->docFileService->getFileExtensionByFormat($format)
        );
    }
}
