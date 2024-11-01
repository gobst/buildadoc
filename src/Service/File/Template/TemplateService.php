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

use ArrayIterator;
use Contract\Service\File\Template\TemplateServiceInterface;
use Dto\Common\Marker;
use Exception\TemplateNotFoundException;
use Illuminate\Support\Collection;
use ReflectionClass;
use Service\File\Filter\MarkerNameFilter;
use Webmozart\Assert\Assert;
use Webmozart\Assert\InvalidArgumentException;

final readonly class TemplateService implements TemplateServiceInterface
{
    private const string MARKER_IDENTIFIER = '###';

    public function __construct()
    {
    }

    /**
     * @throws InvalidArgumentException
     */
    public function fillTemplate(string $template, Collection $markers): string
    {
        Assert::stringNotEmpty($template);

        $templateContent = $this->getTemplate($template);
        $templateContent = str_replace(["\n", "\r"], '', $templateContent);

        if (!$markers->isEmpty()) {
            /** @var ArrayIterator $iterator */
            $iterator = $markers->getIterator();

            while ($iterator->valid()) {
                /** @var Marker $marker */
                $marker = $iterator->current();

                $key = sprintf(
                    '%s%s%s',
                    self::MARKER_IDENTIFIER,
                    $marker->getName(),
                    self::MARKER_IDENTIFIER
                );

                $markerValue = $marker->getValue();
                Assert::notNull($markerValue);

                $templateContent = str_replace($key, $markerValue, $templateContent);
                $iterator->next();
            }
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

    public function initEmptyMarkers(Collection $markers, string $interface): Collection
    {
        Assert::stringNotEmpty($interface);

        $interfaceReflection = new ReflectionClass($interface);
        $definedMarkers = $interfaceReflection->getConstants();

        foreach ($definedMarkers as $markerKey) {
            $foundMarker = $markers->filter(function ($marker) use ($markerKey) {
                return (new MarkerNameFilter($markerKey))->hasName($marker);
            });

            if ($foundMarker->isEmpty()) {
                $markers->push(Marker::create($markerKey)->withValue(''));
            }
        }

        return $markers;
    }
}
