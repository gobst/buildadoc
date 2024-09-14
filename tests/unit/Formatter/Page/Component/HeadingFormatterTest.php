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

namespace unit\Formatter\Page\Component;

use Contract\Formatter\FormatterInterface;
use Formatter\Page\Component\HeadingFormatter;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Webmozart\Assert\InvalidArgumentException;

/**
 * @psalm-suppress ArgumentTypeCoercion
 */
final class HeadingFormatterTest extends TestCase
{
    private FormatterInterface&MockObject $formatter;
    private HeadingFormatter $headingFormatter;

    public function setUp(): void
    {
        $this->formatter = $this->getMockBuilder(FormatterInterface::class)->getMock();
        $this->headingFormatter = new HeadingFormatter($this->formatter);
    }

    #[DataProvider('formatHeadingTestDataProvider')]
    #[TestDox('formatHeading() method works correctly with parameters $format, $contentParts, $level')]
    public function testformatHeading(string $format, array $contentParts, int $level): void
    {
        $this->formatter->expects(self::once())
            ->method('formatContent')
            ->willReturn('test');

        switch ([$format, $contentParts, $level]) {
            case ['dokuwiki', ['test'], 1]:
                $this->formatter->expects(self::once())
                    ->method('getFormat')
                    ->with('dokuwiki', 'heading_level1')
                    ->willReturn('test');
                break;
            case ['dokuwiki', ['test'], 2]:
                $this->formatter->expects(self::once())
                    ->method('getFormat')
                    ->with('dokuwiki', 'heading_level2')
                    ->willReturn('test');
                break;
            case ['dokuwiki', ['test'], 3]:
                $this->formatter->expects(self::once())
                    ->method('getFormat')
                    ->with('dokuwiki', 'heading_level3')
                    ->willReturn('test');
                break;
            case ['dokuwiki', ['test'], 4]:
                $this->formatter->expects(self::once())
                    ->method('getFormat')
                    ->with('dokuwiki', 'heading_level4')
                    ->willReturn('test');
                break;
            case ['dokuwiki', ['test'], 5]:
                $this->formatter->expects(self::once())
                    ->method('getFormat')
                    ->with('dokuwiki', 'heading_level5')
                    ->willReturn('test');
                break;
        }

        $this->headingFormatter->formatHeading($format, $contentParts, $level);
    }

    #[TestDox('formatHeading() method throws InvalidArgumentException on invalid format')]
    public function testformatHeadingFailsWithInvalidFormatOnInvalidArgumentException(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->headingFormatter->formatHeading('xyz', [], 1);
    }

    #[TestDox('formatHeading() method throws InvalidArgumentException on empty format string')]
    public function testformatHeadingFailsWithEmptyFormatStringOnInvalidArgumentException(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->formatter->expects(self::once())
            ->method('getFormat')
            ->willReturn('');
        $this->formatter->expects(self::never())
            ->method('formatContent');

        $this->headingFormatter->formatHeading('dokuwiki', ['test'], 1);
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
