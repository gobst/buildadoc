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

namespace Contract\Generator\Documentation\Class\Page\Component\Link;

interface LinkGeneratorInterface
{
    /**
     * @psalm-param non-empty-string $format
     * @psalm-param non-empty-string $dest
     */
    public function generate(
        string $format,
        string $dest,
        string $text = ''
    ): string;
}
