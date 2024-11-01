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

namespace Contract\Decorator;

interface DecoratorInterface
{
    /**
     * @psalm-param non-empty-string $formatStr
     */
    public function formatText(string $formatStr, array $textParts): string;

    /**
     * @psalm-param non-empty-string $format
     * @psalm-param non-empty-string $type
     */
    public function getFormat(string $format, string $type): string;
}
