<?php

declare(strict_types=1);

namespace Foxxything\Scryfall\Http;

use GuzzleHttp\Client;

final class BulkDataDownloader
{
    private Client $guzzle;

    public function __construct()
    {
        $this->guzzle = new Client();
    }

    /**
     * Downloads a bulk data file directly to disk, streaming rather than
     * loading the whole response into memory.
     */
    public function downloadToFile(string $downloadUri, string $destinationPath): void
    {
        $this->guzzle->get($downloadUri, [
            'sink' => $destinationPath,
        ]);
    }
}