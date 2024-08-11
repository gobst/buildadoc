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
            ->through($this->getClosures($class, $format, $lang))
            ->then(function (Collection $passable) {
                return $passable;
            });
    }

    /**
     * @psalm-param non-empty-string $format
     * @psalm-param non-empty-string $lang
     * @return array<int, Closure>
     */
    private function getClosures(ClassDto $class, string $format, string $lang): array
    {
        $fetchers = [
            'heading',
            'filesTable',
            'classPath',
            'constructor',
            'propertiesList',
            'interfacesList',
            'constantList',
            'methodList'
        ];

        $closures = [];

        foreach($fetchers as $fetcher){
            $closure = function ($passable, $next) use ($class, $format, $lang, $fetcher) {
                $passable = $this->fetcherProvider
                    ->getFetcher($fetcher)
                    ->handle($passable, $class, $format, $lang);
                return $next($passable);
            };
            $closures[] = $closure;
        }

        return $closures;
    }
}
