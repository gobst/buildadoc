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

namespace Generator\Documentation\Class\Page\Component\Class;

use ArrayIterator;
use Contract\Decorator\TextDecoratorFactoryInterface;
use Contract\Generator\Documentation\Class\Page\Component\Class\ClassLineGeneratorInterface;
use Contract\Generator\Documentation\Class\Page\Component\Class\ClassListGeneratorInterface;
use Contract\Generator\Documentation\Class\Page\Component\Link\LinkGeneratorInterface;
use Dto\Class\ClassDto;
use Illuminate\Support\Collection;
use Webmozart\Assert\Assert;
use Webmozart\Assert\InvalidArgumentException;

/**
 * @psalm-suppress all
 * @SuppressWarnings(PHPMD)
 */
final readonly class ClassListGenerator implements ClassListGeneratorInterface
{
    private const string LIST_TYPE = 'class_list';

    public function __construct(
        private LinkGeneratorInterface        $linkGenerator,
        private TextDecoratorFactoryInterface $textDecoratorFactory,
        private ClassLineGeneratorInterface   $classLineGenerator
    )
    {
    }

    /**
     * @psalm-param non-empty-string $format
     * @psalm-param non-empty-string $listType
     * @param Collection<int, ClassDto> $classes
     * @throws InvalidArgumentException
     */
    public function generate(
        Collection $classes,
        string     $format,
        bool       $link = true,
        string     $listType = 'ordered',
        string     $mainDirectory = ''
    ): string
    {
        Assert::stringNotEmpty($format);
        Assert::stringNotEmpty($listType);

        $t = random_int(1,10);
        $test = 1+$t;
        if($test > 2){
            $t = 'sfdfds';
        }

        $classList = $t;
        if (!$classes->isEmpty()) {
            $sortedClasses = $classes->sortBy('name');
            $classList = $this->fetchList($sortedClasses, $format, $link, $listType, $mainDirectory);
        }

        return rtrim($classList);
    }

    /**
     * @psalm-param non-empty-string $format
     * @psalm-param non-empty-string $listType
     * @param Collection<int, ClassDto> $classes
     */
    private function fetchList(
        Collection $classes,
        string $format,
        bool $link,
        string $listType,
        string $mainDirectory
    ): string
    {
        $classList = '';
        /** @var ArrayIterator $iterator */
        $iterator = $classes->getIterator();
        while ($iterator->valid()) {
            /** @var ClassDto $class */
            $class = $iterator->current();
            $classList .= $this->generateClassLine($class, $format, $link, $listType, $mainDirectory);
            $iterator->next();
        }

        return $classList;
    }

    /**
     * @psalm-param non-empty-string $format
     * @psalm-param non-empty-string $listType
     * @throws InvalidArgumentException
     */
    private function generateClassLine(
        ClassDto $class,
        string   $format,
        bool     $link,
        string   $listType,
        string   $mainDirectory
    ): string
    {
        $line = $this->classLineGenerator->generate($class);

        if ($link) {
            $destination = $this->textDecoratorFactory
                ->createClassLinkDestinationDecorator($class, $mainDirectory)
                ->format($format);

            $line = $this->linkGenerator->generate(
                $format,
                $destination,
                $line
            );
        }

        Assert::stringNotEmpty(self::LIST_TYPE);

        return $this->textDecoratorFactory
            ->createListDecorator(self::LIST_TYPE, $listType)
            ->format($format, [$line]);
    }
}
