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

namespace Generator\Documentation\Class\Page\Component\Class;

use Contract\Generator\Documentation\Class\Page\Component\Class\ClassLineGeneratorInterface;
use Dto\Class\ClassDto;

final readonly class ClassLineGenerator implements ClassLineGeneratorInterface
{
    private const string FORMAT = '%s%s';
    private const string PREFIX = 'class::';

    public function __construct()
    {
    }

    public function generate(ClassDto $class): string
    {
        return sprintf(self::FORMAT, self::PREFIX, $class->getName());
    }
}
