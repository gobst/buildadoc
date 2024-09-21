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

namespace Generator\Documentation\Class\Page\Component\Method;

use ArrayIterator;
use Contract\Decorator\TextDecoratorFactoryInterface;
use Contract\Generator\Documentation\Class\Page\Component\Link\LinkGeneratorInterface;
use Contract\Generator\Documentation\Class\Page\Component\Method\MethodLineGeneratorInterface;
use Contract\Generator\Documentation\Class\Page\Component\Method\MethodListGeneratorInterface;
use Dto\Class\ClassDto;
use Dto\Method\Method;
use Illuminate\Support\Collection;
use Service\Class\Filter\MethodNameFilter;
use Webmozart\Assert\Assert;
use Webmozart\Assert\InvalidArgumentException;

final readonly class MethodListGenerator implements MethodListGeneratorInterface
{
    private const string LIST_TYPE = 'method_list';

    public function __construct(
        private LinkGeneratorInterface $linkGenerator,
        private MethodLineGeneratorInterface $methodLineGenerator,
        private TextDecoratorFactoryInterface $textDecoratorFactory
    )
    {
    }

    /**
     * @throws InvalidArgumentException
     */
    public function generate(
        ClassDto $class,
        string   $format,
        bool     $link = true,
        string   $listType = 'ordered',
        bool     $withInheritedMethods = false,
        string   $mainDirectory = ''
    ): string
    {
        Assert::stringNotEmpty($format);
        Assert::stringNotEmpty($listType);

        $list = '';
        $methods = $class->getMethods();
        $inheritedMethods = $withInheritedMethods === true ? $class->getInheritedMethods() : null;

        if (!$methods->isEmpty()) {
            $list = $this->fetchList($methods, $format, $link, $listType, $mainDirectory);
            if ($withInheritedMethods === true && $inheritedMethods !== null && !$inheritedMethods->isEmpty()) {
                // @todo: switch to separated list with heading
                $list .= chr(13) . '-------------------------------' . chr(13) . chr(13);
                $list .= $this->fetchList($inheritedMethods, $format, $link, $listType, $mainDirectory);
            }
        }

        return rtrim($list);
    }

    /**
     * @psalm-param non-empty-string $format
     * @psalm-param non-empty-string $listType
     * @param Collection<int, Method> $methods
     */
    private function fetchList(
        Collection $methods,
        string     $format,
        bool       $link,
        string     $listType,
        string     $mainDirectory
    ): string
    {
        Assert::stringNotEmpty($format);

        $methods = $methods->filter(function ($value) {
            return (new MethodNameFilter('__construct'))->hasNotName($value);
        });

        $list = '';
        /** @var ArrayIterator $iterator */
        $iterator = $methods->getIterator();

        while ($iterator->valid()) {
            /** @var Method $method */
            $method = $iterator->current();
            $list .= $this->generateMethodLine($method, $format, $link, $listType, $mainDirectory);
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
    private function generateMethodLine(
        Method $method,
        string $format,
        bool   $link,
        string $listType,
        string $mainDirectory
    ): string
    {
        $line = $this->methodLineGenerator->generate($method);

        if ($link) {
            $destination = $this->textDecoratorFactory
                ->createMethodLinkDestinationDecorator($method, $mainDirectory)
                ->format($format);
            Assert::stringNotEmpty($destination);

            $line = $this->linkGenerator->generate(
                $format,
                $destination,
                $line
            );
        }

        $description = $method->getDescription();
        $desc = !empty($description) ? ' // ' . $description : '';

        Assert::stringNotEmpty(self::LIST_TYPE);

        return $this->textDecoratorFactory
            ->createListDecorator(self::LIST_TYPE, $listType)
            ->format($format, [$line . $desc]);
    }
}
