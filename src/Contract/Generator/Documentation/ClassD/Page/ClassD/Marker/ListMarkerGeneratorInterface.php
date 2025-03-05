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

namespace Contract\Generator\Documentation\ClassD\Page\ClassD\Marker;

use Dto\ClassD\ClassDto;

interface ListMarkerGeneratorInterface
{
    /**
     * @psalm-param non-empty-string $format
     * @psalm-param non-empty-string $listType
     * @psalm-param non-empty-string $lang
     */
    public function generateUsedByClassList(
        ClassDto $class,
        string $format,
        string $listType,
        string $lang
    ): array;

    /**
     * @psalm-param non-empty-string $format
     * @psalm-param non-empty-string $listType
     * @psalm-param non-empty-string $lang
     */
    public function generateConstantList(
        ClassDto $class,
        string $format,
        string $listType,
        string $lang
    ): array;

    /**
     * @psalm-param non-empty-string $format
     * @psalm-param non-empty-string $listType
     * @psalm-param non-empty-string $lang
     */
    public function generatePropertiesList(
        ClassDto $class,
        string $format,
        string $listType,
        string $lang
    ): array;

    /**
     * @psalm-param non-empty-string $format
     * @psalm-param non-empty-string $listType
     * @psalm-param non-empty-string $lang
     */
    public function generateInterfacesList(
        ClassDto $class,
        string $format,
        string $listType,
        string $lang
    ): array;

    /**
     * @psalm-param non-empty-string $format
     * @psalm-param non-empty-string $listType
     * @psalm-param non-empty-string $lang
     */
    public function generateMethodList(
        ClassDto $class,
        string $format,
        string $listType,
        string $lang,
        string $mainDirectory
    ): array;
}
