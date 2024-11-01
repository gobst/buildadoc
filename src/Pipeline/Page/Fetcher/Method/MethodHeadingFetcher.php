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

namespace Pipeline\Page\Fetcher\Method;

use Contract\Generator\Documentation\Class\Page\Class\Marker\MethodPageMarkerInterface;
use Contract\Generator\Documentation\Class\Page\Component\Heading\HeadingGeneratorInterface;
use Contract\Pipeline\MethodPagePipelineStepInterface;
use Dto\Common\Marker;
use Dto\Method\Method;
use Illuminate\Support\Collection;
use Webmozart\Assert\Assert;
use Webmozart\Assert\InvalidArgumentException;

final readonly class MethodHeadingFetcher implements MethodPagePipelineStepInterface, MethodPageMarkerInterface
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
        Method     $method,
        string     $format,
        string     $lang
    ): Collection {
        Assert::stringNotEmpty($format);
        Assert::stringNotEmpty($lang);

        $lineBreak = chr(13) . chr(13);
        $marker = Marker::create(self::METHOD_HEADING_MARKER)
            ->withValue(
                $this->headingGenerator->generate(
                    $method->getName(),
                    1,
                    $format
                ) . $lineBreak
            );

        return $passable->push($marker);
    }
}
