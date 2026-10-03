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

use Dto\ClassD\ClassDto;

final readonly class ParentClassNameFilter
{
    private string $parentClassName;

    public function __construct(string $parentClassName)
    {
        $this->parentClassName = $parentClassName;
    }

    public function hasParentClass(ClassDto $class): bool
    {
        return $class->getParentClassName() === $this->parentClassName;
    }
}
