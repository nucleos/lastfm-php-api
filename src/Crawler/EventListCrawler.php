<?php

/*
 * This file is part of the ni-ju-san CMS.
 *
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\LastFm\Crawler;

use Symfony\Component\DomCrawler\Crawler;

final class EventListCrawler extends AbstractCrawler
{
    /**
     * @param string $username
     *
     * @return int[]
     */
    public function getUserYears($username)
    {
        $node = $this->crawlEventList($username);

        $years = $node->filter('.content-top .secondary-nav-item-link')->each(function (Crawler $node, $i) {
            if ($i > 0) {
                return (int) trim($node->text());
            }
        });

        sort($years);
        array_shift($years);

        return $years;
    }

    /**
     * Get all events of a user.
     *
     * @param string $username
     * @param int    $year
     * @param int    $page
     *
     * @return array
     */
    public function getEvents($username, $year, $page = 1)
    {
        $node = $this->crawlEventList($username, $year, $page);

        return $node->filter('.events-list-item')->each(function (Crawler $node, $i) {
            $eventNode = $node->filter('.events-list-item-event--title a');

            $id = preg_replace('/.*\/(\d+)+.*/', '$1', $this->parseUrl($eventNode));

            return array(
                'title'   => $this->parseString($eventNode),
                'time'    => new \DateTime($node->filter('time')->attr('datetime')),
                'eventId' => (int) $id,
            );
        });
    }

    /**
     * Gets the pages for a year.
     *
     * @param string $username
     * @param int    $year
     *
     * @return int
     */
    public function getYearPages($username, $year)
    {
        $node = $this->crawlEventList($username, $year);

        return $this->countListPages($node);
    }

    /**
     * Gets the event count for a year.
     *
     * @param string $username
     * @param int    $year
     * @param int    $page
     *
     * @return int
     */
    public function getYearCount($username, $year, $page = 1)
    {
        $node = $this->crawlEventList($username, $year, $page);

        $perPage = $this->countListEvents($node);
        $pages   = $this->countListPages($node);

        if ($pages) {
            $node  = $this->crawlEventList($username, $year, $pages);
            $count = $this->countListEvents($node);

            return ($pages - 1) * $perPage + $count;
        }

        return $perPage;
    }

    /**
     * @param Crawler $node
     *
     * @return int
     */
    private function countListPages(Crawler $node)
    {
        $pagination = $this->parseString($node->filter('.pagination .pages'));

        return $pagination ? (int) preg_replace('/.* of /', '', $pagination) : 1;
    }

    /**
     * @param Crawler $node
     *
     * @return int
     */
    private function countListEvents(Crawler $node)
    {
        return $node->filter('.events-list-item')->count();
    }

    /**
     * @param string $username
     * @param int    $year
     * @param int    $page
     *
     * @return Crawler
     */
    private function crawlEventList($username, $year = 2000, $page = 1)
    {
        $url = 'http://www.last.fm/user/'.$username.'/events/'.$year.'?page='.$page;

        return $this->crawl($url);
    }
}
