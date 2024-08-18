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

use Contract\Generator\Documentation\Class\Page\Class\Marker\ListMarkerGeneratorInterface;
use Contract\Generator\Documentation\Class\Page\Class\Marker\ClassPageMarkerInterface;
use Contract\Generator\Documentation\Class\Page\Component\Class\UsedByClassListGeneratorInterface;
use Contract\Generator\Documentation\Class\Page\Component\Constant\ConstantListGeneratorInterface;
use Contract\Generator\Documentation\Class\Page\Component\Heading\HeadingGeneratorInterface;
use Contract\Generator\Documentation\Class\Page\Component\Interface\InterfaceListGeneratorInterface;
use Contract\Generator\Documentation\Class\Page\Component\Method\MethodListGeneratorInterface;
use Contract\Generator\Documentation\Class\Page\Component\Property\PropertyListGeneratorInterface;
use Contract\Service\Translation\TranslationServiceInterface;
use Dto\Class\ClassDto;
use Webmozart\Assert\Assert;
use Webmozart\Assert\InvalidArgumentException;

final readonly class ListMarkerGenerator implements ListMarkerGeneratorInterface, ClassPageMarkerInterface
{
    public function __construct(
        private TranslationServiceInterface $translationService,
        private MethodListGeneratorInterface $methodListGenerator,
        private ConstantListGeneratorInterface $constListGenerator,
        private PropertyListGeneratorInterface $propListGenerator,
        private InterfaceListGeneratorInterface $interListGenerator,
        private HeadingGeneratorInterface $headingGenerator,
        private UsedByClassListGeneratorInterface $usedByClassListGen,
    ) {}

    /**
     * @throws InvalidArgumentException
     */
    public function generateUsedByClassList(ClassDto $class, string $format, string $listType, string $lang): array
    {
        Assert::stringNotEmpty($format);
        Assert::stringNotEmpty($listType);
        Assert::stringNotEmpty($lang);

        $marker = [];
        $lineBreak = chr(13) . chr(13);
        $this->translationService->setLanguage($lang);

        if ($class->getChildClasses() !== null && !$class->getChildClasses()->isEmpty()) {
            $text = $this->translationService->translate('class.usedbyclasses');
            Assert::stringNotEmpty($text);

            $marker['CLASS_USEDBYCLASSES_HEADING'] = $this->headingGenerator->generate($text, 2, $format) . $lineBreak;
            $marker['CLASS_USEDBYCLASSES_LIST'] = $this->usedByClassListGen->generate($class, $format, true, $listType) . $lineBreak;
        }

        return $marker;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function generateConstantList(ClassDto $class, string $format, string $listType, string $lang): array
    {
        Assert::stringNotEmpty($format);
        Assert::stringNotEmpty($listType);
        Assert::stringNotEmpty($lang);

        $marker = [];
        $lineBreak = chr(13) . chr(13);
        $this->translationService->setLanguage($lang);

        if ($class->getConstants() !== null && !$class->getConstants()->isEmpty()) {
            $text = $this->translationService->translate('class.const');
            Assert::stringNotEmpty($text);

            $marker[self::CONSTANTS_LIST_HEADING_MARKER] = $this->headingGenerator->generate($text, 2, $format) . $lineBreak;
            $marker[self::CONSTANTS_LIST_MARKER] = $this->constListGenerator->generate($class->getConstants(), $format, $listType) . $lineBreak;
        }

        return $marker;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function generatePropertiesList(ClassDto $class, string $format, string $listType, string $lang): array
    {
        Assert::stringNotEmpty($format);
        Assert::stringNotEmpty($listType);
        Assert::stringNotEmpty($lang);

        $marker = [];
        $lineBreak = chr(13) . chr(13);
        $this->translationService->setLanguage($lang);

        if ($class->getProperties() !== null && !$class->getProperties()->isEmpty()) {
            $text = $this->translationService->translate('class.properties');
            Assert::stringNotEmpty($text);

            $marker[self::PROPERTIES_LIST_HEADING_MARKER] = $this->headingGenerator->generate($text, 2, $format) . $lineBreak;
            $marker[self::PROPERTIES_LIST_MARKER] = $this->propListGenerator->generate($class, $format, $listType) . $lineBreak;
        }

        return $marker;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function generateInterfacesList(ClassDto $class, string $format, string $listType, string $lang): array
    {
        Assert::stringNotEmpty($format);
        Assert::stringNotEmpty($listType);
        Assert::stringNotEmpty($lang);

        $marker = [];
        $lineBreak = chr(13) . chr(13);
        $this->translationService->setLanguage($lang);

        if ($class->getInterfaces() !== null && !$class->getInterfaces()->isEmpty()) {
            $text = $this->translationService->translate('class.interfaces');
            Assert::stringNotEmpty($text);

            $marker[self::INTERFACES_LIST_HEADING_MARKER] = $this->headingGenerator->generate($text, 2, $format) . $lineBreak;
            $marker[self::INTERFACES_LIST_MARKER] = $this->interListGenerator->generate($class->getInterfaces(), $format, $listType) . $lineBreak;
        }

        return $marker;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function generateMethodList(ClassDto $class, string $format, string $listType, string $lang): array
    {
        Assert::stringNotEmpty($format);
        Assert::stringNotEmpty($listType);
        Assert::stringNotEmpty($lang);

        $marker = [];
        $lineBreak = chr(13) . chr(13);
        $this->translationService->setLanguage($lang);

        if (!$class->getMethods()->isEmpty()) {
            $text = $this->translationService->translate('class.methods');
            Assert::stringNotEmpty($text);

            $marker[self::METHODS_LIST_HEADING_MARKER] = $this->headingGenerator->generate($text, 2, $format) . $lineBreak;
            $marker[self::METHODS_LIST_MARKER] = $this->methodListGenerator->generate($class, $format, true, $listType, true) . $lineBreak;
        }

        return $marker;
    }
}
