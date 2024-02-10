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

namespace Contract\Generator\Documentation\Class\Page\Component\File;

use Dto\Class\ClassDto;

interface FilesTableGeneratorInterface
{
    /**
     * @psalm-param non-empty-string $format
     */
    public function generate(ClassDto $class, string $format, array $headerLabels): string;
}
