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

namespace unit\Generator\Documentation\Class\Page\Component\Property;

use Contract\Decorator\TextDecoratorFactoryInterface;
use Contract\Decorator\TextDecoratorInterface;
use Contract\Service\ClassD\Data\ModifierDataServiceInterface;
use Dto\ClassD\ClassDto;
use Dto\Common\Modifier;
use Dto\Common\Property;
use Generator\Documentation\ClassD\Page\Component\Property\PropertyListGenerator;
use Illuminate\Support\Collection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Webmozart\Assert\InvalidArgumentException;

#[CoversClass(PropertyListGenerator::class)]
#[UsesClass(Collection::class)]
#[UsesClass(Modifier::class)]
#[UsesClass(Property::class)]
final class PropertyListGeneratorTest extends TestCase
{
    private TextDecoratorInterface&MockObject $listDecorator;
    private TextDecoratorFactoryInterface&MockObject $textDecoratorFactory;
    private ModifierDataServiceInterface&MockObject $modifierDataService;
    private PropertyListGenerator $propListGenerator;

    public function setUp(): void
    {
        $this->listDecorator = $this->getMockBuilder(TextDecoratorInterface::class)
            ->getMock();
        $this->textDecoratorFactory = $this->getMockBuilder(TextDecoratorFactoryInterface::class)
            ->getMock();
        $this->modifierDataService = $this->getMockBuilder(ModifierDataServiceInterface::class)
            ->getMock();

        $this->propListGenerator = new PropertyListGenerator($this->textDecoratorFactory, $this->modifierDataService);
    }

    #[DataProvider('propertyListGeneratorTestDataProvider')]
    #[TestDox('generate() method works correctly with parameters $classDto, $format, $listType')]
    public function testGenerate(ClassDto $classDto, string $format, string $listType): void
    {
        $this->modifierDataService->expects(self::once())
            ->method('implodeModifierDTOCollection')
            ->willReturn('public');

        $this->textDecoratorFactory->expects(self::once())
            ->method('createListDecorator')
            ->willReturn($this->listDecorator);

        $this->listDecorator ->expects(self::once())
            ->method('format')
            ->willReturn('');

        $this->propListGenerator->generate($classDto, $format, $listType);
    }

    #[DataProvider('propertyListGeneratorExceptionTestDataProvider')]
    #[TestDox('generate() method fails on InvalidArgumentException with parameters $classDto, $format, $listType')]
    public function testGenerateWillFailOnInvalidArgumentException($classDto, $format, $listType): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->modifierDataService->expects(self::never())
            ->method('implodeModifierDTOCollection');

        $this->textDecoratorFactory->expects(self::never())
            ->method('createListDecorator');

        $this->listDecorator ->expects(self::never())
            ->method('format');

        $this->propListGenerator->generate($classDto, $format, $listType);
    }

    public static function propertyListGeneratorTestDataProvider(): array
    {
        $classDto = ClassDto::create(
            'testClass',
            __DIR__ . '/../../../data/classes/testClass.php',
            Collection::make(),
            Collection::make()
        );

        /** @var Collection<int, Modifier> $modifiers */
        $modifiers = Collection::make();
        $modifiers->push(Modifier::create('public'));

        /** @var Collection<int, Property> $properties */
        $properties = Collection::make();
        $property = Property::create('testProp', 'string', $modifiers);
        $properties->push($property);

        $classDto = $classDto->withProperties($properties);

        return [
            'testcase 1' => [$classDto, 'dokuwiki', 'ordered'],
            'testcase 2' => [$classDto, 'dokuwiki', 'unordered'],
        ];
    }

    public static function propertyListGeneratorExceptionTestDataProvider(): array
    {
        $classDto = ClassDto::create(
            'testClass',
            __DIR__ . '/../../../data/classes/testClass.php',
            Collection::make(),
            Collection::make()
        );

        /** @var Collection<int, Modifier> $modifiers */
        $modifiers = Collection::make();
        $modifiers->push(Modifier::create('public'));

        /** @var Collection<int, Property> $properties */
        $properties = Collection::make();
        $property = Property::create('testProp', 'string', $modifiers);
        $properties->push($property);

        $classDto = $classDto->withProperties($properties);

        return [
            'testcase 1' => [$classDto, '', 'ordered'],
            'testcase 2' => [$classDto, 'dokuwiki', ''],
        ];
    }
}
