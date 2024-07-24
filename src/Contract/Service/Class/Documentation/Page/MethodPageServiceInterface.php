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

namespace Contract\Service\Class\Documentation\Page;

use Dto\Documentation\DocPage;
use Dto\Method\Method;
use Illuminate\Support\Collection;

interface MethodPageServiceInterface
{
    /**
     * @psalm-param non-empty-string $lang
     * @psalm-param non-empty-string $format
     * @param Collection<int, Method> $methods
     * @return Collection<int, DocPage>
     */
    public function getPages(Collection $methods, string $lang, string $format): Collection;
}
