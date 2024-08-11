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
declare(strict_types = 1);

namespace Generator\Documentation\Class\Page\Class\Marker;

use Contract\Generator\Documentation\Class\Page\Class\Marker\ClassPathMarkerGeneratorInterface;
use Contract\Generator\Documentation\Class\Page\Class\Marker\MarkerInterface;
use Contract\Generator\Documentation\Class\Page\Component\Class\ClassPathGeneratorInterface;
use Contract\Generator\Documentation\Class\Page\Component\Heading\HeadingGeneratorInterface;
use Contract\Service\Translation\TranslationServiceInterface;
use Dto\Class\ClassDto;
use Webmozart\Assert\Assert;
use Webmozart\Assert\InvalidArgumentException;

final readonly class ClassPathMarkerGenerator implements ClassPathMarkerGeneratorInterface, MarkerInterface
{
    public function __construct(
        private TranslationServiceInterface $translationService,
        private HeadingGeneratorInterface $headingGenerator,
        private ClassPathGeneratorInterface $classPathGenerator
    ) {}

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

        if ($class->getParentClasses() !== null && !$class->getParentClasses()->isEmpty()) {
            $text = $this->translationService->translate('class.parentclasses');
            Assert::stringNotEmpty($text);

            $marker[self::CLASS_PATH_HEADING_MARKER] = $this->headingGenerator->generate($text, 2, $format) . $lineBreak;
            $marker[self::CLASS_PATH_MARKER] = $this->classPathGenerator->generate($class, $format) . $lineBreak;
        }

        return $marker;
    }
}
