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

namespace Generator\Documentation\Class\Page\Class;

use Contract\Generator\Documentation\Class\Page\Class\ClassPageGeneratorInterface;
use Contract\Generator\Documentation\Class\Page\Class\Marker\ClassPathMarkerGeneratorInterface;
use Contract\Generator\Documentation\Class\Page\Class\Marker\ConstructorMarkerGeneratorInterface;
use Contract\Generator\Documentation\Class\Page\Class\Marker\ListMarkerGeneratorInterface;
use Contract\Generator\Documentation\Class\Page\Component\File\FilesTableGeneratorInterface;
use Contract\Generator\Documentation\Class\Page\Component\Heading\HeadingGeneratorInterface;
use Contract\Service\File\TemplateServiceInterface;
use Contract\Service\Translation\TranslationServiceInterface;
use Dto\Class\ClassDto;
use Exception;
use Webmozart\Assert\Assert;
use Webmozart\Assert\InvalidArgumentException;

final readonly class ClassPageGenerator implements ClassPageGeneratorInterface
{
    private const string TEMPLATE_FILE = __DIR__ . '/../../../../../../res/templates/dokuwiki/classTmpl.txt';

    public function __construct(
        private TranslationServiceInterface $translationService,
        private FilesTableGeneratorInterface $filesTableGenerator,
        private HeadingGeneratorInterface $headingGenerator,
        private TemplateServiceInterface $templateService,
        private ListMarkerGeneratorInterface $listGenerator,
        private ConstructorMarkerGeneratorInterface $constructorGenerator,
        private ClassPathMarkerGeneratorInterface $classPathGenerator
    ) {}

    /**
     * @throws InvalidArgumentException
     * @throws Exception
     */
    public function generate(ClassDto $class, string $format, string $lang): string
    {
        Assert::stringNotEmpty($lang);
        Assert::stringNotEmpty($format);

        $lineBreak = chr(13) . chr(13);

        $this->translationService->setLanguage($lang);
        $tableTranslations = [
            $this->translationService->translate('class.necfiles'),
            $this->translationService->translate('name'),
            $this->translationService->translate('class.namespace'),
        ];
        $marker = [];

        $marker['###HEADING###'] = $this->headingGenerator->generate($class->getName(), 1, $format) . $lineBreak;
        $marker['###FILES_TABLE###'] = $this->filesTableGenerator->generate($class, $format, $tableTranslations) . $lineBreak;

        $classPathMarker = $this->classPathGenerator->generate($class, $format, $lang);
        $marker = array_merge($marker, $classPathMarker);

        $listMarker = $this->listGenerator->generateConstantList($class, $format, 'unordered', $lang);
        $marker = array_merge($marker, $listMarker);

        $listMarker = $this->listGenerator->generatePropertiesList($class, $format, 'unordered', $lang);
        $marker = array_merge($marker, $listMarker);

        $constructorMarker = $this->constructorGenerator->generate($class, $format, $lang);
        $marker = array_merge($marker, $constructorMarker);

        $listMarker = $this->listGenerator->generateInterfacesList($class, $format, 'unordered', $lang);
        $marker = array_merge($marker, $listMarker);

        $listMarker = $this->listGenerator->generateMethodList($class, $format, 'unordered', $lang);
        $marker = array_merge($marker, $listMarker);

        return $this->templateService->fillTemplate(self::TEMPLATE_FILE, $marker);
    }
}
