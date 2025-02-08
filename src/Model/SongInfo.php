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
 * @SuppressWarnings("PHPMD.ExcessiveParameterList")
 */
final class SongInfo
{
    private string $name;

    private ?string $mbid;

    private ?int $duration;

    private ?Artist $artist;

    private int $listeners;

    private int $playcount;

    /**
     * @var Tag[]
     */
    private array $topTags;

    /**
     * @param Tag[] $topTags
     */
    public function __construct(
        string $name,
        ?string $mbid,
        ?int $duration,
        ?Artist $artist,
        int $listeners,
        int $playcount,
        array $topTags
    ) {
        $this->name      = $name;
        $this->mbid      = $mbid;
        $this->duration  = $duration;
        $this->artist    = $artist;
        $this->listeners = $listeners;
        $this->playcount = $playcount;
        $this->topTags   = $topTags;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getMbid(): ?string
    {
        return $this->mbid;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function getArtist(): ?Artist
    {
        return $this->artist;
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
     * @return Tag[]
     */
    public function getTopTags(): array
    {
        return $this->topTags;
    }

    public static function fromApi(array $data): self
    {
        $tags   = self::createTagsFromApi($data);
        $artist = self::createArtistFromApi($data);

        return new self(
            $data['name'],
            $data['mbid'] ?? null,
            isset($data['duration']) ? (int) $data['duration'] : null,
            $artist,
            isset($data['listeners']) ? (int) $data['listeners'] : 0,
            isset($data['playcount']) ? (int) $data['playcount'] : 0,
            $tags
        );
    }

    /**
     * @param array<string, mixed> $data
     */
    private static function createArtistFromApi(array $data): ?Artist
    {
        $artist = null;

        if (isset($data['artist'])) {
            if (\is_array($data['artist'])) {
                $artist = Artist::fromApi($data['artist']);
            } else {
                $artist = new Artist($data['artist'], null, [], null);
            }
        }

        return $artist;
    }

    private static function createTagsFromApi(array $data): array
    {
        $tags = [];

        if (isset($data['toptags']['tag'])) {
            foreach ((array) $data['toptags']['tag'] as $tag) {
                $tags[] = Tag::fromApi($tag);
            }
        }

        return $tags;
    }
}
