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

namespace Contract\Decorator\Component\Link;

use Dto\Method\Method;

interface MethodLinkDestinationFormatterInterface
{
    /**
     * @psalm-param non-empty-string $format
     */
    public function formatDestination(
        string $format,
        Method $method,
        string $mainDirectory
    ): string;
}
