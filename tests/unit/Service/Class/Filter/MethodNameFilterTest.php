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

use Dto\Common\Modifier;
use Dto\Method\Method;
use Illuminate\Support\Collection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Service\ClassD\Filter\MethodNameFilter;

#[CoversClass(MethodNameFilter::class)]
#[UsesClass(Modifier::class)]
#[UsesClass(Method::class)]
#[UsesClass(Collection::class)]
final class MethodNameFilterTest extends TestCase
{
    private MethodNameFilter $methodNameFilter;

    public function setUp(): void
    {
        $this->methodNameFilter = new MethodNameFilter('testMethod');
    }

    #[TestDox('hasName() method returns true')]
    public function testHasNameReturnsTrue(): void
    {
        /** @var Collection<int, Modifier> $modifiers */
        $modifiers = Collection::make();
        $publicModifierDto = Modifier::create('public');
        $modifiers->push($publicModifierDto);

        $methodDto = Method::create('testMethod', $modifiers, 'string', 'testClass');

        $this->assertTrue($this->methodNameFilter->hasName($methodDto));
    }

    #[TestDox('hasName() method returns false')]
    public function testHasNameReturnsFalse(): void
    {
        /** @var Collection<int, Modifier> $modifiers */
        $modifiers = Collection::make();
        $publicModifierDto = Modifier::create('public');
        $modifiers->push($publicModifierDto);

        $methodDto = Method::create('superTestMethod', $modifiers, 'string', 'testClass');

        $this->assertFalse($this->methodNameFilter->hasName($methodDto));
    }

    #[TestDox('hasNotName() method returns false')]
    public function testHasNotNameReturnsFalse(): void
    {
        /** @var Collection<int, Modifier> $modifiers */
        $modifiers = Collection::make();
        $publicModifierDto = Modifier::create('public');
        $modifiers->push($publicModifierDto);

        $methodDto = Method::create('testMethod', $modifiers, 'string', 'testClass');

        $this->assertFalse($this->methodNameFilter->hasNotName($methodDto));
    }

    #[TestDox('hasNotName() method returns true')]
    public function testHasNotNameReturnsTrue(): void
    {
        /** @var Collection<int, Modifier> $modifiers */
        $modifiers = Collection::make();
        $publicModifierDto = Modifier::create('public');
        $modifiers->push($publicModifierDto);

        $methodDto = Method::create('superTestMethod', $modifiers, 'string', 'testClass');

        $this->assertTrue($this->methodNameFilter->hasNotName($methodDto));
    }
}
