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

namespace unit\Generator\Documentation\Class\Page\Component\File;

use Contract\Decorator\Component\TableDecoratorInterface;
use Dto\ClassD\ClassDto;
use Dto\Common\Modifier;
use Dto\Method\Method;
use Dto\Method\MethodParameter;
use Generator\Documentation\ClassD\Page\Component\File\FilesTableGenerator;
use Illuminate\Support\Collection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Webmozart\Assert\InvalidArgumentException;

#[CoversClass(FilesTableGenerator::class)]
#[UsesClass(Collection::class)]
#[UsesClass(Modifier::class)]
#[UsesClass(MethodParameter::class)]
#[UsesClass(ClassDto::class)]
#[UsesClass(Method::class)]
final class FilesTableGeneratorTest extends TestCase
{
    private TableDecoratorInterface&MockObject $tableFormatter;
    private FilesTableGenerator $filesTableGenerator;

    public function setUp(): void
    {
        $this->tableFormatter = $this->getMockBuilder(TableDecoratorInterface::class)->getMock();
        $this->filesTableGenerator = new FilesTableGenerator($this->tableFormatter);
    }

    #[TestDox('generate() method works correctly')]
    public function testGenerate(): void
    {
        $classDto = $this->getTestClassDto();

        $this->tableFormatter->expects(self::once())
            ->method('format')
            ->willReturn('');

        $this->filesTableGenerator->generate($classDto, 'dokuwiki', ['test1', 'test2']);
    }

    #[TestDox('generate() method fails on InvalidArgumentException')]
    public function testGenerateFailsOnInvalidArgumentException(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->filesTableGenerator->generate($this->getTestClassDto(), '', ['test1', 'test2']);
    }

    private function getTestClassDto(): ClassDto
    {
        /** @var Collection<int, Method> $methods */
        $methods = Collection::make();

        /** @var Collection<int, Modifier> $modifiers */
        $modifiers = Collection::make();
        $publicModifierDto = Modifier::create('public');
        $modifiers->add($publicModifierDto);

        /** @var Collection<int, MethodParameter> $parameters */
        $parameters = Collection::make();
        $parameterDto = MethodParameter::create('testString', 'string');
        $parameterDto = $parameterDto->withDefaultValue('test');
        $parameters->add($parameterDto);
        $methodDto = Method::create('testMethodWithoutPHPDoc', $modifiers, 'string', 'testClass');
        $methodDto->withParameters($parameters);
        $methods->add($methodDto);

        /** @var Collection<int, MethodParameter> $parameters */
        $parameters = Collection::make();
        $parameterDto = MethodParameter::create('testInt', 'int');
        $parameterDto = $parameterDto->withDefaultValue(0);
        $parameters->add($parameterDto);
        $methodDto = Method::create('testMethodWithPHPDoc', $modifiers, 'string', 'testClass');
        $methodDto->withParameters($parameters);
        $methods->add($methodDto);

        /** @var Collection<int, ClassDto> $parentClasses */
        $parentClasses = Collection::make();
        $parentClassDto = ClassDto::create(
            'parentTestClass',
            __DIR__ . '/../../../data/classes/parentTestClass.php',
            Collection::make(),
            Collection::make()
        );
        $parentClasses->add($parentClassDto);

        $classDto = ClassDto::create(
            'testClass',
            __DIR__ . '/../../../data/classes/testClass.php',
            $methods,
            Collection::make()
        );

        return $classDto->withParentClasses($parentClasses);
    }
}
