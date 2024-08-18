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

namespace integration\Pipeline\Page;

use Contract\Pipeline\ClassPagePipelineStepInterface;
use Contract\Pipeline\Fetcher\ClassPageFetcherProviderInterface;
use Dto\Class\ClassDto;
use Dto\Common\Marker;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Collection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Pipeline\Page\ClassPageMarkerPipeline;

#[CoversClass(ClassPageMarkerPipeline::class)]
#[UsesClass(Collection::class)]
#[UsesClass(ClassDto::class)]
#[UsesClass(Pipeline::class)]
#[UsesClass(Marker::class)]
final class ClassPageMarkerPipelineTest extends TestCase
{
    private ClassPageFetcherProviderInterface&MockObject $fetcherProvider;
    private ClassPagePipelineStepInterface&MockObject $fetcher;
    private ClassPageMarkerPipeline $classPageMarkerPipe;

    public function setUp(): void
    {
        $this->fetcherProvider = $this->getMockBuilder(ClassPageFetcherProviderInterface::class)
            ->getMock();
        $this->fetcher = $this->getMockBuilder(ClassPagePipelineStepInterface::class)
            ->getMock();
        $this->classPageMarkerPipe = new ClassPageMarkerPipeline($this->fetcherProvider, new Pipeline());
    }

    #[TestDox('handlePipeline() method works correctly')]
    public function testHandlePipeline(): void
    {
        $classDto = ClassDto::create(
            'testClass',
            __DIR__ . '/../../../data/classes/testClass.php',
            Collection::make(),
            Collection::make()
        );

        /** @var Collection<int, Marker> $passable */
        $passable = Collection::make();

        $this->fetcherProvider->expects(self::exactly(8))
            ->method('getFetcher')
            ->willReturn(
                $this->fetcher
            );

        $this->fetcher->expects(self::exactly(8))
            ->method('handle')
            ->willReturnOnConsecutiveCalls(
                $passable->push(Marker::create('Step1')->withValue('1')),
                $passable->push(Marker::create('Step2')->withValue('2')),
                $passable->push(Marker::create('Step3')->withValue('3')),
                $passable->push(Marker::create('Step4')->withValue('4')),
                $passable->push(Marker::create('Step5')->withValue('5')),
                $passable->push(Marker::create('Step6')->withValue('6')),
                $passable->push(Marker::create('Step7')->withValue('7')),
                $passable->push(Marker::create('Step8')->withValue('8')),
            );

        $actualPassable = $this->classPageMarkerPipe->handlePipeline($classDto, 'dokuwiki', 'de');

        $this->assertEquals($passable, $actualPassable);
    }
}
