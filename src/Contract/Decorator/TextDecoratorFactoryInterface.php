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

namespace Contract\Decorator;

use Contract\Decorator\Component\Link\LinkDestinationDecoratorInterface;
use Contract\Decorator\Component\TableDecoratorInterface;
use Dto\ClassD\ClassDto;
use Dto\Method\Method;

interface TextDecoratorFactoryInterface
{
    /**
     * @psalm-param non-empty-string $listType
     * @psalm-param non-empty-string $listItemType
     */
    public function createListDecorator(string $listType, string $listItemType): TextDecoratorInterface;

    public function createClassLinkDestinationDecorator(
        ClassDto $classDto,
        string $mainDirectory
    ): LinkDestinationDecoratorInterface;

    /**
     * @psalm-param positive-int $level
     */
    public function createHeadingDecorator(int $level): TextDecoratorInterface;

    public function createLinkDecorator(): TextDecoratorInterface;

    public function createMethodLinkDestinationDecorator(
        Method $method,
        string $mainDirectory
    ): LinkDestinationDecoratorInterface;

    public function createTableDecorator(): TableDecoratorInterface;
}
