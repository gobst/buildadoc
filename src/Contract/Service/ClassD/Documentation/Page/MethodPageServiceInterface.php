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

namespace Contract\Service\ClassD\Documentation\Page;

use Dto\Documentation\DocPage;
use Dto\Method\Method;

interface MethodPageServiceInterface
{
    /**
     * @psalm-param non-empty-string $format
     * @psalm-param non-empty-string $lang
     */
    public function generateMethodPage(Method $method, string $format, string $lang): DocPage;
}
