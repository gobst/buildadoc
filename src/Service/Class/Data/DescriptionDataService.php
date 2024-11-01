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

namespace Service\Class\Data;

use Contract\Service\Class\Data\DescriptionDataServiceInterface;
use Service\Class\Filter\TagFilter;
use Webmozart\Assert\Assert;

final class DescriptionDataService implements DescriptionDataServiceInterface
{
    public function __construct()
    {
    }

    /**
     * @psalm-param non-empty-string $phpdoc
     */
    public function getDescriptionByPHPDoc(string $phpdoc): array
    {
        Assert::stringNotEmpty($phpdoc);
        // remove " *" from each line
        $lines = array_map(static fn ($line) => trim($line, ' *'), explode("\n", $phpdoc));
        $lines = array_filter($lines, static function ($line) {
            $filter = false;
            $line = trim($line);
            if (!str_contains($line, '@') && $line !== '/' && $line !== '*/' && !empty($line)) {
                $filter = true;
            }

            return $filter;
        });

        return $lines;
    }

    /**
     * Fetches the PHPDoc block and transfers it to an array.
     *
     * @param string $phpdoc The PHPDoc
     * @param string $tag    The tag that should be transferred out of the PHPDoc (optional)(default:'param')
     * @param int    $limit  (optional)(default:4)
     *
     * @psalm-param non-empty-string $phpdoc
     */
    public function getTagByPHPDoc(string $phpdoc, string $tag = 'param', int $limit = 4): array
    {
        Assert::stringNotEmpty($phpdoc);
        // get PHPDoc
        $args = [];
        if (!empty($phpdoc)) {
            // remove " *" from each line
            $lines = array_map(static fn ($line) => trim($line, ' *'), explode("\n", $phpdoc));
            // get all lines with the given tag
            /** @psalm-suppress PossiblyInvalidArgument */
            $linesTag = array_filter($lines, [new tagFilter($tag), 'hasTag'], ARRAY_FILTER_USE_BOTH);
            // push each value in the corresponding array
            $index1 = 0;
            foreach ($linesTag as $line) {
                $paramDoc = explode(' ', $line, $limit);
                $index2 = 0;
                foreach ($paramDoc as $p) {
                    $args[$index1][$index2] = $p;
                    ++$index2;
                }
                ++$index1;
            }
        }
        if (!empty($args[0]) && $args[0][0] !== '@' . $tag) {
            array_shift($args[0]);
            array_shift($args[0]);
        }

        return $args;
    }
}
