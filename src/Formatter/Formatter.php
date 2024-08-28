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

namespace Formatter;

use Contract\Formatter\DokuWikiFormatInterface;
use Contract\Formatter\FormatterInterface;
use Dto\Common\Modifier;
use Illuminate\Support\Collection;
use Webmozart\Assert\Assert;
use Webmozart\Assert\InvalidArgumentException;

final readonly class Formatter implements FormatterInterface, DokuWikiFormatInterface
{
    private const string FORMAT_SUFFIX = '_FORMAT';

    public function formatContent(string $formatStr, array $contentParts): string
    {
        if (is_array($contentParts[0])) {
            if ($contentParts[0][0] instanceof Modifier) {
                $modifiers = [];
                /** @var Modifier $modifier */
                foreach ($contentParts[0] as $modifier) {
                    $modifiers[] = $modifier->getName();
                }
                $contentParts[0] = implode(' ', $modifiers);
            }
        }

        return vsprintf($formatStr, $contentParts);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function getFormat(string $format, string $type): string
    {
        Assert::stringNotEmpty($format);
        Assert::stringNotEmpty($type);

        return self::{strtoupper($format) . '_' . strtoupper($type) . self::FORMAT_SUFFIX};
    }
}
