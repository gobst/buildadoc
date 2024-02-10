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

use Collection\MethodCollection;
use Collection\ModifierCollection;
use Collection\PropertyCollection;
use Contract\Formatter\Component\ListFormatterInterface;
use Dto\Class\ClassDto;
use Dto\Common\Modifier;
use Dto\Common\Property;
use Generator\Documentation\Class\Page\Component\Property\PropertyListGenerator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Webmozart\Assert\InvalidArgumentException;

final class PropertyListGeneratorTest extends TestCase
{
    private ListFormatterInterface&MockObject $listFormatter;
    private PropertyListGenerator $propListGenerator;

    public function setUp(): void
    {
        $this->listFormatter = $this->getMockBuilder(ListFormatterInterface::class)->getMock();
        $this->propListGenerator = new PropertyListGenerator($this->listFormatter);
    }

    #[DataProvider('propertyListGeneratorTestDataProvider')]
    #[TestDox('generate() method works correctly with parameters $classDto, $format, $listType')]
    public function testGenerate(ClassDto $classDto, string $format, string $listType): void
    {
        $this->listFormatter->expects(self::once())
            ->method('formatListItem')
            ->willReturn('');

        $this->propListGenerator->generate($classDto, $format, $listType);
    }

    #[DataProvider('propertyListGeneratorExceptionTestDataProvider')]
    #[TestDox('generate() method fails on InvalidArgumentException with parameters $classDto, $format, $listType')]
    public function testGenerateWillFailOnInvalidArgumentException($classDto, $format, $listType): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->listFormatter->expects(self::never())
            ->method('formatListItem');

        $this->propListGenerator->generate($classDto, $format, $listType);
    }

    public static function propertyListGeneratorTestDataProvider(): array
    {
        $classDto = ClassDto::create(
            'testClass',
            __DIR__ . '/../../../data/classes/testClass.php',
            new MethodCollection(),
            new ModifierCollection()
        );

        $modifiers = new ModifierCollection();
        $modifiers->add(Modifier::create('public'));

        $properties = new PropertyCollection();
        $property = Property::create(
            'testProp',
            'string',
            $modifiers
        );
        $properties->add($property);

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
            new MethodCollection(),
            new ModifierCollection()
        );

        $modifiers = new ModifierCollection();
        $modifiers->add(Modifier::create('public'));

        $properties = new PropertyCollection();
        $property = Property::create(
            'testProp',
            'string',
            $modifiers
        );
        $properties->add($property);

        $classDto = $classDto->withProperties($properties);

        return [
            'testcase 1' => [$classDto, '', 'ordered'],
            'testcase 2' => [$classDto, 'dokuwiki', ''],
        ];
    }
}
