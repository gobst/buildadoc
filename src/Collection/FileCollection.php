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

use Dto\Common\File;
use Ramsey\Collection\AbstractCollection;

/**
 * @extends AbstractCollection<File>
 */
final class FileCollection extends AbstractCollection
{
    public function getType(): string
    {
        return 'Dto\Common\File';
    }
}
