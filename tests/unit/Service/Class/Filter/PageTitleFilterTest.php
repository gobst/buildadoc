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
use Dto\Documentation\DocPage;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Service\Class\Filter\PageTitleFilter;

#[CoversClass(PageTitleFilter::class)]
#[UsesClass(ClassDto::class)]
final class PageTitleFilterTest extends TestCase
{
    private PageTitleFilter $pageTitleFilter;

    public function setUp(): void
    {
        $this->pageTitleFilter = new PageTitleFilter('testTitle');
    }

    #[TestDox('hasNotPageTitle() method returns true')]
    public function testHasNotPageTitleReturnsTrue(): void
    {
        $docPage = DocPage::create('text', 'superTestTitle', 'fileName', 'txt');

        $this->assertTrue($this->pageTitleFilter->hasNotPageTitle($docPage));
    }

    #[TestDox('hasNotPageTitle() method returns false')]
    public function testHasNotPageTitleReturnsFalse(): void
    {
        $docPage = DocPage::create('text', 'testTitle', 'fileName', 'txt');

        $this->assertFalse($this->pageTitleFilter->hasNotPageTitle($docPage));
    }
}
