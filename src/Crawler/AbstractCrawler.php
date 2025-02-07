<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\LastFm\Crawler;

use DateTimeImmutable;
use Exception;
use Nucleos\LastFm\Connection\ConnectionInterface;
use Nucleos\LastFm\Exception\CrawlException;
use Nucleos\LastFm\Model\Event;
use Nucleos\LastFm\Model\Image;
use Nucleos\LastFm\Model\Venue;
use Nucleos\LastFm\Model\VenueAddress;
use Symfony\Component\DomCrawler\Crawler;

abstract class AbstractCrawler
{
    public const URL_PREFIX = 'http://last.fm';

    public const NEWLINE = "\n";

    private ConnectionInterface $connection;

    public function __construct(ConnectionInterface $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Crawles a url.
     */
    final protected function crawl(string $url, array $params = []): ?Crawler
    {
        if (null !== $content = $this->connection->getPageBody($url, $params)) {
            return new Crawler($content);
        }

        return null;
    }

    final protected function parseEvent(Crawler $node, ?DateTimeImmutable $datetime = null): Event
    {
        $eventNode = $node->filter('.events-list-item-event--title a');

        $url = $this->parseUrl($eventNode);

        if (null === $url) {
            throw new CrawlException('Error parsing event id.');
        }

        $id = (int) preg_replace('/.*\/(\d+)+.*/', '$1', $url);

        if (0 === $id) {
            throw new CrawlException('Error parsing event id.');
        }

        if (null === $datetime) {
            $datetime = $this->parseTime($node);
        }

        $venue = $this->parseVenue($node->filter('.events-list-item-venue'));

        return new Event(
            $id,
            $this->parseString($eventNode) ?? '',
            $datetime,
            $url,
            $venue
        );
    }

    final protected function parseVenue(Crawler $node): ?Venue
    {
        $title   = $this->parseString($node->filter('.events-list-item-venue--title'));

        if (null === $title) {
            return null;
        }

        $city    = $this->parseString($node->filter('.events-list-item-venue--city'));
        $country = $this->parseString($node->filter('.events-list-item-venue--country'));

        return new Venue($title, null, null, new VenueAddress(
            null,
            null,
            $city,
            $country
        ));
    }

    /**
     * Parses a url node.
     */
    final protected function parseUrl(Crawler $node, string $attr = 'href'): ?string
    {
        if (0 === $node->count()) {
            return null;
        }

        if (null !== $url = $node->attr($attr)) {
            return preg_replace('/^\//', static::URL_PREFIX.'/', $url);
        }

        return null;
    }

    /**
     * Parses an image node.
     */
    final protected function parseImage(Crawler $node): ?Image
    {
        $src = $this->parseUrl($node, 'src');

        if (null === $src) {
            return null;
        }

        return new Image($src);
    }

    /**
     * Parses a string node.
     */
    final protected function parseString(Crawler $node, bool $multiline = false): ?string
    {
        if (0 === $node->count()) {
            return null;
        }

        $content = $node->attr('content');

        if (null === $content) {
            if ($multiline) {
                $content = $node->html();
                $content = (string) preg_replace('/<p[^>]*?>/', '', $content);
                $content = str_replace('</p>', static::NEWLINE, $content);
                $content = (string) preg_replace('/<br\s?\/?>/i', static::NEWLINE, $content);
            } else {
                $content = $node->text();
            }
        }

        return trim(strip_tags($content));
    }

    /**
     * Parses a date note.
     */
    final protected function parseDate(Crawler $node): ?DateTimeImmutable
    {
        $content = $this->parseString($node);

        if (null !== $content) {
            return new DateTimeImmutable($content);
        }

        return null;
    }

    /**
     * @throws CrawlException
     */
    private function parseTime(Crawler $node): DateTimeImmutable
    {
        try {
            $dateAttr = $node->filter('time')->attr('datetime');

            if (null === $dateAttr) {
                throw new CrawlException('Date information cannot be found');
            }

            $datetime = new DateTimeImmutable($dateAttr);
        } catch (Exception $exception) {
            throw new CrawlException('Error reading event date', (int) $exception->getCode(), $exception);
        }

        return $datetime;
    }
}
