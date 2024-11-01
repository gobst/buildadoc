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

namespace Contract\Generator\Documentation\Class\Page\Component\Heading;

interface HeadingGeneratorInterface
{
    /**
     * @psalm-param non-empty-string $format
     * @psalm-param non-empty-string $text
     * @psalm-param positive-int $level
     */
    public function generate(string $text, int $level, string $format): string;
}
