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

namespace Contract\Service\Class\Documentation\Page;

use Dto\Class\ClassDto;
use Illuminate\Support\Collection;

interface ClassPageServiceInterface
{
    /**
     * @psalm-param non-empty-string $lang
     * @psalm-param non-empty-string $format
     * @param Collection<int, ClassDto> $classes
     */
    public function dumpPages(Collection $classes, string $destDirectory, string $lang, string $format): void;
}
