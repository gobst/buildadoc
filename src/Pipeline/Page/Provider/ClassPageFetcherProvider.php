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

use Contract\Pipeline\ClassPagePipelineStepInterface;
use Contract\Pipeline\Fetcher\ClassPageFetcherProviderInterface;
use Webmozart\Assert\Assert;
use Webmozart\Assert\InvalidArgumentException;

final readonly class ClassPageFetcherProvider implements ClassPageFetcherProviderInterface
{
    private array $fetchers;

    /**
     * @param array<string, ClassPagePipelineStepInterface> $fetchers
     */
    public function __construct(array $fetchers)
    {
        $this->fetchers = $fetchers;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function getFetcher(string $type): ClassPagePipelineStepInterface
    {
        Assert::stringNotEmpty($type);

        if (!isset($this->fetchers[$type])) {
            throw new InvalidArgumentException("Unknown fetcher type: $type");
        }

        return $this->fetchers[$type];
    }
}
