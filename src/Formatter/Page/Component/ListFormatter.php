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

use Contract\Formatter\Component\ListFormatterInterface;
use Contract\Formatter\FormatterInterface;
use Dto\Common\Modifier;
use Illuminate\Support\Collection;
use Webmozart\Assert\Assert;
use Webmozart\Assert\InvalidArgumentException;

final readonly class ListFormatter implements ListFormatterInterface
{
    public function __construct(private FormatterInterface $formatter) {}

    /**
     * @throws InvalidArgumentException
     */
    public function formatListItem(
        string $format,
        string $listType,
        array $contentParts,
        string $listItemType = 'ordered'
    ): string {
        Assert::stringNotEmpty($format);
        Assert::stringNotEmpty($listType);
        Assert::stringNotEmpty($listItemType);

        return match ($format) {
            'dokuwiki' => $this->formatListItemToDokuWiki($format, $contentParts, $listType, $listItemType),
            default => throw new InvalidArgumentException('Error: Unknown format!'),
        };
    }

    /**
     * @param Collection<int, Modifier> $collection
     */
    public function implodeModifierDTOCollection(Collection $collection, string $delimiter = ' '): string
    {
        return $collection
            ->map(fn($dto) => $dto->getName())
            ->implode($delimiter);
    }

    /**
     * @psalm-param non-empty-string $format
     * @psalm-param non-empty-string $listType
     * @psalm-param non-empty-string $listItemType
     *
     * @throws InvalidArgumentException
     */
    private function formatListItemToDokuWiki(
        string $format,
        array $contentParts,
        string $listType,
        string $listItemType
    ): string {
        $type = $listItemType === 'ordered' ? '-' : '*';
        $formatStr = $this->formatter->getFormat($format, $listType);

        Assert::stringNotEmpty($formatStr);

        $content = $this->formatter->formatContent($formatStr, $contentParts);

        return '  ' . $type . ' ' . $content . chr(13);
    }
}
