<?php

require __DIR__ . '/vendor/autoload.php';

use Foxxything\Scryfall\ScryfallClient;

$scryfall = new ScryfallClient('MyDeckTracker/0.1 (foxx@foxxything.com)');

$card = $scryfall->findCardByName('Lightning Bolt');

echo $card->name . "\n";
echo $card->typeLine . "\n";
echo $card->oracleText . "\n";