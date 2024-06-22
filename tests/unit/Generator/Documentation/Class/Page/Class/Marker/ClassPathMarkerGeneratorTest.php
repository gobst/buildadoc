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

namespace unit\Generator\Documentation\Class\Page\Class\Marker;

use Collection\ClassCollection;
use Collection\MethodCollection;
use Collection\MethodParameterCollection;
use Collection\ModifierCollection;
use Contract\Generator\Documentation\Class\Page\Component\Class\ClassPathGeneratorInterface;
use Contract\Generator\Documentation\Class\Page\Component\Heading\HeadingGeneratorInterface;
use Contract\Service\Translation\TranslationServiceInterface;
use Dto\Class\ClassDto;
use Dto\Common\Modifier;
use Dto\Method\Method;
use Dto\Method\MethodParameter;
use Generator\Documentation\Class\Page\Class\Marker\ClassPathMarkerGenerator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Webmozart\Assert\InvalidArgumentException;

#[CoversClass(ClassPathMarkerGenerator::class)]
#[UsesClass(MethodCollection::class)]
#[UsesClass(Modifier::class)]
#[UsesClass(MethodParameter::class)]
#[UsesClass(MethodParameterCollection::class)]
#[UsesClass(Method::class)]
#[UsesClass(ClassDto::class)]
#[UsesClass(ModifierCollection::class)]
#[UsesClass(ClassCollection::class)]
final class ClassPathMarkerGeneratorTest extends TestCase
{
    private TranslationServiceInterface&MockObject $translationService;
    private HeadingGeneratorInterface&MockObject $headingGenerator;
    private ClassPathGeneratorInterface&MockObject $classPathGenerator;
    private ClassPathMarkerGenerator $classPathMarkerGen;

    public function setUp(): void
    {
        $this->translationService = $this->getMockBuilder(TranslationServiceInterface::class)->getMock();
        $this->headingGenerator = $this->getMockBuilder(HeadingGeneratorInterface::class)->getMock();
        $this->classPathGenerator = $this->getMockBuilder(ClassPathGeneratorInterface::class)->getMock();
        $this->classPathMarkerGen = new ClassPathMarkerGenerator(
            $this->translationService,
            $this->headingGenerator,
            $this->classPathGenerator
        );
    }

    #[TestDox('generate() method works correctly')]
    public function testGenerate(): void
    {
        $classDto = $this->getTestClassDto();

        $this->translationService->expects(self::once())
            ->method('setLanguage');

        $this->translationService->expects(self::once())
            ->method('translate')
            ->willReturn('text1');

        $this->headingGenerator->expects(self::once())
            ->method('generate')
            ->willReturn('testStr');

        $this->classPathGenerator->expects(self::once())
            ->method('generate')
            ->willReturn('testStr2');

        $this->classPathMarkerGen->generate($classDto, 'dokuwiki', 'de');
    }

    #[DataProvider('classPathMarkerGeneratorTestDataProvider')]
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

        $this->classPathGenerator->expects(self::never())
            ->method('generate');

        $this->classPathMarkerGen->generate($this->getTestClassDto(), $format, $lang);
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

        $this->classPathGenerator->expects(self::never())
            ->method('generate');

        $this->classPathMarkerGen->generate($this->getTestClassDto(), 'dokuwiki', 'de');
    }

    private function getTestClassDto(): ClassDto
    {
        $methods = new MethodCollection();

        $modifiers = new ModifierCollection();
        $publicModifierDto = Modifier::create('public');
        $modifiers->add($publicModifierDto);

        $parameters = new MethodParameterCollection();
        $parameterDto = MethodParameter::create('testString', 'string');
        $parameterDto = $parameterDto->withDefaultValue('test');
        $parameters->add($parameterDto);
        $methodDto = Method::create('testMethodWithoutPHPDoc', $modifiers, 'string', 'testClass');
        $methodDto->withParameters($parameters);
        $methods->add($methodDto);

        $parameters = new MethodParameterCollection();
        $parameterDto = MethodParameter::create('testInt', 'int');
        $parameterDto = $parameterDto->withDefaultValue(0);
        $parameters->add($parameterDto);
        $methodDto = Method::create('testMethodWithPHPDoc', $modifiers, 'string', 'testClass');
        $methodDto->withParameters($parameters);
        $methods->add($methodDto);

        $parentClasses = new ClassCollection();
        $parentClassDto = ClassDto::create(
            'parentTestClass',
            __DIR__ . '/../../../data/classes/parentTestClass.php',
            new MethodCollection(),
            new ModifierCollection()
        );
        $parentClasses->add($parentClassDto);

        $classDto = ClassDto::create(
            'testClass',
            __DIR__ . '/../../../data/classes/testClass.php',
            $methods,
            new ModifierCollection()
        );

        return $classDto->withParentClasses($parentClasses);
    }

    public static function classPathMarkerGeneratorTestDataProvider(): array
    {
        return [
            'testcase 1' => ['', 'de'],
            'testcase 2' => ['dokuwiki', ''],
            'testcase 3' => ['', '']
        ];
    }
}
