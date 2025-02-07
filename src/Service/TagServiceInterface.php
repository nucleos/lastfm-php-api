<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\LastFm\Service;

use Nucleos\LastFm\Exception\ApiException;
use Nucleos\LastFm\Exception\NotFoundException;
use Nucleos\LastFm\Model\Album;
use Nucleos\LastFm\Model\Artist;
use Nucleos\LastFm\Model\Chart;
use Nucleos\LastFm\Model\Song;
use Nucleos\LastFm\Model\Tag;
use Nucleos\LastFm\Model\TagInfo;

interface TagServiceInterface
{
    /**
     * Get the metadata for a tag on Last.fm. Includes biography.
     *
     * @throws NotFoundException
     * @throws ApiException
     */
    public function getInfo(string $tag, ?string $lang = null): ?TagInfo;

    /**
     * Search for tags similar to this one. Returns tags ranked by similarity, based on listening data.
     *
     * @return Tag[]
     *
     * @throws NotFoundException
     * @throws ApiException
     */
    public function getSimilar(string $tag): array;

    /**
     * Get the top albums tagged by this tag, ordered by tag count.
     *
     * @return Album[]
     *
     * @throws NotFoundException
     * @throws ApiException
     */
    public function getTopAlbums(string $tag, int $limit = 50, int $page = 1): array;

    /**
     * Get the top artists tagged by this tag, ordered by tag count.
     *
     * @return Artist[]
     *
     * @throws NotFoundException
     * @throws ApiException
     */
    public function getTopArtists(string $tag, int $limit = 50, int $page = 1): array;

    /**
     * Fetches the top global tags on Last.fm, sorted by popularity (number of times used).
     *
     * @return Tag[]
     *
     * @throws NotFoundException
     * @throws ApiException
     */
    public function getTopTags(): array;

    /**
     * Get the top tracks tagged by this tag, ordered by tag count.
     *
     * @return Song[]
     *
     * @throws NotFoundException
     * @throws ApiException
     */
    public function getTopTracks(string $tag, int $limit = 50, int $page = 1): array;

    /**
     * Get a list of available charts for this tag, expressed as date ranges which can be sent to the chart services.
     *
     * @return Chart[]
     *
     * @throws NotFoundException
     * @throws ApiException
     */
    public function getWeeklyChartList(string $tag): array;
}
