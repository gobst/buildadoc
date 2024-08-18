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

namespace Pipeline\Page\Fetcher\Method;

use Contract\Generator\Documentation\Class\Page\Class\Marker\MarkerInterface;
use Contract\Generator\Documentation\Class\Page\Component\Method\MethodLineGeneratorInterface;
use Contract\Pipeline\MethodPagePipelineStepInterface;
use Dto\Common\Marker;
use Dto\Method\Method;
use Illuminate\Support\Collection;
use Webmozart\Assert\Assert;
use Webmozart\Assert\InvalidArgumentException;

final readonly class MethodSignatureFetcher implements MethodPagePipelineStepInterface, MarkerInterface
{
    public function __construct(
        private MethodLineGeneratorInterface $methodLineGenerator
    )
    {
    }

    /**
     * @throws InvalidArgumentException
     */
    public function handle(
        Collection $passable,
        Method     $method,
        string     $format,
        string     $lang
    ): Collection
    {
        Assert::stringNotEmpty($format);
        Assert::stringNotEmpty($lang);

        $marker = Marker::create(self::METHOD_SIGNATURE_MARKER)
            ->withValue(
                $this->methodLineGenerator->generate($method, false)
            );

        return $passable->push($marker);
    }
}
