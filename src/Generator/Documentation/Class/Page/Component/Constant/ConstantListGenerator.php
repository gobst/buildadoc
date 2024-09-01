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
use Contract\Formatter\Component\ListFormatterInterface;
use Contract\Generator\Documentation\Class\Page\Component\Constant\ConstantListGeneratorInterface;
use Dto\Class\Constant;
use Illuminate\Support\Collection;
use Webmozart\Assert\Assert;
use Webmozart\Assert\InvalidArgumentException;

final readonly class ConstantListGenerator implements ConstantListGeneratorInterface
{
    private const string LIST_TYPE = 'constant_list';

    public function __construct(private ListFormatterInterface $listFormatter) {}

    /**
     * @param Collection<int, Constant> $constants
     * @throws InvalidArgumentException
     */
    public function generate(Collection $constants, string $format, string $listType = 'ordered'): string
    {
        Assert::stringNotEmpty($format);
        Assert::stringNotEmpty($listType);

        $list = '';
        if (!$constants->isEmpty()) {
            /** @var ArrayIterator $iterator */
            $iterator = $constants->getIterator();
            while ($iterator->valid()) {
                /** @var Constant $constant */
                $constant = $iterator->current();

                $modifiersStr = $this->listFormatter->implodeModifierDTOCollection($constant->getModifiers());

                $contentParts = [];
                $contentParts[] = $modifiersStr;
                $contentParts[] = $constant->getType();
                $contentParts[] = $constant->getName();
                $contentParts[] = $constant->getValue();

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
