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

namespace Dto\Method;

use Collection\MethodParameterCollection;
use Collection\ModifierCollection;

final class Method
{
    private readonly ModifierCollection $modifiers;

    /**
     * @psalm-var non-empty-string
     */
    private readonly string $name;

    /**
     * @psalm-var non-empty-string
     */
    private readonly string $returnType;

    /**
     * @psalm-var non-empty-string
     */
    private readonly string $class;
    private ?string $description;
    private ?MethodParameterCollection $parameters;

    /**
     * @psalm-param non-empty-string $name
     * @psalm-param non-empty-string $returnType
     * @psalm-param non-empty-string $class
     */
    private function __construct(string $name, ModifierCollection $modifiers, string $returnType, string $class)
    {
        $this->name = $name;
        $this->modifiers = $modifiers;
        $this->returnType = $returnType;
        $this->class = $class;
        $this->description = null;
        $this->parameters = null;
    }

    /**
     * @psalm-param non-empty-string $name
     * @psalm-param non-empty-string $returnType
     * @psalm-param non-empty-string $class
     */
    public static function create(string $name, ModifierCollection $modifiers, string $returnType, string $class): self
    {
        return new self($name, $modifiers, $returnType, $class);
    }

    public function withDescription(string $description): self
    {
        $dto = new self(
            $this->name,
            $this->modifiers,
            $this->returnType,
            $this->class
        );
        $dto->description = $description;
        $dto->parameters = $this->parameters;

        return $dto;
    }

    public function withParameters(MethodParameterCollection $parameters): self
    {
        $dto = new self(
            $this->name,
            $this->modifiers,
            $this->returnType,
            $this->class
        );
        $dto->description = $this->description;
        $dto->parameters = $parameters;

        return $dto;
    }

    public function getModifiers(): ModifierCollection
    {
        return $this->modifiers;
    }

    /**
     * @psalm-return non-empty-string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @psalm-return non-empty-string
     */
    public function getReturnType(): string
    {
        return $this->returnType;
    }

    /**
     * @psalm-return non-empty-string
     */
    public function getClass(): string
    {
        return $this->class;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getParameters(): ?MethodParameterCollection
    {
        return $this->parameters;
    }
}
