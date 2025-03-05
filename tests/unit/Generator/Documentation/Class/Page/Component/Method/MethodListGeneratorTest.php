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

use Contract\Decorator\Component\Link\LinkDestinationDecoratorInterface;
use Contract\Decorator\TextDecoratorFactoryInterface;
use Contract\Decorator\TextDecoratorInterface;
use Contract\Generator\Documentation\ClassD\Page\Component\Link\LinkGeneratorInterface;
use Contract\Generator\Documentation\ClassD\Page\Component\Method\MethodLineGeneratorInterface;
use Dto\ClassD\ClassDto;
use Dto\Common\Modifier;
use Dto\Method\Method;
use Dto\Method\MethodParameter;
use Generator\Documentation\ClassD\Page\Component\Method\MethodListGenerator;
use Illuminate\Support\Collection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Service\ClassD\Filter\MethodNameFilter;
use Webmozart\Assert\InvalidArgumentException;

#[CoversClass(MethodListGenerator::class)]
#[UsesClass(Collection::class)]
#[UsesClass(Modifier::class)]
#[UsesClass(Method::class)]
#[UsesClass(MethodParameter::class)]
#[UsesClass(ClassDto::class)]
#[UsesClass(MethodNameFilter::class)]
final class MethodListGeneratorTest extends TestCase
{
    private LinkGeneratorInterface&MockObject $linkGenerator;
    private MethodLineGeneratorInterface&MockObject $methodLineGenerator;
    private TextDecoratorFactoryInterface&MockObject $textDecoratorFactory;
    private TextDecoratorInterface&MockObject $listDecorator;
    private LinkDestinationDecoratorInterface&MockObject $methodLinkDestDec;
    private MethodListGenerator $methodListGenerator;

    public function setUp(): void
    {
        $this->linkGenerator = $this->getMockBuilder(LinkGeneratorInterface::class)
            ->getMock();
        $this->methodLineGenerator = $this->getMockBuilder(MethodLineGeneratorInterface::class)
            ->getMock();
        $this->textDecoratorFactory = $this->getMockBuilder(TextDecoratorFactoryInterface::class)
            ->getMock();
        $this->listDecorator = $this->getMockBuilder(TextDecoratorInterface::class)
            ->getMock();
        $this->methodLinkDestDec = $this->getMockBuilder(LinkDestinationDecoratorInterface::class)
            ->getMock();

        $this->methodListGenerator = new MethodListGenerator(
            $this->linkGenerator,
            $this->methodLineGenerator,
            $this->textDecoratorFactory
        );
    }

    #[DataProvider('methodListGeneratorTestDataProvider')]
    #[TestDox('generate() method works correctly with parameters $classDto, $format, $link, $listType, $withInheritedMethods')]
    public function testGenerate($classDto, $format, $link, $listType, $withInheritedMethods): void
    {
        $this->methodLineGenerator->expects(self::exactly(2))
            ->method('generate')
            ->willReturn('');

        $this->textDecoratorFactory->expects(self::exactly(2))
            ->method('createListDecorator')
            ->willReturn($this->listDecorator);

        $this->listDecorator->expects(self::exactly(2))
            ->method('format')
            ->willReturn('');

        if ($link === true) {
            $this->textDecoratorFactory->expects(self::exactly(2))
                ->method('createMethodLinkDestinationDecorator')
                ->willReturn($this->methodLinkDestDec);

            $this->methodLinkDestDec->expects(self::exactly(2))
                ->method('format')
                ->willReturn('test123');

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

        $this->textDecoratorFactory->expects(self::never())
            ->method('createListDecorator');

        $this->textDecoratorFactory->expects(self::never())
            ->method('createMethodLinkDestinationDecorator');

        $this->methodLinkDestDec->expects(self::never())
            ->method('format');

        $this->listDecorator->expects(self::never())
            ->method('format');

        $this->linkGenerator->expects(self::never())
            ->method('generate');

        $this->methodListGenerator->generate($classDto, $format, $link, $listType, $withInheritedMethods);
    }

    public static function methodListGeneratorTestDataProvider(): array
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
        $methodDto = $methodDto->withParameters($parameters);
        $methods->push($methodDto);

        /** @var Collection<int, MethodParameter> $parameters */
        $parameters = Collection::make();
        $parameterDto = MethodParameter::create('testInt', 'int');
        $parameterDto = $parameterDto->withDefaultValue(0);
        $parameters->push($parameterDto);
        $methodDto = Method::create('testMethodWithPHPDoc', $modifiers, 'string', 'testClass');
        $methodDto = $methodDto->withParameters($parameters);
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
            Collection::make(),
            Collection::make()
        );

        return [
            'testcase 1' => [$classDto, '', true, 'ordered', false],
            'testcase 2' => [$classDto, 'dokuwiki', false, '', true],
        ];
    }
}
