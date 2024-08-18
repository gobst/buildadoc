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

use Exception;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use Service\File\Template\TemplateService;
use Webmozart\Assert\InvalidArgumentException;

#[CoversClass(TemplateService::class)]
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
        $expectedContent = file_get_contents(__DIR__.'/../../../data/dokuwiki/methodTmplWithContent.txt',);

        $actualContent = $this->templateService->fillTemplate(
            __DIR__.'/../../../../res/templates/dokuwiki/methodTmpl.txt',
            ['###HEADING###' => 'Mega']
        );

        $this->assertSame($expectedContent, $actualContent);
    }

    #[DataProvider('templateServiceFailsOnInvalidArgumentExceptionTestDataProvider')]
    #[TestDox('fillTemplate() fails on InvalidArgumentException with parameters: $template, $marker')]
    public function testFillTemplateFailsOnInvalidArgumentException($template, $marker): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->templateService->fillTemplate($template, $marker);
    }

    #[TestDox('fillTemplate() fails on TemplateNotFoundException')]
    public function testFillTemplateFailsOnTemplateNotFoundException(): void
    {
        $this->expectException(Exception::class);

        $this->templateService->fillTemplate(
            __DIR__.'/../../../data/dokuwiki/unknown.txt',
            ['###HEADING###' => 'Mega']
        );
    }

    public static function templateServiceFailsOnInvalidArgumentExceptionTestDataProvider(): array
    {
        return [
            'testcase 1' => ['', ['###HEADING###' => 'Mega']],
            'testcase 2' => [__DIR__.'/../../../../res/templates/dokuwiki/methodTmpl.txt', []]
        ];
    }

}
