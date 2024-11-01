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

namespace Decorator\Page\Component;

use Contract\Decorator\DokuWikiFormatInterface;
use Contract\Decorator\DecoratorInterface;
use Contract\Decorator\TextDecoratorInterface;
use Webmozart\Assert\Assert;
use Webmozart\Assert\InvalidArgumentException;

final readonly class HeadingDecorator implements TextDecoratorInterface, DokuWikiFormatInterface
{
    private const string HEADING_LEVEL_TYPE = 'heading_level%s';

    /**
     * @psalm-param positive-int $level
     * @throws InvalidArgumentException
     */
    public function __construct(
        private DecoratorInterface $decorator,
        private int $level
    ) {
        Assert::positiveInteger($level);
    }

    /**
     * @psalm-param non-empty-string $format
     * @psalm-param non-empty-list $textParts
     * @throws InvalidArgumentException
     */
    public function format(string $format, array $textParts): string
    {
        Assert::stringNotEmpty($format);
        Assert::notEmpty($textParts);

        return match ($format) {
            self::DOKUWIKI_FORMAT_KEY => $this->formatHeadingToDokuWiki($format, $textParts),
            default => throw new InvalidArgumentException('Error: Unknown format!'),
        };
    }

    /**
     * @psalm-param non-empty-string $format
     * @psalm-param non-empty-list $textParts
     * @throws InvalidArgumentException
     */
    private function formatHeadingToDokuWiki(string $format, array $textParts): string
    {
        Assert::stringNotEmpty(self::HEADING_LEVEL_TYPE);
        $type = sprintf(self::HEADING_LEVEL_TYPE, $this->level);
        $formatStr = $this->decorator->getFormat($format, $type);
        Assert::stringNotEmpty($formatStr);

        return $this->decorator->formatText($formatStr, $textParts);
    }
}
