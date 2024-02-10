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

namespace Contract\Formatter\Component;

interface TableFormatterInterface
{
    /**
     * @psalm-param non-empty-string $format
     */
    public function formatTable(string $format, array $header, array $rows, bool $withHeader): string;
}
