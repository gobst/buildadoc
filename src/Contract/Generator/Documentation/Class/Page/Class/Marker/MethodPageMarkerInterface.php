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

namespace Contract\Generator\Documentation\Class\Page\Class\Marker;

interface MethodPageMarkerInterface
{
    public const string METHOD_HEADING_MARKER = 'HEADING';
    public const string METHOD_PARAMETERS_TABLE_MARKER = 'METHOD_PARAMETERS_TABLE';
    public const string METHOD_SIGNATURE_MARKER = 'METHOD_SIGNATURE';
}
