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

namespace Decorator;

use Contract\Decorator\Component\Link\LinkDestinationDecoratorInterface;
use Contract\Decorator\Component\TableDecoratorInterface;
use Contract\Decorator\DecoratorInterface;
use Contract\Decorator\TextDecoratorFactoryInterface;
use Contract\Decorator\TextDecoratorInterface;
use Contract\Service\File\DocFileServiceInterface;
use Decorator\Page\Component\ClassLinkDestinationDecorator;
use Decorator\Page\Component\HeadingDecorator;
use Decorator\Page\Component\LinkDecorator;
use Decorator\Page\Component\ListDecorator;
use Decorator\Page\Component\MethodLinkDestinationDecorator;
use Decorator\Page\Component\TableDecorator;
use Dto\Class\ClassDto;
use Dto\Method\Method;

final readonly class TextDecoratorFactory implements TextDecoratorFactoryInterface
{
    public function __construct(
        private DecoratorInterface $decorator,
        private DocFileServiceInterface $docFileService
    ) {
    }

    public function createListDecorator(
        string $listType,
        string $listItemType
    ): TextDecoratorInterface {
        return new ListDecorator($this->decorator, $listType, $listItemType);
    }

    public function createClassLinkDestinationDecorator(
        ClassDto $classDto,
        string $mainDirectory
    ): LinkDestinationDecoratorInterface {
        return new ClassLinkDestinationDecorator(
            $this->docFileService,
            $classDto,
            $mainDirectory
        );
    }

    public function createHeadingDecorator(int $level): TextDecoratorInterface
    {
        return new HeadingDecorator($this->decorator, $level);
    }

    public function createLinkDecorator(): TextDecoratorInterface
    {
        return new LinkDecorator($this->decorator);
    }

    public function createMethodLinkDestinationDecorator(
        Method $method,
        string $mainDirectory
    ): LinkDestinationDecoratorInterface {
        return new MethodLinkDestinationDecorator(
            $this->docFileService,
            $method,
            $mainDirectory
        );
    }

    public function createTableDecorator(): TableDecoratorInterface
    {
        return new TableDecorator();
    }
}
