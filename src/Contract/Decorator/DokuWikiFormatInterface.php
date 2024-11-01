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

namespace Contract\Decorator;

interface DokuWikiFormatInterface
{
    public const string DOKUWIKI_PROPERTY_LIST_FORMAT = '%s %s **$%s**%s';
    public const string DOKUWIKI_CONSTANT_LIST_FORMAT = '%s %s **%s**%s';
    public const string DOKUWIKI_INTERFACE_LIST_FORMAT = '%s';
    public const string DOKUWIKI_METHOD_LIST_FORMAT = '%s';
    public const string DOKUWIKI_CLASS_LIST_FORMAT = '%s';
    public const string DOKUWIKI_USEDBYCLASS_LIST_FORMAT = '%s %s';
    public const string DOKUWIKI_LINK_WITH_TEXT_FORMAT = '[[%s|%s]]';
    public const string DOKUWIKI_LINK_WITHOUT_TEXT_FORMAT = '[[%s]]';
    public const string DOKUWIKI_HEADING_LEVEL1_FORMAT = '====== %s ======';
    public const string DOKUWIKI_HEADING_LEVEL2_FORMAT = '===== %s =====';
    public const string DOKUWIKI_HEADING_LEVEL3_FORMAT = '==== %s ====';
    public const string DOKUWIKI_HEADING_LEVEL4_FORMAT = '=== %s ===';
    public const string DOKUWIKI_HEADING_LEVEL5_FORMAT = '== %s ==';
    public const string DOKUWIKI_FORMAT_KEY = 'dokuwiki';
}
