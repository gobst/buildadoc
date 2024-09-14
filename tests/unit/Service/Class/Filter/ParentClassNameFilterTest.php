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

use Dto\Class\ClassDto;
use Illuminate\Support\Collection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Service\Class\Filter\ParentClassNameFilter;

#[CoversClass(ParentClassNameFilter::class)]
#[UsesClass(ClassDto::class)]
#[UsesClass(Collection::class)]
final class ParentClassNameFilterTest extends TestCase
{
    private ParentClassNameFilter $parentClassNameF;

    public function setUp(): void
    {
        $this->parentClassNameF = new ParentClassNameFilter('parentClassName');
    }

    #[TestDox('hasParentClass() method returns true')]
    public function testHasParentClassReturnsTrue(): void
    {
        $classDto = ClassDto::create(
            'testClass',
            __DIR__ . '/../../../data/classes/TestClass.php',
            Collection::make(),
            Collection::make()
        )->withParentClassName('parentClassName');

        $this->assertTrue($this->parentClassNameF->hasParentClass($classDto));
    }

    #[TestDox('hasParentClass() method returns false')]
    public function testHasParentClassReturnsFalse(): void
    {
        $classDto = ClassDto::create(
            'testClass',
            __DIR__ . '/../../../data/classes/TestClass.php',
            Collection::make(),
            Collection::make()
        )->withParentClassName('superParentClassName');

        $this->assertFalse($this->parentClassNameF->hasParentClass($classDto));
    }
}
