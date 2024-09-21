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

namespace unit\Decorator\Page\Component;

use Contract\Decorator\DecoratorInterface;
use Decorator\Page\Component\HeadingDecorator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Webmozart\Assert\InvalidArgumentException;

#[CoversClass(HeadingDecorator::class)]
final class HeadingDecoratorTest extends TestCase
{
    private DecoratorInterface&MockObject $textDecorator;

    public function setUp(): void
    {
        $this->textDecorator = $this->getMockBuilder(DecoratorInterface::class)->getMock();
    }

    #[DataProvider('formatHeadingTestDataProvider')]
    #[TestDox('formatHeading() method works correctly with parameters $format, $textParts, $level')]
    public function testformatHeading(string $format, array $textParts, int $level): void
    {
        $headingDecorator = new HeadingDecorator($this->textDecorator, $level);

        $this->textDecorator->expects(self::once())
            ->method('formatText')
            ->willReturn('test');

        switch ([$format, $textParts, $level]) {
            case ['dokuwiki', ['test'], 1]:
                $this->textDecorator->expects(self::once())
                    ->method('getFormat')
                    ->with('dokuwiki', 'heading_level1')
                    ->willReturn('test');
                break;
            case ['dokuwiki', ['test'], 2]:
                $this->textDecorator->expects(self::once())
                    ->method('getFormat')
                    ->with('dokuwiki', 'heading_level2')
                    ->willReturn('test');
                break;
            case ['dokuwiki', ['test'], 3]:
                $this->textDecorator->expects(self::once())
                    ->method('getFormat')
                    ->with('dokuwiki', 'heading_level3')
                    ->willReturn('test');
                break;
            case ['dokuwiki', ['test'], 4]:
                $this->textDecorator->expects(self::once())
                    ->method('getFormat')
                    ->with('dokuwiki', 'heading_level4')
                    ->willReturn('test');
                break;
            case ['dokuwiki', ['test'], 5]:
                $this->textDecorator->expects(self::once())
                    ->method('getFormat')
                    ->with('dokuwiki', 'heading_level5')
                    ->willReturn('test');
                break;
        }

        $headingDecorator->format($format, $textParts);
    }

    #[TestDox('formatHeading() method throws InvalidArgumentException on invalid format')]
    public function testformatHeadingFailsWithInvalidFormatOnInvalidArgumentException(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $headingDecorator = new HeadingDecorator($this->textDecorator, 1);
        $headingDecorator->format('xyz', []);
    }

    #[TestDox('formatHeading() method throws InvalidArgumentException on empty format string')]
    public function testformatHeadingFailsWithEmptyFormatStringOnInvalidArgumentException(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $headingDecorator = new HeadingDecorator($this->textDecorator, 1);

        $this->textDecorator->expects(self::once())
            ->method('getFormat')
            ->willReturn('');
        $this->textDecorator->expects(self::never())
            ->method('formatText');

        $headingDecorator->format('dokuwiki', ['test']);
    }

    public static function formatHeadingTestDataProvider(): array
    {
        return [
            'testcase 1' => ['dokuwiki', ['test'], 1],
            'testcase 2' => ['dokuwiki', ['test'], 2],
            'testcase 3' => ['dokuwiki', ['test'], 3],
            'testcase 4' => ['dokuwiki', ['test'], 4],
            'testcase 5' => ['dokuwiki', ['test'], 5]
        ];
    }
}
