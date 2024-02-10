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

namespace Generator\Documentation\Class\Page\Component\Method;

use Contract\Generator\Documentation\Class\Page\Component\Method\MethodLineGeneratorInterface;
use Contract\Service\Class\Data\MethodDataServiceInterface;
use Dto\Method\Method;

final readonly class MethodLineGenerator implements MethodLineGeneratorInterface
{
    public function __construct(
        private MethodDataServiceInterface $methodDataService,
    ) {}

    public function generate(Method $method, bool $withModifiers = true, bool $bold = true): string
    {
        $methodSignature = $this->methodDataService->fetchMethodSignature($method, $withModifiers);
        $boldStr = $bold ? '**' : '';

        return $boldStr . $methodSignature . $boldStr;
    }
}
