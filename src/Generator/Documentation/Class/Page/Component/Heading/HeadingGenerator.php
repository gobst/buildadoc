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

namespace Generator\Documentation\Class\Page\Component\Heading;

use Contract\Decorator\TextDecoratorFactoryInterface;
use Contract\Generator\Documentation\Class\Page\Component\Heading\HeadingGeneratorInterface;
use Webmozart\Assert\Assert;
use Webmozart\Assert\InvalidArgumentException;

final readonly class HeadingGenerator implements HeadingGeneratorInterface
{
    public function __construct(private TextDecoratorFactoryInterface $textDecoratorFactory) {}

    /**
     * @throws InvalidArgumentException
     */
    public function generate(string $text, int $level, string $format): string
    {
        Assert::stringNotEmpty($format);
        Assert::stringNotEmpty($text);
        Assert::positiveInteger($level);

        return $this->textDecoratorFactory->createHeadingDecorator($level)->format($format, [$text]);
    }
}
