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

namespace Service\File\Template;

use Contract\Generator\Documentation\ClassD\Page\ClassD\Marker\MethodPageMarkerInterface;
use Contract\Service\File\Template\PageTemplateServiceInterface;
use Contract\Service\File\Template\TemplateServiceInterface;
use Illuminate\Support\Collection;
use ReflectionException;

final readonly class MethodPageTemplateService implements PageTemplateServiceInterface
{
    private const string TEMPLATE_FILE = __DIR__ . '/../../../../res/templates/dokuwiki/methodTmpl.txt';

    public function __construct(private TemplateServiceInterface $templateService)
    {
    }

    /**
     * @throws ReflectionException
     */
    public function fillTemplate(Collection $markers): string
    {
        $markers = $this->templateService->initEmptyMarkers($markers, MethodPageMarkerInterface::class);
        return $this->templateService->fillTemplate(self::TEMPLATE_FILE, $markers);
    }
}
