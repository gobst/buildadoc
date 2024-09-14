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

namespace Service\File\Template\Provider;

use Contract\Service\File\Template\PageTemplateServiceInterface;
use Contract\Service\File\Template\TemplateServiceProviderInterface;
use Webmozart\Assert\Assert;
use Webmozart\Assert\InvalidArgumentException;

final readonly class TemplateServiceProvider implements TemplateServiceProviderInterface
{
    private array $services;

    /**
     * @param array<string, PageTemplateServiceInterface> $services
     */
    public function __construct(array $services)
    {
        $this->services = $services;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function getService(string $type): PageTemplateServiceInterface
    {
        Assert::stringNotEmpty($type);

        if (!isset($this->services[$type])) {
            throw new InvalidArgumentException("Unknown service type: $type");
        }

        return $this->services[$type];
    }
}
