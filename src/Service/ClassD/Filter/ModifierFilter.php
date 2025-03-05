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

namespace Service\ClassD\Filter;

use Dto\Common\Modifier;
use Dto\Method\Method;
use Illuminate\Support\Collection;
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
        $hasModifier = !($this->where === 'or');

        foreach ($this->modifiers as $checkedModifier) {
            if (            $this->where === 'or'
                && $this->containsModifier($checkedModifier, $modifiers)
            ) {
                return true;
            }
            if (!$this->containsModifier($checkedModifier, $modifiers)) {
                return false;
            }
        }

        return $hasModifier;
    }

    /**
     * @psalm-param non-empty-string $modifier
     * @param Collection<int, Modifier> $existingModifiers
     */
    private function containsModifier(string $modifier, Collection $existingModifiers): bool
    {
        foreach ($existingModifiers->all() as $existingModifier) {
            if ($existingModifier->getName() === $modifier) {
                return true;
            }
        }
        return false;
    }
}
