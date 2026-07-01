<?php

require __DIR__ . '/vendor/autoload.php';

use Foxxything\Scryfall\ScryfallClient;

$scryfall = new ScryfallClient('MyDeckTracker/0.1 (foxx@foxxything.com)');

$card = $scryfall->findCardByName('Spectral Procession');

echo $card->name . PHP_EOL;
echo 'Raw mana cost: ' . $card->manaCost . PHP_EOL;
echo 'Mana value (cmc): ' . $card->cmc . PHP_EOL;

$cost = $card->parsedManaCost();

echo 'Is hybrid: ' . ($cost->isHybrid() ? 'yes' : 'no') . PHP_EOL;
echo 'Has variable cost (X/Y/Z): ' . ($cost->hasVariableCost() ? 'yes' : 'no') . PHP_EOL;

echo 'Colors required: ';
foreach ($cost->colors() as $color) {
    echo $color->label() . ' ';
}
echo PHP_EOL;