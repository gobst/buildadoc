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

use Dto\Method\Method;

final readonly class MethodNameFilter
{
    private string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function hasNotName(Method $method): bool
    {
        return $this->name !== $method->getName();
    }

    public function hasName(Method $method): bool
    {
        return $this->name === $method->getName();
    }
}
