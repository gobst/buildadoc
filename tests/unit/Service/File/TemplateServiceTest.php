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

namespace unit\Service\File;

use Contract\Generator\Documentation\Class\Page\Class\Marker\MethodPageMarkerInterface;
use Dto\Common\Marker;
use Exception\TemplateNotFoundException;
use Illuminate\Support\Collection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Service\File\Filter\MarkerNameFilter;
use Service\File\Template\TemplateService;
use Webmozart\Assert\InvalidArgumentException;

#[CoversClass(TemplateService::class)]
#[UsesClass(Marker::class)]
#[UsesClass(Collection::class)]
#[UsesClass(MarkerNameFilter::class)]
final class TemplateServiceTest extends TestCase
{
    private TemplateService $templateService;

    public function setUp(): void
    {
        $this->templateService = new TemplateService();
    }
    #[TestDox('fillTemplate() method works correctly')]
    public function testFillTemplate(): void
    {
        $expectedContent = file_get_contents(__DIR__.'/../../../data/dokuwiki/methodTmplWithContent.txt');

        $collection = Collection::make();
        $collection->push(Marker::create('HEADING')->withValue('Mega'));

        $collection = $this->templateService->initEmptyMarkers($collection, MethodPageMarkerInterface::class);

        $actualContent = $this->templateService->fillTemplate(
            __DIR__.'/../../../../res/templates/dokuwiki/methodTmpl.txt',
            $collection
        );

        $this->assertSame($expectedContent, $actualContent);
    }

    #[DataProvider('templateServiceFailsOnInvalidArgumentExceptionTestDataProvider')]
    #[TestDox('fillTemplate() fails on InvalidArgumentException with parameters: $template, $markers')]
    public function testFillTemplateFailsOnInvalidArgumentException($template, $markers): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->templateService->fillTemplate($template, $markers);
    }

    #[TestDox('fillTemplate() fails on TemplateNotFoundException')]
    public function testFillTemplateFailsOnTemplateNotFoundException(): void
    {
        $this->expectException(TemplateNotFoundException::class);

        $collection = Collection::make();
        $collection->push(Marker::create('HEADING')->withValue('Mega'));

        $this->templateService->fillTemplate(
            __DIR__.'/../../../data/dokuwiki/unknown.txt',
            $collection
        );
    }

    public static function templateServiceFailsOnInvalidArgumentExceptionTestDataProvider(): array
    {
        return [
            'testcase 1' => ['', Collection::make()->push(Marker::create('HEADING')->withValue('Mega'))],
        ];
    }
}
