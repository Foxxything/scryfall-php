<?php

declare(strict_types=1);

namespace Foxxything\Scryfall\Model;

final readonly class BulkData
{
    public function __construct(
        public string $id,
        public string $type,
        public string $updatedAt,
        public string $uri,
        public string $name,
        public string $description,
        public string $downloadUri,
        public int $size,
        public string $contentType,
        public string $contentEncoding,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            type: $data['type'],
            updatedAt: $data['updated_at'],
            uri: $data['uri'],
            name: $data['name'],
            description: $data['description'],
            downloadUri: $data['download_uri'],
            size: $data['size'],
            contentType: $data['content_type'],
            contentEncoding: $data['content_encoding'],
        );
    }
}