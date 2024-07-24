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

namespace unit\Generator\Documentation\Class\Page\Class\Marker;

use Contract\Generator\Documentation\Class\Page\Component\Heading\HeadingGeneratorInterface;
use Contract\Generator\Documentation\Class\Page\Component\Method\MethodLineGeneratorInterface;
use Contract\Service\Translation\TranslationServiceInterface;
use Dto\Class\ClassDto;
use Dto\Common\Modifier;
use Dto\Method\Method;
use Dto\Method\MethodParameter;
use Generator\Documentation\Class\Page\Class\Marker\ConstructorMarkerGenerator;
use Illuminate\Support\Collection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Service\Class\Filter\MethodNameFilter;
use Webmozart\Assert\InvalidArgumentException;

#[CoversClass(ConstructorMarkerGenerator::class)]
#[UsesClass(Modifier::class)]
#[UsesClass(MethodParameter::class)]
#[UsesClass(Method::class)]
#[UsesClass(ClassDto::class)]
#[UsesClass(MethodNameFilter::class)]
#[UsesClass(Collection::class)]
final class ConstructorMarkerGeneratorTest extends TestCase
{
    private TranslationServiceInterface&MockObject $translationService;
    private HeadingGeneratorInterface&MockObject $headingGenerator;
    private MethodLineGeneratorInterface&MockObject $methodLineGenerator;
    private ConstructorMarkerGenerator $constructorMarkerGen;

    public function setUp(): void
    {
        $this->translationService = $this->getMockBuilder(TranslationServiceInterface::class)->getMock();
        $this->headingGenerator = $this->getMockBuilder(HeadingGeneratorInterface::class)->getMock();
        $this->methodLineGenerator = $this->getMockBuilder(MethodLineGeneratorInterface::class)->getMock();
        $this->constructorMarkerGen = new ConstructorMarkerGenerator(
            $this->translationService,
            $this->headingGenerator,
            $this->methodLineGenerator
        );
    }

    #[TestDox('generate() method works correctly')]
    public function testGenerate(): void
    {
        $lineBreak = chr(13) . chr(13);

        $this->translationService->expects(self::once())
            ->method('setLanguage');

        $this->translationService->expects(self::once())
            ->method('translate')
            ->willReturn('text1');

        $this->headingGenerator->expects(self::once())
            ->method('generate')
            ->willReturn('testStr');

        $this->methodLineGenerator->expects(self::once())
            ->method('generate')
            ->willReturn('testStr2');

        $expectedMarker = [];
        $expectedMarker['###CONSTRUCTOR_HEADING###'] = 'testStr' . $lineBreak;
        $expectedMarker['###CONSTRUCTOR###'] = 'testStr2' . $lineBreak;

        $actualMarker = $this->constructorMarkerGen->generate(
            $this->getTestClassDtoWithConstructor(),
            'dokuwiki',
            'de'
        );

        $this->assertSame($expectedMarker, $actualMarker);
    }

    #[DataProvider('constructorMarkerGeneratorTestDataProvider')]
    #[TestDox('generate() method fails on InvalidArgumentException with parameters $format, $lang')]
    public function testGenerateFailsOnInvalidArgumentException($format, $lang): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->translationService->expects(self::never())
            ->method('setLanguage');

        $this->translationService->expects(self::never())
            ->method('translate');

        $this->headingGenerator->expects(self::never())
            ->method('generate');

        $this->methodLineGenerator->expects(self::never())
            ->method('generate');

