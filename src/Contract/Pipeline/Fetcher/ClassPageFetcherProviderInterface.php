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

namespace Contract\Pipeline\Fetcher;

use Contract\Pipeline\ClassPagePipelineStepInterface;

interface ClassPageFetcherProviderInterface
{
    /**
     * @psalm-param non-empty-string $type
     */
    public function getFetcher(string $type): ClassPagePipelineStepInterface;
}
