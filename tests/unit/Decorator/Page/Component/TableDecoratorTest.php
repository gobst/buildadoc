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

use Decorator\Page\Component\TableDecorator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use Webmozart\Assert\InvalidArgumentException;

#[CoversClass(TableDecorator::class)]
final class TableDecoratorTest extends TestCase
{
    private const string EXPECTED_DOKUWIKI_OUTPUT_FILE = __DIR__ . '/../../../../data/dokuwiki/methodTable.txt';

    private TableDecorator $tableDecorator;

    public function setUp(): void
    {
        $this->tableDecorator = new TableDecorator();
    }

    #[TestDox('format() method works correctly with DokuWiki format')]
    public function testFormatWithDokuWikiFormat(): void
    {
        $expectedString = file_get_contents(self::EXPECTED_DOKUWIKI_OUTPUT_FILE);
        $actualString = $this->tableDecorator->format(
            'dokuwiki',
            ['Parameter', 'Typ', 'Beschreibung', 'Standardwert'],
            [['$testInt', 'int', '', '0']],
            true
        );

        $this->assertSame($expectedString, $actualString);
    }

    #[DataProvider('formatTestDataProvider')]
    #[TestDox('format() method fails on InvalidArgumentException with parameters $format, $header, $rows, $withHeader')]
    public function testFormatFailsOnInvalidArgumentException(
        string $format,
        array $header,
        array $rows,
        bool $withHeader
    ): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->tableDecorator->format($format, $header, $rows, $withHeader);
    }

    public static function formatTestDataProvider(): array
    {
        return [
            'testcase 1' => ['',  ['Parameter', 'Typ', 'Beschreibung', 'Standardwert'], ['$testInt', 'int', '', '0'], true],
            'testcase 2' => ['unknown',  ['Parameter', 'Typ', 'Beschreibung', 'Standardwert'], ['$testInt', 'int', '', '0'], true]
        ];
    }
}
