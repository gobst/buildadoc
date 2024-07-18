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

namespace unit\Generator\Documentation\Class\Page\Component\Class;

use Collection\ClassCollection;
use Collection\MethodCollection;
use Collection\MethodParameterCollection;
use Collection\ModifierCollection;
use Contract\Formatter\Component\ListFormatterInterface;
use Contract\Generator\Documentation\Class\Page\Component\Link\LinkGeneratorInterface;
use Dto\Class\ClassDto;
use Dto\Common\Modifier;
use Dto\Method\Method;
use Dto\Method\MethodParameter;
use Generator\Documentation\Class\Page\Component\Class\UsedByClassListGenerator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Webmozart\Assert\InvalidArgumentException;

#[CoversClass(UsedByClassListGenerator::class)]
#[UsesClass(MethodCollection::class)]
#[UsesClass(Modifier::class)]
#[UsesClass(MethodParameter::class)]
#[UsesClass(MethodParameterCollection::class)]
#[UsesClass(Method::class)]
#[UsesClass(ClassDto::class)]
#[UsesClass(ModifierCollection::class)]
#[UsesClass(ClassCollection::class)]
class UsedByClassListGeneratorTest extends TestCase
{
    private ListFormatterInterface&MockObject $listFormatter;
    private LinkGeneratorInterface&MockObject $linkGenerator;
    private UsedByClassListGenerator $usedByClassListGen;

    public function setUp(): void
    {
        $this->linkGenerator = $this->getMockBuilder(LinkGeneratorInterface::class)->getMock();
        $this->listFormatter = $this->getMockBuilder(ListFormatterInterface::class)->getMock();

        $this->usedByClassListGen = new UsedByClassListGenerator($this->listFormatter, $this->linkGenerator);
    }

    #[TestDox('generate() method returns correct class path in DokuWiki format')]
    public function testGenerateWithDokuWikiFormat(): void
    {
        $class = self::getTestClassDto();

        $this->linkGenerator->expects(self::exactly(2))
            ->method('generate')
            ->willReturn('linktest');

        $this->listFormatter->expects(self::exactly(2))
            ->method('formatListItem')
            ->willReturnOnConsecutiveCalls('test1 ', 'test2');

        $actualOutput = $this->usedByClassListGen->generate($class, 'dokuwiki');

        $this->assertSame('test1 test2', $actualOutput);
    }
    #[DataProvider('generateTestDataProvider')]
    #[TestDox('generate() method fails on InvalidArgumentException with parameters $class, $format, $link, $listType')]
    public function testGenerateFailsOnInvalidArgumentException($class, $format, $link, $listType): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->linkGenerator->expects(self::never())
            ->method('generate');

        $this->listFormatter->expects(self::never())
            ->method('formatListItem');

        $this->usedByClassListGen->generate($class, $format, $link, $listType);
    }

    public static function getTestClassDto(): ClassDto
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

        $childClasses = new ClassCollection();

        $childClassDto = ClassDto::create(
            'childTestClass',
            __DIR__ . '/../../../data/classes/childTestClass.php',
            new MethodCollection(),
            new ModifierCollection()
        );
        $childClasses->add($childClassDto);

        $child2ClassDto = ClassDto::create(
            'child2TestClass',
            __DIR__ . '/../../../data/classes/child2TestClass.php',
            new MethodCollection(),
            new ModifierCollection()
        );
        $childClasses->add($child2ClassDto);

        $classDto = ClassDto::create(
            'testClass',
            __DIR__ . '/../../../data/classes/testClass.php',
            $methods,
            new ModifierCollection()
        );

        return $classDto->withChildClasses($childClasses);
    }

    public static function generateTestDataProvider(): array
    {
        return [
            'testcase 1' => [self::getTestClassDto(), '', true, 'ordered'],
            'testcase 2' => [self::getTestClassDto(), 'dokuwiki', true, '']
        ];
    }
}
