<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\LastFm\Model;

final class Song
{
    private string $name;

    private ?int $duration;

    private ?Artist $artist;

    public function __construct(string $name, ?int $duration, ?Artist $artist)
    {
        $this->name     = $name;
        $this->duration = $duration;
        $this->artist   = $artist;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function getArtist(): ?Artist
    {
        return $this->artist;
    }

    public static function fromApi(array $data): self
    {
        $artist = isset($data['artist']) ? Artist::fromApi($data['artist']) : null;

        return new self(
            $data['name'],
            isset($data['duration']) ? (int) $data['duration'] : null,
            $artist
        );
    }
}
