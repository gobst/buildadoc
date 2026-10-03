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

use Contract\Generator\Documentation\ClassD\Page\ClassD\Marker\TableOfContentsPageMarkerInterface;
use Contract\Generator\Documentation\ClassD\Page\Component\Heading\HeadingGeneratorInterface;
use Contract\Pipeline\TableOfContentsPagePipelineStepInterface;
use Contract\Service\Translation\TranslationServiceInterface;
use Dto\Common\Marker;
use Illuminate\Support\Collection;
use Webmozart\Assert\Assert;
use Webmozart\Assert\InvalidArgumentException;

final readonly class HeadingFetcher implements TableOfContentsPagePipelineStepInterface, TableOfContentsPageMarkerInterface
{
    private const string TRANSLATION_KEY = 'tableofcontents.heading';

    public function __construct(
        private HeadingGeneratorInterface $headingGenerator,
        private TranslationServiceInterface $translationService
    ) {
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
    ): Collection {
        Assert::stringNotEmpty($format);
        Assert::stringNotEmpty($lang);

        $lineBreak = chr(13) . chr(13);
        $text = $this->translationService->translate(self::TRANSLATION_KEY);
        Assert::stringNotEmpty($text);

        $marker = Marker::create(self::TABLEOFCONTENTS_HEADING_MARKER)
            ->withValue(
                $this->headingGenerator->generate(
                    $text,
                    1,
                    $format
                ) . $lineBreak
            );

        return $passable->push($marker);
    }
}
