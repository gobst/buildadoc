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

namespace unit\Pipeline\Page\Fetcher;

use Contract\Generator\Documentation\Class\Page\Class\Marker\ClassPageMarkerInterface;
use Contract\Generator\Documentation\Class\Page\Component\Class\ClassPathGeneratorInterface;
use Dto\Class\ClassDto;
use Dto\Common\Marker;
use Illuminate\Support\Collection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Pipeline\Page\Fetcher\Class\ClassPathFetcher;
use Webmozart\Assert\InvalidArgumentException;

#[CoversClass(ClassPathFetcher::class)]
#[UsesClass(ClassDto::class)]
#[UsesClass(Collection::class)]
class ClassPathFetcherTest extends TestCase
{
    private ClassPathFetcher $classPathFetcher;
    private ClassPathGeneratorInterface $classPathGenerator;

    protected function setUp(): void
    {
        $this->classPathGenerator = $this->getMockBuilder(ClassPathGeneratorInterface::class)->getMock();
        $this->classPathFetcher = new ClassPathFetcher($this->classPathGenerator);
    }

    #[TestDox('handle() method works correctly')]
    public function testHandle(): void
    {
        $passable = new Collection();
        $classDto = ClassDto::create('test', 'test', new Collection(), new Collection());
        $format = 'testFormat';
        $lang = 'testLang';
        $mainDirectory = 'testMainDirectory';
        $expectedValue = 'expectedValue';

        $this->classPathGenerator
            ->method('generate')
            ->with($classDto, $format, $mainDirectory)
            ->willReturn($expectedValue);

        $result = $this->classPathFetcher->handle($passable, $classDto, $format, $lang, $mainDirectory);

        $this->assertNotEmpty($result);
        $this->assertInstanceOf(Marker::class, $result->first());
        $this->assertEquals(ClassPageMarkerInterface::CLASS_PATH_MARKER, $result->first()->getName());
        $this->assertEquals($expectedValue, $result->first()->getValue());
    }

    #[TestDox('handle() method throws InvalidArgumentException when $format is empty')]
    public function testHandleEmptyFormat(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $classDto = ClassDto::create('test', 'test', new Collection(), new Collection());

        $this->classPathFetcher->handle(
            new Collection(),
            $classDto,
            '',
            'testLang',
            'testMainDirectory'
        );
     }

    #[TestDox('handle() method throws InvalidArgumentException when $lang is empty')]
     public function testHandleEmptyLang(): void
     {
         $this->expectException(InvalidArgumentException::class);

         $classDto = ClassDto::create('test', 'test', new Collection(), new Collection());

         $this->classPathFetcher->handle(
             new Collection(),
             $classDto,
             'testFormat',
             '',
             'testMainDirectory'
         );
      }
 }
