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

use ArrayIterator;
use Dto\Common\Modifier;
use Ramsey\Collection\AbstractCollection;

/**
 * @extends AbstractCollection<Modifier>
 */
final class ModifierCollection extends AbstractCollection
{
    public function getType(): string
    {
        return 'Dto\Common\Modifier';
    }

    public function toString(): string
    {
        /** @var ArrayIterator $iterator */
        $iterator = $this->getIterator();
        $modifiers = [];
        while ($iterator->valid()) {
            $modifiers[] = $iterator->current()->getName();
            $iterator->next();
        }

        return implode(' ', $modifiers);
    }
}
