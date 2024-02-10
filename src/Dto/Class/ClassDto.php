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

namespace Dto\Class;

use Collection\ClassCollection;
use Collection\ConstantCollection;
use Collection\FileCollection;
use Collection\InterfaceCollection;
use Collection\MethodCollection;
use Collection\ModifierCollection;
use Collection\PropertyCollection;
use Collection\TraitCollection;

final class ClassDto
{
    private readonly MethodCollection $methods;
    private readonly ModifierCollection $modifiers;

    /**
     * @psalm-var non-empty-string
     */
    private readonly string $name;

    /**
     * @psalm-var non-empty-string
     */
    private readonly string $filepath;
    private ?PropertyCollection $properties;
    private ?ConstantCollection $constants;
    private ?string $namespace;
    private ?InterfaceCollection $interfaces;
    private ?TraitCollection $traits;
    private ?ClassCollection $parentClasses;
    private ?ClassCollection $childClasses;
    private ?string $parentClassName;
    private ?FileCollection $necessaryFiles;
    private ?MethodCollection $inheritedMethods;

    /**
     * @psalm-param non-empty-string $name
     * @psalm-param non-empty-string $filepath
     */
    private function __construct(
        string $name,
        string $filepath,
        MethodCollection $methods,
        ModifierCollection $modifiers
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
     */
    public static function create(
        string $name,
        string $filepath,
        MethodCollection $methods,
        ModifierCollection $modifiers
    ): self {
        return new self($name, $filepath, $methods, $modifiers);
    }

    public function withProperties(PropertyCollection $properties): self
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

    public function withParentClasses(ClassCollection $parentClasses): self
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

    public function withNecessaryFiles(FileCollection $necessaryFiles): self
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

    public function withTraits(TraitCollection $traits): self
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

    public function withInterfaces(InterfaceCollection $interfaces): self
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

    public function withConstants(ConstantCollection $constants): self
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

    public function withInheritedMethods(MethodCollection $inheritedMethods): self
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

    public function withChildClasses(ClassCollection $childClasses): self
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

    public function getMethods(): MethodCollection
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

    public function getProperties(): ?PropertyCollection
    {
        return $this->properties;
    }

    public function getConstants(): ?ConstantCollection
    {
        return $this->constants;
    }

    public function getNamespace(): ?string
    {
        return $this->namespace;
    }

    public function getInterfaces(): ?InterfaceCollection
    {
        return $this->interfaces;
    }

    public function getTraits(): ?TraitCollection
    {
        return $this->traits;
    }

    public function getParentClasses(): ?ClassCollection
    {
        return $this->parentClasses;
    }

    public function getNecessaryFiles(): ?FileCollection
    {
        return $this->necessaryFiles;
    }

    public function getModifiers(): ModifierCollection
    {
        return $this->modifiers;
    }

    public function getParentClassName(): ?string
    {
        return $this->parentClassName;
    }

    public function getInheritedMethods(): ?MethodCollection
    {
        return $this->inheritedMethods;
    }

    public function getChildClasses(): ?ClassCollection
    {
        return $this->childClasses;
    }
}
