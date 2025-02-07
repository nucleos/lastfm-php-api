<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\LastFm\Model;


final class Venue
{
    private string $name;

    private ?string $url;

    private ?string $telephone;

    private VenueAddress $address;

    public function __construct(string $name, ?string $url, ?string $telephone, VenueAddress $address)
    {
        $this->name      = $name;
        $this->url       = $url;
        $this->telephone = $telephone;
        $this->address   = $address;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function getAddress(): VenueAddress
    {
        return $this->address;
    }
}
