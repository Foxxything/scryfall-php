<?php

declare(strict_types=1);

namespace Foxxything\Scryfall\Model;

final readonly class List_
{
    /** @param array<int, mixed> $data */
    public function __construct(
        public array $data,
        public bool $hasMore,
        public ?string $nextPage,
        public ?int $totalCards,
        public ?array $warnings,
    ) {}

    /**
     * @param array $raw The raw decoded JSON response
     * @param callable $mapItem Maps each entry in `data` to a typed object, e.g. Card::fromArray(...)
     */
    public static function fromArray(array $raw, callable $mapItem): self
    {
        return new self(
            data: array_map($mapItem, $raw['data'] ?? []),
            hasMore: $raw['has_more'] ?? false,
            nextPage: $raw['next_page'] ?? null,
            totalCards: $raw['total_cards'] ?? null,
            warnings: $raw['warnings'] ?? null,
        );
    }

    public function isEmpty(): bool
    {
        return $this->data === [];
    }

    public function count(): int
    {
        return count($this->data);
    }
}