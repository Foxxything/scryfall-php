<?php

declare(strict_types=1);

namespace Foxxything\Scryfall\Enum;

enum Color: string
{
    case White = 'W';
    case Blue = 'U';
    case Black = 'B';
    case Red = 'R';
    case Green = 'G';
    case Colorless = 'C';

    public function label(): string
    {
        return match ($this) {
            self::White => 'White',
            self::Blue => 'Blue',
            self::Black => 'Black',
            self::Red => 'Red',
            self::Green => 'Green',
            self::Colorless => 'Colorless',
        };
    }

    /** @param array<int, string> $codes e.g. ['R', 'U'] from Scryfall's raw colors array */
    public static function fromArray(array $codes): array
    {
        return array_map(fn (string $code) => self::from($code), $codes);
    }
}