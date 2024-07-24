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

namespace Generator\Documentation\Class\Page\Component\Property;

use ArrayIterator;
use Contract\Formatter\Component\ListFormatterInterface;
use Contract\Generator\Documentation\Class\Page\Component\Property\PropertyListGeneratorInterface;
use Dto\Class\ClassDto;
use Dto\Common\Property;
use Webmozart\Assert\Assert;
use Webmozart\Assert\InvalidArgumentException;

final readonly class PropertyListGenerator implements PropertyListGeneratorInterface
{
    private const string LIST_TYPE = 'property_list';

    public function __construct(private ListFormatterInterface $listFormatter) {}

    /**
     * @throws InvalidArgumentException
     */
    public function generate(ClassDto $class, string $format, string $listType = 'ordered'): string
    {
        Assert::stringNotEmpty($format);
        Assert::stringNotEmpty($listType);

        $properties = $class->getProperties();
        $list = '';
        if ($properties !== null && !$properties->isEmpty()) {
            /** @var ArrayIterator $iterator */
            $iterator = $properties->getIterator();
            while ($iterator->valid()) {
                /** @var Property $property */
                $property = $iterator->current();
                $modifiersStr = $property->getModifiers()->toArray();
                $contentParts = [];
                $contentParts[] = $modifiersStr;
                $contentParts[] = $property->getType();
                $contentParts[] = $property->getName();
                $contentParts[] = $property->getDefaultValue();
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
