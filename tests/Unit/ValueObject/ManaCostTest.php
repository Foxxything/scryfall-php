<?php

declare(strict_types=1);

namespace Foxxything\Scryfall\Tests\Unit\ValueObject;

use Foxxything\Scryfall\Enum\Color;
use Foxxything\Scryfall\Enum\Symbol;
use Foxxything\Scryfall\ValueObject\ManaCost;
use PHPUnit\Framework\TestCase;

final class ManaCostTest extends TestCase
{
    public function testParsesSimpleGenericAndColoredSymbols(): void
    {
        $cost = ManaCost::parse('{2}{R}{R}');

        $this->assertSame([Symbol::Two, Symbol::Red, Symbol::Red], $cost->symbols);
    }

    public function testParsesSingleColorSymbol(): void
    {
        $cost = ManaCost::parse('{R}');

        $this->assertSame([Symbol::Red], $cost->symbols);
    }

    public function testParsesEmptyStringToNoSymbols(): void
    {
        $cost = ManaCost::parse('');

        $this->assertSame([], $cost->symbols);
    }

    public function testParsesZeroCost(): void
    {
        $cost = ManaCost::parse('{0}');

        $this->assertSame([Symbol::Zero], $cost->symbols);
    }

    public function testHasVariableCostDetectsX(): void
    {
        $cost = ManaCost::parse('{X}{R}');

        $this->assertTrue($cost->hasVariableCost());
    }

    public function testHasVariableCostFalseWhenNoXYZ(): void
    {
        $cost = ManaCost::parse('{2}{R}{R}');

        $this->assertFalse($cost->hasVariableCost());
    }

    public function testIsHybridDetectsSlashSymbols(): void
    {
        $cost = ManaCost::parse('{R/W}{R/W}');

        $this->assertTrue($cost->isHybrid());
    }

    public function testIsHybridFalseForMonoColorCost(): void
    {
        $cost = ManaCost::parse('{2}{R}{R}');

        $this->assertFalse($cost->isHybrid());
    }

    public function testIsPhyrexianDetectsPhyrexianSymbols(): void
    {
        $cost = ManaCost::parse('{R/P}');

        $this->assertTrue($cost->isPhyrexian());
    }

    public function testIsPhyrexianFalseForNonPhyrexianHybrid(): void
    {
        $cost = ManaCost::parse('{R/W}');

        $this->assertFalse($cost->isPhyrexian());
    }

    public function testColorsReturnsSingleColorForMonoColorCost(): void
    {
        $cost = ManaCost::parse('{2}{R}{R}');

        $this->assertSame([Color::Red], $cost->colors());
    }

    public function testColorsDedupesRepeatedSymbols(): void
    {
        $cost = ManaCost::parse('{R}{R}{R}');

        // Three red pips should still yield exactly one Color::Red
        $this->assertCount(1, $cost->colors());
        $this->assertSame([Color::Red], $cost->colors());
    }

    public function testColorsReturnsBothColorsForHybridSymbol(): void
    {
        $cost = ManaCost::parse('{R/W}');

        $this->assertEqualsCanonicalizing([Color::Red, Color::White], $cost->colors());
    }

    public function testColorsReturnsBothColorsForGuildCost(): void
    {
        $cost = ManaCost::parse('{1}{U}{B}'); // Dimir-style cost

        $this->assertEqualsCanonicalizing([Color::Blue, Color::Black], $cost->colors());
    }

    public function testColorsResolvesPhyrexianSymbolToItsColor(): void
    {
        $cost = ManaCost::parse('{G/P}');

        $this->assertSame([Color::Green], $cost->colors());
    }

    public function testColorsEmptyForColorlessCost(): void
    {
        $cost = ManaCost::parse('{2}');

        $this->assertSame([], $cost->colors());
    }

    public function testToStringReturnsOriginalRawCost(): void
    {
        $cost = ManaCost::parse('{2}{R}{R}');

        $this->assertSame('{2}{R}{R}', (string) $cost);
        $this->assertSame('{2}{R}{R}', $cost->raw);
    }

    public function testUnrecognizedSymbolIsSilentlyDropped(): void
    {
        // Simulates Scryfall adding a new symbol before the enum is updated
        $cost = ManaCost::parse('{2}{NEWSYMBOL}{R}');

        $this->assertSame([Symbol::Two, Symbol::Red], $cost->symbols);
    }
}