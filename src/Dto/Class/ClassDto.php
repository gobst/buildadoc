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

namespace Dto\Class;

use Dto\Common\File;
use Dto\Common\Modifier;
use Dto\Common\Property;
use Dto\Method\Method;
use Illuminate\Support\Collection;

final class ClassDto
{
    /**
     * @var Collection<int, Method>
     */
    private readonly Collection $methods;

    /**
     * @var Collection<int, Modifier>
     */
    private readonly Collection $modifiers;

    /**
     * @psalm-var non-empty-string
     */
    private readonly string $name;

    /**
     * @psalm-var non-empty-string
     */
    private readonly string $filepath;

    /**
     * @var Collection<int, Property>|null
     */
    private ?Collection $properties;

    /**
     * @var Collection<int, Constant>|null
     */
    private ?Collection $constants;

    private ?string $namespace;

    /**
     * @var Collection<int, InterfaceDto>|null
     */
    private ?Collection $interfaces;

    /**
     * @var Collection<int, TraitDto>|null
     */
    private ?Collection $traits;

    /**
     * @var Collection<int, ClassDto>|null
     */
    private ?Collection $parentClasses;

    /**
     * @var Collection<int, ClassDto>|null
     */
    private ?Collection $childClasses;

    private ?string $parentClassName;

    /**
     * @var Collection<int, File>|null
     */
    private ?Collection $necessaryFiles;

    /**
     * @var Collection<int, Method>|null
     */
    private ?Collection $inheritedMethods;

    /**
     * @psalm-param non-empty-string $name
     * @psalm-param non-empty-string $filepath
     * @param Collection<int, Method> $methods
     * @param Collection<int, Modifier> $modifiers
     */
    private function __construct(
        string $name,
        string $filepath,
        Collection $methods,
        Collection $modifiers
    ) {
        $this->name = $name;
        $this->filepath = $filepath;
        $this->methods = $methods;
        $this->modifiers = $modifiers;
        $this->properties = null;
        $this->constants = null;
        $this->namespace = null;
        $this->interfaces = null;
        $this->traits = null;
        $this->parentClasses = null;
        $this->necessaryFiles = null;
        $this->parentClassName = null;
        $this->inheritedMethods = null;
        $this->childClasses = null;
    }

    /**
     * @psalm-param non-empty-string $name
     * @psalm-param non-empty-string $filepath
     * @param Collection<int, Method> $methods
     * @param Collection<int, Modifier> $modifiers
     */
    public static function create(
        string $name,
        string $filepath,
        Collection $methods,
        Collection $modifiers
    ): self {
        return new self($name, $filepath, $methods, $modifiers);
    }

    /**
     * @param Collection<int, Property> $properties
     */
    public function withProperties(Collection $properties): self
    {
        $dto = new self(
            $this->name,
            $this->filepath,
            $this->methods,
            $this->modifiers
        );
        $dto->properties = $properties;
        $dto->parentClasses = $this->parentClasses;
        $dto->necessaryFiles = $this->necessaryFiles;
        $dto->traits = $this->traits;
        $dto->interfaces = $this->interfaces;
        $dto->namespace = $this->namespace;
        $dto->constants = $this->constants;
        $dto->parentClassName = $this->parentClassName;
        $dto->inheritedMethods = $this->inheritedMethods;
        $dto->childClasses = $this->childClasses;

        return $dto;
    }

    /**
     * @param Collection<int, ClassDto> $parentClasses
     */
    public function withParentClasses(Collection $parentClasses): self
    {
        $dto = new self(
            $this->name,
            $this->filepath,
            $this->methods,
            $this->modifiers
        );
        $dto->properties = $this->properties;
        $dto->parentClasses = $parentClasses;
        $dto->necessaryFiles = $this->necessaryFiles;
        $dto->interfaces = $this->interfaces;
        $dto->traits = $this->traits;
        $dto->namespace = $this->namespace;
        $dto->constants = $this->constants;
        $dto->parentClassName = $this->parentClassName;
        $dto->inheritedMethods = $this->inheritedMethods;
        $dto->childClasses = $this->childClasses;

        return $dto;
    }

