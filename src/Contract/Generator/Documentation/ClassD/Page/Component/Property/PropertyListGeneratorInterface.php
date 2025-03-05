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

namespace Contract\Generator\Documentation\ClassD\Page\Component\Property;

use Dto\ClassD\ClassDto;

interface PropertyListGeneratorInterface
{
    /**
     * @psalm-param non-empty-string $format
     * @psalm-param non-empty-string $listType
     */
    public function generate(ClassDto $class, string $format, string $listType = 'ordered'): string;
}
