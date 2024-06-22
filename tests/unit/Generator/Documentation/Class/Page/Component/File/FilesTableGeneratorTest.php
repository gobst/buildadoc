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

use Collection\ClassCollection;
use Collection\MethodCollection;
use Collection\MethodParameterCollection;
use Collection\ModifierCollection;
use Contract\Formatter\Component\TableFormatterInterface;
use Dto\Class\ClassDto;
use Dto\Common\Modifier;
use Dto\Method\Method;
use Dto\Method\MethodParameter;
use Generator\Documentation\Class\Page\Component\File\FilesTableGenerator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Webmozart\Assert\InvalidArgumentException;

#[CoversClass(FilesTableGenerator::class)]
final class FilesTableGeneratorTest extends TestCase
{
    private TableFormatterInterface&MockObject $tableFormatter;
    private FilesTableGenerator $filesTableGenerator;

    public function setUp(): void
    {
        $this->tableFormatter = $this->getMockBuilder(TableFormatterInterface::class)->getMock();
        $this->filesTableGenerator = new FilesTableGenerator($this->tableFormatter);
    }

    #[TestDox('generate() method works correctly')]
    public function testGenerate(): void
    {
        $classDto = $this->getTestClassDto();

        $this->tableFormatter->expects(self::once())
            ->method('formatTable')
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
}
