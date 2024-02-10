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

use Dto\Class\Constant;
use Ramsey\Collection\AbstractCollection;

/**
 * @extends AbstractCollection<Constant>
 */
final class ConstantCollection extends AbstractCollection
{
    public function getType(): string
    {
        return 'Dto\Class\Constant';
    }
}
