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

namespace Generator\Documentation\Class\Page\Component\Link;

use Contract\Decorator\TextDecoratorFactoryInterface;
use Contract\Generator\Documentation\Class\Page\Component\Link\LinkGeneratorInterface;
use Webmozart\Assert\Assert;
use Webmozart\Assert\InvalidArgumentException;

final readonly class LinkGenerator implements LinkGeneratorInterface
{
    public function __construct(private TextDecoratorFactoryInterface $textDecoratorFactory)
    {
    }

    /**
     * @throws InvalidArgumentException
     */
    public function generate(
        string $format,
        string $dest,
        string $text = ''
    ): string {
        Assert::stringNotEmpty($format);
        Assert::stringNotEmpty($dest);

        return $this->textDecoratorFactory->createLinkDecorator()->format($format, [$dest, $text]);
    }
}
