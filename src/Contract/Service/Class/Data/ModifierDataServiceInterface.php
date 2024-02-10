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

namespace Contract\Service\Class\Data;

use Collection\ModifierCollection;
use PhpParser\Node;

interface ModifierDataServiceInterface
{
    public function getModifiers(Node $node): ModifierCollection;
}
