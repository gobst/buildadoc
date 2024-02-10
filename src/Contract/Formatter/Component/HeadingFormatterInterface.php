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

interface HeadingFormatterInterface
{
    /**
     * @psalm-param non-empty-string $format
     * @psalm-param positive-int $level
     */
    public function formatHeading(string $format, array $contentParts, int $level): string;
}
