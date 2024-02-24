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
use PHPUnit\Framework\TestCase;
use Service\File\FileService;

final class FileServiceTest extends TestCase
{
    private FileService $fileService;

    public function setUp(): void
    {
        $this->fileService = new FileService();
    }

/*    public function testGetFileListOfDir(): void
    {
        $actualFiles = $this->fileService->getAllFilesWithinDir(
            __DIR__ . '/../../../data/classes/',
            new FileCollection()
        );

        $this->assertInstanceOf(FileCollection::class, $actualFiles);
        $this->assertEquals($this->getTestFileList(), $actualFiles);
    }*/

    private function getTestFileList(): FileCollection
    {
        $files = new FileCollection();

        $dto = File::create(
            'parentTest2Class',
            __DIR__ . '/../../../data/classes/more/parentTest2Class.php',
            'parentTest2Class.php',
            __DIR__ . '/../../../data/classes/more',
            66
        );
        $dto = $dto->withExtension('php');
        $files->add($dto);

        $dto = File::create(
            'test2Class',
            __DIR__ . '/../../../data/classes/more/test2Class.php',
            'test2Class.php',
            __DIR__ . '/../../../data/classes/more',
            652
        );
        $dto = $dto->withExtension('php');
        $files->add($dto);

        $dto = File::create(
            'parentTestClass',
            __DIR__ . '/../../../data/classes/parentTestClass.php',
            'parentTestClass.php',
            __DIR__ . '/../../../data/classes',
            382
        );
        $dto = $dto->withExtension('php');
        $files->add($dto);

        $dto = File::create(
            'testClass',
            __DIR__ . '/../../../data/classes/testClass.php',
            'testClass.php',
            __DIR__ . '/../../../data/classes',
            617
        );
        $dto = $dto->withExtension('php');
        $files->add($dto);

        $dto = File::create(
            'testInterface',
            __DIR__ . '/../../../data/classes/testInterface.php',
            'testInterface.php',
            __DIR__ . '/../../../data/classes',
            123
        );
        $dto = $dto->withExtension('php');
        $files->add($dto);

        $dto = File::create(
            'testInterface2',
            __DIR__ . '/../../../data/classes/testInterface2.php',
            'testInterface2.php',
            __DIR__ . '/../../../data/classes',
            132
        );
        $dto = $dto->withExtension('php');
        $files->add($dto);

        $dto = File::create(
            'testTrait',
            __DIR__ . '/../../../data/classes/testTrait.php',
            'testTrait.php',
            __DIR__ . '/../../../data/classes',
            132
        );
        $dto = $dto->withExtension('php');
        $files->add($dto);

        return $files;
    }
}
