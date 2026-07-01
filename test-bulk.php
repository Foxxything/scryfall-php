<?php
require __DIR__ . '/vendor/autoload.php';

use Foxxything\Scryfall\Http\BulkDataDownloader;
use Foxxything\Scryfall\ScryfallClient;

$scryfall = new ScryfallClient('MyDeckTracker/0.1 (foxx@foxxything.com)');

// See what's available
$bulkFiles = $scryfall->bulkData();
foreach ($bulkFiles->data as $file) {
    echo "{$file->type}: {$file->size} bytes, updated {$file->updatedAt}\n";
}

// Fetch metadata for a specific type
$oracleCards = $scryfall->bulkDataByType('oracle_cards');

echo $oracleCards->downloadUri; // e.g. https://data.scryfall.io/oracle-cards/oracle-cards-....json
echo $oracleCards->size;        // bytes

// Actually download it
$downloader = new BulkDataDownloader();
$downloader->downloadToFile($oracleCards->downloadUri, __DIR__ . '/oracle-cards.json');