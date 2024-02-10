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

interface ListFormatterInterface
{
    /**
     * @psalm-param non-empty-string $format
     * @psalm-param non-empty-string $listType
     * @psalm-param non-empty-string $listItemType
     */
    public function formatListItem(
        string $format,
        string $listType,
        array $contentParts,
        string $listItemType = 'ordered'
    ): string;
}
