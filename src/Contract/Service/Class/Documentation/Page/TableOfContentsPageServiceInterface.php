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

namespace Contract\Service\Class\Documentation\Page;

use Dto\Class\ClassDto;
use Dto\Documentation\DocPage;
use Illuminate\Support\Collection;

interface TableOfContentsPageServiceInterface
{
    /**
     * @psalm-param non-empty-string $format
     * @psalm-param non-empty-string $lang
     * @param Collection<int, ClassDto> $classes
     */
    public function generateTableOfContentsPage(
        Collection $classes,
        string $format,
        string $lang,
        string $mainDirectory
    ): DocPage;
}
