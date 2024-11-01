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

namespace Service\Class\Filter;

use Dto\Class\ClassDto;

final readonly class ClassNameFilter
{
    private string $className;

    public function __construct(string $className)
    {
        $this->className = $className;
    }

    public function hasClassName(ClassDto $class): bool
    {
        $name = !empty($class->getNamespace()) ? $class->getNamespace() . '\\' . $class->getName() : $class->getName();

        return $name === $this->className;
    }
}
