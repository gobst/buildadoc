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

namespace Contract\Service\Class\Documentation;

interface ClassDocumentationServiceInterface
{
    /**
     * @psalm-param non-empty-string $sourceDir
     * @psalm-param non-empty-string $destDir
     * @psalm-param non-empty-string $name
     * @psalm-param non-empty-string $lang
     * @psalm-param non-empty-string $format
     */
    public function buildDocumentation(
        string $sourceDir,
        string $destDir,
        string $name,
        string $lang,
        string $format
    ): void;
}
