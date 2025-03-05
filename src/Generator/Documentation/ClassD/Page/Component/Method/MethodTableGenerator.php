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

namespace Generator\Documentation\ClassD\Page\Component\Method;

use ArrayIterator;
use Contract\Decorator\Component\TableDecoratorInterface;
use Contract\Generator\Documentation\ClassD\Page\Component\Method\MethodTableGeneratorInterface;
use Dto\Method\Method;
use Dto\Method\MethodParameter;
use Webmozart\Assert\Assert;
use Webmozart\Assert\InvalidArgumentException;

final readonly class MethodTableGenerator implements MethodTableGeneratorInterface
{
    public function __construct(
        private TableDecoratorInterface $tableFormatter
    ) {
    }

    /**
     * @throws InvalidArgumentException
     */
    public function generate(Method $method, string $format, array $headerLabels): string
    {
        Assert::stringNotEmpty($format);

        $rows = [];
        $methodParameters = $method->getParameters();
        if ($methodParameters !== null && !$methodParameters->isEmpty()) {
            /** @var ArrayIterator $iterator */
            $iterator = $methodParameters->getIterator();
            $index = 0;
            while ($iterator->valid()) {
                /** @var MethodParameter $parameter */
                $parameter = $iterator->current();
                // | Name | Type | Description | Defaultvalue |
                $rows[$index][] = '$' . trim($parameter->getName());
                $rows[$index][] = trim($parameter->getType());
                // @todo: fill parameter description
                $rows[$index][] = trim('');
                $defaultValue = $parameter->getDefaultValue();
                if ($defaultValue !== null) {
                    $default = $parameter->getType() === 'string'
                        ? trim($parameter->getDefaultValue())
                        : $parameter->getDefaultValue();
                    $rows[$index][] = $default;
                }
                ++$index;
                $iterator->next();
            }
        }

        return rtrim($this->tableFormatter->format($format, $headerLabels, $rows, true));
    }
}
