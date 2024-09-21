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

namespace Generator\Documentation\Class\Page\Component\File;

use ArrayIterator;
use Contract\Decorator\Component\TableDecoratorInterface;
use Contract\Generator\Documentation\Class\Page\Component\File\FilesTableGeneratorInterface;
use Dto\Class\ClassDto;
use Webmozart\Assert\Assert;
use Webmozart\Assert\InvalidArgumentException;

final readonly class FilesTableGenerator implements FilesTableGeneratorInterface
{
    public function __construct(
        private TableDecoratorInterface $tableFormatter
    ) {}

    /**
     * @throws InvalidArgumentException
     */
    public function generate(ClassDto $class, string $format, array $headerLabels): string
    {
        Assert::stringNotEmpty($format);
        $rows = [];
        $rows[0][0] = $class->getFilepath();
        $rows[0][1] = $class->getName();
        $rows[0][2] = $class->getNamespace() !== null ? $class->getNamespace() : '';
        $parentClasses = $class->getParentClasses();
        if ($parentClasses !== null && !$parentClasses->isEmpty()) {
            $index = 1;
            /** @var ArrayIterator $iterator */
            $iterator = $parentClasses->getIterator();
            while ($iterator->valid()) {
                /** @var ClassDto $parentClass */
                $parentClass = $iterator->current();
                $rows[$index][0] = $parentClass->getFilepath();
                $rows[$index][1] = $parentClass->getName();
                $rows[$index][2] = $parentClass->getNamespace() !== null ? $parentClass->getNamespace() : '';
                ++$index;
                $iterator->next();
            }
        }

        return rtrim($this->tableFormatter->format($format, $headerLabels, $rows, true));
    }
}
