<?php

declare(strict_types=1);

namespace Foxxything\Scryfall\Tests\Unit\Enum;

use Foxxything\Scryfall\Enum\Color;
use PHPUnit\Framework\TestCase;

final class ColorTest extends TestCase
{
    public function testFromArrayMapsCodesToCases(): void
    {
        $colors = Color::fromArray(['R', 'U']);

        $this->assertSame([Color::Red, Color::Blue], $colors);
    }

    public function testFromArrayWithEmptyArrayReturnsEmptyArray(): void
    {
        $this->assertSame([], Color::fromArray([]));
    }

    public function testLabelReturnsHumanReadableName(): void
    {
        $this->assertSame('Red', Color::Red->label());
    }

    public function testInvalidColorCodeThrows(): void
    {
        $this->expectException(\ValueError::class);
        Color::from('X');
    }
}