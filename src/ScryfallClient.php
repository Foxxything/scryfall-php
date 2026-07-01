<?php
// src/ScryfallClient.php
declare(strict_types=1);

namespace Foxxything\Scryfall;

use Foxxything\Scryfall\Http\HttpClient;
use Foxxything\Scryfall\Model\Card;
use Foxxything\Scryfall\Model\List_;

final class ScryfallClient
{
    private HttpClient $http;

    public function __construct(string $userAgent = 'ScryfallPHP/0.1')
    {
        $this->http = new HttpClient($userAgent);
    }

    public function findCardByName(string $name, bool $fuzzy = false): Card
    {
        $data = $this->http->get('/cards/named', [
            $fuzzy ? 'fuzzy' : 'exact' => $name,
        ]);

        return Card::fromArray($data);
    }

    public function search(string $query, int $page = 1): List_
    {
        $data = $this->http->get('/cards/search', [
            'q' => $query,
            'page' => $page,
        ]);

        return List_::fromArray($data, Card::fromArray(...));
    }
}