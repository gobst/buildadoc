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

final class Marker
{
    /**
     * @psalm-var non-empty-string
     */
    private readonly string $name;

    private ?string $value;

    /**
     * @psalm-param non-empty-string $name
     */
    private function __construct(string $name)
    {
        $this->name = $name;
        $this->value = null;
    }

    /**
     * @psalm-param non-empty-string $name
     */
    public static function create(string $name): self
    {
        return new self($name);
    }

    public function withValue(string $value): self
    {
        $dto = new self($this->name);
        $dto->value = $value;

        return $dto;
    }

    /**
     * @psalm-return non-empty-string
     */
    public function getName(): string
    {
        return $this->name;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }
}
