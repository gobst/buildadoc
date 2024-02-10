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

namespace Service\Translation;

use Contract\Service\Translation\TranslationServiceInterface;
use Webmozart\Assert\Assert;
use Webmozart\Assert\InvalidArgumentException;

final class TranslationService implements TranslationServiceInterface
{
    private array $translations;

    /**
     * @throws InvalidArgumentException
     */
    public function translate(string $key): string
    {
        Assert::stringNotEmpty($key);

        $index = explode('.', $key);

        return count($index) === 2 ? $this->translations[$index[0]][$index[1]] : $this->translations[$index[0]];
    }

    /**
     * @throws InvalidArgumentException
     */
    public function setLanguage(string $lang): void
    {
        Assert::stringNotEmpty($lang);

        $this->translations = $this->loadTranslationFile($lang);
    }

    private function loadTranslationFile(string $lang): array
    {
        return require sprintf('%s/../../../res/translations/%s.php', __DIR__, $lang);
    }
}
