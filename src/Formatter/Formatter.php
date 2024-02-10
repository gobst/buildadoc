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

namespace Formatter;

use Contract\Formatter\DokuWikiFormatInterface;
use Contract\Formatter\FormatterInterface;

final readonly class Formatter implements FormatterInterface, DokuWikiFormatInterface
{
    private const string FORMAT_SUFFIX = '_FORMAT';

    public function formatContent(string $formatStr, array $contentParts): string
    {
        return vsprintf($formatStr, $contentParts);
    }

    public function getFormat(string $format, string $type): string
    {
        return self::{strtoupper($format) . '_' . strtoupper($type) . self::FORMAT_SUFFIX};
    }
}
