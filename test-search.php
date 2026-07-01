<?php

require __DIR__ . '/vendor/autoload.php';

use Foxxything\Scryfall\ScryfallClient;

$scryfall = new ScryfallClient('MyDeckTracker/0.1 (foxx@foxxything.com)');

$results = $scryfall->search('c:red t:instant cmc<=1');

echo "Total cards matching: {$results->totalCards}\n";
echo "Returned this page: {$results->count()}\n";
echo "Has more pages: " . ($results->hasMore ? 'yes' : 'no') . "\n";
echo str_repeat('-', 40) . "\n";

foreach ($results->data as $card) {
    echo "{$card->name} ({$card->manaCost}) - {$card->typeLine}\n";
}