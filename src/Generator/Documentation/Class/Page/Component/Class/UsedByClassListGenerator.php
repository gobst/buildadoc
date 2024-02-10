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

namespace Generator\Documentation\Class\Page\Component\Class;

use ArrayIterator;
use Contract\Formatter\Component\ListFormatterInterface;
use Contract\Generator\Documentation\Class\Page\Component\Class\UsedByClassListGeneratorInterface;
use Contract\Generator\Documentation\Class\Page\Component\Link\LinkGeneratorInterface;
use Dto\Class\ClassDto;
use Webmozart\Assert\Assert;
use Webmozart\Assert\InvalidArgumentException;

final readonly class UsedByClassListGenerator implements UsedByClassListGeneratorInterface
{
    private const string LIST_TYPE = 'usedbyclass_list';

    public function __construct(
        private ListFormatterInterface $listFormatter,
        private LinkGeneratorInterface $linkGenerator
    ) {}

    /**
     * @throws InvalidArgumentException
     */
    public function generate(
        ClassDto $class,
        string $format,
        bool $link = true,
        string $listType = 'ordered'
    ): string {
        Assert::stringNotEmpty($format);
        Assert::stringNotEmpty($listType);

        $list = '';
        $childClasses = $class->getChildClasses();

        if ($childClasses !== null && !$childClasses->isEmpty()) {
            /** @var ArrayIterator $iterator */
            $iterator = $childClasses->getIterator();
            while ($iterator->valid()) {
                /** @var ClassDto $childClass */
                $childClass = $iterator->current();

                $className = $childClass->getName();
                $modifiersStr = $childClass->getModifiers()->toString();

                $contentParts = [];
                $contentParts[] = $modifiersStr;
                $contentParts[] = $link ? $this->linkGenerator->generate($format, $className, $className) : $className;

                Assert::stringNotEmpty(self::LIST_TYPE);

                $list .= $this->listFormatter->formatListItem(
                    $format,
                    self::LIST_TYPE,
                    $contentParts,
                    $listType
                );

                $iterator->next();
            }
        }

        return rtrim($list);
    }
}
