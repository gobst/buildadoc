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

namespace Service\File\Filter;

use Dto\Common\File;

final readonly class FileNameFilter
{
    private string $fileName;

    public function __construct(string $fileName)
    {
        $this->fileName = $fileName;
    }

    public function hasFileName(File $file): bool
    {
        return $file->getPath() === $this->fileName;
    }
}
