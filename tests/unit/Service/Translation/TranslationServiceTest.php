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

namespace unit\Service\Translation;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use Service\Translation\TranslationService;
use Webmozart\Assert\InvalidArgumentException;

#[CoversClass(TranslationService::class)]
final class TranslationServiceTest extends TestCase
{
    private TranslationService $translationService;

    public function setUp(): void
    {
        $this->translationService = new TranslationService();
    }

    #[TestDox('translate() method works correctly')]
    public function testTranslate(): void
    {
        $this->translationService->setLanguage('de');
        $this->assertSame('Methoden', $this->translationService->translate('class.methods'));
    }

    #[TestDox('translate() method fails on InvalidArgumentException')]
    public function testTranslateFailsOnInvalidArgumentException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->translationService->translate('');
    }

    #[TestDox('setLanguage() method fails on InvalidArgumentException')]
    public function testSetLanguageFailsOnInvalidArgumentException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->translationService->setLanguage('');
    }
}
