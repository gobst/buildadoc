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
use Contract\Generator\Documentation\Class\Page\Component\Link\LinkGeneratorInterface;
use Dto\Class\ClassDto;
use Dto\Common\Modifier;
use Dto\Method\Method;
use Dto\Method\MethodParameter;
use Generator\Documentation\Class\Page\Component\Class\ClassPathGenerator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ClassPathGeneratorTest extends TestCase
{
    private LinkGeneratorInterface&MockObject $linkGenerator;
    private ClassPathGenerator $classPathGenerator;

    public function setUp(): void
    {
        $this->linkGenerator = $this->getMockBuilder(LinkGeneratorInterface::class)->getMock();
        $this->classPathGenerator = new ClassPathGenerator($this->linkGenerator);
    }

/*    public function testGenerateWithDokuWikiFormat(): void
    {
        $class = $this->getTestClassDto();
        $this->linkGenerator->expects(self::once())
            ->method('generate')
            ->willReturn('[[parenttestclass|parentTestClass]]');

        $actualOutput = $this->classPathGenerator->generate($class, 'dokuwiki');
        $expectedOutput = file_get_contents('tests/data/DokuWiki/classPath.txt');

        $this->assertEquals($expectedOutput, $actualOutput, 'Output is not as expected.');
    }*/

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
