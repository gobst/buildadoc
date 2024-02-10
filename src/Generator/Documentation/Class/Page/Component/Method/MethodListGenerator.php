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

namespace Generator\Documentation\Class\Page\Component\Method;

use ArrayIterator;
use Collection\MethodCollection;
use Contract\Formatter\Component\ListFormatterInterface;
use Contract\Generator\Documentation\Class\Page\Component\Link\LinkGeneratorInterface;
use Contract\Generator\Documentation\Class\Page\Component\Method\MethodLineGeneratorInterface;
use Contract\Generator\Documentation\Class\Page\Component\Method\MethodListGeneratorInterface;
use Dto\Class\ClassDto;
use Dto\Method\Method;
use Service\Class\Filter\MethodNameFilter;
use Webmozart\Assert\Assert;
use Webmozart\Assert\InvalidArgumentException;

final readonly class MethodListGenerator implements MethodListGeneratorInterface
{
    private const string LIST_TYPE = 'method_list';

    public function __construct(
        private LinkGeneratorInterface $linkGenerator,
        private MethodLineGeneratorInterface $methodLineGenerator,
        private ListFormatterInterface $listFormatter
    ) {}

    /**
     * @throws InvalidArgumentException
     */
    public function generate(
        ClassDto $class,
        string $format,
        bool $link = true,
        string $listType = 'ordered',
        bool $withInheritedMethods = false
    ): string {
        Assert::stringNotEmpty($format);
        Assert::stringNotEmpty($listType);

        $list = '';
        $methods = $class->getMethods();
        $inheritedMethods = $withInheritedMethods === true ? $class->getInheritedMethods() : null;

        if (!$methods->isEmpty()) {
            $list = $this->fetchList($methods, $format, $link, $listType);
            if ($withInheritedMethods === true && $inheritedMethods !== null && !$inheritedMethods->isEmpty()) {
                // @todo: switch to separated list with heading
                $list .= chr(13) . '-------------------------------' . chr(13) . chr(13);
                $list .= $this->fetchList($inheritedMethods, $format, $link, $listType);
            }
        }

        return rtrim($list);
    }

    /**
     * @psalm-param non-empty-string $format
     * @psalm-param non-empty-string $listType
     */
    private function fetchList(MethodCollection $methods, string $format, bool $link, string $listType): string
    {
        $methods = new MethodCollection(
            $methods
                ->filter([new MethodNameFilter('__construct'), 'hasNotName'])
                ->toArray()
        );

        $list = '';
        /** @var ArrayIterator $iterator */
        $iterator = $methods->getIterator();

        while ($iterator->valid()) {
            $list .= $this->generateMethodLine($iterator->current(), $format, $link, $listType);
            $iterator->next();
        }

        return $list;
    }

    /**
     * @psalm-param non-empty-string $format
     * @psalm-param non-empty-string $listType
     *
     * @throws InvalidArgumentException
     */
    private function generateMethodLine(Method $method, string $format, bool $link, string $listType): string
    {
        $line = $this->methodLineGenerator->generate($method);
        if ($link) {
            $line = $this->linkGenerator->generate($format, $method->getName(), $line);
        }

        $description = $method->getDescription();
        $desc = !empty($description) ? ' // ' . $description : '';

        Assert::stringNotEmpty(self::LIST_TYPE);

        return $this->listFormatter->formatListItem($format, self::LIST_TYPE, [$line . $desc], $listType);
    }
}
