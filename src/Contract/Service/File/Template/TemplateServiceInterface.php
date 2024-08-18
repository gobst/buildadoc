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

namespace Contract\Service\File\Template;

use Dto\Common\Marker;
use Illuminate\Support\Collection;
use ReflectionException;
use Webmozart\Assert\InvalidArgumentException;

interface TemplateServiceInterface
{
    /**
     * @psalm-param non-empty-string $template
     * @param Collection<int, Marker> $markers
     */
    public function fillTemplate(string $template, Collection $markers): string;

    /**
     * @param Collection<int, Marker> $markers
     * @psalm-param non-empty-string $interface
     * @return Collection<int, Marker>
     *
     * @throws ReflectionException
     * @throws InvalidArgumentException
     */
    public function initEmptyMarkers(Collection $markers, string $interface): Collection;
}
