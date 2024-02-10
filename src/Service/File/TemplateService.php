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

namespace Service\File;

use Contract\Service\File\TemplateServiceInterface;
use Exception;
use Exception\TemplateNotFoundException;
use Webmozart\Assert\Assert;

final readonly class TemplateService implements TemplateServiceInterface
{
    public function __construct() {}

    /**
     * @throws Exception
     */
    public function fillTemplate(string $template, array $marker): string
    {
        Assert::stringNotEmpty($template);
        Assert::notEmpty($marker);

        $emptyMarker = $this->initEmptyMarkerArray();
        $marker = array_merge($emptyMarker, $marker);

        try {
            $templateContent = $this->getTemplate($template);
            $templateContent = str_replace(["\n", "\r"], '', $templateContent);
        } catch (TemplateNotFoundException $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
        foreach ($marker as $key => $value) {
            $templateContent = str_replace($key, $value, $templateContent);
        }

        return $templateContent;
    }

    /**
     * @psalm-param non-empty-string $file
     *
     * @throws TemplateNotFoundException
     */
    private function getTemplate(string $file): string
    {
        if (file_exists($file)) {
            return file_get_contents($file);
        }
        throw new TemplateNotFoundException($file);
    }

    private function initEmptyMarkerArray(): array
    {
        $marker = [];
        $marker['###HEADING###'] = '';
        $marker['###FILES_TABLE###'] = '';
        $marker['###CLASS_PATH_HEADING###'] = '';
        $marker['###CLASS_PATH###'] = '';
        $marker['###CLASS_USEDBYCLASSES_HEADING###'] = '';
        $marker['###CLASS_USEDBYCLASSES_LIST###'] = '';
        $marker['###CONSTANTS_LIST_HEADING###'] = '';
        $marker['###CONSTANTS_LIST###'] = '';
        $marker['###CLASS_PROPERTIES_LIST_HEADING###'] = '';
        $marker['###CLASS_PROPERTIES_LIST###'] = '';
        $marker['###CONSTRUCTOR_HEADING###'] = '';
        $marker['###CONSTRUCTOR###'] = '';
        $marker['###CLASS_INTERFACES_LIST_HEADING###'] = '';
        $marker['###CLASS_INTERFACES_LIST###'] = '';
        $marker['###METHODS_LIST_HEADING###'] = '';
        $marker['###METHODS_LIST###'] = '';
        $marker['###METHOD_PARAMETERS_TABLE###'] = '';
        $marker['###METHOD_SIGNATURE###'] = '';

        return $marker;
    }
}
