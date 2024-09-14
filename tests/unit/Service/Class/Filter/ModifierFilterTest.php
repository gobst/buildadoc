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

namespace unit\Service\Class\Filter;

use Dto\Common\Modifier;
use Dto\Method\Method;
use Dto\Method\MethodParameter;
use Illuminate\Support\Collection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Service\Class\Filter\ModifierFilter;

#[CoversClass(ModifierFilter::class)]
#[UsesClass(MethodParameter::class)]
#[UsesClass(Modifier::class)]
#[UsesClass(Method::class)]
#[UsesClass(Collection::class)]
final class ModifierFilterTest extends TestCase
{
    private ModifierFilter $modifierFilter;

    #[TestDox('hasModifier() method returns true with "or" condition')]
    public function testHasModifierReturnsTrueWithOr(): void
    {
        $this->modifierFilter = new ModifierFilter(['public']);

        /** @var Collection<int, Modifier> $modifiers */
        $modifiers = Collection::make();
        $publicModifierDto = Modifier::create('public');
        $modifiers->push($publicModifierDto);

        /** @var Collection<int, MethodParameter> $parameters */
        $parameters = Collection::make();
        $parameterDto = MethodParameter::create('testString', 'string');
        $parameterDto = $parameterDto->withDefaultValue('test');
        $parameters->push($parameterDto);
        $methodDto = Method::create(
            'testMethodWithoutPHPDoc',
            $modifiers,
            'string',
            'testClass')
        ->withParameters($parameters);

        $this->assertTrue($this->modifierFilter->hasModifier($methodDto));
    }

    #[TestDox('hasModifier() method returns false with "or" condition')]
    public function testHasModifierReturnsFalseWithOr(): void
    {
        $this->modifierFilter = new ModifierFilter(['public']);

        /** @var Collection<int, Modifier> $modifiers */
        $modifiers = Collection::make();
        $publicModifierDto = Modifier::create('private');
        $modifiers->push($publicModifierDto);

        /** @var Collection<int, MethodParameter> $parameters */
        $parameters = Collection::make();
        $parameterDto = MethodParameter::create('testString', 'string');
        $parameterDto = $parameterDto->withDefaultValue('test');
        $parameters->push($parameterDto);
        $methodDto = Method::create(
            'testMethodWithoutPHPDoc',
            $modifiers,
            'string',
            'testClass')
        ->withParameters($parameters);

        $this->assertFalse($this->modifierFilter->hasModifier($methodDto));
    }

    #[TestDox('hasModifier() method returns true with "and" condition')]
    public function testHasModifierReturnsTrueWithAnd(): void
    {
        $this->modifierFilter = new ModifierFilter(['readonly', 'private'], 'and');

        /** @var Collection<int, Modifier> $modifiers */
        $modifiers = Collection::make();
        $modifiers->push(Modifier::create('private'));
        $modifiers->push(Modifier::create('readonly'));

        /** @var Collection<int, MethodParameter> $parameters */
        $parameters = Collection::make();
        $parameterDto = MethodParameter::create('testString', 'string');
        $parameterDto = $parameterDto->withDefaultValue('test');
        $parameters->push($parameterDto);
        $methodDto = Method::create(
            'testMethodWithoutPHPDoc',
            $modifiers,
            'string',
            'testClass')
            ->withParameters($parameters);

        $this->assertTrue($this->modifierFilter->hasModifier($methodDto));
    }

    #[TestDox('hasModifier() method returns false with "and" condition')]
    public function testHasModifierReturnsFalseWithAnd(): void
    {
        $this->modifierFilter = new ModifierFilter(['public', 'readonly'], 'and');

        /** @var Collection<int, Modifier> $modifiers */
        $modifiers = Collection::make();
        $modifiers->push(Modifier::create('public'));

        /** @var Collection<int, MethodParameter> $parameters */
        $parameters = Collection::make();
        $parameterDto = MethodParameter::create('testString', 'string');
        $parameterDto = $parameterDto->withDefaultValue('test');
        $parameters->push($parameterDto);
        $methodDto = Method::create(
            'testMethodWithoutPHPDoc',
            $modifiers,
            'string',
            'testClass')
        ->withParameters($parameters);

        $this->assertFalse($this->modifierFilter->hasModifier($methodDto));
    }
}
