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

namespace Generator\Documentation\Class\Page\Component\Constant;

use ArrayIterator;
use Contract\Decorator\TextDecoratorFactoryInterface;
use Contract\Generator\Documentation\Class\Page\Component\Constant\ConstantListGeneratorInterface;
use Contract\Service\Class\Data\ModifierDataServiceInterface;
use Dto\Class\Constant;
use Illuminate\Support\Collection;
use Webmozart\Assert\Assert;
use Webmozart\Assert\InvalidArgumentException;

final readonly class ConstantListGenerator implements ConstantListGeneratorInterface
{
    private const string LIST_TYPE = 'constant_list';

    public function __construct(
        private TextDecoratorFactoryInterface $textDecoratorFactory,
        private ModifierDataServiceInterface $modifierDataService
    )
    {
    }

    /**
     * @param Collection<int, Constant> $constants
     * @throws InvalidArgumentException
     */
    public function generate(Collection $constants, string $format, string $listType = 'ordered'): string
    {
        Assert::stringNotEmpty($format);
        Assert::stringNotEmpty($listType);

        $list = '';
        $listDecorator = $this->textDecoratorFactory->createListDecorator(self::LIST_TYPE, $listType);

        if (!$constants->isEmpty()) {
            /** @var ArrayIterator $iterator */
            $iterator = $constants->getIterator();
            while ($iterator->valid()) {
                /** @var Constant $constant */
                $constant = $iterator->current();

                $modifiersStr = $this->modifierDataService->implodeModifierDTOCollection($constant->getModifiers());

                $textParts = [];
                $textParts[] = $modifiersStr;
                $textParts[] = $constant->getType();
                $textParts[] = $constant->getName();
                $textParts[] = $constant->getValue();

                Assert::stringNotEmpty(self::LIST_TYPE);

                $list .= $listDecorator->format($format, $textParts);

                $iterator->next();
            }
        }

        return rtrim($list);
    }
}
