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

namespace Formatter\Page\Component;

use Contract\Formatter\Component\HeadingFormatterInterface;
use Contract\Formatter\FormatterInterface;
use Webmozart\Assert\Assert;
use Webmozart\Assert\InvalidArgumentException;

final readonly class HeadingFormatter implements HeadingFormatterInterface
{
    private const string HEADING_LEVEL_TYPE = 'heading_level%s';

    public function __construct(private FormatterInterface $formatter) {}

    /**
     * @throws InvalidArgumentException
     */
    public function formatHeading(string $format, array $contentParts, int $level): string
    {
        Assert::stringNotEmpty($format);
        Assert::positiveInteger($level);

        return match ($format) {
            'dokuwiki' => $this->formatHeadingToDokuWiki($format, $contentParts, $level),
            default => throw new InvalidArgumentException('Error: Unknown format!'),
        };
    }

    /**
     * @psalm-param non-empty-string $format
     *
     * @throws InvalidArgumentException
     */
    private function formatHeadingToDokuWiki(string $format, array $contentParts, int $level): string
    {
        Assert::stringNotEmpty(self::HEADING_LEVEL_TYPE);
        $type = sprintf(self::HEADING_LEVEL_TYPE, $level);
        $formatStr = $this->formatter->getFormat($format, $type);
        Assert::stringNotEmpty($formatStr);

        return $this->formatter->formatContent($formatStr, $contentParts);
    }
}
