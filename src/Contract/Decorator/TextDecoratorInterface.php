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

namespace Contract\Decorator;

interface TextDecoratorInterface
{
    /**
     * @psalm-param non-empty-string $format
     * @psalm-param non-empty-list $textParts
     */
    public function format(string $format, array $textParts): string;
}
