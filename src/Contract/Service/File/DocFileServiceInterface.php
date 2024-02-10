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

namespace Contract\Service\File;

use Collection\DocPageCollection;

interface DocFileServiceInterface
{
    public function dumpDocFiles(DocPageCollection $pages, string $destDirectory): void;
}
