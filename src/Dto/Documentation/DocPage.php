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

namespace Dto\Documentation;

final readonly class DocPage
{
    /**
     * @psalm-var non-empty-string
     */
    private string $content;

    /**
     * @psalm-var non-empty-string
     */
    private string $title;

    /**
     * @psalm-var non-empty-string
     */
    private string $fileName;

    /**
     * @psalm-var non-empty-string
     */
    private string $fileExtension;

    /**
     * @psalm-param non-empty-string $content
     * @psalm-param non-empty-string $title
     * @psalm-param non-empty-string $fileName
     * @psalm-param non-empty-string $fileExtension
     */
    private function __construct(
        string $content,
        string $title,
        string $fileName,
        string $fileExtension
    ) {
        $this->content = $content;
        $this->title = $title;
        $this->fileName = $fileName;
        $this->fileExtension = $fileExtension;
    }

    /**
     * @psalm-param non-empty-string $content
     * @psalm-param non-empty-string $title
     * @psalm-param non-empty-string $fileName
     * @psalm-param non-empty-string $fileExtension
     */
    public static function create(
        string $content,
        string $title,
        string $fileName,
        string $fileExtension
    ): self {
        return new self($content, $title, $fileName, $fileExtension);
    }

    /**
     * @psalm-return non-empty-string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @psalm-return non-empty-string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @psalm-return non-empty-string
     */
    public function getFileName(): string
    {
        return $this->fileName;
    }

    /**
     * @psalm-return non-empty-string
     */
    public function getFileExtension(): string
    {
        return $this->fileExtension;
    }
}
