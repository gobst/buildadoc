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

use Dto\Documentation\DocPage;
use Ramsey\Collection\AbstractCollection;

/**
 * @extends AbstractCollection<DocPage>
 */
final class DocPageCollection extends AbstractCollection
{
    public function getType(): string
    {
        return 'Dto\Documentation\DocPage';
    }
}
