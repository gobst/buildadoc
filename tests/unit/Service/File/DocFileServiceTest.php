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

namespace unit\Service\File;

use Collection\DocPageCollection;
use Contract\Service\File\FileServiceInterface;
use Dto\Documentation\DocPage;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Service\File\DocFileService;

#[CoversClass(DocFileService::class)]
#[UsesClass(DocPage::class)]
final class DocFileServiceTest extends TestCase
{
    private FileServiceInterface&MockObject $fileService;
    private DocFileService $docFileService;

    public function setUp(): void
    {
        $this->fileService = $this->getMockBuilder(FileServiceInterface::class)
            ->getMock();
        $this->docFileService = new DocFileService($this->fileService);
    }
    #[TestDox('dumpDocFiles() method works correctly')]
    public function testDumpDocFiles(): void
    {
        $collection = new DocPageCollection();
        $docPage = DocPage::create('superContent', 'superTitle', 'super', 'txt');
        $collection->add($docPage);

        $this->fileService->expects($this->once())->method('dumpFile');

        $this->docFileService->dumpDocFiles($collection, 'destDir');
    }
}
