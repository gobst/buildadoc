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

namespace Pipeline\Page\Provider;

use Contract\Pipeline\Fetcher\TableOfContentsPageFetcherProviderInter;
use Contract\Pipeline\MethodPagePipelineStepInterface;
use Contract\Pipeline\TableOfContentsPagePipelineStepInterface;
use Webmozart\Assert\Assert;
use Webmozart\Assert\InvalidArgumentException;

final readonly class TableOfContentsPageFetcherProvider implements TableOfContentsPageFetcherProviderInter
{
    private array $fetchers;

    /**
     * @param array<string, MethodPagePipelineStepInterface> $fetchers
     */
    public function __construct(array $fetchers)
    {
        $this->fetchers = $fetchers;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function getFetcher(string $type): TableOfContentsPagePipelineStepInterface
    {
        Assert::stringNotEmpty($type);

        if (!isset($this->fetchers[$type])) {
            throw new InvalidArgumentException("Unknown fetcher type: $type");
        }

        return $this->fetchers[$type];
    }
}
