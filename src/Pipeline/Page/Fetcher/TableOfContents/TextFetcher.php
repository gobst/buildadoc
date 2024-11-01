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
use Contract\Pipeline\TableOfContentsPagePipelineStepInterface;
use Contract\Service\Translation\TranslationServiceInterface;
use Dto\Common\Marker;
use Illuminate\Support\Collection;
use Webmozart\Assert\Assert;
use Webmozart\Assert\InvalidArgumentException;

final readonly class TextFetcher implements TableOfContentsPagePipelineStepInterface, TableOfContentsPageMarkerInterface
{
    private const string TRANSLATION_KEY = 'tableofcontents.text';

    public function __construct(
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

        $marker = Marker::create(self::TABLEOFCONTENTS_TEXT_MARKER)
            ->withValue(
                sprintf(
                    '%s%s',
                    $text,
                    $lineBreak
                )
            );

        return $passable->push($marker);
    }
}
