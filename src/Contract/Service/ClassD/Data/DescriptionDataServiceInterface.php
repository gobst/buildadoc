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

namespace Contract\Service\ClassD\Data;

interface DescriptionDataServiceInterface
{
    /**
     * @psalm-param non-empty-string $phpdoc
     */
    public function getDescriptionByPHPDoc(string $phpdoc): array;

    /**
     * @psalm-param non-empty-string $phpdoc
     */
    public function getTagByPHPDoc(string $phpdoc, string $tag = 'param', int $limit = 4): array;
}
