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
use Contract\Pipeline\Fetcher\MethodPageFetcherProviderInterface;
use Contract\Pipeline\MethodPageMarkerPipelineInterface;
use Dto\Method\Method;
use Illuminate\Contracts\Pipeline\Pipeline;
use Illuminate\Support\Collection;
use Webmozart\Assert\Assert;

final readonly class MethodPageMarkerPipeline implements MethodPageMarkerPipelineInterface
{
    private const string METHOD_HEADING_KEY_FETCHER = 'methodHeading';
    private const string METHOD_TABLE_KEY_FETCHER = 'methodTable';
    private const string METHOD_SIGNATURE_KEY_FETCHER = 'methodSignature';

    public function __construct(
        private MethodPageFetcherProviderInterface $fetcherProvider,
        private Pipeline                           $pipeline
    )
    {
    }

    public function handlePipeline(Method $method, string $format, string $lang): Collection
    {
        Assert::stringNotEmpty($format);
        Assert::stringNotEmpty($lang);

        return $this->pipeline
            ->send(Collection::make())
            ->through($this->getClosures($method, $format, $lang))
            ->then(function (Collection $passable) {
                return $passable;
            });
    }

    /**
     * @psalm-param non-empty-string $format
     * @psalm-param non-empty-string $lang
     * @return array<int, Closure>
     */
    private function getClosures(Method $method, string $format, string $lang): array
    {
        $fetchers = [
            self::METHOD_HEADING_KEY_FETCHER,
            self::METHOD_TABLE_KEY_FETCHER,
            self::METHOD_SIGNATURE_KEY_FETCHER
        ];

        $closures = [];

        foreach ($fetchers as $fetcher) {
            $closure = function ($passable, $next) use ($method, $format, $lang, $fetcher) {
                $passable = $this->fetcherProvider
                    ->getFetcher($fetcher)
                    ->handle($passable, $method, $format, $lang);
                return $next($passable);
            };
            $closures[] = $closure;
        }

        return $closures;
    }
}
