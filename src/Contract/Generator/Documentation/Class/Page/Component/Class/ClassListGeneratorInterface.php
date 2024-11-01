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

namespace Contract\Generator\Documentation\Class\Page\Component\Class;

use Dto\Class\ClassDto;
use Illuminate\Support\Collection;

interface ClassListGeneratorInterface
{
    /**
     * @psalm-param non-empty-string $format
     * @psalm-param non-empty-string $listType
     * @param Collection<int, ClassDto> $classes
     */
    public function generate(
        Collection $classes,
        string     $format,
        bool       $link = true,
        string     $listType = 'ordered',
        string     $mainDirectory = ''
    ): string;
}
