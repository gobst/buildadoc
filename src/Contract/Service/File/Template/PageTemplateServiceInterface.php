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

namespace Contract\Service\File\Template;

use Dto\Common\Marker;
use Illuminate\Support\Collection;

interface PageTemplateServiceInterface
{
    /**
     * @param Collection<int, Marker> $markers
     */
    public function fillTemplate(Collection $markers): string;
}
