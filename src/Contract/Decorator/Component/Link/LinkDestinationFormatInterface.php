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

namespace Contract\Decorator\Component\Link;

interface LinkDestinationFormatInterface
{
    public const string METHOD_DOKUWIKI_DEST_FILENAME_FORMAT = '%s_%s';
    public const string METHOD_DOKUWIKI_DESTINATION_FORMAT = '%s:%s:%s';
    public const string METHOD_DEST_FILENAME_FORMAT = '%s_%s.%s';
    public const string METHOD_DESTINATION_FORMAT = '%s/%s/%s';
    public const string CLASS_DOKUWIKI_DEST_FILENAME_FORMAT = '%s';
    public const string CLASS_DOKUWIKI_DESTINATION_FORMAT = '%s:%s:%s';
    public const string CLASS_DEST_FILENAME_FORMAT = '%s.%s';
    public const string CLASS_DESTINATION_FORMAT = '%s/%s/%s';
}
