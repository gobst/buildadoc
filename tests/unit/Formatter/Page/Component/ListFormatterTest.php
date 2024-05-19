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
use Formatter\Page\Component\ListFormatter;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Webmozart\Assert\InvalidArgumentException;

/**
 * @psalm-suppress ArgumentTypeCoercion
 */
#[CoversClass(ListFormatter::class)]
final class ListFormatterTest extends TestCase
{
    private FormatterInterface&MockObject $formatter;
    private ListFormatter $listFormatter;

    public function setUp(): void
    {
        $this->formatter = $this->getMockBuilder(FormatterInterface::class)->getMock();
        $this->listFormatter = new ListFormatter($this->formatter);
    }

    #[TestDox('formatListItem() method returns ordered list item in DokuWiki format')]
    public function testformatListItemWithOrderedAndDokuWikiFormat(): void
    {
        $this->formatter->expects(self::once())
            ->method('getFormat')
            ->with('dokuwiki', 'constant_list')
            ->willReturn('%s');

        $this->formatter->expects(self::once())
            ->method('formatContent')
            ->with('%s', ['list'])
            ->willReturn('list');

        $expectedString =  '  - list'. chr(13);
        $actualString = $this->listFormatter->formatListItem(
            'dokuwiki',
            'constant_list',
            ['list']
        );

        $this->assertSame($expectedString, $actualString);
    }

    #[TestDox('formatListItem() method returns unordered list item in DokuWiki format')]
    public function testformatListItemWithUnorderedAndDokuWikiFormat(): void
    {
        $this->formatter->expects(self::once())
            ->method('getFormat')
            ->with('dokuwiki', 'constant_list')
            ->willReturn('%s');

        $this->formatter->expects(self::once())
            ->method('formatContent')
            ->with('%s', ['list'])
            ->willReturn('list');

        $expectedString =  '  * list'. chr(13);
        $actualString = $this->listFormatter->formatListItem(
            'dokuwiki',
            'constant_list',
            ['list'],
            'unordered'
        );

        $this->assertSame($expectedString, $actualString);
    }

    #[DataProvider('formatListTestDataProvider')]
    #[TestDox('formatListItem() method fails on InvalidArgumentException with parameters $format, $listType, $contentParts, $listItemType')]
    public function testformatListItemFailsOnInvalidArgumentException(
        string $format,
        string $listType,
        array $contentParts,
        string $listItemType
    ): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->formatter->expects(self::never())
            ->method('getFormat');

        $this->formatter->expects(self::never())
            ->method('formatContent');

        $this->listFormatter->formatListItem($format, $listType, $contentParts, $listItemType);
    }

    public static function formatListTestDataProvider(): array
    {
        return [
            'testcase 1' => ['', 'method_list', ['text'], 'ordered'],
            'testcase 2' => ['dokuwiki', '', ['text'], 'ordered'],
            'testcase 3' => ['dokuwiki', 'method_list', ['text'], '']
        ];
    }
}
