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

use Collection\ModifierCollection;
use Contract\Service\Class\Data\ModifierDataServiceInterface;
use Dto\Common\Modifier;
use PhpParser\Node;

final class ModifierDataService implements ModifierDataServiceInterface
{
    public function __construct() {}

    public function getModifiers(Node $node): ModifierCollection
    {
        $modifiers = new ModifierCollection();
        switch ($node->flags) {
            case Node\Stmt\Class_::MODIFIER_PROTECTED:
                $modifiers->add(Modifier::create('protected'));
                break;
            case Node\Stmt\Class_::MODIFIER_PRIVATE:
                $modifiers->add(Modifier::create('private'));
                break;
            case Node\Stmt\Class_::MODIFIER_PUBLIC + Node\Stmt\Class_::MODIFIER_STATIC:
                $modifiers->add(Modifier::create('public'));
                $modifiers->add(Modifier::create('static'));
                break;
            case Node\Stmt\Class_::MODIFIER_PRIVATE + Node\Stmt\Class_::MODIFIER_STATIC:
                $modifiers->add(Modifier::create('private'));
                $modifiers->add(Modifier::create('static'));
                break;
            case Node\Stmt\Class_::MODIFIER_PROTECTED + Node\Stmt\Class_::MODIFIER_STATIC:
                $modifiers->add(Modifier::create('protected'));
                $modifiers->add(Modifier::create('static'));
                break;
            case Node\Stmt\Class_::MODIFIER_PUBLIC + Node\Stmt\Class_::MODIFIER_ABSTRACT:
                $modifiers->add(Modifier::create('abstract'));
                $modifiers->add(Modifier::create('public'));
                break;
            case Node\Stmt\Class_::MODIFIER_PRIVATE + Node\Stmt\Class_::MODIFIER_ABSTRACT:
                $modifiers->add(Modifier::create('abstract'));
                $modifiers->add(Modifier::create('private'));
                break;
            case Node\Stmt\Class_::MODIFIER_PROTECTED + Node\Stmt\Class_::MODIFIER_ABSTRACT:
                $modifiers->add(Modifier::create('abstract'));
                $modifiers->add(Modifier::create('protected'));
                break;
            case Node\Stmt\Class_::MODIFIER_PUBLIC + Node\Stmt\Class_::MODIFIER_FINAL:
                $modifiers->add(Modifier::create('final'));
                $modifiers->add(Modifier::create('public'));
                break;
            case Node\Stmt\Class_::MODIFIER_PROTECTED + Node\Stmt\Class_::MODIFIER_FINAL:
                $modifiers->add(Modifier::create('final'));
                $modifiers->add(Modifier::create('protected'));
                break;
            default:
                $modifiers->add(Modifier::create('public'));
                break;
        }

        return $modifiers;
    }
}
