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

final class File
{
    /**
     * @psalm-var non-empty-string
     */
    private readonly string $name;

    /**
     * @psalm-var non-empty-string
     */
    private readonly string $directory;

    /**
     * @psalm-var non-empty-string
     */
    private readonly string $path;

    /**
     * @psalm-var non-empty-string
     */
    private readonly string $basename;

    /**
     * Size in Bytes.
     *
     * @psalm-var non-negative-int
     */
    private readonly int $size;
    private string $extension;

    /**
     * @psalm-param non-empty-string $name
     * @psalm-param non-empty-string $path
     * @psalm-param non-empty-string $basename
     * @psalm-param non-empty-string $directory
     * @psalm-param non-negative-int $size
     */
    private function __construct(string $name, string $path, string $basename, string $directory, int $size)
    {
        $this->name = $name;
        $this->path = $path;
        $this->basename = $basename;
        $this->directory = $directory;
        $this->size = $size;
    }

    /**
     * @psalm-param non-empty-string $name
     * @psalm-param non-empty-string $path
     * @psalm-param non-empty-string $basename
     * @psalm-param non-empty-string $directory
     * @psalm-param non-negative-int $size
     */
    public static function create(string $name, string $path, string $basename, string $directory, int $size): self
    {
        return new self($name, $path, $basename, $directory, $size);
    }

    public function withExtension(string $extension): self
    {
        $dto = new self(
            $this->name,
            $this->path,
            $this->basename,
            $this->directory,
            $this->size
        );
        $dto->extension = $extension;

        return $dto;
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
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @psalm-return non-empty-string
     */
    public function getBasename(): string
    {
        return $this->basename;
    }

    /**
     * @psalm-return non-negative-int
     */
    public function getSize(): int
    {
        return $this->size;
    }

    /**
     * @psalm-return non-empty-string
     */
    public function getDirectory(): string
    {
        return $this->directory;
    }

    public function getExtension(): string
    {
        return $this->extension;
    }
}
