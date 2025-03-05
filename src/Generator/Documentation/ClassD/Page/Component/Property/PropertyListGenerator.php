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

namespace Generator\Documentation\ClassD\Page\Component\Property;

use ArrayIterator;
use Contract\Decorator\TextDecoratorFactoryInterface;
use Contract\Generator\Documentation\ClassD\Page\Component\Property\PropertyListGeneratorInterface;
use Contract\Service\ClassD\Data\ModifierDataServiceInterface;
use Dto\ClassD\ClassDto;
use Dto\Common\Property;
use Webmozart\Assert\Assert;
use Webmozart\Assert\InvalidArgumentException;

final readonly class PropertyListGenerator implements PropertyListGeneratorInterface
{
    private const string LIST_TYPE = 'property_list';

    public function __construct(
        private TextDecoratorFactoryInterface $textDecoratorFactory,
        private ModifierDataServiceInterface $modifierDataService
    ) {
    }

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

            $listDecorator = $this->textDecoratorFactory->createListDecorator(self::LIST_TYPE, $listType);

            while ($iterator->valid()) {
                /** @var Property $property */
                $property = $iterator->current();

                $propertiesStr = $this->modifierDataService->implodeModifierDTOCollection($property->getModifiers());

                $textParts = [];
                $textParts[] = $propertiesStr;
                $textParts[] = $property->getType();
                $textParts[] = $property->getName();
                $textParts[] = $property->getDefaultValue();
                $list .= $listDecorator->format($format, $textParts);
                $iterator->next();
            }
        }

        return rtrim($list);
    }
}
