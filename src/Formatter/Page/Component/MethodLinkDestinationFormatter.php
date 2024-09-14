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

use Contract\Formatter\Component\Link\LinkDestinationFormatInterface;
use Contract\Formatter\Component\Link\MethodLinkDestinationFormatterInterface;
use Contract\Formatter\DokuWikiFormatInterface;
use Dto\Method\Method;
use Service\File\DocFileService;
use Webmozart\Assert\Assert;
use Webmozart\Assert\InvalidArgumentException;

final readonly class MethodLinkDestinationFormatter implements MethodLinkDestinationFormatterInterface, LinkDestinationFormatInterface, DokuWikiFormatInterface
{
    public function __construct(private DocFileService $docFileService)
    {
    }

    /**
     * @throws InvalidArgumentException
     */
    public function formatDestination(string $format, Method $method, string $mainDirectory): string
    {
        Assert::stringNotEmpty($format);

        return $this->fetchMethodDestination($format, $method, $mainDirectory);
    }

    /**
     * @psalm-param non-empty-string $format
     * @psalm-return non-empty-string
     */
    private function fetchMethodDestination(string $format, Method $method, string $mainDirectory): string
    {
        if ($format === self::DOKUWIKI_FORMAT_KEY) {
            return $this->fetchDokuWikiLinkDestination($method, $mainDirectory);
        }

        return $this->fetchLinkDestination($format, $method, $mainDirectory);
    }

    /**
     * @psalm-return non-empty-string
     */
    private function fetchDokuWikiLinkDestination(Method $method, string $mainDirectory): string
    {
        $className = $method->getClass();
        $filename = sprintf(
            self::METHOD_DOKUWIKI_DEST_FILENAME_FORMAT,
            $className,
            $method->getName()
        );
        return strtolower(
            sprintf(
                self::METHOD_DOKUWIKI_DESTINATION_FORMAT,
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
    private function fetchLinkDestination(string $format, Method $method, string $mainDirectory): string
    {
        $className = $method->getClass();
        $filename = sprintf(
            self::METHOD_DEST_FILENAME_FORMAT,
            $className,
            $method->getName(),
            $this->docFileService->getFileExtensionByFormat($format)
        );
        return strtolower(
            sprintf(
                self::METHOD_DESTINATION_FORMAT,
                $mainDirectory,
                $className,
                $filename
            )
        );
    }
}
