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

use Contract\Pipeline\ClassPageMarkerPipelineInterface;
use Contract\Pipeline\Fetcher\FetcherProviderInterface;
use Dto\Class\ClassDto;
use Illuminate\Contracts\Pipeline\Pipeline;
use Illuminate\Support\Collection;
use Webmozart\Assert\Assert;

final readonly class ClassPageMarkerPipeline implements ClassPageMarkerPipelineInterface
{
    public function __construct(
        private FetcherProviderInterface $fetcherProvider,
        private Pipeline $pipeline
    )
    {
    }

    public function handlePipeline(ClassDto $class, string $format, string $lang): Collection
    {
        Assert::stringNotEmpty($format);
        Assert::stringNotEmpty($lang);

        return $this->pipeline
            ->send(Collection::make())
            ->through([
                function ($passable, $next) use ($class, $format, $lang) {
                    $passable = $this->fetcherProvider
                        ->getFetcher('heading')
                        ->handle($passable, $class, $format, $lang);

                    return $next($passable);
                },
                function ($passable, $next) use ($class, $format, $lang) {
                    $passable = $this->fetcherProvider
                        ->getFetcher('filesTable')
                        ->handle($passable, $class, $format, $lang);

                    return $next($passable);
                },
                function ($passable, $next) use ($class, $format, $lang) {
                    $passable = $this->fetcherProvider
                        ->getFetcher('classPath')
                        ->handle($passable, $class, $format, $lang);

                    return $next($passable);
                },
                function ($passable, $next) use ($class, $format, $lang) {
                    $passable = $this->fetcherProvider
                        ->getFetcher('constructor')
                        ->handle($passable, $class, $format, $lang);

                    return $next($passable);
                },
                function ($passable, $next) use ($class, $format, $lang) {
                    $passable = $this->fetcherProvider
                        ->getFetcher('propertiesList')
                        ->handle($passable, $class, $format, $lang);

                    return $next($passable);
                },
                function ($passable, $next) use ($class, $format, $lang) {
                    $passable = $this->fetcherProvider
                        ->getFetcher('interfacesList')
                        ->handle($passable, $class, $format, $lang);

                    return $next($passable);
                },
                function ($passable, $next) use ($class, $format, $lang) {
                    $passable = $this->fetcherProvider
                        ->getFetcher('constantList')
                        ->handle($passable, $class, $format, $lang);

                    return $next($passable);
                },
                function ($passable, $next) use ($class, $format, $lang) {
                    $passable = $this->fetcherProvider
                        ->getFetcher('methodList')
                        ->handle($passable, $class, $format, $lang);

                    return $next($passable);
                },
            ])
            ->then(function (Collection $passable) {
                return $passable;
            });
    }
}
