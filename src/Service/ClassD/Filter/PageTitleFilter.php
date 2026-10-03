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

namespace Service\ClassD\Filter;

use Dto\Documentation\DocPage;

final readonly class PageTitleFilter
{
    private string $title;

    public function __construct(string $title)
    {
        $this->title = $title;
    }

    public function hasNotPageTitle(DocPage $page): bool
    {
        return $this->title !== $page->getTitle();
    }
}
