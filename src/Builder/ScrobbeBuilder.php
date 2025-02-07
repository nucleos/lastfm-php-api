<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\LastFm\Builder;

final class ScrobbeBuilder
{
    private array $tracks;

    private function __construct()
    {
        $this->tracks = [];
    }

    public static function create(): self
    {
        return new self();
    }

    public function addTrack(TrackBuilder $builder): self
    {
        $this->tracks[] = $builder->getData();

        return $this;
    }

    public function count(): int
    {
        return \count($this->tracks);
    }

    public function getQuery(): array
    {
        $query = [];

        $tracks = $this->tracks;

        foreach ($tracks as $i => $track) {
            $keys = array_keys($track);

            foreach ($keys as $key) {
                $query[\sprintf('%s[%s]', $key, $i)] = $tracks[$i][$key];
            }
        }

        return $query;
    }
}
