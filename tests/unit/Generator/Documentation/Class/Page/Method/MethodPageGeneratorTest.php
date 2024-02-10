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

namespace unit\Generator\Documentation\Class\Page\Method;

use Collection\MethodParameterCollection;
use Collection\ModifierCollection;
use Contract\Generator\Documentation\Class\Page\Component\Heading\HeadingGeneratorInterface;
use Contract\Generator\Documentation\Class\Page\Component\Method\MethodLineGeneratorInterface;
use Contract\Generator\Documentation\Class\Page\Component\Method\MethodTableGeneratorInterface;
use Contract\Service\File\TemplateServiceInterface;
use Contract\Service\Translation\TranslationServiceInterface;
use Dto\Common\Modifier;
use Dto\Method\Method;
use Dto\Method\MethodParameter;
use Generator\Documentation\Class\Page\Method\MethodPageGenerator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Webmozart\Assert\InvalidArgumentException;

final class MethodPageGeneratorTest extends TestCase
{
    private TemplateServiceInterface&MockObject $templateService;
    private MethodTableGeneratorInterface&MockObject $methodTableGenerator;
    private MethodLineGeneratorInterface&MockObject $methodLineGenerator;
    private TranslationServiceInterface&MockObject $translationService;
    private HeadingGeneratorInterface&MockObject $headingGenerator;
    private MethodPageGenerator $methodPageGenerator;

    public function setUp(): void
    {
        $this->templateService = $this->getMockBuilder(TemplateServiceInterface::class)->getMock();
        $this->methodTableGenerator = $this->getMockBuilder(MethodTableGeneratorInterface::class)->getMock();
        $this->methodLineGenerator = $this->getMockBuilder(MethodLineGeneratorInterface::class)->getMock();
        $this->translationService = $this->getMockBuilder(TranslationServiceInterface::class)->getMock();
        $this->headingGenerator = $this->getMockBuilder(HeadingGeneratorInterface::class)->getMock();
        $this->methodPageGenerator = new MethodPageGenerator(
            $this->templateService,
            $this->methodTableGenerator,
            $this->methodLineGenerator,
            $this->translationService,
            $this->headingGenerator
        );
    }

    #[DataProvider('methodPageGeneratorTestDataProvider')]
    #[TestDox('generate() method works correctly with parameters $method, $format, $lang')]
    public function testGenerate(Method $method, string $format, string $lang): void
    {
        $this->translationService->expects(self::once())
            ->method('setLanguage');
        $this->translationService->expects(self::exactly(4))
            ->method('translate')
            ->willReturn('Test');
        $this->headingGenerator->expects(self::once())
            ->method('generate')
            ->willReturn('Testheading');
        $this->methodTableGenerator->expects(self::once())
            ->method('generate')
            ->willReturn('Testtable');
        $this->methodLineGenerator->expects(self::once())
            ->method('generate')
            ->willReturn('Testline');
        $this->templateService->expects(self::once())
            ->method('fillTemplate')
            ->willReturn('TestTemplate');

        $this->methodPageGenerator->generate($method, $format, $lang);
    }

    #[DataProvider('methodPageGeneratorExceptionTestDataProvider')]
    #[TestDox('generate() method fails on InvalidArgumentException with parameters $method, $format, $lang')]
    public function testGenerateWillFailOnInvalidArgumentException(Method $method, string $format, string $lang): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->translationService->expects(self::never())->method('setLanguage');
        $this->translationService->expects(self::never())->method('translate');
        $this->headingGenerator->expects(self::never())->method('generate');
        $this->methodTableGenerator->expects(self::never())->method('generate');
        $this->methodLineGenerator->expects(self::never())->method('generate');
        $this->templateService->expects(self::never())->method('fillTemplate');

        $this->methodPageGenerator->generate($method, $format, $lang);
    }

    public static function methodPageGeneratorTestDataProvider(): array
    {
        $modifiers = new ModifierCollection();
        $publicModifierDto = Modifier::create('public');
        $modifiers->add($publicModifierDto);

        $parameters = new MethodParameterCollection();
        $parameterDto = MethodParameter::create('testString', 'string');
        $parameterDto = $parameterDto->withDefaultValue('test');
        $parameters->add($parameterDto);
        $methodDto = Method::create('testMethodWithoutPHPDoc', $modifiers, 'string', 'testClass');
        $methodDto = $methodDto->withParameters($parameters);

        return [
            'testcase 1' => [$methodDto, 'dokuwiki', 'de'],
            'testcase 2' => [$methodDto, 'dokuwiki', 'en'],
        ];
    }

    public static function methodPageGeneratorExceptionTestDataProvider(): array
    {
        $modifiers = new ModifierCollection();
        $publicModifierDto = Modifier::create('public');
        $modifiers->add($publicModifierDto);

        $parameters = new MethodParameterCollection();
        $parameterDto = MethodParameter::create('testString', 'string');
        $parameterDto = $parameterDto->withDefaultValue('test');
        $parameters->add($parameterDto);
        $methodDto = Method::create('testMethodWithoutPHPDoc', $modifiers, 'string', 'testClass');
        $methodDto = $methodDto->withParameters($parameters);

        return [
            'testcase 1' => [$methodDto, '', 'de'],
            'testcase 2' => [$methodDto, 'dokuwiki', ''],
        ];
    }
}
