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
use Formatter\Page\Component\LinkFormatter;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Webmozart\Assert\InvalidArgumentException;

/**
 * @psalm-suppress ArgumentTypeCoercion
 */
final class LinkFormatterTest extends TestCase
{
    private FormatterInterface&MockObject $formatter;
    private LinkFormatter $linkFormatter;

    public function setUp(): void
    {
        $this->formatter = $this->getMockBuilder(FormatterInterface::class)->getMock();
        $this->linkFormatter = new LinkFormatter($this->formatter);
    }

    #[DataProvider('formatLinkTestDataProvider')]
    #[TestDox('formatLink() method works correctly with parameters $format, $contentParts')]
    public function testformatLink(string $format, array $contentParts): void
    {
        $this->formatter->expects(self::once())
            ->method('formatContent')
            ->willReturn('test');

        if(!empty($contentParts[1])){
            $this->formatter->expects(self::once())
                ->method('getFormat')
                ->with('dokuwiki', 'link_with_text')
                ->willReturn('test');
        }else{
            $this->formatter->expects(self::once())
                ->method('getFormat')
                ->with('dokuwiki', 'link_without_text')
                ->willReturn('test');
        }

        $this->linkFormatter->formatLink($format, $contentParts);
    }

    #[TestDox('formatLink() method throws InvalidArgumentException on invalid format')]
    public function testformatLinkFailsWithInvalidFormatOnInvalidArgumentException(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->linkFormatter->formatLink('xyz', []);
    }

    #[TestDox('formatLink() method throws InvalidArgumentException on empty format string')]
    public function testformatLinkFailsWithEmptyFormatStringOnInvalidArgumentException(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->formatter->expects(self::once())
            ->method('getFormat')
            ->willReturn('');
        $this->formatter->expects(self::never())
            ->method('formatContent');

        $this->linkFormatter->formatLink('dokuwiki', ['test']);
    }

    public static function formatLinkTestDataProvider(): array
    {
        return [
            'testcase 1' => ['dokuwiki', ['link']],
            'testcase 2' => ['dokuwiki', ['link', 'linkText']]
        ];
    }
}
