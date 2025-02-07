<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\LastFm\Model;


final class ArtistInfo
{
    private string $name;

    private ?string $mbid;

    /**
     * @var Image[]
     */
    private array $image;

    private ?string $url;

    private int $playcount;

    private ?string $bioSummary;

    private ?string $bioContent;

    private int $tagcount;

    /**
     * @var Tag[]
     */
    private array $tags;

    /**
     * @param Image[] $image
     * @param Tag[]   $tags
     */
    public function __construct(
        string $name,
        ?string $mbid,
        array $image,
        ?string $url,
        int $playcount,
        ?string $bioSummary,
        ?string $bioContent,
        int $tagcount,
        array $tags
    ) {
        $this->name       = $name;
        $this->mbid       = $mbid;
        $this->image      = $image;
        $this->url        = $url;
        $this->playcount  = $playcount;
        $this->bioSummary = $bioSummary;
        $this->bioContent = $bioContent;
        $this->tagcount   = $tagcount;
        $this->tags       = $tags;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getMbid(): ?string
    {
        return $this->mbid;
    }

    /**
     * @return Image[]
     */
    public function getImage(): array
    {
        return $this->image;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function getPlaycount(): int
    {
        return $this->playcount;
    }

    public function getBioSummary(): ?string
    {
        return $this->bioSummary;
    }

    public function getBioContent(): ?string
    {
        return $this->bioContent;
    }

    public function getTagcount(): int
    {
        return $this->tagcount;
    }

    /**
     * @return Tag[]
     */
    public function getTags(): array
    {
        return $this->tags;
    }

    public static function fromApi(array $data): self
    {
        $images = self::createImagesFromApi($data);
        $tags   = self::createTagsFromApi($data);

        return new self(
            $data['name'],
            $data['mbid'] ?? null,
            $images,
            $data['url'] ?? null,
            isset($data['playcount']) ? (int) $data['playcount'] : 0,
            $data['bio']['summary'] ?? null,
            $data['bio']['content'] ?? null,
            isset($data['tagcount']) ? (int) $data['tagcount'] : 0,
            $tags
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
