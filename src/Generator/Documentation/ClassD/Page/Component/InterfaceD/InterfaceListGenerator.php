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

namespace Generator\Documentation\ClassD\Page\Component\InterfaceD;

use ArrayIterator;
use Contract\Decorator\TextDecoratorFactoryInterface;
use Contract\Generator\Documentation\ClassD\Page\Component\InterfaceD\InterfaceListGeneratorInterface;
use Contract\Generator\Documentation\ClassD\Page\Component\Link\LinkGeneratorInterface;
use Dto\ClassD\InterfaceDto;
use Illuminate\Support\Collection;
use Webmozart\Assert\Assert;
use Webmozart\Assert\InvalidArgumentException;

final readonly class InterfaceListGenerator implements InterfaceListGeneratorInterface
{
    private const string LIST_TYPE = 'interface_list';

    public function __construct(
        private LinkGeneratorInterface $linkGenerator,
        private TextDecoratorFactoryInterface $textDecoratorFactory
    ) {
    }

    /**
     * @param Collection<int, InterfaceDto> $interfaces
     * @throws InvalidArgumentException
     */
    public function generate(
        Collection $interfaces,
        string $format,
        string $listType = 'ordered',
        bool $linked = true
    ): string {
        Assert::stringNotEmpty($format);
        Assert::stringNotEmpty($listType);

        $list = '';
        if (!$interfaces->isEmpty()) {
            /** @var ArrayIterator $iterator */
            $iterator = $interfaces->getIterator();

            $listDecorator = $this->textDecoratorFactory->createListDecorator(self::LIST_TYPE, $listType);

            while ($iterator->valid()) {
                /** @var InterfaceDto $interface */
                $interface = $iterator->current();
                if ($linked) {
                    $interfaceName = $interface->getName();
                    $interfaceStr = $this->linkGenerator->generate(
                        $format,
                        strtolower($interfaceName),
                        'interface::' . $interfaceName
                    );
                } else {
                    $interfaceStr = 'interface::' . $interface->getName();
                }

                Assert::stringNotEmpty(self::LIST_TYPE);

                $list .= $listDecorator->format($format, [$interfaceStr]);

                $iterator->next();
            }
        }

        return rtrim($list);
    }
}
