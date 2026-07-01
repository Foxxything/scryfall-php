<?php

declare(strict_types=1);

namespace Foxxything\Scryfall\Model;

final readonly class RelatedCardObject
{
    public function __construct(
        public string $id,
        public string $component, // token | meld_part | meld_result | combo_piece
        public string $name,
        public string $typeLine,
        public string $uri,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            component: $data['component'],
            name: $data['name'],
            typeLine: $data['type_line'],
            uri: $data['uri'],
        );
    }
}