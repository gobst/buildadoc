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

use Contract\Generator\Documentation\Class\Page\Class\Marker\ListMarkerGeneratorInterface;
use Contract\Generator\Documentation\Class\Page\Class\Marker\ClassPageMarkerInterface;
use Contract\Pipeline\ClassPagePipelineStepInterface;
use Dto\Class\ClassDto;
use Dto\Common\Marker;
use Illuminate\Support\Collection;
use Webmozart\Assert\Assert;
use Webmozart\Assert\InvalidArgumentException;

final readonly class MethodListFetcher implements ClassPagePipelineStepInterface, ClassPageMarkerInterface
{
    public function __construct(private ListMarkerGeneratorInterface $listGenerator)
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
    ): Collection
    {
        Assert::stringNotEmpty($format);
        Assert::stringNotEmpty($lang);

        $marker = $this->listGenerator->generateMethodList(
            $class,
            $format,
            'unordered',
            $lang,
            $mainDirectory
        );

        $passable->push(Marker::create(self::METHODS_LIST_HEADING_MARKER)
            ->withValue(
                $marker[self::METHODS_LIST_HEADING_MARKER]
            ));
        $passable->push(Marker::create(self::METHODS_LIST_MARKER)
            ->withValue(
                $marker[self::METHODS_LIST_MARKER]
            ));

        return $passable;
    }
}
