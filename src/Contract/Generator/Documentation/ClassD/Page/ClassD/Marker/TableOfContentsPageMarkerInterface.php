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

namespace Contract\Generator\Documentation\ClassD\Page\ClassD\Marker;

interface TableOfContentsPageMarkerInterface
{
    public const string TABLEOFCONTENTS_HEADING_MARKER = 'HEADING';
    public const string TABLEOFCONTENTS_TEXT_MARKER = 'TABLEOFCONTENTS_TEXT';
    public const string TABLEOFCONTENTS_CLASS_LIST_MARKER = 'TABLEOFCONTENTS_CLASS_LIST';
}
