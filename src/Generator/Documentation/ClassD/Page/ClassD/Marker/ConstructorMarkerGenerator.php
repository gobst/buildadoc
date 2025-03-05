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

namespace Generator\Documentation\ClassD\Page\ClassD\Marker;

use Contract\Generator\Documentation\ClassD\Page\ClassD\Marker\ConstructorMarkerGeneratorInterface;
use Contract\Generator\Documentation\ClassD\Page\ClassD\Marker\ClassPageMarkerInterface;
use Contract\Generator\Documentation\ClassD\Page\Component\Heading\HeadingGeneratorInterface;
use Contract\Generator\Documentation\ClassD\Page\Component\Method\MethodLineGeneratorInterface;
use Contract\Service\Translation\TranslationServiceInterface;
use Dto\ClassD\ClassDto;
use Dto\Method\Method;
use Illuminate\Support\Collection;
use Service\ClassD\Filter\MethodNameFilter;
use Webmozart\Assert\Assert;
use Webmozart\Assert\InvalidArgumentException;

final readonly class ConstructorMarkerGenerator implements ConstructorMarkerGeneratorInterface, ClassPageMarkerInterface
{
    public function __construct(
        private TranslationServiceInterface $translationService,
        private HeadingGeneratorInterface $headingGenerator,
        private MethodLineGeneratorInterface $methodLineGenerator
    ) {
    }

    /**
     * @throws InvalidArgumentException
     */
    public function generate(ClassDto $class, string $format, string $lang): array
    {
        Assert::stringNotEmpty($format);
        Assert::stringNotEmpty($lang);

        $marker = [];
        $lineBreak = chr(13) . chr(13);
        $this->translationService->setLanguage($lang);

        $constructor = $this->getConstructor($class);
        if ($constructor !== false) {
            $text = $this->translationService->translate('class.constructor');

            Assert::stringNotEmpty($text);
            Assert::isInstanceOf($constructor, Method::class);

            $marker[self::CONSTRUCTOR_HEADING_MARKER] = $this->headingGenerator->generate($text, 2, $format) . $lineBreak;
            $marker[self::CONSTRUCTOR_MARKER] = $this->methodLineGenerator->generate($constructor) . $lineBreak;
        }

        return $marker;
    }

    private function getConstructor(ClassDto $class): Method|bool
    {
        $collection = Collection::make($class->getMethods()->filter(function ($value) {
            return (new MethodNameFilter('__construct'))->hasName($value);
        }));

        if ($collection->isEmpty()) {
            return false;
        }

        return $collection->first() ?? false;
    }
}
