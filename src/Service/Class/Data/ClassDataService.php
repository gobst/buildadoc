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

namespace Service\Class\Data;

use ArrayIterator;
use Contract\Service\Class\Data\ClassDataServiceInterface;
use Contract\Service\Class\Data\MethodDataServiceInterface;
use Contract\Service\Class\Data\ModifierDataServiceInterface;
use Contract\Service\File\FileServiceInterface;
use Dto\Class\ClassDto;
use Dto\Class\Constant;
use Dto\Class\InterfaceDto;
use Dto\Class\TraitDto;
use Dto\Common\File;
use Dto\Common\Property;
use Illuminate\Support\Collection;
use PhpParser\Node;
use PhpParser\NodeFinder;
use PhpParser\Parser;
use PhpParser\ParserFactory;
use Service\Class\Filter\ClassNameFilter;
use Service\Class\Filter\ParentClassNameFilter;
use Webmozart\Assert\Assert;
use Webmozart\Assert\InvalidArgumentException;

use function gettype;

/**
 * @psalm-suppress NoInterfaceProperties
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
final class ClassDataService implements ClassDataServiceInterface
{
    private FileServiceInterface $fileService;
    private MethodDataServiceInterface $methodDataService;
    private ModifierDataServiceInterface $modifierDataService;
    private Parser $phpParser;
    private NodeFinder $nodeFinder;

    public function __construct(
        FileServiceInterface $fileService,
        MethodDataServiceInterface $methodDataService,
        ModifierDataServiceInterface $modifierDataService
    ) {
        $this->fileService = $fileService;
        $this->methodDataService = $methodDataService;
        $this->modifierDataService = $modifierDataService;
        $this->nodeFinder = new NodeFinder();
        $this->phpParser = (new ParserFactory())->create(ParserFactory::PREFER_PHP7);
    }

    public function getClassData(File $file): ?ClassDto
    {
        $classAst = $this->getAst($file->getPath());

        Assert::notNull($classAst);

        $classNode = $this->nodeFinder->findInstanceOf($classAst, Node\Stmt\Class_::class);
        if (!empty($classNode[0])) {
            $namespaceNode = $this->nodeFinder->findInstanceOf($classAst, Node\Stmt\Namespace_::class);
            $namespace = !empty($namespaceNode[0]) ? $namespaceNode[0]->name : null;
            $parentClass = !empty($classNode[0]->extends) ? implode('\\', $classNode[0]->extends->parts) : null;
            $methods = $this->methodDataService->getMethods($classAst);
            $class = ClassDto::create(
                $classNode[0]->name->name,
                $file->getPath(),
                $methods,
                Collection::make()
            )
                ->withConstants($this->getConstants($classAst))
                ->withProperties($this->getProperties($classAst))
                ->withInterfaces($this->getInterfaces($classAst))
                ->withTraits($this->getTraits($classAst));
            if ($parentClass !== null) {
                $class = $class->withParentClassName($parentClass);
            }
            if ($namespace !== null) {
                $class = $class->withNamespace($namespace);
            }

            return $class;
        }

        return null;
    }

    public function getConstants(array $ast): Collection
    {
        /** @var Collection<int, Constant> $constants */
        $constants = Collection::make();
        $constantsAst = $this->nodeFinder->findInstanceOf($ast, Node\Stmt\ClassConst::class);

        foreach ($constantsAst as $const) {
            $modifiers = $this->modifierDataService->getModifiers($const);
            $type = empty($const->consts[0]->value->value) ? 'string' : gettype($const->consts[0]->value->value);

            Assert::stringNotEmpty($type);

            // @todo: if the value is a boolean it is not recognized by gettype() because in AST it is a string
            $value = !empty($const->consts[0]->value->value) ? $const->consts[0]->value->value : '';
            $constant = Constant::create(
                $const->consts[0]->name->name,
                $type,
                $value,
                $modifiers
            );

            $constants->push($constant);
        }

        return $constants;
    }

    public function getProperties(array $ast): Collection
    {
        /** @var Collection<int, Property> $properties */
        $properties = Collection::make();
        $propertiesAst = $this->nodeFinder->findInstanceOf($ast, Node\Stmt\Property::class);
        foreach ($propertiesAst as $prop) {

            $modifiers = $this->modifierDataService->getModifiers($prop);
            $type = !empty($prop->type->name) ? $prop->type->name : '';

            $defaultValue = !empty($prop->props[0]->default->value) ? $prop->props[0]->default->value : '';
            $property = Property::create(
                $prop->props[0]->name->name,
                $type,
                $modifiers
            );
            if ($defaultValue !== '') {
                $property = $property->withDefaultValue($defaultValue);
            }
            $properties->push($property);
        }

        return $properties;
    }

    public function getInterfaces(array $ast): Collection
    {
        /** @var Collection<int, InterfaceDto> $interfaces */
        $interfaces = Collection::make();
        $nodes = $this->nodeFinder->findInstanceOf($ast, Node\Stmt\Class_::class);
        if (!empty($nodes)) {
            if (is_array($nodes[0]->implements)) {
                foreach ($nodes[0]->implements as $interface) {
                    if (count($interface->parts) > 1) {
                        $name = array_pop($interface->parts);
                        $namespace = implode('\\', $interface->parts);
                    } else {
                        $name = $interface->parts[0];
                        $namespace = '';
                    }
                    $interfaceDto = InterfaceDto::create($name);
                    if ($namespace !== '') {
                        $interfaceDto = $interfaceDto->withNamespace($namespace);
                    }
                    $interfaces->push($interfaceDto);
                }
            }
        }

        return $interfaces;
    }

    public function getTraits(array $ast): Collection
    {
        /** @var Collection<int, TraitDto> $traits */
        $traits = Collection::make();
        $nodes = $this->nodeFinder->findInstanceOf($ast, Node\Stmt\TraitUse::class);
        if (!empty($nodes)) {
            if (is_array($nodes[0]->traits)) {
                foreach ($nodes[0]->traits as $trait) {
                    if (count($trait->parts) > 1) {
                        $name = array_pop($trait->parts);
                        $namespace = implode('\\', $trait->parts);
                    } else {
                        $name = $trait->parts[0];
                        $namespace = '';
                    }
                    $traitDto = TraitDto::create($name);
                    if ($namespace !== '') {
                        $traitDto = $traitDto->withNamespace($namespace);
                    }
                    $traits->push($traitDto);
                }
            }
        }

        return $traits;
    }

    /**
     * @param Collection<int, File> $files
     * @return Collection<int, ClassDto>
     */
    public function getAllClasses(Collection $files): Collection
    {
        /** @var Collection<int, ClassDto> $classes */
        $classes = Collection::make();
        foreach ($files as $file) {
            $class = $this->getClassData($file);
            if ($class !== null) {
                $classes->push($class);
            }
        }
        /** @var ArrayIterator $iterator */
        $iterator = $classes->getIterator();
        while ($iterator->valid()) {
            /** @var ClassDto $class */
            $class = $iterator->current();
            $parentClasses = $this->getAllParentClassesByClass($class, $classes, Collection::make());
            $childClasses = $this->getChildClasses($class->getName(), $classes);
            $necessaryFiles = $this->getAllNecessaryFilesByClass($class, $parentClasses, $files);

            $classes[$iterator->key()] = count($parentClasses) > 0
                    ? $classes[$iterator->key()]->withParentClasses($parentClasses)
                    : $classes[$iterator->key()];

            $classes[$iterator->key()] = count($parentClasses) > 0
                ? $classes[$iterator->key()]->withChildClasses($childClasses)
                : $classes[$iterator->key()];

            $classes[$iterator->key()] = $classes[$iterator->key()]->withNecessaryFiles($necessaryFiles);

            $inheritedMethods = $this->methodDataService->fetchInheritedMethods($classes[$iterator->key()]);
            $classes[$iterator->key()] = $classes[$iterator->key()]->withInheritedMethods($inheritedMethods);

            $iterator->next();
        }

        return $classes;
    }

    /**
     * @param Collection<int, ClassDto> $classes
     * @param Collection<int, ClassDto> $parentClasses
     * @return Collection<int, ClassDto>
     */
    private function getAllParentClassesByClass(
        ClassDto $class,
        Collection $classes,
        Collection $parentClasses,
    ): Collection {
        $parentClassName = $class->getParentClassName();
        if (!empty($parentClassName)) {
            $parentClass = $this->getSingleClass($parentClassName, $classes);
            if($parentClass !== null){
                $parentClasses->push($parentClass);
                if (!empty($parentClass->getParentClassName())) {
                    $this->getAllParentClassesByClass($parentClass, $classes, $parentClasses);
                }
            }
        }

        return $parentClasses;
    }

    /**
     * @param ClassDto $class
     * @param Collection<int, ClassDto> $parentClasses
     * @param Collection<int, File> $files
     * @return Collection<int, File>
     */
    private function getAllNecessaryFilesByClass(
        ClassDto $class,
        Collection $parentClasses,
        Collection $files
    ): Collection
    {
        /** @var Collection<int, File> $fileCollection */
        $fileCollection = Collection::make();
        $file = $this->fileService->getSingleFile($class->getFilepath(), $files);
        if($file !== null){
            $fileCollection->push($file);
            foreach ($parentClasses as $parentClass) {
                $file = $this->fileService->getSingleFile($parentClass->getFilepath(), $files);
                if($file !== null) {
                    $fileCollection->push($file);
                }
            }
        }

        return $fileCollection;
    }

    /**
     * @param Collection<int, ClassDto> $classes
     *
     * @throws InvalidArgumentException
     */
    public function getSingleClass(string $className, Collection $classes): ?ClassDto
    {
        Assert::stringNotEmpty($className);

        return $classes->filter(function ($value) use ($className) {
            return (new ClassNameFilter($className))->hasClassName($value);
        })->first();
    }

    public function getAst(string $phpFile): ?array
    {
        return $this->phpParser->parse(file_get_contents($phpFile));
    }

    /**
     * @param Collection<int, ClassDto> $classes
     * @return Collection<int, ClassDto>
     * @throws InvalidArgumentException
     */
    private function getChildClasses(string $parentClassName, Collection $classes): Collection
    {
        Assert::stringNotEmpty($parentClassName);

        return $classes->filter(function ($value) use ($parentClassName) {
            return (new ParentClassNameFilter($parentClassName))->hasParentClass($value);
        });
    }
}
