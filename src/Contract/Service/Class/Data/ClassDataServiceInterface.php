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

namespace Contract\Service\Class\Data;

use Dto\Class\ClassDto;
use Dto\Class\Constant;
use Dto\Class\InterfaceDto;
use Dto\Class\TraitDto;
use Dto\Common\File;
use Dto\Common\Property;
use Illuminate\Support\Collection;

interface ClassDataServiceInterface
{
    /**
     * @return Collection<int, Constant>
     */
    public function getConstants(array $ast): Collection;

    /**
     * @return Collection<int, Property>
     */
    public function getProperties(array $ast): Collection;

    /**
     * @return Collection<int, InterfaceDto>
     */
    public function getInterfaces(array $ast): Collection;

    /**
     * @return Collection<int, TraitDto>
     */
    public function getTraits(array $ast): Collection;

    /**
     * @param Collection<int, File> $files
     * @return Collection<int, ClassDto>
     */
    public function getAllClasses(Collection $files): Collection;

    /**
     * @psalm-param non-empty-string $phpFile
     */
    public function getAst(string $phpFile): ?array;

    public function getClassData(File $file): ?ClassDto;

    /**
     * @param Collection<int, ClassDto> $classes
     */
    public function getSingleClass(string $className, Collection $classes): ?ClassDto;
}
