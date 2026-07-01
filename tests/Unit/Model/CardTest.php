<?php

declare(strict_types=1);

namespace Foxxything\Scryfall\Tests\Unit\Model;

use Foxxything\Scryfall\Model\Card;
use PHPUnit\Framework\TestCase;

final class CardTest extends TestCase
{
    public function testMapsSingleFacedCardFromArray(): void
    {
        $raw = json_decode(
            file_get_contents(__DIR__ . '/../../Fixtures/card-lightning-bolt.json'),
            true
        );

        $card = Card::fromArray($raw);

        $this->assertSame('Lightning Bolt', $card->name);
        $this->assertSame('{R}', $card->manaCost);
        $this->assertSame('Instant', $card->typeLine);
        $this->assertFalse($card->isMultiFaced());
        $this->assertNull($card->cardFaces);
    }

    public function testTcgplayerIdIsMappedAsInt(): void
    {
        $raw = json_decode(
            file_get_contents(__DIR__ . '/../../Fixtures/card-lightning-bolt.json'),
            true
        );

        $card = Card::fromArray($raw);

        // Regression test for the int/string TypeError you hit earlier
        $this->assertTrue($card->tcgplayerId === null || is_int($card->tcgplayerId));
    }

    public function testMapsMultiFacedCardWithCardFaces(): void
    {
        $raw = json_decode(
            file_get_contents(__DIR__ . '/../../Fixtures/card-spikefield-hazard.json'),
            true
        );

        $card = Card::fromArray($raw);

        $this->assertTrue($card->isMultiFaced());
        $this->assertCount(2, $card->cardFaces);
        $this->assertNotEmpty($card->cardFaces[0]->manaCost);
    }
}