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

namespace Contract\Service\ClassD\Documentation\Page;

use Dto\ClassD\ClassDto;
use Dto\Documentation\DocPage;
use Illuminate\Support\Collection;

interface ClassPageServiceInterface
{
    /**
     * @psalm-param non-empty-string $format
     * @psalm-param non-empty-string $lang
     * @return Collection<int, DocPage>
     */
    public function generateClassPageIncludingMethodPages(
        ClassDto $class,
        string $format,
        string $lang,
        string $mainDirectory
    ): Collection;
}
