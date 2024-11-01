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

namespace Contract\Generator\Documentation\Class\Page\Component\Method;

use Dto\Method\Method;

interface MethodTableGeneratorInterface
{
    /**
     * @psalm-param non-empty-string $format
     */
    public function generate(Method $method, string $format, array $headerLabels): string;
}
