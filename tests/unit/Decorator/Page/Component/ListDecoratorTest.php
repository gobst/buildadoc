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
use Decorator\Page\Component\ListDecorator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Webmozart\Assert\InvalidArgumentException;

#[CoversClass(ListDecorator::class)]
final class ListDecoratorTest extends TestCase
{
    private DecoratorInterface&MockObject $decorator;

    public function setUp(): void
    {
        $this->decorator = $this->getMockBuilder(DecoratorInterface::class)->getMock();
    }

    #[TestDox('format() method returns ordered list item in DokuWiki format')]
    public function testformatWithOrderedAndDokuWikiFormat(): void
    {
        $listDecorator = new ListDecorator($this->decorator, 'constant_list');

        $this->decorator->expects(self::once())
            ->method('getFormat')
            ->with('dokuwiki', 'constant_list')
            ->willReturn('%s');

        $this->decorator->expects(self::once())
            ->method('formatText')
            ->with('%s', ['list'])
            ->willReturn('list');

        $expectedString =  '  - list'. chr(13);
        $actualString = $listDecorator->format(
            'dokuwiki',
            ['list']
        );

        $this->assertSame($expectedString, $actualString);
    }

    #[TestDox('format() method returns unordered list item in DokuWiki format')]
    public function testformatWithUnorderedAndDokuWikiFormat(): void
    {
        $listDecorator = new ListDecorator($this->decorator, 'constant_list', 'unordered');

        $this->decorator->expects(self::once())
            ->method('getFormat')
            ->with('dokuwiki', 'constant_list')
            ->willReturn('%s');

        $this->decorator->expects(self::once())
            ->method('formatText')
            ->with('%s', ['list'])
            ->willReturn('list');

        $expectedString =  '  * list'. chr(13);
        $actualString = $listDecorator->format(
            'dokuwiki',
            ['list']
        );

        $this->assertSame($expectedString, $actualString);
    }

    #[DataProvider('formatListTestDataProvider')]
    #[TestDox('format() method fails on InvalidArgumentException with parameters $format, $listType, $contentParts, $listItemType')]
    public function testformatListItemFailsOnInvalidArgumentException(
        string $format,
        string $listType,
        array  $textParts,
        string $listItemType
    ): void
    {
        $this->expectException(InvalidArgumentException::class);

        $listDecorator = new ListDecorator($this->decorator, $listType, $listItemType);

        $this->decorator->expects(self::never())
            ->method('getFormat');

        $this->decorator->expects(self::never())
            ->method('formatText');

        $listDecorator->format($format, $textParts);
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
