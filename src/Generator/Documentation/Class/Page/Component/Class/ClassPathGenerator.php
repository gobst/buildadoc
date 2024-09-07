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
use Contract\Formatter\Component\Link\ClassLinkDestinationFormatterInterface;
use Contract\Generator\Documentation\Class\Page\Component\Class\ClassPathGeneratorInterface;
use Contract\Generator\Documentation\Class\Page\Component\Link\LinkGeneratorInterface;
use Dto\Class\ClassDto;
use Illuminate\Support\Collection;
use Webmozart\Assert\Assert;
use Webmozart\Assert\InvalidArgumentException;

final readonly class ClassPathGenerator implements ClassPathGeneratorInterface
{
    public function __construct(
        private LinkGeneratorInterface $linkGenerator,
        private ClassLinkDestinationFormatterInterface $classLinkDestFormat
    )
    {
    }

    /**
     * @throws InvalidArgumentException
     */
    public function generate(ClassDto $class, string $format, string $mainDirectory = ''): string
    {
        Assert::stringNotEmpty($format);

        $parentClassesPath = $this->generateParentClassesPath($class, $format, $mainDirectory);

        if (!empty($parentClassesPath)) {
            array_unshift($parentClassesPath, $class->getName());
        }

        return implode(' --> ', $parentClassesPath);
    }

    /**
     * @throws InvalidArgumentException
     */
    private function generateParentClassesPath(ClassDto $class, string $format, string $mainDirectory): array
    {
        Assert::stringNotEmpty($format);

        $parentClassesPath = [];
        $parentClassesPath[] = $class->getName();
        $parentClasses = $class->getParentClasses();

        if ($parentClasses !== null && !$parentClasses->isEmpty()) {
            $parentClassesPath = $this->generateClassPath($parentClasses, $format, $mainDirectory);
        }

        return $parentClassesPath;
    }

    /**
     * @param Collection<int, ClassDto> $classes
     * @throws InvalidArgumentException
     */
    private function generateClassPath(Collection $classes, string $format, string $mainDirectory): array
    {
        Assert::stringNotEmpty($format);

        $classPath = [];

        /** @var ArrayIterator $iterator */
        $iterator = $classes->getIterator();
        while ($iterator->valid()) {
            /** @var ClassDto $class */
            $class = $iterator->current();
            $className = $class->getName();
            Assert::stringNotEmpty($className);

            $destination = $this->classLinkDestFormat->formatDestination($format, $class, $mainDirectory);
            Assert::stringNotEmpty($destination);

            $classPath[] = $this->linkGenerator->generate($format, $destination);
            $iterator->next();
        }

        return $classPath;
    }
}
