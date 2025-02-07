<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\LastFm\Model;


final class Tag
{
    private string $name;

    private ?string $url;

    private ?int $count;

    public function __construct(string $name, ?string $url, ?int $count)
    {
        $this->name  = $name;
        $this->url   = $url;
        $this->count = $count;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function getCount(): ?int
    {
        return $this->count;
    }

    public static function fromApi(array $data): self
    {
        return new self(
            $data['name'],
            $data['url'] ?? null,
            isset($data['count']) ? (int) $data['count'] : null
        );
    }
}
