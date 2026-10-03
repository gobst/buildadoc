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

namespace Contract\Generator\Documentation\ClassD\Page\Component\InterfaceD;

use Dto\ClassD\InterfaceDto;
use Illuminate\Support\Collection;

interface InterfaceListGeneratorInterface
{
    /**
     * @psalm-param non-empty-string $format
     * @psalm-param non-empty-string $listType
     * @param Collection<int, InterfaceDto> $interfaces
     */
    public function generate(
        Collection $interfaces,
        string $format,
        string $listType = 'ordered',
        bool $linked = true
    ): string;
}
