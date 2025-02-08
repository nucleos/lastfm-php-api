<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\LastFm\Model;

use DateTimeImmutable;

final class Event
{
    private int $eventId;

    private string $title;

    private DateTimeImmutable $eventDate;

    private string $url;

    private ?Venue $venue;

    public function __construct(int $eventId, string $title, DateTimeImmutable $eventDate, string $url, ?Venue $venue)
    {
        $this->eventId   = $eventId;
        $this->title     = $title;
        $this->eventDate = $eventDate;
        $this->url       = $url;
        $this->venue     = $venue;
    }

    public function getEventId(): int
    {
        return $this->eventId;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getEventDate(): DateTimeImmutable
    {
        return $this->eventDate;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getVenue(): ?Venue
    {
        return $this->venue;
    }
}
