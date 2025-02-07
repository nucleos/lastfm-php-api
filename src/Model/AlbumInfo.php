<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\LastFm\Model;

/**
 * @SuppressWarnings(PHPMD.ExcessiveParameterList)
 */
final class AlbumInfo
{
    private ?string $name;

    private ?Artist $artist;

    private ?string $mbid;

    private ?string $url;

    /**
     * @var Image[]
     */
    private array $images;

    private int $listeners;

    private int $playcount;

    /**
     * @var Song[]
     */
    private array $tracks;

    /**
     * @var Tag[]
     */
    private array $tags;

    private ?string $wikiSummary;

    /**
     * @param Image[] $images
     * @param Song[]  $tracks
     * @param Tag[]   $tags
     */
    public function __construct(
        ?string $name,
        ?Artist $artist,
        ?string $mbid,
        ?string $url,
        array $images,
        int $listeners,
        int $playcount,
        array $tracks,
        array $tags,
        ?string $wikiSummary
    ) {
        $this->name         = $name;
        $this->artist       = $artist;
        $this->mbid         = $mbid;
        $this->url          = $url;
        $this->images       = $images;
        $this->listeners    = $listeners;
        $this->playcount    = $playcount;
        $this->tracks       = $tracks;
        $this->tags         = $tags;
        $this->wikiSummary  = $wikiSummary;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getArtist(): ?Artist
    {
        return $this->artist;
    }

    public function getMbid(): ?string
    {
        return $this->mbid;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * @return Image[]
     */
    public function getImage(): array
    {
        return $this->images;
    }

    public function getListeners(): int
    {
        return $this->listeners;
    }

    public function getPlaycount(): int
    {
        return $this->playcount;
    }

    /**
     * @return Song[]
     */
    public function getTracks(): array
    {
        return $this->tracks;
    }

    /**
     * @return Tag[]
     */
    public function getTags(): array
    {
        return $this->tags;
    }

    public function getWikiSummary(): ?string
    {
        return $this->wikiSummary;
    }

    public static function fromApi(array $data): self
    {
        $images = self::createImagesFromApi($data);
        $tracks = self::createTracksFromApi($data);
        $tags   = self::createTagsFromApi($data);

        return new self(
            $data['name'],
            new Artist($data['artist'], null, [], null),
            $data['mbid'] ?? null,
            $data['url']  ?? null,
            $images,
            $data['listeners'] ?? 0,
            $data['playcount'] ?? 0,
            $tracks,
            $tags,
            $data['wiki']['summary'] ?? null
        );
    }

    private static function createImagesFromApi(array $data): array
    {
        $images = [];

        if (\array_key_exists('image', $data)) {
            foreach ((array) $data['image'] as $image) {
                $images[] = new Image($image['#text']);
            }
        }

        return $images;
    }

    private static function createTracksFromApi(array $data): array
    {
        $tracks = [];

        if (\array_key_exists('tracks', $data) && \array_key_exists('track', $data['tracks'])) {
            foreach ((array) $data['tracks']['track'] as $track) {
                $tracks[] = Song::fromApi($track);
            }
        }

        return $tracks;
    }

    private static function createTagsFromApi(array $data): array
    {
        $tags = [];

        if (\array_key_exists('tags', $data) && \array_key_exists('tag', $data['tags'])) {
            foreach ((array) $data['tags']['tag'] as $tag) {
                $tags[] = Tag::fromApi($tag);
            }
        }

        return $tags;
    }
}