        $this->constructorMarkerGen->generate($this->getTestClassDtoWithConstructor(), $format, $lang);
    }

    #[TestDox('generate() method fails on empty translation')]
    public function testGenerateFailsOnEmptyTranslation(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->translationService->expects(self::once())
            ->method('setLanguage');

        $this->translationService->expects(self::once())
            ->method('translate')
            ->willReturn('');

        $this->headingGenerator->expects(self::never())
            ->method('generate');

        $this->methodLineGenerator->expects(self::never())
            ->method('generate');

        $this->constructorMarkerGen->generate($this->getTestClassDtoWithConstructor(), 'dokuwiki', 'de');
    }

    #[TestDox('generate() method works correctly without constructor')]
    public function testGenerateWithoutConstructor(): void
    {
        $this->translationService->expects(self::once())
            ->method('setLanguage');

        $this->translationService->expects(self::never())
            ->method('translate');

        $this->headingGenerator->expects(self::never())
            ->method('generate');

        $this->methodLineGenerator->expects(self::never())
            ->method('generate');

        $this->assertSame(
            [],
            $this->constructorMarkerGen->generate(
                $this->getTestClassDtoWithoutConstructor(),
                'dokuwiki',
                'de')
        );
    }

    private function getTestClassDtoWithConstructor(): ClassDto
    {
        /** @var Collection<int, Method> $methods */
        $methods = Collection::make();

        /** @var Collection<int, Modifier> $modifiers */
        $modifiers = Collection::make();
        $publicModifierDto = Modifier::create('public');
        $modifiers->push($publicModifierDto);

        /** @var Collection<int, MethodParameter> $parameters */
        $parameters = Collection::make();
        $parameterDto = MethodParameter::create('testString', 'string');
        $parameterDto = $parameterDto->withDefaultValue('test');
        $parameters->push($parameterDto);
        $methodDto = Method::create('testMethodWithoutPHPDoc', $modifiers, 'string', 'testClass');
        $methodDto->withParameters($parameters);
        $methods->push($methodDto);

        /** @var Collection<int, MethodParameter> $parameters */
        $parameters = Collection::make();
        $parameterDto = MethodParameter::create('testInt', 'int');
        $parameterDto = $parameterDto->withDefaultValue(0);
        $parameters->push($parameterDto);
        $methodDto = Method::create('__construct', $modifiers, 'string', 'testClass');
        $methodDto->withParameters($parameters);
        $methods->push($methodDto);

        /** @var Collection<int, ClassDto> $parentClasses */
        $parentClasses = Collection::make();
        $parentClassDto = ClassDto::create(
            'parentTestClass',
            __DIR__ . '/../../../data/classes/parentTestClass.php',
            Collection::make(),
            Collection::make()
        );
        $parentClasses->push($parentClassDto);

        $classDto = ClassDto::create(
            'testClass',
            __DIR__ . '/../../../data/classes/testClass.php',
            $methods,
            Collection::make()
        );

        return $classDto->withParentClasses($parentClasses);
    }

    private function getTestClassDtoWithoutConstructor(): ClassDto
    {
        /** @var Collection<int, Method> $methods */
        $methods = Collection::make();

        /** @var Collection<int, Modifier> $modifiers */
        $modifiers = Collection::make();
        $publicModifierDto = Modifier::create('public');
        $modifiers->push($publicModifierDto);

        /** @var Collection<int, MethodParameter> $parameters */
        $parameters = Collection::make();
        $parameterDto = MethodParameter::create('testString', 'string');
        $parameterDto = $parameterDto->withDefaultValue('test');
        $parameters->push($parameterDto);
        $methodDto = Method::create('testMethodWithoutPHPDoc', $modifiers, 'string', 'testClass');
        $methodDto->withParameters($parameters);
        $methods->push($methodDto);

        /** @var Collection<int, ClassDto> $parentClasses */
        $parentClasses = Collection::make();
        $parentClassDto = ClassDto::create(
            'parentTestClass',
            __DIR__ . '/../../../data/classes/parentTestClass.php',
            Collection::make(),
            Collection::make()
        );
        $parentClasses->push($parentClassDto);

        $classDto = ClassDto::create(
            'testClass',
            __DIR__ . '/../../../data/classes/testClass.php',
            $methods,
            Collection::make()
        );

        return $classDto->withParentClasses($parentClasses);
    }

    public static function constructorMarkerGeneratorTestDataProvider(): array
    {
        return [
            'testcase 1' => ['', 'de'],
            'testcase 2' => ['dokuwiki', ''],
            'testcase 3' => ['', '']
        ];
    }
}
