<?php

declare(strict_types=1);

namespace Foxxything\Scryfall\Model;

final readonly class Card
{
    public function __construct(
        // Core fields
        public string $id,
        public ?string $oracleId,
        public string $lang,
        public string $layout,
        public ?int $arenaId,
        public ?int $mtgoId,
        public ?int $mtgoFoilId,
        public ?array $multiverseIds,
        public ?int $tcgplayerId,
        public ?int $tcgplayerEtchedId,
        public ?int $cardmarketId,
        public string $prizeSearchUri,
        public string $rulingsUri,
        public string $scryfallUri,
        public string $uri,

        // Gameplay fields
        public string $name,
        public ?string $manaCost,
        public float $cmc,
        public string $typeLine,
        public ?string $oracleText,
        public array $colors,
        public array $colorIdentity,
        public ?array $colorIndicator,
        public array $keywords,
        public array $legalities,
        public ?string $power,
        public ?string $toughness,
        public ?string $loyalty,
        public ?string $defense,
        public bool $reserved,
        public ?bool $gameChanger,
        public ?int $edhrecRank,
        public ?int $pennyRank,
        public ?array $producedMana,
        public ?array $allParts,       // RelatedCardObject[]
        public ?array $cardFaces,      // CardFace[]

        // Print fields
        public string $set,
        public string $setName,
        public string $setId,
        public string $setType,
        public string $collectorNumber,
        public string $rarity,
        public bool $booster,
        public bool $digital,
        public bool $fullArt,
        public bool $textless,
        public bool $oversized,
        public bool $promo,
        public bool $reprint,
        public bool $variation,
        public ?string $variationOf,
        public string $borderColor,
        public string $frame,
        public ?array $frameEffects,
        public array $finishes,
        public array $games,
        public string $imageStatus,
        public ?ImageUris $imageUris,
        public ?string $artist,
        public ?string $illustrationId,
        public ?string $flavorName,
        public ?string $flavorText,
        public ?string $watermark,
        public ?string $securityStamp,
        public bool $storySpotlight,
        public ?bool $contentWarning,
        public string $releasedAt,
        public array $prices,
        public array $relatedUris,
        public ?array $purchaseUris,
        public ?Preview $preview,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            oracleId: $data['oracle_id'] ?? null,
            lang: $data['lang'],
            layout: $data['layout'],
            arenaId: $data['arena_id'] ?? null,
            mtgoId: $data['mtgo_id'] ?? null,
            mtgoFoilId: $data['mtgo_foil_id'] ?? null,
            multiverseIds: $data['multiverse_ids'] ?? null,
            tcgplayerId: $data['tcgplayer_id'] ?? null,
            tcgplayerEtchedId: $data['tcgplayer_etched_id'] ?? null,
            cardmarketId: $data['cardmarket_id'] ?? null,
            prizeSearchUri: $data['prints_search_uri'],
            rulingsUri: $data['rulings_uri'],
            scryfallUri: $data['scryfall_uri'],
            uri: $data['uri'],

            name: $data['name'],
            manaCost: $data['mana_cost'] ?? null,
            cmc: (float) ($data['cmc'] ?? 0),
            typeLine: $data['type_line'],
            oracleText: $data['oracle_text'] ?? null,
            colors: $data['colors'] ?? [],
            colorIdentity: $data['color_identity'] ?? [],
            colorIndicator: $data['color_indicator'] ?? null,
            keywords: $data['keywords'] ?? [],
            legalities: $data['legalities'] ?? [],
            power: $data['power'] ?? null,
            toughness: $data['toughness'] ?? null,
            loyalty: $data['loyalty'] ?? null,
            defense: $data['defense'] ?? null,
            reserved: $data['reserved'] ?? false,
            gameChanger: $data['game_changer'] ?? null,
            edhrecRank: $data['edhrec_rank'] ?? null,
            pennyRank: $data['penny_rank'] ?? null,
            producedMana: $data['produced_mana'] ?? null,
            allParts: isset($data['all_parts'])
                ? array_map(RelatedCardObject::fromArray(...), $data['all_parts'])
                : null,
            cardFaces: isset($data['card_faces'])
                ? array_map(CardFace::fromArray(...), $data['card_faces'])
                : null,

            set: $data['set'],
            setName: $data['set_name'],
            setId: $data['set_id'],
            setType: $data['set_type'],
            collectorNumber: $data['collector_number'],
            rarity: $data['rarity'],
            booster: $data['booster'] ?? false,
            digital: $data['digital'] ?? false,
            fullArt: $data['full_art'] ?? false,
            textless: $data['textless'] ?? false,
            oversized: $data['oversized'] ?? false,
            promo: $data['promo'] ?? false,
            reprint: $data['reprint'] ?? false,
            variation: $data['variation'] ?? false,
            variationOf: $data['variation_of'] ?? null,
            borderColor: $data['border_color'],
            frame: $data['frame'],
            frameEffects: $data['frame_effects'] ?? null,
            finishes: $data['finishes'] ?? [],
            games: $data['games'] ?? [],
            imageStatus: $data['image_status'],
            imageUris: isset($data['image_uris']) ? ImageUris::fromArray($data['image_uris']) : null,
            artist: $data['artist'] ?? null,
            illustrationId: $data['illustration_id'] ?? null,
            flavorName: $data['flavor_name'] ?? null,
            flavorText: $data['flavor_text'] ?? null,
            watermark: $data['watermark'] ?? null,
            securityStamp: $data['security_stamp'] ?? null,
            storySpotlight: $data['story_spotlight'] ?? false,
            contentWarning: $data['content_warning'] ?? null,
            releasedAt: $data['released_at'],
            prices: $data['prices'] ?? [],
            relatedUris: $data['related_uris'] ?? [],
            purchaseUris: $data['purchase_uris'] ?? null,
            preview: Preview::fromFlatArray($data),
        );
    }

    /** True for transform, MDFC, split, flip, adventure, and other multi-faced layouts */
    public function isMultiFaced(): bool
    {
        return $this->cardFaces !== null;
    }

    /** Returns this card's primary image, falling back to the front face if multi-faced */
    public function primaryImage(string $size = 'normal'): ?string
    {
        if ($this->imageUris !== null) {
            return $this->imageUris->{$size} ?? null;
        }

        return $this->cardFaces[0]?->imageUris?->{$size} ?? null;
    }
}