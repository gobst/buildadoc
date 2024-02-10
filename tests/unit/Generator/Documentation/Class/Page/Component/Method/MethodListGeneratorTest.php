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

namespace unit\Generator\Documentation\Class\Page\Component\Method;

use Collection\ClassCollection;
use Collection\MethodCollection;
use Collection\MethodParameterCollection;
use Collection\ModifierCollection;
use Contract\Formatter\Component\ListFormatterInterface;
use Contract\Generator\Documentation\Class\Page\Component\Link\LinkGeneratorInterface;
use Contract\Generator\Documentation\Class\Page\Component\Method\MethodLineGeneratorInterface;
use Dto\Class\ClassDto;
use Dto\Common\Modifier;
use Dto\Method\Method;
use Dto\Method\MethodParameter;
use Generator\Documentation\Class\Page\Component\Method\MethodListGenerator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Webmozart\Assert\InvalidArgumentException;

final class MethodListGeneratorTest extends TestCase
{
    private LinkGeneratorInterface&MockObject $linkGenerator;
    private MethodLineGeneratorInterface&MockObject $methodLineGenerator;
    private ListFormatterInterface&MockObject $listFormatter;
    private MethodListGenerator $methodListGenerator;

    public function setUp(): void
    {
        $this->linkGenerator = $this->getMockBuilder(LinkGeneratorInterface::class)->getMock();
        $this->methodLineGenerator = $this->getMockBuilder(MethodLineGeneratorInterface::class)->getMock();
        $this->listFormatter = $this->getMockBuilder(ListFormatterInterface::class)->getMock();
        $this->methodListGenerator = new MethodListGenerator(
            $this->linkGenerator,
            $this->methodLineGenerator,
            $this->listFormatter
        );
    }

    #[DataProvider('methodListGeneratorTestDataProvider')]
    #[TestDox('generate() method works correctly with parameters $classDto, $format, $link, $listType, $withInheritedMethods')]
    public function testGenerate($classDto, $format, $link, $listType, $withInheritedMethods): void
    {
        $this->methodLineGenerator->expects(self::exactly(2))
            ->method('generate')
            ->willReturn('');

        $this->listFormatter->expects(self::exactly(2))
            ->method('formatListItem')
            ->willReturn('');

        if ($link === true) {
            $this->linkGenerator->expects(self::exactly(2))
                ->method('generate')
                ->willReturn('');
        }

        $this->methodListGenerator->generate($classDto, $format, $link, $listType, $withInheritedMethods);
    }

    #[DataProvider('methodListGeneratorExceptionTestDataProvider')]
    #[TestDox('generate() method fails on InvalidArgumentException with parameters $classDto, $format, $link, $listType, $withInheritedMethods')]
    public function testGenerateWillFailOnInvalidArgumentException(
        $classDto,
        $format,
        $link,
        $listType,
        $withInheritedMethods
    ): void {
        $this->expectException(InvalidArgumentException::class);

        $this->methodLineGenerator->expects(self::never())
            ->method('generate');

        $this->listFormatter->expects(self::never())
            ->method('formatListItem');

        $this->linkGenerator->expects(self::never())
            ->method('generate');

        $this->methodListGenerator->generate($classDto, $format, $link, $listType, $withInheritedMethods);
    }

    public static function methodListGeneratorTestDataProvider(): array
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
        $methodDto = $methodDto->withParameters($parameters);
        $methods->add($methodDto);

        $parameters = new MethodParameterCollection();
        $parameterDto = MethodParameter::create('testInt', 'int');
        $parameterDto = $parameterDto->withDefaultValue(0);
        $parameters->add($parameterDto);
        $methodDto = Method::create('testMethodWithPHPDoc', $modifiers, 'string', 'testClass');
        $methodDto = $methodDto->withParameters($parameters);
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
        $classDto = $classDto->withParentClasses($parentClasses);

        return [
            'testcase 1' => [$classDto, 'dokuwiki', true, 'ordered', false],
            'testcase 2' => [$classDto, 'dokuwiki', false, 'ordered', true],
            'testcase 3' => [$classDto, 'dokuwiki', false, 'ordered', false],
            'testcase 4' => [$classDto, 'dokuwiki', true, 'ordered', true],
            'testcase 5' => [$classDto, 'dokuwiki', true, 'unordered', false],
            'testcase 6' => [$classDto, 'dokuwiki', false, 'unordered', true],
            'testcase 7' => [$classDto, 'dokuwiki', false, 'unordered', false],
            'testcase 8' => [$classDto, 'dokuwiki', true, 'unordered', true],
        ];
    }

    public static function methodListGeneratorExceptionTestDataProvider(): array
    {
        $classDto = ClassDto::create(
            'testClass',
            __DIR__ . '/../../../data/classes/testClass.php',
            new MethodCollection(),
            new ModifierCollection()
        );

        return [
            'testcase 1' => [$classDto, '', true, 'ordered', false],
            'testcase 2' => [$classDto, 'dokuwiki', false, '', true],
        ];
    }
}
