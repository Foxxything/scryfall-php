# Scryfall PHP

A typed PHP wrapper around the Scryfall REST API (https://scryfall.com/docs/api). Maps raw JSON responses into typed objects instead of arrays.

## Requirements

- PHP 8.3+
- Composer

## Installation

```bash
composer install
```

## Usage

```php
require __DIR__ . '/vendor/autoload.php';

use Foxxything\Scryfall\ScryfallClient;

$scryfall = new ScryfallClient('MyApp/1.0 (you@example.com)');

// Find a card by exact name
$card = $scryfall->findCardByName('Lightning Bolt');

echo $card->name;        // "Lightning Bolt"
echo $card->manaCost;    // "{R}"
echo $card->oracleText;  // "Lightning Bolt deals 3 damage to any target."

// Search for cards
$results = $scryfall->search('c:red t:instant cmc<=1');

foreach ($results->data as $card) {
    echo $card->name . PHP_EOL;
}
```

Scryfall requires a descriptive User-Agent with contact info on every request. Pass yours to the constructor.

### Mana costs

Mana cost strings can be parsed into structured data:

```php
$cost = $card->parsedManaCost();

$cost->symbols;             // Symbol[], every symbol in the cost
$cost->isHybrid();          // bool
$cost->isPhyrexian();       // bool
$cost->hasVariableCost();   // bool, true if the cost includes X/Y/Z
$cost->colors();            // Color[], colors required to pay this cost
```

Use `$card->cmc` for mana value. Don't derive it from the parsed cost. Hybrid, Phyrexian, and X costs have MTG-specific mana value rules that Scryfall already computes correctly.

### Multi-faced cards

Cards with multiple faces (transform, MDFC, split, adventure) expose their faces through `cardFaces`:

```php
if ($card->isMultiFaced()) {
    foreach ($card->cardFaces as $face) {
        echo $face->name . ': ' . $face->manaCost . PHP_EOL;
    }
}
```

### Format legality

```php
if ($card->isLegalIn('commander')) {
    // ...
}
```

## Project structure

```
src/
├── ScryfallClient.php   entry point
├── Http/
│   └── HttpClient.php   Guzzle-based client with request throttling
├── Model/
│   ├── Card.php
│   ├── CardFace.php
│   ├── ImageUris.php
│   ├── Preview.php
│   ├── RelatedCardObject.php
│   └── List_.php         generic paginated result wrapper
├── Enum/
│   ├── Color.php
│   ├── Rarity.php
│   ├── Legality.php
│   └── Symbol.php         every Scryfall mana/card symbol
├── ValueObject/
│   └── ManaCost.php       parses raw mana cost strings
└── Exception/
    ├── ScryfallException.php
    ├── NotFoundException.php
    └── RateLimitException.php
```

## Testing

```bash
vendor/bin/phpunit
```

Tests run against fixture files in `tests/Fixtures/`, captured from real Scryfall responses. No network access required to run the suite.

## Status

Personal project, under active development. Not published as a Composer package.

Implemented: card lookup by name, card search, mana cost parsing.

Not yet implemented: sets, rulings, bulk data, catalogs.