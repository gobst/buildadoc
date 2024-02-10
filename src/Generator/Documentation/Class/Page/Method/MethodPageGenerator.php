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

namespace Generator\Documentation\Class\Page\Method;

use Contract\Generator\Documentation\Class\Page\Component\Heading\HeadingGeneratorInterface;
use Contract\Generator\Documentation\Class\Page\Component\Method\MethodLineGeneratorInterface;
use Contract\Generator\Documentation\Class\Page\Component\Method\MethodTableGeneratorInterface;
use Contract\Generator\Documentation\Class\Page\Method\MethodPageGeneratorInterface;
use Contract\Service\File\TemplateServiceInterface;
use Contract\Service\Translation\TranslationServiceInterface;
use Dto\Method\Method;
use Exception;
use Webmozart\Assert\Assert;
use Webmozart\Assert\InvalidArgumentException;

final readonly class MethodPageGenerator implements MethodPageGeneratorInterface
{
    private const string TEMPLATE_FILE = __DIR__ . '/../../../../../../res/templates/dokuwiki/methodTmpl.txt';

    public function __construct(
        private TemplateServiceInterface $templateService,
        private MethodTableGeneratorInterface $methodTableGenerator,
        private MethodLineGeneratorInterface $methodLineGenerator,
        private TranslationServiceInterface $translationService,
        private HeadingGeneratorInterface $headingGenerator
    ) {}

    /**
     * @throws InvalidArgumentException
     * @throws Exception
     */
    public function generate(Method $method, string $format, string $lang): string
    {
        Assert::stringNotEmpty($format);
        Assert::stringNotEmpty($lang);

        $lineBreak = chr(13) . chr(13);

        $this->translationService->setLanguage($lang);
        $tableTranslations = [
            $this->translationService->translate('nameofparam'),
            $this->translationService->translate('type'),
            $this->translationService->translate('description'),
            $this->translationService->translate('defaultval'),
        ];

        $marker = [];
        $marker['###HEADING###'] = $this->headingGenerator->generate($method->getName(), 1, $format) . $lineBreak;

        if ($method->getParameters() !== null && !$method->getParameters()->isEmpty()) {
            $marker['###METHOD_PARAMETERS_TABLE###'] = $this->methodTableGenerator->generate($method, $format, $tableTranslations) . $lineBreak;
        }

        $marker['###METHOD_SIGNATURE###'] = $this->methodLineGenerator->generate($method, false);

        return $this->templateService->fillTemplate(self::TEMPLATE_FILE, $marker);
    }
}
