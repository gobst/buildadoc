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

namespace Collection;

use Dto\Method\Method;
use Ramsey\Collection\AbstractCollection;

/**
 * @extends AbstractCollection<Method>
 */
final class MethodCollection extends AbstractCollection
{
    public function getType(): string
    {
        return 'Dto\Method\Method';
    }
}
