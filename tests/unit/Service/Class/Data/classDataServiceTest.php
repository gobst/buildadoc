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

namespace unit\Service\Class\Data;

use Collection\ClassCollection;
use Collection\MethodCollection;
use Collection\ModifierCollection;
use Contract\Service\Class\Data\MethodDataServiceInterface;
use Contract\Service\Class\Data\ModifierDataServiceInterface;
use Contract\Service\File\FileServiceInterface;
use Dto\Class\ClassDto;
use Dto\Common\File;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Ramsey\Collection\Exception\NoSuchElementException;
use Service\Class\Data\ClassDataService;
use Webmozart\Assert\InvalidArgumentException;

/**
 * @todo: how I can mock getAst method in same class?
 *
 * @todo: how I can mock getClassByIndex method in same class?
 */
final class classDataServiceTest extends TestCase
{
    private FileServiceInterface&MockObject $fileService;
    private MethodDataServiceInterface&MockObject $methodDataService;
    private ModifierDataServiceInterface&MockObject $modifierDataService;
    private ClassDataService $classDataService;

    public function setUp(): void
    {
        $this->fileService = $this->getMockBuilder(FileServiceInterface::class)->getMock();
        $this->methodDataService = $this->getMockBuilder(MethodDataServiceInterface::class)->getMock();
        $this->modifierDataService = $this->getMockBuilder(ModifierDataServiceInterface::class)->getMock();
        $this->classDataService = new ClassDataService(
            $this->fileService,
            $this->methodDataService,
            $this->modifierDataService
        );
    }

    // @todo
    /*public function testGetClassData(): void
    {
    }*/

    #[TestDox('getSingleClass() method works correctly')]
    public function testGetSingleClass(): void
    {
        $classes = new ClassCollection();

        $firstClass = ClassDto::create(
            'TestClass1',
            '/path/to/file',
            new MethodCollection(),
            new ModifierCollection()
        );

        $secondClass = ClassDto::create(
            'TestClass2',
            '/path/to/file',
            new MethodCollection(),
            new ModifierCollection()
        );

        $classes->add($firstClass);
        $classes->add($secondClass);

        $expected = $secondClass;
        $actual = $this->classDataService->getSingleClass('TestClass2', $classes);

        $this->assertEquals($expected, $actual);
    }

    #[TestDox('getSingleClass() method fails on InvalidArgumentException')]
    public function testGetSingleClassWillFailOnInvalidArgumentException(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->classDataService->getSingleClass('', new ClassCollection());
    }

    #[TestDox('getSingleClass() method fails on NoSuchElementException')]
    public function testGetSingleClassWillFailOnNoSuchElementException(): void
    {
        $this->expectException(NoSuchElementException::class);

        $classes = new ClassCollection();

        $firstClass = ClassDto::create(
            'TestClass1',
            '/path/to/file',
            new MethodCollection(),
            new ModifierCollection()
        );

        $secondClass = ClassDto::create(
            'TestClass2',
            '/path/to/file',
            new MethodCollection(),
            new ModifierCollection()
        );

        $classes->add($firstClass);
        $classes->add($secondClass);

        $this->classDataService->getSingleClass('TestClass3', $classes);
    }

    // @todo
    /*public function testGetInterfaces(): void
    {
        $expectedData = [];
        $expectedData[0]['name'] = 'testInterface';
        $expectedData[1]['name'] = 'testInterface2';

        $this->assertEquals(
            $expectedData,
            $this->classDataService->getInterfaces(
                __DIR__ . '/../../../data/classes/testClass.php'
            ),
            'Error in testGetInterfaces()'
        );
    }*/

    // @todo
    /*public function testGetProperties(): void
    {
        $expectedData = [];
        $expectedData[0]['name'] = 'testparam1';
        $expectedData[0]['default'] = '';
        $expectedData[0]['type'] = 'string';
        $expectedData[0]['modifiers'][0] = 'private';
        $expectedData[1]['name'] = 'testparam2';
        $expectedData[1]['default'] = '';
        $expectedData[1]['type'] = 'string';
        $expectedData[1]['modifiers'][0] = 'private';

        $this->assertEquals(
            $expectedData,
            $this->classDataService->getProperties(
                __DIR__ . '/../../../data/classes/testClass.php'
            ),
            'Error in testGetProperties()'
        );
    }*/

    // @todo
    /*public function testGetTraits(): void
    {
        $expectedData = [];
        $expectedData[0]['name'] = 'testTrait';

        $this->assertEquals(
            $expectedData,
            $this->classDataService->getTraits(
                __DIR__ . '/../../../data/classes/testClass.php'
            ),
            'Error in testGetTraits()'
        );
    }*/

    // @todo
    /*public function testGetConstants(): void
    {
        $expectedData = [];
        $expectedData[0]['name'] = 'testConst';
        $expectedData[0]['value'] = 'testval1';
        $expectedData[0]['type'] = 'string';
        $expectedData[0]['modifiers'][0] = 'private';
        $expectedData[1]['name'] = 'testConst2';
        $expectedData[1]['value'] = 2;
        $expectedData[1]['type'] = 'integer';
        $expectedData[1]['modifiers'][0] = 'public';

        $this->assertEquals(
            $expectedData,
            $this->classDataService->getConstants(
                __DIR__ . '/../../../data/classes/testClass.php'
            ),
            'Error in testGetConstants()'
        );
    }*/

    // @todo
    /*public function testGetAllClasses(): void
    {
    }*/

    // @todo
   /* public function testGetClassData(): void
    {
        echo __DIR__ . '/../../../../data/classes/testClass.php';
        $file = File::create('test.php', __DIR__ . '/../../../../data/classes/testClass.php', 'testClass', 'classes', 111);
        $this->classDataService->getClassData($file);
    }*/
}
