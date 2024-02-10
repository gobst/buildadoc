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

use Collection\ClassCollection;

interface ClassPageServiceInterface
{
    /**
     * @psalm-param non-empty-string $lang
     * @psalm-param non-empty-string $format
     */
    public function dumpPages(ClassCollection $classes, string $destDirectory, string $lang, string $format): void;
}
