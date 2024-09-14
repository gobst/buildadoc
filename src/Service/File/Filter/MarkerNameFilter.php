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

namespace Service\File\Filter;

use Dto\Common\Marker;

final readonly class MarkerNameFilter
{
    private string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function hasName(Marker $marker): bool
    {
        return $this->name === $marker->getName();
    }
}
