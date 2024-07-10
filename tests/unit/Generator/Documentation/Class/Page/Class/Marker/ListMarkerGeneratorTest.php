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

use Collection\ClassCollection;
use Collection\ConstantCollection;
use Collection\InterfaceCollection;
use Collection\MethodCollection;
use Collection\MethodParameterCollection;
use Collection\ModifierCollection;
use Collection\PropertyCollection;
use Contract\Generator\Documentation\Class\Page\Component\Class\UsedByClassListGeneratorInterface;
use Contract\Generator\Documentation\Class\Page\Component\Constant\ConstantListGeneratorInterface;
use Contract\Generator\Documentation\Class\Page\Component\Heading\HeadingGeneratorInterface;
use Contract\Generator\Documentation\Class\Page\Component\Interface\InterfaceListGeneratorInterface;
use Contract\Generator\Documentation\Class\Page\Component\Method\MethodListGeneratorInterface;
use Contract\Generator\Documentation\Class\Page\Component\Property\PropertyListGeneratorInterface;
use Contract\Service\Translation\TranslationServiceInterface;
use Dto\Class\ClassDto;
use Dto\Class\Constant;
use Dto\Class\InterfaceDto;
use Dto\Common\Modifier;
use Dto\Common\Property;
use Dto\Method\Method;
use Dto\Method\MethodParameter;
use Generator\Documentation\Class\Page\Class\Marker\ListMarkerGenerator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Service\Class\Filter\MethodNameFilter;
use Webmozart\Assert\InvalidArgumentException;

#[CoversClass(ListMarkerGenerator::class)]
#[UsesClass(MethodCollection::class)]
#[UsesClass(MethodParameterCollection::class)]
#[UsesClass(ModifierCollection::class)]
#[UsesClass(ClassCollection::class)]
#[UsesClass(PropertyCollection::class)]
#[UsesClass(ConstantCollection::class)]
#[UsesClass(InterfaceCollection::class)]
#[UsesClass(MethodNameFilter::class)]
#[UsesClass(Constant::class)]
#[UsesClass(InterfaceDto::class)]
#[UsesClass(Modifier::class)]
#[UsesClass(Property::class)]
#[UsesClass(Method::class)]
#[UsesClass(ClassDto::class)]
#[UsesClass(Modifier::class)]
#[UsesClass(MethodParameter::class)]
final class ListMarkerGeneratorTest extends TestCase
{
    private TranslationServiceInterface&MockObject $translationService;
    private MethodListGeneratorInterface&MockObject $methodListGenerator;
    private ConstantListGeneratorInterface&MockObject $constantListGen;
    private PropertyListGeneratorInterface&MockObject $propertyListGen;
    private InterfaceListGeneratorInterface&MockObject $interfaceListGen;
    private HeadingGeneratorInterface&MockObject $headingGenerator;
    private UsedByClassListGeneratorInterface&MockObject $usedByClassListGen;
    private ListMarkerGenerator $listMarkerGenerator;

    public function setUp(): void
    {
        $this->translationService = $this->getMockBuilder(TranslationServiceInterface::class)
            ->getMock();
        $this->methodListGenerator = $this->getMockBuilder(MethodListGeneratorInterface::class)
            ->getMock();
        $this->constantListGen = $this->getMockBuilder(ConstantListGeneratorInterface::class)
            ->getMock();
        $this->propertyListGen = $this->getMockBuilder(PropertyListGeneratorInterface::class)
            ->getMock();
        $this->interfaceListGen = $this->getMockBuilder(InterfaceListGeneratorInterface::class)
            ->getMock();
        $this->headingGenerator = $this->getMockBuilder(HeadingGeneratorInterface::class)
            ->getMock();
        $this->usedByClassListGen = $this->getMockBuilder(UsedByClassListGeneratorInterface::class)
            ->getMock();

        $this->listMarkerGenerator = new ListMarkerGenerator(
            $this->translationService,
            $this->methodListGenerator,
            $this->constantListGen,
            $this->propertyListGen,
            $this->interfaceListGen,
            $this->headingGenerator,
            $this->usedByClassListGen
        );
    }

