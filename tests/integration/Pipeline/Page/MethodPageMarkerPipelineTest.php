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

use Contract\Pipeline\Fetcher\MethodPageFetcherProviderInterface;
use Contract\Pipeline\MethodPagePipelineStepInterface;
use Dto\Common\Marker;
use Dto\Method\Method;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Collection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Pipeline\Page\MethodPageMarkerPipeline;

#[CoversClass(MethodPageMarkerPipeline::class)]
#[UsesClass(Collection::class)]
#[UsesClass(Method::class)]
#[UsesClass(Pipeline::class)]
#[UsesClass(Marker::class)]
final class MethodPageMarkerPipelineTest extends TestCase
{
    private MethodPageFetcherProviderInterface&MockObject $fetcherProvider;
    private MethodPagePipelineStepInterface&MockObject $fetcher;
    private MethodPageMarkerPipeline $methodPageMarkerPipe;

    public function setUp(): void
    {
        $this->fetcherProvider = $this->getMockBuilder(MethodPageFetcherProviderInterface::class)
            ->getMock();
        $this->fetcher = $this->getMockBuilder(MethodPagePipelineStepInterface::class)
            ->getMock();
        $this->methodPageMarkerPipe = new MethodPageMarkerPipeline($this->fetcherProvider, new Pipeline());
    }

    #[TestDox('handlePipeline() method works correctly')]
    public function testHandlePipeline(): void
    {
        $methodDto = Method::create(
            'testMethodWithoutPHPDoc',
            Collection::make(),
            'string',
            'testClass'
        );

        /** @var Collection<int, Marker> $passable */
        $passable = Collection::make();

        $this->fetcherProvider->expects(self::exactly(3))
            ->method('getFetcher')
            ->willReturn(
                $this->fetcher
            );

        $this->fetcher->expects(self::exactly(3))
            ->method('handle')
            ->willReturnOnConsecutiveCalls(
                $passable->push(Marker::create('Step1')->withValue('1')),
                $passable->push(Marker::create('Step2')->withValue('2')),
                $passable->push(Marker::create('Step3')->withValue('3')),
            );

        $actualPassable = $this->methodPageMarkerPipe->handlePipeline($methodDto, 'dokuwiki', 'de');

        $this->assertEquals($passable, $actualPassable);
    }
}
