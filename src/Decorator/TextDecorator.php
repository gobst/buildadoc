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

namespace Decorator;

use Contract\Decorator\DokuWikiFormatInterface;
use Contract\Decorator\DecoratorInterface;
use Webmozart\Assert\Assert;
use Webmozart\Assert\InvalidArgumentException;

final readonly class TextDecorator implements DecoratorInterface, DokuWikiFormatInterface
{
    private const string FORMAT_SUFFIX = '_FORMAT';

    /**
     * @psalm-param non-empty-string $formatStr
     * @throws InvalidArgumentException
     */
    public function formatText(string $formatStr, array $textParts): string
    {
        Assert::stringNotEmpty($formatStr);
        return vsprintf($formatStr, $textParts);
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
