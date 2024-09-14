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

namespace Dto\Common;

use Illuminate\Support\Collection;

final class Property
{
    /**
     * @var Collection<int, Modifier>
     */
    private readonly Collection $modifiers;

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
     * @param Collection<int, Modifier> $modifiers
     */
    private function __construct(
        string $name,
        string $type,
        Collection $modifiers
    ) {
        $this->name = $name;
        $this->type = $type;
        $this->modifiers = $modifiers;
        $this->defaultValue = null;
    }

    /**
     * @psalm-param non-empty-string $name
     * @psalm-param non-empty-string $type
     * @param Collection<int, Modifier> $modifiers
     */
    public static function create(
        string $name,
        string $type,
        Collection $modifiers
    ): self {
        return new self($name, $type, $modifiers);
    }

    public function withDefaultValue(mixed $defaultValue): self
    {
        $dto = new self(
            $this->name,
            $this->type,
            $this->modifiers
        );
        $dto->defaultValue = $defaultValue;

        return $dto;
    }

    /**
     * @return Collection<int, Modifier>
     */
    public function getModifiers(): Collection
    {
        return $this->modifiers;
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