    /**
     * @param Collection<int, File> $necessaryFiles
     */
    public function withNecessaryFiles(Collection $necessaryFiles): self
    {
        $dto = new self(
            $this->name,
            $this->filepath,
            $this->methods,
            $this->modifiers
        );
        $dto->properties = $this->properties;
        $dto->parentClasses = $this->parentClasses;
        $dto->necessaryFiles = $necessaryFiles;
        $dto->traits = $this->traits;
        $dto->interfaces = $this->interfaces;
        $dto->namespace = $this->namespace;
        $dto->constants = $this->constants;
        $dto->parentClassName = $this->parentClassName;
        $dto->inheritedMethods = $this->inheritedMethods;
        $dto->childClasses = $this->childClasses;

        return $dto;
    }

    /**
     * @param Collection<int, TraitDto> $traits
     */
    public function withTraits(Collection $traits): self
    {
        $dto = new self(
            $this->name,
            $this->filepath,
            $this->methods,
            $this->modifiers
        );
        $dto->properties = $this->properties;
        $dto->parentClasses = $this->parentClasses;
        $dto->necessaryFiles = $this->necessaryFiles;
        $dto->traits = $traits;
        $dto->interfaces = $this->interfaces;
        $dto->namespace = $this->namespace;
        $dto->constants = $this->constants;
        $dto->parentClassName = $this->parentClassName;
        $dto->inheritedMethods = $this->inheritedMethods;
        $dto->childClasses = $this->childClasses;

        return $dto;
    }

    /**
     * @param Collection<int, InterfaceDto> $interfaces
     */
    public function withInterfaces(Collection $interfaces): self
    {
        $dto = new self(
            $this->name,
            $this->filepath,
            $this->methods,
            $this->modifiers
        );
        $dto->properties = $this->properties;
        $dto->parentClasses = $this->parentClasses;
        $dto->necessaryFiles = $this->necessaryFiles;
        $dto->traits = $this->traits;
        $dto->interfaces = $interfaces;
        $dto->namespace = $this->namespace;
        $dto->constants = $this->constants;
        $dto->parentClassName = $this->parentClassName;
        $dto->inheritedMethods = $this->inheritedMethods;
        $dto->childClasses = $this->childClasses;

        return $dto;
    }

    public function withNamespace(string $namespace): self
    {
        $dto = new self(
            $this->name,
            $this->filepath,
            $this->methods,
            $this->modifiers
        );
        $dto->properties = $this->properties;
        $dto->parentClasses = $this->parentClasses;
        $dto->necessaryFiles = $this->necessaryFiles;
        $dto->traits = $this->traits;
        $dto->interfaces = $this->interfaces;
        $dto->namespace = $namespace;
        $dto->constants = $this->constants;
        $dto->parentClassName = $this->parentClassName;
        $dto->inheritedMethods = $this->inheritedMethods;
        $dto->childClasses = $this->childClasses;

        return $dto;
    }

    /**
     * @param Collection<int, Constant> $constants
     */
    public function withConstants(Collection $constants): self
    {
        $dto = new self(
            $this->name,
            $this->filepath,
            $this->methods,
            $this->modifiers
        );
        $dto->properties = $this->properties;
        $dto->parentClasses = $this->parentClasses;
        $dto->necessaryFiles = $this->necessaryFiles;
        $dto->traits = $this->traits;
        $dto->interfaces = $this->interfaces;
        $dto->namespace = $this->namespace;
        $dto->constants = $constants;
        $dto->parentClassName = $this->parentClassName;
        $dto->inheritedMethods = $this->inheritedMethods;
        $dto->childClasses = $this->childClasses;

        return $dto;
    }

