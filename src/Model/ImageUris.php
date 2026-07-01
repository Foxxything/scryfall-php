<?php

declare(strict_types=1);

namespace Foxxything\Scryfall\Model;

final readonly class ImageUris
{
    public function __construct(
        public ?string $small,
        public ?string $normal,
        public ?string $large,
        public ?string $png,
        public ?string $artCrop,
        public ?string $borderCrop,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            small: $data['small'] ?? null,
            normal: $data['normal'] ?? null,
            large: $data['large'] ?? null,
            png: $data['png'] ?? null,
            artCrop: $data['art_crop'] ?? null,
            borderCrop: $data['border_crop'] ?? null,
        );
    }
}