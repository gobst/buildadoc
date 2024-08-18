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

namespace Pipeline\Page\Fetcher\Class;

use Contract\Generator\Documentation\Class\Page\Class\Marker\ClassPageMarkerInterface;
use Contract\Generator\Documentation\Class\Page\Component\Class\ClassPathGeneratorInterface;
use Contract\Pipeline\ClassPagePipelineStepInterface;
use Dto\Class\ClassDto;
use Dto\Common\Marker;
use Illuminate\Support\Collection;
use Webmozart\Assert\Assert;
use Webmozart\Assert\InvalidArgumentException;

final readonly class ClassPathFetcher implements ClassPagePipelineStepInterface, ClassPageMarkerInterface
{
    public function __construct(private ClassPathGeneratorInterface $classPathGenerator)
    {
    }

    /**
     * @throws InvalidArgumentException
     */
    public function handle(
        Collection $passable,
        ClassDto $class,
        string $format,
        string $lang
    ): Collection
    {
        Assert::stringNotEmpty($format);
        Assert::stringNotEmpty($lang);

        $marker = Marker::create(self::CLASS_PATH_MARKER)
            ->withValue(
                $this->classPathGenerator->generate(
                    $class,
                    $format
                )
            );

        return $passable->push($marker);
    }
}
