<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\LastFm\Service;

use Core23\LastFm\Exception\ApiException;
use Core23\LastFm\Exception\NotFoundException;
use Core23\LastFm\Model\Artist;
use Core23\LastFm\Model\Song;

final class GeoService extends AbstractService
{
    /**
     * Get the most popular artists on Last.fm by country.
     *
     * @param string $country
     * @param int    $limit
     * @param int    $page
     *
     * @throws ApiException
     * @throws NotFoundException
     *
     * @return Artist[]
     */
    public function getTopArtists(string $country, int $limit = 50, int $page = 1): array
    {
        $response = $this->unsignedCall('geo.getTopArtists', [
            'country' => $country,
            'limit'   => $limit,
            'page'    => $page,
        ]);

        if (!isset($response['topartists']['artist'])) {
            return [];
        }

        return array_map(function ($data) {
            return Artist::fromApi($data);
        }, $response['topartists']['artist']);
    }

    /**
     * Get the most popular tracks on Last.fm last week by country.
     *
     * @param string $country
     * @param string $location
     * @param int    $limit
     * @param int    $page
     *
     * @throws ApiException
     * @throws NotFoundException
     *
     * @return Song[]
     */
    public function getTopTracks(string $country, string $location = null, $limit = 50, int $page = 1): array
    {
        $response = $this->unsignedCall('geo.getTopTracks', [
            'country'  => $country,
            'location' => $location,
            'limit'    => $limit,
            'page'     => $page,
        ]);

        if (!isset($response['tracks']['track'])) {
            return [];
        }

        return array_map(function ($data) {
            return Song::fromApi($data);
        }, $response['tracks']['track']);
    }
}
