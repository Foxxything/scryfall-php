<?php

declare(strict_types=1);

namespace Foxxything\Scryfall\Tests\Unit\Model;

use Foxxything\Scryfall\Enum\Color;
use Foxxything\Scryfall\Enum\Legality;
use Foxxything\Scryfall\Enum\Rarity;
use Foxxything\Scryfall\Enum\Symbol;
use Foxxything\Scryfall\Model\Card;
use PHPUnit\Framework\TestCase;

final class CardTest extends TestCase
{
    private function loadFixture(string $name): array
    {
        $path = __DIR__ . '/../../Fixtures/' . $name;
        $this->assertFileExists($path, "Fixture missing: {$name}");

        $raw = json_decode(file_get_contents($path), true);
        $this->assertIsArray($raw, "Fixture did not contain valid JSON: {$name}");

        return $raw;
    }

    public function testMapsSingleFacedCardFromArray(): void
    {
        $card = Card::fromArray($this->loadFixture('card-lightning-bolt.json'));

        $this->assertSame('Lightning Bolt', $card->name);
        $this->assertSame('{R}', $card->manaCost);
        $this->assertSame('Instant', $card->typeLine);
        $this->assertFalse($card->isMultiFaced());
        $this->assertNull($card->cardFaces);
    }

    public function testTcgplayerIdIsMappedAsInt(): void
    {
        $card = Card::fromArray($this->loadFixture('card-lightning-bolt.json'));

        // Regression test for the int/string TypeError hit earlier
        $this->assertTrue($card->tcgplayerId === null || is_int($card->tcgplayerId));
    }

    public function testColorsAreMappedToColorEnum(): void
    {
        $card = Card::fromArray($this->loadFixture('card-lightning-bolt.json'));

        $this->assertContainsOnlyInstancesOf(Color::class, $card->colors);
        $this->assertContainsOnlyInstancesOf(Color::class, $card->colorIdentity);
        $this->assertContains(Color::Red, $card->colors);
        $this->assertContains(Color::Red, $card->colorIdentity);
    }

    public function testRarityIsMappedToRarityEnum(): void
    {
        $card = Card::fromArray($this->loadFixture('card-lightning-bolt.json'));

        $this->assertInstanceOf(Rarity::class, $card->rarity);
    }

    public function testLegalitiesAreMappedToLegalityEnumKeepingFormatKeys(): void
    {
        $card = Card::fromArray($this->loadFixture('card-lightning-bolt.json'));

        // Keys stay as raw format strings (commander, modern, pauper, ...)
        $this->assertArrayHasKey('commander', $card->legalities);

        // Values are converted to the Legality enum
        $this->assertContainsOnlyInstancesOf(Legality::class, $card->legalities);
    }

    public function testIsLegalInHelperReturnsTrueForLegalFormat(): void
    {
        $card = Card::fromArray($this->loadFixture('card-lightning-bolt.json'));

        // Lightning Bolt is legal in Commander
        $this->assertTrue($card->isLegalIn('commander'));
    }

    public function testIsLegalInHelperReturnsFalseForUnknownFormat(): void
    {
        $card = Card::fromArray($this->loadFixture('card-lightning-bolt.json'));

        $this->assertFalse($card->isLegalIn('made-up-format'));
    }

    public function testMapsMultiFacedCardWithCardFaces(): void
    {
        $card = Card::fromArray($this->loadFixture('card-spikefield-hazard.json'));

        $this->assertTrue($card->isMultiFaced());
        $this->assertCount(2, $card->cardFaces);
        $this->assertNotEmpty($card->cardFaces[0]->manaCost);
    }

    public function testParsedManaCostWorksFromRealCardFixture(): void
    {
        $raw = json_decode(
            file_get_contents(__DIR__ . '/../../Fixtures/card-lightning-bolt.json'),
            true
        );
        $card = \Foxxything\Scryfall\Model\Card::fromArray($raw);

        $cost = $card->parsedManaCost();

        $this->assertSame([Symbol::Red], $cost->symbols);
        $this->assertSame([Color::Red], $cost->colors());
    }
}