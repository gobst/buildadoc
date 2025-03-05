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

use Contract\Generator\Documentation\ClassD\Page\ClassD\Marker\ClassPageMarkerInterface;
use Contract\Generator\Documentation\ClassD\Page\Component\Heading\HeadingGeneratorInterface;
use Contract\Pipeline\ClassPagePipelineStepInterface;
use Dto\ClassD\ClassDto;
use Dto\Common\Marker;
use Illuminate\Support\Collection;
use Webmozart\Assert\Assert;
use Webmozart\Assert\InvalidArgumentException;

final readonly class HeadingFetcher implements ClassPagePipelineStepInterface, ClassPageMarkerInterface
{
    public function __construct(
        private HeadingGeneratorInterface $headingGenerator
    ) {
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

        $lineBreak = chr(13) . chr(13);
        $marker = Marker::create(self::HEADING_MARKER)
            ->withValue(
                $this->headingGenerator->generate(
                    $class->getName(),
                    1,
                    $format
                ) . $lineBreak
            );

        return $passable->push($marker);
    }
}
