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

namespace Formatter\Page\Component;

use Contract\Formatter\Component\Link\ClassLinkDestinationFormatterInterface;
use Contract\Formatter\Component\Link\LinkDestinationFormatInterface;
use Contract\Formatter\DokuWikiFormatInterface;
use Dto\Class\ClassDto;
use Service\File\DocFileService;
use Webmozart\Assert\Assert;
use Webmozart\Assert\InvalidArgumentException;

final readonly class ClassLinkDestinationFormatter implements ClassLinkDestinationFormatterInterface, LinkDestinationFormatInterface, DokuWikiFormatInterface
{
    public function __construct(private DocFileService $docFileService)
    {
    }

    /**
     * @throws InvalidArgumentException
     */
    public function formatDestination(string $format, ClassDto $class, string $mainDirectory): string
    {
        Assert::stringNotEmpty($format);

        return $this->fetchClassDestination($format, $class, $mainDirectory);
    }

    /**
     * @psalm-param non-empty-string $format
     * @psalm-return non-empty-string
     */
    private function fetchClassDestination(string $format, ClassDto $class, string $mainDirectory): string
    {
        if ($format === self::DOKUWIKI_FORMAT_KEY) {
            return $this->fetchDokuWikiLinkDestination($class, $mainDirectory);
        }

        return $this->fetchLinkDestination($format, $class, $mainDirectory);
    }

    /**
     * @psalm-return non-empty-string
     */
    private function fetchDokuWikiLinkDestination(ClassDto $class, string $mainDirectory): string
    {
        $className = $class->getName();
        $filename = sprintf(
            self::CLASS_DOKUWIKI_DEST_FILENAME_FORMAT,
            $className
        );
        return strtolower(
            sprintf(
                self::CLASS_DOKUWIKI_DESTINATION_FORMAT,
                $mainDirectory,
                $className,
                $filename
            )
        );
    }

    /**
     * @psalm-param non-empty-string $format
     * @psalm-return non-empty-string
     */
    private function fetchLinkDestination(string $format, ClassDto $class, string $mainDirectory): string
    {
        $className = $class->getName();
        $filename = sprintf(
            self::CLASS_DEST_FILENAME_FORMAT,
            $className,
            $this->docFileService->getFileExtensionByFormat($format)
        );
        return strtolower(
            sprintf(
                self::CLASS_DESTINATION_FORMAT,
                $mainDirectory,
                $className,
                $filename
            )
        );
    }
}
