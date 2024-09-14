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

use Dto\Common\Modifier;
use Illuminate\Support\Collection;

final readonly class Constant
{
    /**
     * @var Collection<int, Modifier> $modifiers
     */
    private Collection $modifiers;

    /**
     * @psalm-var non-empty-string
     */
    private string $type;

    /**
     * @psalm-var non-empty-string
     */
    private string $name;

    private mixed $value;

    /**
     * @psalm-param non-empty-string $name
     * @psalm-param non-empty-string $type
     * @param Collection<int, Modifier> $modifiers
     */
    private function __construct(
        string $name,
        string $type,
        mixed $value,
        Collection $modifiers
    ) {
        $this->name = $name;
        $this->type = $type;
        $this->value = $value;
        $this->modifiers = $modifiers;
    }

    /**
     * @psalm-param non-empty-string $name
     * @psalm-param non-empty-string $type
     * @param Collection<int, Modifier> $modifiers
     */
    public static function create(
        string $name,
        string $type,
        mixed $value,
        Collection $modifiers
    ): self {
        return new self($name, $type, $value, $modifiers);
    }

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

    public function getValue(): mixed
    {
        return $this->value;
    }
}
