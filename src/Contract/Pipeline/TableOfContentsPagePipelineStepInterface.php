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

namespace Contract\Pipeline;

use Dto\Common\Marker;
use Illuminate\Support\Collection;

interface TableOfContentsPagePipelineStepInterface
{
    /**
     * @psalm-param non-empty-string $lang
     * @psalm-param non-empty-string $format
     * @return Collection<int, Marker>
     */
    public function handle(
        Collection $passable,
        Collection $classes,
        string $format,
        string $lang,
        string $mainDirectory
    ): Collection;
}
