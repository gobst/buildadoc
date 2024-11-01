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

namespace Dto\Common;

final readonly class Modifier
{
    /**
     * @psalm-var non-empty-string
     */
    private string $name;

    /**
     * @psalm-param non-empty-string $name
     */
    private function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * @psalm-param non-empty-string $name
     */
    public static function create(string $name): self
    {
        return new self($name);
    }

    /**
     * @psalm-return non-empty-string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
