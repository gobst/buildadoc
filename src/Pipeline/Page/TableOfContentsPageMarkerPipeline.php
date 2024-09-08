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

namespace Pipeline\Page;

use Closure;
use Contract\Pipeline\Fetcher\TableOfContentsPageFetcherProviderInter;
use Contract\Pipeline\TableOfContentsPageMarkerPipelineInterface;
use Dto\Common\Marker;
use Illuminate\Contracts\Pipeline\Pipeline;
use Illuminate\Support\Collection;
use Webmozart\Assert\Assert;

final readonly class TableOfContentsPageMarkerPipeline implements TableOfContentsPageMarkerPipelineInterface
{
    private const string TABLEOFCONTENTS_HEADING_KEY_FETCHER = 'tableofcontentsHeading';
    private const string TABLEOFCONTENTS_TEXT_KEY_FETCHER = 'tableofcontentsText';
    private const string TABLEOFCONTENTS_CLASS_LIST_KEY_FETCHER = 'tableofcontentsClassList';

    public function __construct(
        private TableOfContentsPageFetcherProviderInter $fetcherProvider,
        private Pipeline $pipeline
    )
    {
    }

    /**
     * @psalm-param non-empty-string $lang
     * @psalm-param non-empty-string $format
     * @return Collection<int, Marker>
     */
    public function handlePipeline(Collection $classes, string $format, string $lang, string $mainDirectory): Collection
    {
        Assert::stringNotEmpty($format);
        Assert::stringNotEmpty($lang);

        return $this->pipeline
            ->send(Collection::make())
            ->through($this->getClosures($classes, $format, $lang, $mainDirectory))
            ->then(function (Collection $passable) {
                return $passable;
            });
    }

    /**
     * @psalm-param non-empty-string $format
     * @psalm-param non-empty-string $lang
     * @return array<int, Closure>
     */
    private function getClosures(Collection $classes, string $format, string $lang, string $mainDirectory): array
    {
        $fetchers = [
            self::TABLEOFCONTENTS_HEADING_KEY_FETCHER ,
            self::TABLEOFCONTENTS_TEXT_KEY_FETCHER,
            self::TABLEOFCONTENTS_CLASS_LIST_KEY_FETCHER
        ];

        $closures = [];

        foreach ($fetchers as $fetcher) {
            $closure = function ($passable, $next) use ($classes, $format, $lang, $fetcher, $mainDirectory) {
                $passable = $this->fetcherProvider
                    ->getFetcher($fetcher)
                    ->handle($passable, $classes, $format, $lang, $mainDirectory);
                return $next($passable);
            };
            $closures[] = $closure;
        }

        return $closures;
    }
}
