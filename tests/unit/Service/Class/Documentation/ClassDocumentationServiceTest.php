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

namespace unit\Service\Class\Documentation;

use Collection\ClassCollection;
use Collection\FileCollection;
use Contract\Service\Class\Data\ClassDataServiceInterface;
use Contract\Service\Class\Documentation\Page\ClassPageServiceInterface;
use Contract\Service\File\FileServiceInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Service\Class\Documentation\ClassDocumentationService;
use Webmozart\Assert\InvalidArgumentException;

#[CoversClass(ClassDocumentationService::class)]
#[UsesClass(FileCollection::class)]
#[UsesClass(ClassCollection::class)]
final class ClassDocumentationServiceTest extends TestCase
{
    private ClassDataServiceInterface&MockObject $classDataService;
    private FileServiceInterface&MockObject $fileService;
    private ClassPageServiceInterface&MockObject $classPageService;
    private ClassDocumentationService $classDocService;

    public function setUp(): void
    {
        $this->classDataService = $this->getMockBuilder(ClassDataServiceInterface::class)
            ->getMock();
        $this->fileService = $this->getMockBuilder(FileServiceInterface::class)
            ->getMock();
        $this->classPageService = $this->getMockBuilder(ClassPageServiceInterface::class)
            ->getMock();

        $this->classDocService = new ClassDocumentationService(
            $this->classDataService,
            $this->fileService,
            $this->classPageService
        );
    }

    #[TestDox('buildDocumentation() method works correctly')]
    public function testBuildDocumentation(): void
    {
        $this->classPageService->expects(self::once())
            ->method('dumpPages');

        $this->classDataService->expects(self::once())
            ->method('getAllClasses')
            ->willReturn(new ClassCollection());

        $this->fileService->expects(self::once())
            ->method('getAllFilesWithinDir')
            ->willReturn(new FileCollection());

        $this->classDocService->buildDocumentation(
            'test/source',
            'test/destination',
            'de',
            'dokuwiki'
        );
    }

    #[DataProvider('classDocumentationTestDataProvider')]
    #[TestDox('buildDocumentation() method fails on InvalidArgumentException with parameters: $sourceDir, $destDir, $lang, $format')]
    public function testGenerateUsedByClassListFailsOnInvalidArgumentException(
        string $sourceDir,
        string $destDir,
        string $lang,
        string $format
    ): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->classPageService->expects(self::never())
            ->method('dumpPages');

        $this->classDataService->expects(self::never())
            ->method('getAllClasses');

        $this->fileService->expects(self::never())
            ->method('getAllFilesWithinDir');

        $this->classDocService->buildDocumentation($sourceDir, $destDir, $lang, $format);
    }

    public static function classDocumentationTestDataProvider(): array
    {
        return [
            'testcase 1' => ['', 'destination/dir', 'de', 'dokuwiki'],
            'testcase 2' => ['source/dir', '', 'de', 'dokuwiki'],
            'testcase 3' => ['source/dir', 'destination/dir', '', 'dokuwiki'],
            'testcase 4' => ['source/dir', 'destination/dir', 'de', ''],
        ];
    }
}
