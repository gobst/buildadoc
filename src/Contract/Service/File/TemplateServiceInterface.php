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

namespace Contract\Service\File;

use Exception;

interface TemplateServiceInterface
{
    /**
     * @psalm-param non-empty-string $template
     * @psalm-param non-empty-array<string, string> $marker
     *
     * @throws Exception
     */
    public function fillTemplate(string $template, array $marker): string;
}
