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

namespace Contract\Service\Class\Data;

use Dto\Common\Modifier;
use Illuminate\Support\Collection;
use PhpParser\Node;

interface ModifierDataServiceInterface
{
    /**
     * @return Collection<int, Modifier>
     */
    public function getModifiers(Node $node): Collection;

    /**
     * @param Collection<int, Modifier> $collection
     */
    public function implodeModifierDTOCollection(Collection $collection, string $delimiter = ' '): string;
}
