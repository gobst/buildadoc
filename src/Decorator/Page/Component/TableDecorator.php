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

namespace Decorator\Page\Component;

use Contract\Decorator\Component\TableDecoratorInterface;
use Contract\Decorator\DokuWikiFormatInterface;
use Webmozart\Assert\Assert;
use Webmozart\Assert\InvalidArgumentException;

final readonly class TableDecorator implements TableDecoratorInterface, DokuWikiFormatInterface
{
    /**
     * @throws InvalidArgumentException
     */
    public function format(string $format, array $header, array $rows, bool $withHeader): string
    {
        Assert::stringNotEmpty($format);
        $table = '';
        if (!empty($header) && $withHeader === true) {
            $table = $this->formatTableRowToFormat($format, $header, true);
        }
        if (!empty($rows)) {
            foreach ($rows as $row) {
                $table .= $this->formatTableRowToFormat($format, $row, false);
            }
        }

        return $table;
    }

    /**
     * @psalm-param non-empty-string $format
     * @throws InvalidArgumentException
     */
    private function formatTableRowToFormat(string $format, array $row, bool $header): string
    {
        return match ($format) {
            self::DOKUWIKI_FORMAT_KEY => $this->formatTableRowToDokuWiki($row, $header),
            default => throw new InvalidArgumentException('Error: Unknown format!'),
        };
    }

    private function formatTableRowToDokuWiki(array $row, bool $header = false): string
    {
        $tableRow = '';
        $cntCols = count($row);
        $sign = $header === true ? '^' : '|';
        if (!empty($row)) {
            $index = 1;
            foreach ($row as $col) {
                $col = preg_replace('/\t+|\r\n|\n|\r/', '', '<nowiki>' . $col . '</nowiki>');
                // first column
                if ($index === 1) {
                    if ($cntCols === 1) {
                        $tableRow .= $sign . ' ' . $col . ' ' . $sign . chr(13);
                    } else {
                        $tableRow .= $sign . ' ' . $col . ' ';
                    }
                // last column
                } elseif ($index === $cntCols) {
                    $tableRow .= $sign . ' ' . $col . ' ' . $sign . chr(13);
                // middle columns
                } else {
                    $tableRow .= $sign . ' ' . $col . ' ';
                }
                ++$index;
            }
        }

        return $tableRow;
    }
}
