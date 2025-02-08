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

/**
 * @SuppressWarnings("PHPMD.ExcessiveParameterList")
 */
final class EventInfo
{
    private int $eventId;

    private string $title;

    private ?string $description;

    private ?DateTimeImmutable $eventDate;

    private ?DateTimeImmutable $eventEndDate;

    private ?string $eventWebsite;

    private ?Image $image;

    private ?string $url;

    private bool $festival;

    private Venue $venue;

    /**
     * @var Artist[]
     */
    private array $artists;

    /**
     * @param Artist[] $artists
     */
    public function __construct(
        int $eventId,
        string $title,
        ?string $description,
        ?DateTimeImmutable $eventDate,
        ?DateTimeImmutable $eventEndDate,
        ?string $eventWebsite,
        ?Image $image,
        ?string $url,
        bool $festival,
        Venue $venue,
        array $artists
    ) {
        $this->eventId      = $eventId;
        $this->title        = $title;
        $this->description  = $description;
        $this->eventDate    = $eventDate;
        $this->eventEndDate = $eventEndDate;
        $this->eventWebsite = $eventWebsite;
        $this->image        = $image;
        $this->url          = $url;
        $this->festival     = $festival;
        $this->venue        = $venue;
        $this->artists      = $artists;
    }

    public function getEventId(): int
    {
        return $this->eventId;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getEventDate(): ?DateTimeImmutable
    {
        return $this->eventDate;
    }

    public function getEventEndDate(): ?DateTimeImmutable
    {
        return $this->eventEndDate;
    }

    public function getEventWebsite(): ?string
    {
        return $this->eventWebsite;
    }

    public function getImage(): ?Image
    {
        return $this->image;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function isFestival(): bool
    {
        return $this->festival;
    }

    public function getVenue(): Venue
    {
        return $this->venue;
    }

    /**
     * @return Artist[]
     */
    public function getArtists(): array
    {
        return $this->artists;
    }
}
