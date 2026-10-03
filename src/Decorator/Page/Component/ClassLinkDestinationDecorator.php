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

namespace Decorator\Page\Component;

use Contract\Decorator\Component\Link\LinkDestinationDecoratorInterface;
use Contract\Decorator\Component\Link\LinkDestinationFormatInterface;
use Contract\Decorator\DokuWikiFormatInterface;
use Contract\Service\File\DocFileServiceInterface;
use Dto\ClassD\ClassDto;
use Webmozart\Assert\Assert;
use Webmozart\Assert\InvalidArgumentException;

final readonly class ClassLinkDestinationDecorator implements LinkDestinationDecoratorInterface, LinkDestinationFormatInterface, DokuWikiFormatInterface
{
    public function __construct(
        private DocFileServiceInterface $docFileService,
        private ClassDto $classDto,
        private string $mainDirectory
    ) {
    }

    /**
     * @psalm-param non-empty-string $format
     * @psalm-return non-empty-string
     * @throws InvalidArgumentException
     */
    public function format(string $format): string
    {
        Assert::stringNotEmpty($format);
        $destination = $this->fetchClassDestination($format);
        assert::stringNotEmpty($destination);
        return $destination;
    }

    /**
     * @psalm-param non-empty-string $format
     * @psalm-return non-empty-string
     */
    private function fetchClassDestination(string $format): string
    {
        if ($format === self::DOKUWIKI_FORMAT_KEY) {
            return $this->fetchDokuWikiLinkDestination();
        }

        return $this->fetchLinkDestination($format);
    }

    /**
     * @psalm-return non-empty-string
     */
    private function fetchDokuWikiLinkDestination(): string
    {
        $className = $this->classDto->getName();
        $filename = sprintf(
            self::CLASS_DOKUWIKI_DEST_FILENAME_FORMAT,
            $className
        );
        return strtolower(
            sprintf(
                self::CLASS_DOKUWIKI_DESTINATION_FORMAT,
                $this->mainDirectory,
                $className,
                $filename
            )
        );
    }

    /**
     * @psalm-param non-empty-string $format
     * @psalm-return non-empty-string
     */
    private function fetchLinkDestination(string $format): string
    {
        $className = $this->classDto->getName();
        $filename = sprintf(
            self::CLASS_DEST_FILENAME_FORMAT,
            $className,
            $this->docFileService->getFileExtensionByFormat($format)
        );
        return strtolower(
            sprintf(
                self::CLASS_DESTINATION_FORMAT,
                $this->mainDirectory,
                $className,
                $filename
            )
        );
    }
}
