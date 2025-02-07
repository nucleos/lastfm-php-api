<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\LastFm\Service;

use Nucleos\LastFm\Client\ApiClientInterface;
use Nucleos\LastFm\Model\Album;
use Nucleos\LastFm\Model\Artist;
use Nucleos\LastFm\Model\Chart;
use Nucleos\LastFm\Model\Song;
use Nucleos\LastFm\Model\Tag;
use Nucleos\LastFm\Model\TagInfo;
use Nucleos\LastFm\Util\ApiHelper;

final class TagService implements TagServiceInterface
{
    private ApiClientInterface $client;

    public function __construct(ApiClientInterface $client)
    {
        $this->client = $client;
    }

    public function getInfo(string $tag, ?string $lang = null): ?TagInfo
    {
        $response = $this->client->unsignedCall('tag.getInfo', [
            'tag'  => $tag,
            'lang' => $lang,
        ]);

        if (!isset($response['tag'])) {
            return null;
        }

        return TagInfo::fromApi($response['tag']);
    }

    public function getSimilar(string $tag): array
    {
        $response = $this->client->unsignedCall('tag.getSimilar', [
            'tag' => $tag,
        ]);

        if (!isset($response['similartags']['tag'])) {
            return [];
        }

        return ApiHelper::mapList(
            static function (array $data): Tag {
                return Tag::fromApi($data);
            },
            $response['similartags']['tag']
        );
    }

    public function getTopAlbums(string $tag, int $limit = 50, int $page = 1): array
    {
        $response = $this->client->unsignedCall('tag.getTopAlbums', [
            'tag'   => $tag,
            'limit' => $limit,
            'page'  => $page,
        ]);

        if (!isset($response['albums']['album'])) {
            return [];
        }

        return ApiHelper::mapList(
            static function (array $data): Album {
                return Album::fromApi($data);
            },
            $response['albums']['album']
        );
    }

    public function getTopArtists(string $tag, int $limit = 50, int $page = 1): array
    {
        $response = $this->client->unsignedCall('tag.getTopArtists', [
            'tag'   => $tag,
            'limit' => $limit,
            'page'  => $page,
        ]);

        if (!isset($response['topartists']['artist'])) {
            return [];
        }

        return ApiHelper::mapList(
            static function (array $data): Artist {
                return Artist::fromApi($data);
            },
            $response['topartists']['artist']
        );
    }

    public function getTopTags(): array
    {
        $response = $this->client->unsignedCall('tag.getTopTags');

        if (!isset($response['toptags']['tag'])) {
            return [];
        }

        return ApiHelper::mapList(
            static function (array $data): Tag {
                return Tag::fromApi($data);
            },
            $response['toptags']['tag']
        );
    }

    public function getTopTracks(string $tag, int $limit = 50, int $page = 1): array
    {
        $response = $this->client->unsignedCall('tag.getTopTracks', [
            'tag'   => $tag,
            'limit' => $limit,
            'page'  => $page,
        ]);

        if (!isset($response['tracks']['track'])) {
            return [];
        }

        return ApiHelper::mapList(
            static function (array $data): Song {
                return Song::fromApi($data);
            },
            $response['tracks']['track']
        );
    }

    public function getWeeklyChartList(string $tag): array
    {
        $response = $this->client->unsignedCall('tag.getWeeklyChartList', [
            'tag' => $tag,
        ]);

        if (!isset($response['weeklychartlist']['chart'])) {
            return [];
        }

        return ApiHelper::mapList(
            static function (array $data): Chart {
                return Chart::fromApi($data);
            },
            $response['weeklychartlist']['chart']
        );
    }
}
