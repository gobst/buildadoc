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

namespace Contract\Generator\Documentation\Class\Page\Class\Marker;

interface ClassPageMarkerInterface
{
    public const string METHODS_LIST_HEADING_MARKER = 'METHODS_LIST_HEADING';
    public const string METHODS_LIST_MARKER = 'METHODS_LIST';
    public const string PROPERTIES_LIST_HEADING_MARKER = 'CLASS_PROPERTIES_LIST_HEADING';
    public const string PROPERTIES_LIST_MARKER = 'CLASS_PROPERTIES_LIST';
    public const string INTERFACES_LIST_HEADING_MARKER = 'CLASS_INTERFACES_LIST_HEADING';
    public const string INTERFACES_LIST_MARKER = 'CLASS_INTERFACES_LIST';
    public const string CONSTANTS_LIST_HEADING_MARKER = 'CONSTANTS_LIST_HEADING';
    public const string CONSTANTS_LIST_MARKER = 'CONSTANTS_LIST';
    public const string FILES_TABLE_MARKER = 'FILES_TABLE';
    public const string HEADING_MARKER = 'HEADING';
    public const string CONSTRUCTOR_HEADING_MARKER = 'CONSTRUCTOR_HEADING';
    public const string CONSTRUCTOR_MARKER = 'CONSTRUCTOR';
    public const string CLASS_PATH_MARKER = 'CLASS_PATH';
    public const string CLASS_PATH_HEADING_MARKER = 'CLASS_PATH_HEADING';
    public const string CLASS_USEDBYCLASSES_HEADING_MARKER = 'CLASS_USEDBYCLASSES_HEADING';
    public const string CLASS_USEDBYCLASSES_LIST_MARKER = 'CLASS_USEDBYCLASSES_LIST';
}
