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

namespace Service\Class\Filter;

final readonly class TagFilter
{
    private string $tag;

    public function __construct(string $tag)
    {
        $this->tag = $tag;
    }

    public function hasTag(string $txt)
    {
        return str_contains($txt, '@' . $this->tag);
    }
}
