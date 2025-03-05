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

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use Service\ClassD\Filter\TagFilter;

#[CoversClass(TagFilter::class)]
final class TagFilterTest extends TestCase
{
    private TagFilter $tagFilter;

    public function setUp(): void
    {
        $this->tagFilter = new TagFilter('param');
    }

    #[TestDox('hasTag() method returns true')]
    public function testHasTagReturnsTrue(): void
    {
        $this->assertTrue($this->tagFilter->hasTag('@param'));
    }

    #[TestDox('hasTag() method returns false')]
    public function testHasTagReturnsFalse(): void
    {
        $this->assertFalse($this->tagFilter->hasTag('@see'));
    }
}
