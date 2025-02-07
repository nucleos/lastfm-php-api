<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\LastFm\Model;

final class Album
{
    private ?string $name;

    private ?Artist $artist;

    private ?string $mbid;

    private ?string $url;

    /**
     * @var Image[]
     */
    private array $images;

    /**
     * @param Image[] $images
     */
    public function __construct(?string $name, ?Artist $artist, ?string $mbid, ?string $url, array $images)
    {
        $this->name   = $name;
        $this->artist = $artist;
        $this->mbid   = $mbid;
        $this->url    = $url;
        $this->images = $images;
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
    public function getImages(): array
    {
        return $this->images;
    }

    public static function fromApi(array $data): self
    {
        $images = self::createImagesFromApi($data);

        return new self(
            $data['name'],
            $data['artist'] ? Artist::fromApi($data['artist']) : null,
            $data['mbid'] ?? null,
            $data['url']  ?? null,
            $images
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
}
