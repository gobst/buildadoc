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

namespace Pipeline\Page\Fetcher\ClassD;

use Contract\Generator\Documentation\ClassD\Page\ClassD\Marker\ConstructorMarkerGeneratorInterface;
use Contract\Generator\Documentation\ClassD\Page\ClassD\Marker\ClassPageMarkerInterface;
use Contract\Pipeline\ClassPagePipelineStepInterface;
use Dto\ClassD\ClassDto;
use Dto\Common\Marker;
use Illuminate\Support\Collection;
use Webmozart\Assert\Assert;
use Webmozart\Assert\InvalidArgumentException;

final readonly class ConstructorFetcher implements ClassPagePipelineStepInterface, ClassPageMarkerInterface
{
    public function __construct(private ConstructorMarkerGeneratorInterface $constructorMarkerGen)
    {
    }

    /**
     * @throws InvalidArgumentException
     */
    public function handle(
        Collection $passable,
        ClassDto   $class,
        string     $format,
        string     $lang,
        string     $mainDirectory
    ): Collection {
        Assert::stringNotEmpty($format);
        Assert::stringNotEmpty($lang);

        $marker = $this->constructorMarkerGen->generate($class, $format, $lang);

        $passable->push(Marker::create(self::CONSTRUCTOR_HEADING_MARKER)
            ->withValue(
                $marker[self::CONSTRUCTOR_HEADING_MARKER]
            ));
        $passable->push(Marker::create(self::CONSTRUCTOR_MARKER)
            ->withValue(
                $marker[self::CONSTRUCTOR_MARKER]
            ));

        return $passable;
    }
}
