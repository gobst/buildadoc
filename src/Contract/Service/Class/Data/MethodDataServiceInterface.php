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

namespace Contract\Service\Class\Data;

use Dto\Class\ClassDto;
use Dto\Method\Method;
use Illuminate\Support\Collection;

interface MethodDataServiceInterface
{
    /**
     * @return Collection<int, Method>
     */
    public function getMethods(array $ast): Collection;

    /**
     * @return Collection<int, Method>
     */
    public function fetchInheritedMethods(ClassDto $class): Collection;

    public function fetchMethodSignature(Method $method, bool $withModifiers = true): string;

    /**
     * @psalm-param non-empty-string $name
     * @param Collection<int, Method> $methods
     */
    public function fetchMethod(string $name, Collection $methods): Method|bool;
}
