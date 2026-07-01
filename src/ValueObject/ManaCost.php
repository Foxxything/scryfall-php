<?php

declare(strict_types=1);

namespace Foxxything\Scryfall\ValueObject;

use Foxxything\Scryfall\Enum\Color;
use Foxxything\Scryfall\Enum\Symbol;

final readonly class ManaCost
{
    /** @param Symbol[] $symbols */
    private function __construct(
        public string $raw,
        public array $symbols,
    ) {}

    public static function parse(string $raw): self
    {
        // Matches each {...} token: {2}, {R}, {W/U}, {G/W/P}, {X}, etc.
        preg_match_all('/\{([^}]+)\}/', $raw, $matches);

        $symbols = array_map(
            fn (string $token) => Symbol::tryFrom('{' . $token . '}'),
            $matches[1]
        );

        // Drop any tokens that didn't match a known Symbol case
        // (e.g. a brand-new symbol Scryfall added before this enum was updated)
        $symbols = array_values(array_filter($symbols));

        return new self($raw, $symbols);
    }

    public function hasVariableCost(): bool
    {
        return $this->containsAny(Symbol::X, Symbol::Y, Symbol::Z);
    }

    public function isHybrid(): bool
    {
        foreach ($this->symbols as $symbol) {
            if (str_contains($symbol->value, '/')) {
                return true;
            }
        }

        return false;
    }

    public function isPhyrexian(): bool
    {
        foreach ($this->symbols as $symbol) {
            if (str_ends_with($symbol->value, '/P}')) {
                return true;
            }
        }

        return false;
    }

    private function containsAny(Symbol ...$targets): bool
    {
        foreach ($this->symbols as $symbol) {
            if (in_array($symbol, $targets, true)) {
                return true;
            }
        }

        return false;
    }

    /** Derives which Colors this cost requires (for color-identity style checks). */
    public function colors(): array
    {
        $colors = [];

        foreach ($this->symbols as $symbol) {
            $mapped = match ($symbol) {
                Symbol::White, Symbol::WhitePhyrexian => [Color::White],
                Symbol::Blue, Symbol::BluePhyrexian => [Color::Blue],
                Symbol::Black, Symbol::BlackPhyrexian => [Color::Black],
                Symbol::Red, Symbol::RedPhyrexian => [Color::Red],
                Symbol::Green, Symbol::GreenPhyrexian => [Color::Green],

                Symbol::WhiteBlue => [Color::White, Color::Blue],
                Symbol::WhiteBlack => [Color::White, Color::Black],
                Symbol::BlackRed => [Color::Black, Color::Red],
                Symbol::BlackGreen => [Color::Black, Color::Green],
                Symbol::BlueBlack => [Color::Blue, Color::Black],
                Symbol::BlueRed => [Color::Blue, Color::Red],
                Symbol::RedGreen => [Color::Red, Color::Green],
                Symbol::RedWhite => [Color::Red, Color::White],
                Symbol::GreenWhite => [Color::Green, Color::White],
                Symbol::GreenBlue => [Color::Green, Color::Blue],

                // Colorless hybrid — {C/W}, {C/U}, etc.
                Symbol::ColorlessWhite => [Color::White],
                Symbol::ColorlessBlue => [Color::Blue],
                Symbol::ColorlessBlack => [Color::Black],
                Symbol::ColorlessRed => [Color::Red],
                Symbol::ColorlessGreen => [Color::Green],

                // Generic-or-color hybrid — {2/W}, {2/U}, etc. (this was missing)
                Symbol::TwoOrWhite => [Color::White],
                Symbol::TwoOrBlue => [Color::Blue],
                Symbol::TwoOrBlack => [Color::Black],
                Symbol::TwoOrRed => [Color::Red],
                Symbol::TwoOrGreen => [Color::Green],

                default => [],
            };

            foreach ($mapped as $color) {
                $colors[$color->value] = $color;
            }
        }

        return array_values($colors);
    }

    public function __toString(): string
    {
        return $this->raw;
    }
}