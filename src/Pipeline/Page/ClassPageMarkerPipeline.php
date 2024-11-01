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
use Contract\Pipeline\Fetcher\ClassPageFetcherProviderInterface;
use Dto\Class\ClassDto;
use Dto\Common\Marker;
use Illuminate\Contracts\Pipeline\Pipeline;
use Illuminate\Support\Collection;
use Webmozart\Assert\Assert;

final readonly class ClassPageMarkerPipeline implements ClassPageMarkerPipelineInterface
{
    private const string CLASS_HEADING_KEY_FETCHER = 'heading';
    private const string FILES_TABLE_KEY_FETCHER = 'filesTable';
    private const string CLASS_PATH_KEY_FETCHER = 'classPath';
    private const string CONSTRUCTOR_KEY_FETCHER = 'constructor';
    private const string PROPERTIES_LIST_KEY_FETCHER = 'propertiesList';
    private const string INTERFACES_LIST_KEY_FETCHER = 'interfacesList';
    private const string CONSTANT_LIST_KEY_FETCHER = 'constantList';
    private const string METHOD_LIST_KEY_FETCHER = 'methodList';
    private const string CLASS_USEDBYCLASSES_LIST_KEY_FETCHER = 'usedByClassesList';

    public function __construct(
        private ClassPageFetcherProviderInterface $fetcherProvider,
        private Pipeline                          $pipeline
    ) {
    }

    /**
     * @psalm-param non-empty-string $lang
     * @psalm-param non-empty-string $format
     * @return Collection<int, Marker>
     */
    public function handlePipeline(
        ClassDto $class,
        string $format,
        string $lang,
        string $mainDirectory
    ): Collection {
        Assert::stringNotEmpty($format);
        Assert::stringNotEmpty($lang);

        return $this->pipeline
            ->send(Collection::make())
            ->through($this->getClosures($class, $format, $lang, $mainDirectory))
            ->then(function (Collection $passable) {
                return $passable;
            });
    }

    /**
     * @psalm-param non-empty-string $format
     * @psalm-param non-empty-string $lang
     * @return array<int, Closure>
     */
    private function getClosures(
        ClassDto $class,
        string $format,
        string $lang,
        string $mainDirectory
    ): array {
        $fetchers = [
            self::CLASS_HEADING_KEY_FETCHER,
            self::FILES_TABLE_KEY_FETCHER,
            self::CLASS_PATH_KEY_FETCHER,
            self::CONSTRUCTOR_KEY_FETCHER,
            self::PROPERTIES_LIST_KEY_FETCHER,
            self::INTERFACES_LIST_KEY_FETCHER,
            self::CONSTANT_LIST_KEY_FETCHER,
            self::METHOD_LIST_KEY_FETCHER,
            self::CLASS_USEDBYCLASSES_LIST_KEY_FETCHER
        ];

        $closures = [];

        foreach ($fetchers as $fetcher) {
            $closure = function ($passable, $next) use ($class, $format, $lang, $fetcher, $mainDirectory) {
                $passable = $this->fetcherProvider
                    ->getFetcher($fetcher)
                    ->handle($passable, $class, $format, $lang, $mainDirectory);
                return $next($passable);
            };
            $closures[] = $closure;
        }

        return $closures;
    }
}
