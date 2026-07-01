<?php

declare(strict_types=1);

namespace Foxxything\Scryfall\Model;

final readonly class Preview
{
    public function __construct(
        public ?string $previewedAt,
        public ?string $sourceUri,
        public ?string $source,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            previewedAt: $data['previewed_at'] ?? null,
            sourceUri: $data['source_uri'] ?? null,
            source: $data['source'] ?? null,
        );
    }

    /** Handles the flattened preview.* keys Scryfall puts on the parent Card object */
    public static function fromFlatArray(array $cardData): ?self
    {
        if (!isset($cardData['preview'])) {
            return null;
        }

        return self::fromArray($cardData['preview']);
    }
}