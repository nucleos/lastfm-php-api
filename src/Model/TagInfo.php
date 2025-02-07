<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\LastFm\Model;


final class TagInfo
{
    private string $name;

    private ?string $url;

    private int $total;

    private int $reach;

    private ?string $wikiSummary;

    public function __construct(string $name, ?string $url, int $total, int $reach, ?string $wikiSummary)
    {
        $this->name        = $name;
        $this->url         = $url;
        $this->total       = $total;
        $this->reach       = $reach;
        $this->wikiSummary = $wikiSummary;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function getTotal(): int
    {
        return $this->total;
    }

    public function getReach(): int
    {
        return $this->reach;
    }

    public function getWikiSummary(): ?string
    {
        return $this->wikiSummary;
    }

    public static function fromApi(array $data): self
    {
        return new self(
            $data['name'],
            $data['url'] ?? null,
            $data['total'] ? (int) $data['total'] : 0,
            $data['reach'] ? (int) $data['reach'] : 0,
            $data['wiki']['summary'] ?? null
        );
    }
}
