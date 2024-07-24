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

use Contract\Service\Class\Data\ModifierDataServiceInterface;
use Dto\Common\Modifier;
use Illuminate\Support\Collection;
use PhpParser\Node;

/**
 * @psalm-suppress NoInterfaceProperties
 */
final class ModifierDataService implements ModifierDataServiceInterface
{
    public function __construct() {}

    /**
     * @return Collection<int, Modifier>
     */
    public function getModifiers(Node $node): Collection
    {
        $modifiers = Collection::make();
        switch ($node->flags) {
            case Node\Stmt\Class_::MODIFIER_PROTECTED:
                $modifiers->push(Modifier::create('protected'));
                break;
            case Node\Stmt\Class_::MODIFIER_PRIVATE:
                $modifiers->push(Modifier::create('private'));
                break;
            case Node\Stmt\Class_::MODIFIER_PUBLIC + Node\Stmt\Class_::MODIFIER_STATIC:
                $modifiers->push(Modifier::create('public'));
                $modifiers->push(Modifier::create('static'));
                break;
            case Node\Stmt\Class_::MODIFIER_PRIVATE + Node\Stmt\Class_::MODIFIER_STATIC:
                $modifiers->push(Modifier::create('private'));
                $modifiers->push(Modifier::create('static'));
                break;
            case Node\Stmt\Class_::MODIFIER_PROTECTED + Node\Stmt\Class_::MODIFIER_STATIC:
                $modifiers->push(Modifier::create('protected'));
                $modifiers->push(Modifier::create('static'));
                break;
            case Node\Stmt\Class_::MODIFIER_PUBLIC + Node\Stmt\Class_::MODIFIER_ABSTRACT:
                $modifiers->push(Modifier::create('abstract'));
                $modifiers->push(Modifier::create('public'));
                break;
            case Node\Stmt\Class_::MODIFIER_PRIVATE + Node\Stmt\Class_::MODIFIER_ABSTRACT:
                $modifiers->push(Modifier::create('abstract'));
                $modifiers->push(Modifier::create('private'));
                break;
            case Node\Stmt\Class_::MODIFIER_PROTECTED + Node\Stmt\Class_::MODIFIER_ABSTRACT:
                $modifiers->push(Modifier::create('abstract'));
                $modifiers->push(Modifier::create('protected'));
                break;
            case Node\Stmt\Class_::MODIFIER_PUBLIC + Node\Stmt\Class_::MODIFIER_FINAL:
                $modifiers->push(Modifier::create('final'));
                $modifiers->push(Modifier::create('public'));
                break;
            case Node\Stmt\Class_::MODIFIER_PROTECTED + Node\Stmt\Class_::MODIFIER_FINAL:
                $modifiers->push(Modifier::create('final'));
                $modifiers->push(Modifier::create('protected'));
                break;
            default:
                $modifiers->push(Modifier::create('public'));
                break;
        }

        return $modifiers;
    }
}
