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

use Contract\Decorator\Component\Link\LinkDestinationDecoratorInterface;
use Contract\Decorator\TextDecoratorFactoryInterface;
use Contract\Generator\Documentation\Class\Page\Component\Link\LinkGeneratorInterface;
use Decorator\Page\Component\ClassLinkDestinationDecorator;
use Dto\Class\ClassDto;
use Dto\Common\Modifier;
use Dto\Method\Method;
use Dto\Method\MethodParameter;
use Generator\Documentation\Class\Page\Component\Class\ClassPathGenerator;
use Illuminate\Support\Collection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Webmozart\Assert\InvalidArgumentException;

#[CoversClass(ClassPathGenerator::class)]
#[UsesClass(Modifier::class)]
#[UsesClass(MethodParameter::class)]
#[UsesClass(Method::class)]
#[UsesClass(ClassDto::class)]
#[UsesClass(Collection::class)]
class ClassPathGeneratorTest extends TestCase
{
    private LinkGeneratorInterface&MockObject $linkGenerator;
    private TextDecoratorFactoryInterface&MockObject $textDecoratorFactory;
    private LinkDestinationDecoratorInterface&MockObject $classLinkDestDecorator;
    private ClassPathGenerator $classPathGenerator;

    public function setUp(): void
    {
        $this->linkGenerator = $this->getMockBuilder(LinkGeneratorInterface::class)
            ->getMock();
        $this->textDecoratorFactory = $this->getMockBuilder(TextDecoratorFactoryInterface::class)
            ->getMock();
        $this->classLinkDestDecorator = $this->getMockBuilder(LinkDestinationDecoratorInterface::class)
            ->getMock();

        $this->classPathGenerator = new ClassPathGenerator($this->linkGenerator, $this->textDecoratorFactory);
    }

    #[TestDox('generate() method returns correct class path in DokuWiki format')]
    public function testGenerateWithDokuWikiFormat(): void
    {
        $class = $this->getTestClassDto();

        $this->textDecoratorFactory->expects(self::once())
            ->method('createClassLinkDestinationDecorator')
            ->willReturn($this->classLinkDestDecorator);

        $this->classLinkDestDecorator->expects(self::once())
            ->method('format')
            ->willReturn('parenttestclass');

        $this->linkGenerator->expects(self::once())
            ->method('generate')
            ->willReturn('[[parenttestclass|parentTestClass]]');

        $actualOutput = $this->classPathGenerator->generate($class, 'dokuwiki');

        $this->assertSame('testClass --> [[parenttestclass|parentTestClass]]', $actualOutput);
    }

    #[TestDox('generate() method fails on InvalidArgumentException with invalid output format')]
    public function testGenerateFailsOnInvalidArgumentExceptionWithInvalidFormat(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $class = $this->getTestClassDto();

        $this->textDecoratorFactory->expects(self::never())
            ->method('createClassLinkDestinationDecorator')
            ->willReturn($this->classLinkDestDecorator);

        $this->linkGenerator->expects(self::never())
            ->method('generate');

        $this->classPathGenerator->generate($class, '');
    }

    private function getTestClassDto(): ClassDto
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
        $methodDto = Method::create('testMethodWithPHPDoc', $modifiers, 'string', 'testClass');
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
}
