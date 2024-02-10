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

namespace unit\Generator\Documentation\Class\Page\Class;

use Collection\MethodCollection;
use Collection\ModifierCollection;
use Contract\Generator\Documentation\Class\Page\Class\Marker\ClassPathMarkerGeneratorInterface;
use Contract\Generator\Documentation\Class\Page\Class\Marker\ConstructorMarkerGeneratorInterface;
use Contract\Generator\Documentation\Class\Page\Class\Marker\ListMarkerGeneratorInterface;
use Contract\Generator\Documentation\Class\Page\Component\File\FilesTableGeneratorInterface;
use Contract\Generator\Documentation\Class\Page\Component\Heading\HeadingGeneratorInterface;
use Contract\Service\File\TemplateServiceInterface;
use Contract\Service\Translation\TranslationServiceInterface;
use Dto\Class\ClassDto;
use Generator\Documentation\Class\Page\Class\ClassPageGenerator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Webmozart\Assert\InvalidArgumentException;

final class ClassPageGeneratorTest extends TestCase
{
    private TranslationServiceInterface&MockObject $translationService;
    private FilesTableGeneratorInterface&MockObject $filesTableGenerator;
    private HeadingGeneratorInterface&MockObject $headingGenerator;
    private TemplateServiceInterface&MockObject $templateService;
    private ListMarkerGeneratorInterface&MockObject $listGenerator;
    private ConstructorMarkerGeneratorInterface&MockObject $constructorGenerator;
    private ClassPathMarkerGeneratorInterface&MockObject $classPathGenerator;
    private ClassPageGenerator $classPageGenerator;

    public function setUp(): void
    {
        $this->translationService = $this->getMockBuilder(TranslationServiceInterface::class)->getMock();
        $this->filesTableGenerator = $this->getMockBuilder(FilesTableGeneratorInterface::class)->getMock();
        $this->headingGenerator = $this->getMockBuilder(HeadingGeneratorInterface::class)->getMock();
        $this->templateService = $this->getMockBuilder(TemplateServiceInterface::class)->getMock();
        $this->listGenerator = $this->getMockBuilder(ListMarkerGeneratorInterface::class)->getMock();
        $this->constructorGenerator = $this->getMockBuilder(ConstructorMarkerGeneratorInterface::class)->getMock();
        $this->classPathGenerator = $this->getMockBuilder(ClassPathMarkerGeneratorInterface::class)->getMock();
        $this->classPageGenerator = new ClassPageGenerator(
            $this->translationService,
            $this->filesTableGenerator,
            $this->headingGenerator,
            $this->templateService,
            $this->listGenerator,
            $this->constructorGenerator,
            $this->classPathGenerator
        );
    }

    #[DataProvider('classPageGeneratorTestDataProvider')]
    #[TestDox('generate() method works correctly with parameters $class, $format, $lang')]
    public function testGenerate(ClassDto $class, string $format, string $lang): void
    {
        $this->translationService->expects(self::once())
            ->method('setLanguage');
        $this->translationService->expects(self::exactly(3))
            ->method('translate')
            ->willReturn('Test');
        $this->headingGenerator->expects(self::once())
            ->method('generate')
            ->willReturn('Testheading');
        $this->filesTableGenerator->expects(self::once())
            ->method('generate')
            ->willReturn('Testtable');
        $this->classPathGenerator->expects(self::once())
            ->method('generate')
            ->willReturn([]);
        $this->listGenerator->expects(self::once())
            ->method('generateConstantList')
            ->willReturn([]);
        $this->listGenerator->expects(self::once())
            ->method('generateMethodList')
            ->willReturn([]);
        $this->listGenerator->expects(self::once())
            ->method('generateInterfacesList')
            ->willReturn([]);
        $this->listGenerator->expects(self::once())
            ->method('generatePropertiesList')
            ->willReturn([]);
        $this->constructorGenerator->expects(self::once())
            ->method('generate')
            ->willReturn([]);
        $this->templateService->expects(self::once())
            ->method('fillTemplate')
            ->willReturn('Testtemplate');

        $this->classPageGenerator->generate($class, $format, $lang);
    }

    #[DataProvider('classPageGeneratorExceptionTestDataProvider')]
    #[TestDox('generate() method fails on InvalidArgumentException with parameters $class, $format, $lang')]
    public function testGenerateWillFailOnInvalidArgumentException(ClassDto $class, string $format, string $lang): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->translationService->expects(self::never())->method('setLanguage');
        $this->translationService->expects(self::never())->method('translate');
        $this->headingGenerator->expects(self::never())->method('generate');
        $this->filesTableGenerator->expects(self::never())->method('generate');
        $this->classPathGenerator->expects(self::never())->method('generate');
        $this->listGenerator->expects(self::never())->method('generateConstantList');
        $this->listGenerator->expects(self::never())->method('generateMethodList');
        $this->listGenerator->expects(self::never())->method('generateInterfacesList');
        $this->listGenerator->expects(self::never())->method('generatePropertiesList');
        $this->constructorGenerator->expects(self::never())->method('generate');
        $this->templateService->expects(self::never())->method('fillTemplate');

        $this->classPageGenerator->generate($class, $format, $lang);
    }

    public static function classPageGeneratorTestDataProvider(): array
    {
        $classDto = ClassDto::create(
            'testClass',
            __DIR__ . '/../../../data/classes/testClass.php',
            new MethodCollection(),
            new ModifierCollection()
        );

        return [
            'testcase 1' => [$classDto, 'dokuwiki', 'de'],
            'testcase 2' => [$classDto, 'dokuwiki', 'en'],
        ];
    }

    public static function classPageGeneratorExceptionTestDataProvider(): array
    {
        $classDto = ClassDto::create(
            'testClass',
            __DIR__ . '/../../../data/classes/testClass.php',
            new MethodCollection(),
            new ModifierCollection()
        );

        return [
            'testcase 1' => [$classDto, '', 'de'],
            'testcase 2' => [$classDto, 'dokuwiki', ''],
        ];
    }
}
