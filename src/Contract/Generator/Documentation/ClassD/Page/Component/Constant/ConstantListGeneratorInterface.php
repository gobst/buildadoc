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

namespace Contract\Generator\Documentation\ClassD\Page\Component\Constant;

use Dto\ClassD\Constant;
use Illuminate\Support\Collection;

interface ConstantListGeneratorInterface
{
    /**
     * @psalm-param non-empty-string $format
     * @psalm-param non-empty-string $listType
     * @param Collection<int, Constant> $constants
     */
    public function generate(Collection $constants, string $format, string $listType = 'ordered'): string;
}
