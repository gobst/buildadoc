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

namespace Service\Class\Documentation\Page;

use ArrayIterator;
use Collection\DocPageCollection;
use Collection\MethodCollection;
use Contract\Formatter\DokuWikiFormatInterface;
use Contract\Generator\Documentation\Class\Page\Method\MethodPageGeneratorInterface;
use Contract\Service\Class\Documentation\Page\MethodPageServiceInterface;
use Dto\Documentation\DocPage;
use Dto\Method\Method;
use Service\Class\Filter\PageTitleFilter;
use Webmozart\Assert\Assert;

final readonly class MethodPageService implements MethodPageServiceInterface, DokuWikiFormatInterface
{
    private const string FILE_EXTENSION_SUFFIX = 'FILE_EXTENSION';

    public function __construct(
        private MethodPageGeneratorInterface $methodPageGenerator
    ) {}

    public function getPages(MethodCollection $methods, string $lang, string $format): DocPageCollection
    {
        Assert::stringNotEmpty($format);
        Assert::stringNotEmpty($lang);

        $docPageCollection = new DocPageCollection();
        $fileExtension = $this->getFileExtension($format);
        /** @var ArrayIterator $iterator */
        $iterator = $methods->getIterator();

        while ($iterator->valid()) {
            /** @var Method $method */
            $method = $iterator->current();
            $methodPage = $this->methodPageGenerator->generate($method, $format, $lang);
            $methodName = $method->getName();
            $fileName = sprintf('%s_%s', $method->getClass(), $methodName);

            Assert::stringNotEmpty($methodPage);
            Assert::stringNotEmpty($fileName);
            Assert::stringNotEmpty($fileExtension);

            $dto = DocPage::create(
                $methodPage,
                $methodName,
                $fileName,
                $fileExtension
            );

            $docPageCollection->add($dto);
            $iterator->next();
        }

        return new DocPageCollection(
            $docPageCollection
                ->filter([new PageTitleFilter('__construct'), 'hasNotPageTitle'])
                ->toArray()
        );
    }

    private function getFileExtension(string $format): string
    {
        return self::{sprintf('%s_%s', strtoupper($format), self::FILE_EXTENSION_SUFFIX)};
    }
}
