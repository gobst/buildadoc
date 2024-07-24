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

use Collection\FileCollection;
use Dto\Common\File;
use Illuminate\Support\Collection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Service\File\FileService;
use Service\File\Filter\FileNameFilter;
use Symfony\Component\Filesystem\Filesystem;use Webmozart\Assert\InvalidArgumentException;

#[CoversClass(FileService::class)]
#[UsesClass(File::class)]
#[UsesClass(FileNameFilter::class)]
final class FileServiceTest extends TestCase
{
    private Filesystem&MockObject $filesystem;
    private FileService $fileService;

    public function setUp(): void
    {
        $this->filesystem = $this->getMockBuilder(Filesystem::class)
            ->getMock();
        $this->fileService = new FileService($this->filesystem);
    }

    #[TestDox('getAllFilesWithinDir() method works correctly with no excluded files')]
    public function testGetFileListOfDir(): void
    {
        /** @var Collection<int, File> $collection */
        $collection = Collection::make();
        $file1 = File::create(
            'parentTest2Class',
            '/home/gobst/Projects/BuildADoc/tests/unit/Service/File/../../../data/classes/more/parentTest2Class.php',
            'parentTest2Class.php',
            '/home/gobst/Projects/BuildADoc/tests/unit/Service/File/../../../data/classes/more',
            91
        )->withExtension('php');
        $file2 = File::create(
            'test2Class',
            '/home/gobst/Projects/BuildADoc/tests/unit/Service/File/../../../data/classes/more/test2Class.php',
            'test2Class.php',
            '/home/gobst/Projects/BuildADoc/tests/unit/Service/File/../../../data/classes/more',
            642
        )->withExtension('php');
        $collection->push($file1);
        $collection->push($file2);

        $actualFiles = $this->fileService->getAllFilesWithinDir(
            __DIR__.'/../../../data/classes/more/',
            Collection::make()
        );

        $this->assertEquals($collection, $actualFiles);
    }

    #[TestDox('getAllFilesWithinDir() method works correctly with excluded files')]
    public function testGetFileListOfDirWithExcludeFiles(): void
    {
        $collection = Collection::make();
        $file1 = File::create(
            'test2Class',
            '/home/gobst/Projects/BuildADoc/tests/unit/Service/File/../../../data/classes/more/test2Class.php',
            'test2Class.php',
            '/home/gobst/Projects/BuildADoc/tests/unit/Service/File/../../../data/classes/more',
            642
        )->withExtension('php');
        $collection->push($file1);

        $actualFiles = $this->fileService->getAllFilesWithinDir(
            __DIR__.'/../../../data/classes/more/',
            Collection::make(),
            ['parentTest2Class.php']
        );

        $this->assertEquals($collection, $actualFiles);
    }

    #[TestDox('getAllFilesWithinDir() method works correctly with extension')]
    public function testGetFileListOfDirWithExtension(): void
    {
        $collection = new FileCollection();

        $actualFiles = $this->fileService->getAllFilesWithinDir(
            __DIR__.'/../../../data/classes/more/',
            Collection::make(),
            [],
            'txt'
        );

        $this->assertEquals($collection, $actualFiles);
    }

    #[DataProvider('fileServiceFailsOnInvalidArgumentExceptionTestDataProvider')]
    #[TestDox('getAllFilesWithinDir() fails on InvalidArgumentException with parameters: $directory, $excludeFiles, $extension')]
    public function testGetFileListOfDirFailsOnInvalidArgumentException($directory, $excludeFiles, $extension): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->fileService->getAllFilesWithinDir(
            $directory,
            Collection::make(),
            $excludeFiles,
            $extension
        );
    }

    #[TestDox('dumpFile() method works correctly')]
    public function testDumpFile(): void
    {
        $this->filesystem->expects(self::once())->method('dumpFile');
        $this->fileService->dumpFile('testfile', 'superContent');
    }

    #[TestDox('dumpFile() method fails on InvalidArgumentException')]
    public function testDumpFileFailsOnInvalidArgumentException(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->filesystem->expects(self::never())->method('dumpFile');
        $this->fileService->dumpFile('', 'superContent');
    }

    #[TestDox('dumpFile() method works correctly')]
    public function testGetSingleFile(): void
    {
        $collection = Collection::make();
        $file1 = File::create(
            'parentTest2Class',
            '/home/gobst/Projects/BuildADoc/tests/unit/Service/File/../../../data/classes/more/parentTest2Class.php',
            'parentTest2Class.php',
            '/home/gobst/Projects/BuildADoc/tests/unit/Service/File/../../../data/classes/more',
            91
        )->withExtension('php');
        $file2 = File::create(
            'test2Class',
            '/home/gobst/Projects/BuildADoc/tests/unit/Service/File/../../../data/classes/more/test2Class.php',
            'test2Class.php',
            '/home/gobst/Projects/BuildADoc/tests/unit/Service/File/../../../data/classes/more',
            642
        )->withExtension('php');
        $collection->push($file1);
        $collection->push($file2);

        $actualDto = $this->fileService->getSingleFile(
            '/home/gobst/Projects/BuildADoc/tests/unit/Service/File/../../../data/classes/more/test2Class.php',
            $collection
        );

        $this->assertEquals($file2, $actualDto);
    }

    public static function fileServiceFailsOnInvalidArgumentExceptionTestDataProvider(): array
    {
        return [
            'testcase 1' => ['', [], 'php'],
            'testcase 2' => [__DIR__ . '/../../../data/classes/more', [], '']
        ];
    }

}
