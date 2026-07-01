<?php

declare(strict_types=1);

namespace Foxxything\Scryfall\Tests\Unit\Model;

use Foxxything\Scryfall\Model\Card;
use Foxxything\Scryfall\Model\List_;
use PHPUnit\Framework\TestCase;

final class ListTest extends TestCase
{
    public function testMapsSearchResultsIntoCards(): void
    {
        $path = __DIR__ . '/../../Fixtures/search-red-instants.json';
        $this->assertFileExists($path, 'Fixture file missing — did the curl download succeed?');

        $raw = json_decode(file_get_contents($path), true);
        $this->assertIsArray($raw, 'Fixture file did not contain valid JSON.');

        $list = List_::fromArray($raw, Card::fromArray(...));

        $this->assertGreaterThan(0, $list->count());
        $this->assertInstanceOf(Card::class, $list->data[0]);
        $this->assertContainsOnlyInstancesOf(Card::class, $list->data);
    }

    public function testEmptyListReportsIsEmpty(): void
    {
        $emptyRaw = ['object' => 'list', 'data' => [], 'has_more' => false];

        $list = List_::fromArray($emptyRaw, Card::fromArray(...));

        $this->assertTrue($list->isEmpty());
        $this->assertSame(0, $list->count());
    }
}