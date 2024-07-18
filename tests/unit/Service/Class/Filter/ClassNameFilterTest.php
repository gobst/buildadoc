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

declare(strict_types=1);

namespace unit\Service\Class\Filter;

use Collection\MethodCollection;
use Collection\ModifierCollection;
use Dto\Class\ClassDto;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Service\Class\Filter\ClassNameFilter;

#[CoversClass(ClassNameFilter::class)]
#[UsesClass(ClassDto::class)]
final class ClassNameFilterTest extends TestCase
{
    private ClassNameFilter $classNameFilter;

    public function setUp(): void
    {
        $this->classNameFilter = new ClassNameFilter('testClass');
    }

    #[TestDox('hasClassName() method returns true')]
    public function testHasClassNameReturnsTrue(): void
    {
        $classDto = ClassDto::create(
            'testClass',
            __DIR__ . '/../../../data/classes/TestClass.php',
            new MethodCollection(),
            new ModifierCollection()
        );

        $this->assertTrue($this->classNameFilter->hasClassName($classDto));
    }

    #[TestDox('hasClassName() method returns false')]
    public function testHasClassNameReturnsFalse(): void
    {
        $classDto = ClassDto::create(
            'superTestClass',
            __DIR__ . '/../../../data/classes/TestClass.php',
            new MethodCollection(),
            new ModifierCollection()
        );

        $this->assertFalse($this->classNameFilter->hasClassName($classDto));
    }
}