    #[TestDox('generateUsedByClassList() method works correctly')]
    public function testGenerateUsedByClassList(): void
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

        $this->usedByClassListGen->expects(self::once())
            ->method('generate')
            ->willReturn('testStr2');

        $expectedMarker = [];
        $expectedMarker['###CLASS_USEDBYCLASSES_HEADING###'] = 'testStr' . $lineBreak;
        $expectedMarker['###CLASS_USEDBYCLASSES_LIST###'] = 'testStr2' . $lineBreak;

        $actualMarker = $this->listMarkerGenerator->generateUsedByClassList(
            $this->getTestClassDto(),
            'dokuwiki',
            'ordered',
            'de'
        );

        $this->assertSame($expectedMarker, $actualMarker);
    }

    #[DataProvider('listMarkerGeneratorTestDataProvider')]
    #[TestDox('generateUsedByClassList() method fails on InvalidArgumentException with parameters: $format, $listType, $lang')]
    public function testGenerateUsedByClassListFailsOnInvalidArgumentException(
        string $format,
        string $listType,
        string $lang
    ): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->translationService->expects(self::never())
            ->method('setLanguage');

        $this->translationService->expects(self::never())
            ->method('translate');

        $this->headingGenerator->expects(self::never())
            ->method('generate');

        $this->usedByClassListGen->expects(self::never())
            ->method('generate');

        $this->listMarkerGenerator->generateUsedByClassList(
            $this->getTestClassDto(),
            $format,
            $listType,
            $lang
        );
    }

    #[TestDox('generateUsedByClassList() method fails on empty translation')]
    public function testGenerateUsedByClassListFailsOnEmptyTranslation(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->translationService->expects(self::once())
            ->method('setLanguage');

        $this->translationService->expects(self::once())
            ->method('translate')
            ->willReturn('');

        $this->headingGenerator->expects(self::never())
            ->method('generate');

        $this->usedByClassListGen->expects(self::never())
            ->method('generate');

        $this->listMarkerGenerator->generateUsedByClassList(
            $this->getTestClassDto(),
            'dokuwiki',
            'ordered',
            'de'
        );
    }

    #[TestDox('generateConstantList() method works correctly')]
    public function testGenerateConstantList(): void
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

        $this->constantListGen->expects(self::once())
            ->method('generate')
            ->willReturn('testStr2');

        $expectedMarker = [];
        $expectedMarker['###CONSTANTS_LIST_HEADING###'] = 'testStr' . $lineBreak;
        $expectedMarker['###CONSTANTS_LIST###'] = 'testStr2' . $lineBreak;

        $actualMarker = $this->listMarkerGenerator->generateConstantList(
            $this->getTestClassDto(),
            'dokuwiki',
            'ordered',
            'de'
        );

        $this->assertSame($expectedMarker, $actualMarker);
    }

    #[DataProvider('listMarkerGeneratorTestDataProvider')]
    #[TestDox('generateConstantList() method fails on InvalidArgumentException with parameters: $format, $listType, $lang')]
    public function testGenerateConstantListFailsOnInvalidArgumentException(
        string $format,
        string $listType,
        string $lang
    ): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->translationService->expects(self::never())
            ->method('setLanguage');

        $this->translationService->expects(self::never())
            ->method('translate');

        $this->headingGenerator->expects(self::never())
            ->method('generate');

        $this->constantListGen->expects(self::never())
            ->method('generate');

        $this->listMarkerGenerator->generateUsedByClassList(
            $this->getTestClassDto(),
            $format,
            $listType,
            $lang
        );
    }

    #[TestDox('generateConstantList() method fails on empty translation')]
    public function testGenerateConstantListFailsOnEmptyTranslation(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->translationService->expects(self::once())
            ->method('setLanguage');

        $this->translationService->expects(self::once())
            ->method('translate')
            ->willReturn('');

        $this->headingGenerator->expects(self::never())
            ->method('generate');

        $this->constantListGen->expects(self::never())
            ->method('generate');

        $this->listMarkerGenerator->generateUsedByClassList(
            $this->getTestClassDto(),
            'dokuwiki',
            'ordered',
            'de'
        );
    }

    #[TestDox('generatePropertiesList() method works correctly')]
    public function testGeneratePropertiesList(): void
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

        $this->propertyListGen->expects(self::once())
            ->method('generate')
            ->willReturn('testStr2');

        $expectedMarker = [];
        $expectedMarker['###CLASS_PROPERTIES_LIST_HEADING###'] = 'testStr' . $lineBreak;
        $expectedMarker['###CLASS_PROPERTIES_LIST###'] = 'testStr2' . $lineBreak;

        $actualMarker = $this->listMarkerGenerator->generatePropertiesList(
            $this->getTestClassDto(),
            'dokuwiki',
            'ordered',
            'de'
        );

        $this->assertSame($expectedMarker, $actualMarker);
    }

    #[DataProvider('listMarkerGeneratorTestDataProvider')]
    #[TestDox('generatePropertiesList() method fails on InvalidArgumentException with parameters: $format, $listType, $lang')]
    public function testGeneratePropertiesListFailsOnInvalidArgumentException(
        string $format,
        string $listType,
        string $lang
    ): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->translationService->expects(self::never())
            ->method('setLanguage');

        $this->translationService->expects(self::never())
            ->method('translate');

        $this->headingGenerator->expects(self::never())
            ->method('generate');

        $this->propertyListGen->expects(self::never())
            ->method('generate');

        $this->listMarkerGenerator->generatePropertiesList(
            $this->getTestClassDto(),
            $format,
            $listType,
            $lang
        );
    }

    #[TestDox('generatePropertiesList() method fails on empty translation')]
    public function testGeneratePropertiesListFailsOnEmptyTranslation(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->translationService->expects(self::once())
            ->method('setLanguage');

        $this->translationService->expects(self::once())
            ->method('translate')
            ->willReturn('');

        $this->headingGenerator->expects(self::never())
            ->method('generate');

        $this->propertyListGen->expects(self::never())
            ->method('generate');

        $this->listMarkerGenerator->generatePropertiesList(
            $this->getTestClassDto(),
            'dokuwiki',
            'ordered',
            'de'
        );
    }

    #[TestDox('generateInterfacesList() method works correctly')]
    public function testGenerateInterfacesList(): void
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

        $this->interfaceListGen->expects(self::once())
            ->method('generate')
            ->willReturn('testStr2');

        $expectedMarker = [];
        $expectedMarker['###CLASS_INTERFACES_LIST_HEADING###'] = 'testStr' . $lineBreak;
        $expectedMarker['###CLASS_INTERFACES_LIST###'] = 'testStr2' . $lineBreak;

        $actualMarker = $this->listMarkerGenerator->generateInterfacesList(
            $this->getTestClassDto(),
            'dokuwiki',
            'ordered',
            'de'
        );

        $this->assertSame($expectedMarker, $actualMarker);
    }

    #[DataProvider('listMarkerGeneratorTestDataProvider')]
    #[TestDox('generateInterfacesList() method fails on InvalidArgumentException with parameters: $format, $listType, $lang')]
    public function testGenerateInterfacesListFailsOnInvalidArgumentException(
        string $format,
        string $listType,
        string $lang
    ): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->translationService->expects(self::never())
            ->method('setLanguage');

        $this->translationService->expects(self::never())
            ->method('translate');

        $this->headingGenerator->expects(self::never())
            ->method('generate');

        $this->interfaceListGen->expects(self::never())
            ->method('generate');

        $this->listMarkerGenerator->generateInterfacesList(
            $this->getTestClassDto(),
            $format,
            $listType,
            $lang
        );
    }

    #[TestDox('generateInterfacesList() method fails on empty translation')]
    public function testGenerateInterfacesListFailsOnEmptyTranslation(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->translationService->expects(self::once())
            ->method('setLanguage');

        $this->translationService->expects(self::once())
            ->method('translate')
            ->willReturn('');

        $this->headingGenerator->expects(self::never())
            ->method('generate');

        $this->interfaceListGen->expects(self::never())
            ->method('generate');

        $this->listMarkerGenerator->generateInterfacesList(
            $this->getTestClassDto(),
            'dokuwiki',
            'ordered',
            'de'
        );
    }

    #[TestDox('generateMethodList() method works correctly')]
    public function testGenerateMethodList(): void
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

        $this->methodListGenerator->expects(self::once())
            ->method('generate')
            ->willReturn('testStr2');

        $expectedMarker = [];
        $expectedMarker['###METHODS_LIST_HEADING###'] = 'testStr' . $lineBreak;
        $expectedMarker['###METHODS_LIST###'] = 'testStr2' . $lineBreak;

        $actualMarker = $this->listMarkerGenerator->generateMethodList(
            $this->getTestClassDto(),
            'dokuwiki',
            'ordered',
            'de'
        );

        $this->assertSame($expectedMarker, $actualMarker);
    }

    #[DataProvider('listMarkerGeneratorTestDataProvider')]
    #[TestDox('generateMethodList() method fails on InvalidArgumentException with parameters: $format, $listType, $lang')]
    public function testGenerateMethodListFailsOnInvalidArgumentException(
        string $format,
        string $listType,
        string $lang
    ): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->translationService->expects(self::never())
            ->method('setLanguage');

        $this->translationService->expects(self::never())
            ->method('translate');

        $this->headingGenerator->expects(self::never())
            ->method('generate');

        $this->methodListGenerator->expects(self::never())
            ->method('generate');

        $this->listMarkerGenerator->generateMethodList(
            $this->getTestClassDto(),
            $format,
            $listType,
            $lang
        );
    }

    #[TestDox('generateMethodList() method fails on empty translation')]
    public function testGenerateMethodListFailsOnEmptyTranslation(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->translationService->expects(self::once())
            ->method('setLanguage');

        $this->translationService->expects(self::once())
            ->method('translate')
            ->willReturn('');

        $this->headingGenerator->expects(self::never())
            ->method('generate');

        $this->methodListGenerator->expects(self::never())
            ->method('generate');

        $this->listMarkerGenerator->generateMethodList(
            $this->getTestClassDto(),
            'dokuwiki',
            'ordered',
            'de'
        );
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
        $methodDto = Method::create('__construct', $modifiers, 'string', 'testClass');
        $methodDto->withParameters($parameters);
        $methods->add($methodDto);

        $constants = new ConstantCollection();
        $constant = Constant::create('testConstant', 'string', 'test', $modifiers);
        $constants->add($constant);

        $properties = new PropertyCollection();
        $property = Property::create('testProperty', 'string', $modifiers);
        $properties->add($property);

        $interfaces = new InterfaceCollection();
        $interface = InterfaceDto::create('testInterface');
        $interfaces->add($interface);

        $childClasses = new ClassCollection();
        $childClassDto = ClassDto::create(
            'parentTestClass',
            __DIR__ . '/../../../data/classes/childTestClass.php',
            new MethodCollection(),
            new ModifierCollection()
        );
        $childClasses->add($childClassDto);

        $classDto = ClassDto::create(
            'testClass',
            __DIR__ . '/../../../data/classes/testClass.php',
            $methods,
            new ModifierCollection()
        );

        return $classDto
            ->withChildClasses($childClasses)
            ->withConstants($constants)
            ->withProperties($properties)
            ->withInterfaces($interfaces);
    }

    public static function listMarkerGeneratorTestDataProvider(): array
    {
        return [
            'testcase 1' => ['', 'ordered', 'de'],
            'testcase 2' => ['dokuwiki', '', 'de'],
            'testcase 3' => ['dokuwiki', 'ordered', '']
        ];
    }
}
