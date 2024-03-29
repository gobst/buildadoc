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
use Collection\MethodCollection;
use Collection\MethodParameterCollection;
use Contract\Service\Class\Data\DescriptionDataServiceInterface;
use Contract\Service\Class\Data\MethodDataServiceInterface;
use Contract\Service\Class\Data\ModifierDataServiceInterface;
use Dto\Class\ClassDto;
use Dto\Method\Method;
use Dto\Method\MethodParameter;
use PhpParser\Node;
use PhpParser\NodeFinder;
use Service\Class\Filter\ModifierFilter;
use Webmozart\Assert\Assert;
use Webmozart\Assert\InvalidArgumentException;

use function gettype;

/**
 * @psalm-suppress NoInterfaceProperties
 */
final class MethodDataService implements MethodDataServiceInterface
{
    private NodeFinder $nodeFinder;
    private ModifierDataServiceInterface $modifierDataService;
    private DescriptionDataServiceInterface $descDataService;

    public function __construct(
        ModifierDataServiceInterface $modifierDataService,
        DescriptionDataServiceInterface $descDataService
    ) {
        $this->nodeFinder = new NodeFinder();
        $this->modifierDataService = $modifierDataService;
        $this->descDataService = $descDataService;
    }

    /**
     * @psalm-suppress UndefinedMethod
     */
    public function getMethods(array $ast): MethodCollection
    {
        // @todo: refactor method
        $methods = new MethodCollection();
        /** @var Node\Stmt\ClassMethod $methodNode */
        $methodNode = $this->nodeFinder->findInstanceOf($ast, Node\Stmt\ClassMethod::class);
        /** @var Node\Stmt\Class_ $classNode */
        $classNode = $this->nodeFinder->findInstanceOf($ast, Node\Stmt\Class_::class);

        foreach ($methodNode as $method) {
            $params = $this->fetchMethodParams($method);
            $modifiers = $this->modifierDataService->getModifiers($method);
            $docComment = $method->getDocComment();
            $phpDoc = $docComment !== null ? $docComment->getText() : '';
            if ($method->returnType === null) {
                $returnType = null;
            } elseif (!empty($method->returnType->parts[0])) {
                $returnType = $method->returnType->parts[0];
            } else {
                $returnType = null;
            }
            if ($phpDoc !== '' && $returnType === null) {
                $docReturn = $this->descDataService->getTagByPHPDoc($phpDoc, 'return');
                if (!empty($docReturn[0][1])) {
                    $returnType = $docReturn[0][1];
                } else {
                    $returnType = 'void';
                }
            } elseif ($returnType === null) {
                $returnType = 'void';
            }
            // get description out of PHPDoc
            $descL = '';
            if ($phpDoc !== '') {
                $descArray = $this->descDataService->getDescriptionByPHPDoc($phpDoc);
                $desc = implode('. ', $descArray);
                $descL = empty($desc) ? '' : $desc;
            }
            $classNamespace = empty($classNode[0]->namespacedName) ? '' : $classNode[0]->namespacedName . '_';
            $className = sprintf('%s%s', $classNamespace, $classNode[0]->name->name);

            Assert::stringNotEmpty($className);

            $methodDto = Method::create($method->name->name, $modifiers, $returnType, $className);
            if (!$params->isEmpty()) {
                $methodDto = $methodDto->withParameters($params);
            }
            if (!empty($descL)) {
                $methodDto = $methodDto->withDescription($descL);
            }
            $methods->add($methodDto);
        }

        return $methods;
    }

    private function fetchMethodParams(Node $method): MethodParameterCollection
    {
        $params = new MethodParameterCollection();
        $methodParams = $this->nodeFinder->findInstanceOf($method, Node\Param::class);
        foreach ($methodParams as $param) {
            if (empty($param->type)) {
                if (!empty($param->default)) {
                    $paramType = gettype($param->default);
                } else {
                    $paramType = 'mixed';
                }
            } else {
                $paramType = $param->type->name;
            }
            $default = null;
            // @todo: There is an issue if the default is a boolean
            if (!empty($param->default)) {
                if ($paramType === 'bool') {
                    $default = $param->default->name->parts[0];
                } elseif (!empty($param->default->value)) {
                    $default = $param->default->value;
                } elseif (!empty($param->default->name->name)) {
                    $default = $param->default->name->name;
                }
            }
            $paramDto = MethodParameter::create($param->var->name, $paramType);
            if ($default !== null) {
                $paramDto = $paramDto->withDefaultValue($default);
            }
            $params->add($paramDto);
        }

        return $params;
    }

    public function fetchInheritedMethods(ClassDto $class): MethodCollection
    {
        $inheritedMethods = new MethodCollection();
        $parentClasses = $class->getParentClasses();
        if ($parentClasses !== null) {
            /** @var ArrayIterator $iterator */
            $iterator = $parentClasses->getIterator();
            while ($iterator->valid()) {
                /** @var ClassDto $class */
                $class = $iterator->current();
                $inheritedMethods = $inheritedMethods->merge($class->getMethods());
                $iterator->next();
            }
        }

        return new MethodCollection(
            $inheritedMethods
                ->filter([new ModifierFilter(['public', 'protected'], 'or'), 'hasModifier'])
                ->toArray()
        );
    }

    /**
     * Assembles a method line with parameters and the type of the return value.
     */
    public function fetchMethodSignature(Method $method, bool $withModifiers = true): string
    {
        $modifiersStr = '';
        if ($withModifiers) {
            $modifiers = $method->getModifiers();
            /** @var ArrayIterator $iterator */
            $iterator = $modifiers->getIterator();
            while ($iterator->valid()) {
                $modifiersStr .= $iterator->current()->getName() . ' ';
                $iterator->next();
            }
        }
        $paramsStr = '';
        $params = $method->getParameters();
        if ($params !== null) {
            $paramsStr = $this->paramsToString($params);
        }

        return $modifiersStr . $method->getName() . '(' . trim($paramsStr) . '): ' . $method->getReturnType();
    }

    /**
     * @psalm-param non-empty-string $name
     *
     * @throws InvalidArgumentException
     */
    public function fetchMethod(string $name, MethodCollection $methods): Method|bool
    {
        Assert::stringNotEmpty($name);
        /** @var ArrayIterator $iterator */
        $iterator = $methods->getIterator();
        while ($iterator->valid()) {
            if ($iterator->current()->getName() === $name) {
                return $iterator->current();
            }
            $iterator->next();
        }

        return false;
    }

    private function paramsToString(MethodParameterCollection $params): string
    {
        $paramsStr = '';
        /** @var ArrayIterator $iterator */
        $iterator = $params->getIterator();

        while ($iterator->valid()) {
            /** @var MethodParameter $param */
            $param = $iterator->current();
            $paramsStr .= $param->getType() . ' $' . $param->getName();

            $defaultValue = $param->getType() === 'string'
                ? '\'' . $param->getDefaultValue() . '\''
                : $param->getDefaultValue();

            $paramsStr .= $defaultValue !== null ? ' = ' . $defaultValue : '';
            $paramsStr .= ', ';

            $iterator->next();
        }

        // Remove the last two signs
        return substr($paramsStr, 0, -2);
    }
}
