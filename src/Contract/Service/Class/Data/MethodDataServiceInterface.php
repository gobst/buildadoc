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

namespace Contract\Service\Class\Data;

use Collection\MethodCollection;
use Dto\Class\ClassDto;
use Dto\Method\Method;

interface MethodDataServiceInterface
{
    public function getMethods(array $ast): MethodCollection;

    public function fetchInheritedMethods(ClassDto $class): MethodCollection;

    public function fetchMethodSignature(Method $method, bool $withModifiers = true): string;

    /**
     * @psalm-param non-empty-string $name
     */
    public function fetchMethod(string $name, MethodCollection $methods): Method|bool;
}
