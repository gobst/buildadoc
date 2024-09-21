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

final readonly class LinkDecorator implements TextDecoratorInterface, DokuWikiFormatInterface
{
    private const string LINK_WITHOUT_TEXT_TYPE = 'link_without_text';
    private const string LINK_WITH_TEXT_TYPE = 'link_with_text';

    public function __construct(private DecoratorInterface $decorator)
    {
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
            self::DOKUWIKI_FORMAT_KEY => $this->formatLinkToDokuWiki($format, $textParts),
            default => throw new InvalidArgumentException('Error: Unknown format!'),
        };
    }

    /**
     * @psalm-param non-empty-string $format
     * @psalm-param non-empty-list $textParts
     * @throws InvalidArgumentException
     */
    private function formatLinkToDokuWiki(string $format, array $textParts): string
    {
        Assert::stringNotEmpty(self::LINK_WITH_TEXT_TYPE);
        Assert::stringNotEmpty(self::LINK_WITHOUT_TEXT_TYPE);

        $type = !empty($textParts[1]) ? self::LINK_WITH_TEXT_TYPE : self::LINK_WITHOUT_TEXT_TYPE;
        $formatStr = $this->decorator->getFormat($format, $type);

        Assert::stringNotEmpty($formatStr);

        return $this->decorator->formatText($formatStr, $textParts);
    }
}
