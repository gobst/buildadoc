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

use Formatter\Page\Component\TableFormatter;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use Webmozart\Assert\InvalidArgumentException;

/**
 * @psalm-suppress ArgumentTypeCoercion
 */
#[CoversClass(TableFormatter::class)]
final class TableFormatterTest extends TestCase
{
    private const string EXPECTED_DOKUWIKI_OUTPUT_FILE = __DIR__ . '/../../../../data/dokuwiki/methodTable.txt';

    private TableFormatter $tableFormatter;

    public function setUp(): void
    {
        $this->tableFormatter = new TableFormatter();
    }

    #[TestDox('formatListItem() method returns ordered list item in DokuWiki format')]
    public function testformatListItemWithOrderedAndDokuWikiFormat(): void
    {
        $expectedString = file_get_contents(self::EXPECTED_DOKUWIKI_OUTPUT_FILE);
        $actualString = $this->tableFormatter->formatTable(
            'dokuwiki',
            ['Parameter', 'Typ', 'Beschreibung', 'Standardwert'],
            [['$testInt', 'int', '', '0']],
            true
        );

        $this->assertSame($expectedString, $actualString);
    }

    #[DataProvider('formatTableTestDataProvider')]
    #[TestDox('formatTable() method fails on InvalidArgumentException with parameters $format, $header, $rows, $withHeader')]
    public function testformatTableItemFailsOnInvalidArgumentException(
        string $format,
        array $header,
        array $rows,
        bool $withHeader
    ): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->tableFormatter->formatTable($format, $header, $rows, $withHeader);
    }

    public static function formatTableTestDataProvider(): array
    {
        return [
            'testcase 1' => ['',  ['Parameter', 'Typ', 'Beschreibung', 'Standardwert'], ['$testInt', 'int', '', '0'], true],
            'testcase 2' => ['unknown',  ['Parameter', 'Typ', 'Beschreibung', 'Standardwert'], ['$testInt', 'int', '', '0'], true]
        ];
    }
}
