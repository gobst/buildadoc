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

namespace Dto\Class;

use Collection\ConstantCollection;
use Collection\MethodCollection;

final class InterfaceDto
{
    /**
     * @psalm-var non-empty-string
     */
    private readonly string $name;
    private ?string $namespace;
    private ?ConstantCollection $constants;
    private ?MethodCollection $methods;

    /**
     * @psalm-param non-empty-string $name
     */
    private function __construct(string $name)
    {
        $this->name = $name;
        $this->namespace = null;
        $this->constants = null;
        $this->methods = null;
    }

    /**
     * @psalm-param non-empty-string $name
     */
    public static function create(string $name): self
    {
        return new self($name);
    }

    public function withNamespace(string $namespace): self
    {
        $dto = new self($this->name);
        $dto->namespace = $namespace;
        $dto->constants = $this->constants;
        $dto->methods = $this->methods;

        return $dto;
    }

    public function withConstants(ConstantCollection $constants): self
    {
        $dto = new self($this->name);
        $dto->namespace = $this->namespace;
        $dto->constants = $constants;
        $dto->methods = $this->methods;

        return $dto;
    }

    public function withMethods(MethodCollection $methods): self
    {
        $dto = new self($this->name);
        $dto->namespace = $this->namespace;
        $dto->constants = $this->constants;
        $dto->methods = $methods;

        return $dto;
    }

    /**
     * @psalm-return non-empty-string $name
     */
    public function getName(): string
    {
        return $this->name;
    }

    public function getNamespace(): ?string
    {
        return $this->namespace;
    }

    public function getConstants(): ?ConstantCollection
    {
        return $this->constants;
    }

    public function getMethods(): ?MethodCollection
    {
        return $this->methods;
    }
}
