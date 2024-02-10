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

use Collection\ClassCollection;
use Collection\ConstantCollection;
use Collection\FileCollection;
use Collection\InterfaceCollection;
use Collection\PropertyCollection;
use Collection\TraitCollection;
use Dto\Class\ClassDto;
use Dto\Common\File;

interface ClassDataServiceInterface
{
    public function getConstants(array $ast): ConstantCollection;

    public function getProperties(array $ast): PropertyCollection;

    public function getInterfaces(array $ast): InterfaceCollection;

    public function getTraits(array $ast): TraitCollection;

    public function getAllClasses(FileCollection $files): ClassCollection;

    /**
     * @psalm-param non-empty-string $phpFile
     */
    public function getAst(string $phpFile): ?array;

    public function getClassData(File $file): ?ClassDto;

    public function getSingleClass(string $className, ClassCollection $classes): ClassDto;
}
