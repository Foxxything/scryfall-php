<?php

declare(strict_types=1);

namespace Foxxything\Scryfall\Tests\Unit\Enum;

use Foxxything\Scryfall\Enum\Legality;
use PHPUnit\Framework\TestCase;

final class LegalityTest extends TestCase
{
    public function testAllScryfallStatusesMapCleanly(): void
    {
        // These are Scryfall's exact documented values — if this fails,
        // the enum's backing strings don't match what the API sends.
        $this->assertSame(Legality::Legal, Legality::from('legal'));
        $this->assertSame(Legality::NotLegal, Legality::from('not_legal'));
        $this->assertSame(Legality::Restricted, Legality::from('restricted'));
        $this->assertSame(Legality::Banned, Legality::from('banned'));
    }
}