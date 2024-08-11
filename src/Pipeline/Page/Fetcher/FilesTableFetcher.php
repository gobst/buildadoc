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

namespace Pipeline\Page\Fetcher;

use Contract\Generator\Documentation\Class\Page\Class\Marker\MarkerInterface;
use Contract\Generator\Documentation\Class\Page\Component\File\FilesTableGeneratorInterface;
use Contract\Pipeline\ClassPagePipelineStepInterface;
use Contract\Service\Translation\TranslationServiceInterface;
use Dto\Class\ClassDto;
use Dto\Common\Marker;
use Illuminate\Support\Collection;
use Webmozart\Assert\Assert;
use Webmozart\Assert\InvalidArgumentException;

final readonly class FilesTableFetcher implements ClassPagePipelineStepInterface, MarkerInterface
{
    public function __construct(
        private FilesTableGeneratorInterface $filesTableGenerator,
        private TranslationServiceInterface $translationService
    )
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

        $lineBreak = chr(13) . chr(13);

        $this->translationService->setLanguage($lang);
        $tableTranslations = [
            $this->translationService->translate('class.necfiles'),
            $this->translationService->translate('name'),
            $this->translationService->translate('class.namespace'),
        ];

        $marker = Marker::create(self::FILES_TABLE_MARKER)
            ->withValue(
                $this->filesTableGenerator->generate(
                    $class,
                    $format,
                    $tableTranslations
                ) . $lineBreak
            );

        return $passable->push($marker);
    }
}
