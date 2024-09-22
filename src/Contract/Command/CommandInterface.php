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

namespace Contract\Command;

interface CommandInterface
{
    public const string DEFAULT_LANGUAGE = 'de';
    public const string HEADING_TXT = 'BuildADoc';
    public const string FONT = __DIR__ . '/../../../res/fonts/puffy.flf';
    public const string SCRIPT_PATH = __DIR__ . '/../../Command/Scripts/buildDocumentation.php';
    public const string SCRIPT_TYPE = 'php';
    public const string START_TEXT = 'Generate. Please wait...';
    public const string OK_TEXT = 'Finished successfully!';
    public const string END_TEXT = 'done!';
    public const string ERROR_TEXT = "Errors occurred during the generation:\n";
    public const string INFO_TEXT = 'BuildADoc by Guido Obst and contributors - PHP class documentation generator for DokuWiki';
}
