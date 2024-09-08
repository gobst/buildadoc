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

namespace Pipeline\Page\Fetcher\TableOfContents;

use Contract\Generator\Documentation\Class\Page\Class\Marker\TableOfContentsPageMarkerInterface;
use Contract\Generator\Documentation\Class\Page\Component\Class\ClassListGeneratorInterface;
use Contract\Pipeline\TableOfContentsPagePipelineStepInterface;
use Dto\Common\Marker;
use Illuminate\Support\Collection;
use Webmozart\Assert\Assert;
use Webmozart\Assert\InvalidArgumentException;

final readonly class ClassListFetcher implements TableOfContentsPagePipelineStepInterface, TableOfContentsPageMarkerInterface
{
    private const string FORMAT = '%s%s';

    public function __construct(
        private ClassListGeneratorInterface $classListGenerator
    )
    {
    }

    /**
     * @throws InvalidArgumentException
     */
    public function handle(
        Collection $passable,
        Collection $classes,
        string     $format,
        string     $lang,
        string     $mainDirectory
    ): Collection
    {
        Assert::stringNotEmpty($format);
        Assert::stringNotEmpty($lang);

        $lineBreak = chr(13) . chr(13);
        $list = $this->classListGenerator->generate(
            $classes,
            $format,
            true,
            'unordered',
            $mainDirectory
        );

        $marker = Marker::create(self::TABLEOFCONTENTS_CLASS_LIST_MARKER)
            ->withValue(
                sprintf(
                    self::FORMAT,
                    $list,
                    $lineBreak
                )
            );

        return $passable->push($marker);
    }
}
