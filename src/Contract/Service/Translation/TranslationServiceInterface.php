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

namespace Contract\Service\Translation;

interface TranslationServiceInterface
{
    /**
     * @psalm-param non-empty-string $key
     */
    public function translate(string $key): string;

    /**
     * @psalm-param non-empty-string $lang
     */
    public function setLanguage(string $lang): void;
}
