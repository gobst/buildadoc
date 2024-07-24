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

namespace unit\Service\File\Filter;

use Dto\Common\File;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use Service\Class\Filter\TagFilter;
use Service\File\Filter\FileNameFilter;

#[CoversClass(FileNameFilter::class)]
final class FileNameFilterTest extends TestCase
{
    private FileNameFilter $fileNameFilter;

    public function setUp(): void
    {
        $this->fileNameFilter = new FileNameFilter(
            '/home/gobst/Projects/BuildADoc/tests/unit/Service/File/../../../data/classes/more/test2Class.php'
        );
    }

    #[TestDox('hasFileName() method returns true')]
    public function testHasFileNameReturnsTrue(): void
    {
        $file = File::create(
            'test2Class',
            '/home/gobst/Projects/BuildADoc/tests/unit/Service/File/../../../data/classes/more/test2Class.php',
            'test2Class.php',
            '/home/gobst/Projects/BuildADoc/tests/unit/Service/File/../../../data/classes/more',
            642
        )->withExtension('php');

        $this->assertTrue($this->fileNameFilter->hasFileName($file));
    }

    #[TestDox('hasFileName() method returns false')]
    public function testHasFileNameReturnsFalse(): void
    {
        $file = File::create(
            'test',
            '/home/gobst/Projects/BuildADoc/tests/unit/Service/File/../../../data/classes/more/test.php',
            'test.php',
            '/home/gobst/Projects/BuildADoc/tests/unit/Service/File/../../../data/classes/more',
            642
        )->withExtension('php');

        $this->assertFalse($this->fileNameFilter->hasFileName($file));
    }
}
