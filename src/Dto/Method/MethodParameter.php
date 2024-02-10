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

final class MethodParameter
{
    /**
     * @psalm-var non-empty-string
     */
    private readonly string $type;

    /**
     * @psalm-var non-empty-string
     */
    private readonly string $name;
    private mixed $defaultValue;

    /**
     * @psalm-param non-empty-string $name
     * @psalm-param non-empty-string $type
     */
    private function __construct(string $name, string $type)
    {
        $this->name = $name;
        $this->type = $type;
        $this->defaultValue = null;
    }

    /**
     * @psalm-param non-empty-string $name
     * @psalm-param non-empty-string $type
     */
    public static function create(string $name, string $type): self
    {
        return new self($name, $type);
    }

    public function withDefaultValue(mixed $defaultValue): self
    {
        $dto = new self(
            $this->name,
            $this->type
        );
        $dto->defaultValue = $defaultValue;

        return $dto;
    }

    /**
     * @psalm-return non-empty-string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @psalm-return non-empty-string
     */
    public function getName(): string
    {
        return $this->name;
    }

    public function getDefaultValue(): mixed
    {
        return $this->defaultValue;
    }
}
