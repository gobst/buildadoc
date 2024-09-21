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

final readonly class ListDecorator implements TextDecoratorInterface, DokuWikiFormatInterface
{
    /**
     * @psalm-param non-empty-string $listType
     * @psalm-param non-empty-string $listItemType
     * @throws InvalidArgumentException
     */
    public function __construct(
        private DecoratorInterface $formatter,
        private string $listType,
        private string $listItemType = 'ordered'
    )
    {
        Assert::stringNotEmpty($this->listType);
        Assert::stringNotEmpty($this->listItemType);
    }

    /**
     * @psalm-param non-empty-string $format
     * @psalm-param non-empty-list $textParts
     * @psalm-return non-empty-string
     */
    public function format(string $format, array $textParts): string
    {
        Assert::stringNotEmpty($format);
        Assert::notEmpty($textParts);

        return match ($format) {
            self::DOKUWIKI_FORMAT_KEY => $this->formatListItemToDokuWiki($format, $textParts),
            default => throw new InvalidArgumentException("Format $format is not supported by ListDecorator!"),
        };
    }

    /**
     * @psalm-param non-empty-string $format
     * @psalm-param non-empty-string $listType
     * @psalm-param non-empty-string $listItemType
     * @psalm-return non-empty-string
     * @throws InvalidArgumentException
     */
    private function formatListItemToDokuWiki(
        string $format,
        array  $textParts
    ): string
    {
        $type = $this->listItemType === 'ordered' ? '-' : '*';
        $formatStr = $this->formatter->getFormat($format, $this->listType);

        Assert::stringNotEmpty($formatStr);

        $content = $this->formatter->formatText($formatStr, $textParts);

        return '  ' . $type . ' ' . $content . chr(13);
    }
}