    public function withParentClassName(string $parentClassName): self
    {
        $dto = new self(
            $this->name,
            $this->filepath,
            $this->methods,
            $this->modifiers
        );
        $dto->properties = $this->properties;
        $dto->parentClasses = $this->parentClasses;
        $dto->necessaryFiles = $this->necessaryFiles;
        $dto->traits = $this->traits;
        $dto->interfaces = $this->interfaces;
        $dto->namespace = $this->namespace;
        $dto->constants = $this->constants;
        $dto->parentClassName = $parentClassName;
        $dto->inheritedMethods = $this->inheritedMethods;
        $dto->childClasses = $this->childClasses;

        return $dto;
    }

    /**
     * @param Collection<int, Method> $inheritedMethods
     */
    public function withInheritedMethods(Collection $inheritedMethods): self
    {
        $dto = new self(
            $this->name,
            $this->filepath,
            $this->methods,
            $this->modifiers
        );
        $dto->properties = $this->properties;
        $dto->parentClasses = $this->parentClasses;
        $dto->necessaryFiles = $this->necessaryFiles;
        $dto->traits = $this->traits;
        $dto->interfaces = $this->interfaces;
        $dto->namespace = $this->namespace;
        $dto->constants = $this->constants;
        $dto->parentClassName = $this->parentClassName;
        $dto->inheritedMethods = $inheritedMethods;
        $dto->childClasses = $this->childClasses;

        return $dto;
    }

    /**
     * @param Collection<int, ClassDto> $childClasses
     */
    public function withChildClasses(Collection $childClasses): self
    {
        $dto = new self(
            $this->name,
            $this->filepath,
            $this->methods,
            $this->modifiers
        );
        $dto->properties = $this->properties;
        $dto->parentClasses = $this->parentClasses;
        $dto->necessaryFiles = $this->necessaryFiles;
        $dto->interfaces = $this->interfaces;
        $dto->traits = $this->traits;
        $dto->namespace = $this->namespace;
        $dto->constants = $this->constants;
        $dto->parentClassName = $this->parentClassName;
        $dto->inheritedMethods = $this->inheritedMethods;
        $dto->childClasses = $childClasses;

        return $dto;
    }

    /**
     * @return Collection<int, Method>
     */
    public function getMethods(): Collection
    {
        return $this->methods;
    }

    /**
     * @psalm-return non-empty-string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @psalm-return non-empty-string
     */
    public function getFilepath(): string
    {
        return $this->filepath;
    }

    /**
     * @return Collection<int, Property>|null
     */
    public function getProperties(): ?Collection
    {
        return $this->properties;
    }

    /**
     * @return Collection<int, Constant>|null
     */
    public function getConstants(): ?Collection
    {
        return $this->constants;
    }

    public function getNamespace(): ?string
    {
        return $this->namespace;
    }

    /**
     * @return Collection<int, InterfaceDto>|null
     */
    public function getInterfaces(): ?Collection
    {
        return $this->interfaces;
    }

    /**
     * @return Collection<int, TraitDto>|null
     */
    public function getTraits(): ?Collection
    {
        return $this->traits;
    }

    /**
     * @return Collection<int, ClassDto>|null
     */
    public function getParentClasses(): ?Collection
    {
        return $this->parentClasses;
    }
    /**
     * @return Collection<int, File>|null
     */
    public function getNecessaryFiles(): ?Collection
    {
        return $this->necessaryFiles;
    }

    /**
     * @return Collection<int, Modifier>
     */
    public function getModifiers(): Collection
    {
        return $this->modifiers;
    }

    public function getParentClassName(): ?string
    {
        return $this->parentClassName;
    }

    /**
     * @return Collection<int, Method>|null
     */
    public function getInheritedMethods(): ?Collection
    {
        return $this->inheritedMethods;
    }

    /**
     * @return Collection<int, ClassDto>|null
     */
    public function getChildClasses(): ?Collection
    {
        return $this->childClasses;
    }
}
