<?php

declare(strict_types=1);

namespace Foxxything\Scryfall\Http;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Foxxything\Scryfall\Exception\NotFoundException;
use Foxxything\Scryfall\Exception\RateLimitException;
use Foxxything\Scryfall\Exception\ScryfallException;

final class HttpClient
{
    private Client $guzzle;
    private float $lastRequestAt = 0.0;

    public function __construct(string $userAgent)
    {
        $this->guzzle = new Client([
            'base_uri' => 'https://api.scryfall.com',
            'headers' => [
                'User-Agent' => $userAgent,
                'Accept' => 'application/json',
            ],
            'timeout' => 10,
        ]);
    }

    /** @return array<string, mixed> */
    public function get(string $path, array $query = []): array
    {
        $this->throttle();

        try {
            $response = $this->guzzle->get($path, [
                'query' => array_filter($query, fn ($v) => $v !== null),
            ]);
        } catch (ClientException $e) {
            throw $this->mapError($e);
        }

        return json_decode((string) $response->getBody(), true, flags: JSON_THROW_ON_ERROR);
    }

    private function throttle(): void
    {
        $elapsed = microtime(true) - $this->lastRequestAt;
        if ($elapsed < 0.1) {
            usleep((int) ((0.1 - $elapsed) * 1_000_000));
        }
        $this->lastRequestAt = microtime(true);
    }

    private function mapError(ClientException $e): ScryfallException
    {
        $status = $e->getResponse()->getStatusCode();
        $body = json_decode((string) $e->getResponse()->getBody(), true);
        $details = $body['details'] ?? $e->getMessage();

        return match ($status) {
            404 => new NotFoundException($details),
            429 => new RateLimitException($details),
            default => new ScryfallException($details),
        };
    }
}