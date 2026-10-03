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

use Contract\Generator\Documentation\ClassD\Page\ClassD\Marker\MethodPageMarkerInterface;
use Contract\Generator\Documentation\ClassD\Page\Component\Method\MethodTableGeneratorInterface;
use Contract\Pipeline\MethodPagePipelineStepInterface;
use Contract\Service\Translation\TranslationServiceInterface;
use Dto\Common\Marker;
use Dto\Method\Method;
use Illuminate\Support\Collection;
use Webmozart\Assert\Assert;
use Webmozart\Assert\InvalidArgumentException;

final readonly class MethodTableFetcher implements MethodPagePipelineStepInterface, MethodPageMarkerInterface
{
    private const string NAME_PARAM_TRANS_KEY = 'nameofparam';
    private const string TYPE_TRANS_KEY = 'type';
    private const string DESCRIPTION_TRANS_KEY = 'description';
    private const string DEFAULT_VALUE_TRANS_KEY = 'defaultval';

    public function __construct(
        private MethodTableGeneratorInterface $methodTableGenerator,
        private TranslationServiceInterface   $translationService
    ) {
    }

    /**
     * @throws InvalidArgumentException
     */
    public function handle(
        Collection $passable,
        Method $method,
        string $format,
        string $lang
    ): Collection {
        Assert::stringNotEmpty($format);
        Assert::stringNotEmpty($lang);

        $lineBreak = chr(13) . chr(13);

        $this->translationService->setLanguage($lang);
        $tableTranslations = [
            $this->translationService->translate(self::NAME_PARAM_TRANS_KEY),
            $this->translationService->translate(self::TYPE_TRANS_KEY),
            $this->translationService->translate(self::DESCRIPTION_TRANS_KEY),
            $this->translationService->translate(self::DEFAULT_VALUE_TRANS_KEY),
        ];

        $marker = Marker::create(self::METHOD_PARAMETERS_TABLE_MARKER)
            ->withValue(
                $this->methodTableGenerator->generate(
                    $method,
                    $format,
                    $tableTranslations
                ) . $lineBreak
            );

        return $passable->push($marker);
    }
}
