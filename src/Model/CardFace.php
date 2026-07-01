<?php

declare(strict_types=1);

namespace Foxxything\Scryfall\Model;

final readonly class CardFace
{
    public function __construct(
        public string $name,
        public string $manaCost,
        public ?string $typeLine,
        public ?string $oracleText,
        public ?array $colors,
        public ?array $colorIndicator,
        public ?string $power,
        public ?string $toughness,
        public ?string $loyalty,
        public ?string $defense,
        public ?string $flavorText,
        public ?string $printedName,
        public ?string $printedText,
        public ?string $printedTypeLine,
        public ?string $watermark,
        public ?string $artistName,
        public ?string $artistId,
        public ?string $illustrationId,
        public ?ImageUris $imageUris,
        public ?string $oracleId,   // only present on reversible_card layout faces
        public ?float $cmc,          // only present on reversible_card layout faces
        public ?string $layout,      // only present on reversible_card layout faces
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            manaCost: $data['mana_cost'] ?? '',
            typeLine: $data['type_line'] ?? null,
            oracleText: $data['oracle_text'] ?? null,
            colors: $data['colors'] ?? null,
            colorIndicator: $data['color_indicator'] ?? null,
            power: $data['power'] ?? null,
            toughness: $data['toughness'] ?? null,
            loyalty: $data['loyalty'] ?? null,
            defense: $data['defense'] ?? null,
            flavorText: $data['flavor_text'] ?? null,
            printedName: $data['printed_name'] ?? null,
            printedText: $data['printed_text'] ?? null,
            printedTypeLine: $data['printed_type_line'] ?? null,
            watermark: $data['watermark'] ?? null,
            artistName: $data['artist'] ?? null,
            artistId: $data['artist_id'] ?? null,
            illustrationId: $data['illustration_id'] ?? null,
            imageUris: isset($data['image_uris']) ? ImageUris::fromArray($data['image_uris']) : null,
            oracleId: $data['oracle_id'] ?? null,
            cmc: isset($data['cmc']) ? (float) $data['cmc'] : null,
            layout: $data['layout'] ?? null,
        );
    }
}