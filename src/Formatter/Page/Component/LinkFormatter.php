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

use Contract\Formatter\Component\LinkFormatterInterface;
use Contract\Formatter\FormatterInterface;
use Webmozart\Assert\Assert;
use Webmozart\Assert\InvalidArgumentException;

final readonly class LinkFormatter implements LinkFormatterInterface
{
    private const string LINK_WITHOUT_TEXT_TYPE = 'link_without_text';
    private const string LINK_WITH_TEXT_TYPE = 'link_with_text';

    public function __construct(private FormatterInterface $formatter) {}

    /**
     * @throws InvalidArgumentException
     */
    public function formatLink(string $format, array $contentParts): string
    {
        Assert::stringNotEmpty($format);

        return match ($format) {
            'dokuwiki' => $this->formatLinkToDokuWiki($format, $contentParts),
            default => throw new InvalidArgumentException('Error: Unknown format!'),
        };
    }

    /**
     * @psalm-param non-empty-string $format
     *
     * @throws InvalidArgumentException
     */
    private function formatLinkToDokuWiki(string $format, array $contentParts): string
    {
        Assert::stringNotEmpty(self::LINK_WITH_TEXT_TYPE);
        Assert::stringNotEmpty(self::LINK_WITHOUT_TEXT_TYPE);

        $type = !empty($contentParts[1]) ? self::LINK_WITH_TEXT_TYPE : self::LINK_WITHOUT_TEXT_TYPE;
        $formatStr = $this->formatter->getFormat($format, $type);

        Assert::stringNotEmpty($formatStr);

        return $this->formatter->formatContent($formatStr, $contentParts);
    }
}
