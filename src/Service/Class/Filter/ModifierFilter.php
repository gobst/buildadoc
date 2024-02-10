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

namespace Service\Class\Filter;

use ArrayIterator;
use Dto\Method\Method;
use Webmozart\Assert\Assert;
use Webmozart\Assert\InvalidArgumentException;

final readonly class ModifierFilter
{
    private array $modifiers;
    private string $where;

    public function __construct(array $modifiers, string $where = 'or')
    {
        $this->modifiers = $modifiers;
        $this->where = $where;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function hasModifier(Method $method): bool
    {
        $modifiers = $method->getModifiers();

        /** @var ArrayIterator $iterator */
        $iterator = $modifiers->getIterator();
        $containsModifier = !($this->where === 'or');

        while ($iterator->valid()) {
            Assert::stringNotEmpty($iterator->current()->getName());

            if ($this->where === 'or') {
                if ($this->containsModifier($iterator->current()->getName(), $this->modifiers)) {
                    return true;
                }
            } elseif (!$this->containsModifier($iterator->current()->getName(), $this->modifiers)) {
                return false;
            }
            $iterator->next();
        }

        return $containsModifier;
    }

    /**
     * @psalm-param non-empty-string $modifier
     */
    private function containsModifier(string $modifier, array $existingModifiers): bool
    {
        return in_array($modifier, $existingModifiers);
    }
}
