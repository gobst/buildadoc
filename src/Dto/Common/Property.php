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

use Collection\ModifierCollection;

final class Property
{
    private readonly ModifierCollection $modifiers;

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
    private function __construct(
        string $name,
        string $type,
        ModifierCollection $modifiers
    ) {
        $this->name = $name;
        $this->type = $type;
        $this->modifiers = $modifiers;
        $this->defaultValue = null;
    }

    /**
     * @psalm-param non-empty-string $name
     * @psalm-param non-empty-string $type
     */
    public static function create(
        string $name,
        string $type,
        ModifierCollection $modifiers
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

    public function getModifiers(): ModifierCollection
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
