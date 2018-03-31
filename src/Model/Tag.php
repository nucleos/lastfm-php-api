<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\LastFm\Model;

final class Tag
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string|null
     */
    private $url;

    /**
     * @var int|null
     */
    private $count;

    /**
     * Tag constructor.
     *
     * @param string      $name
     * @param null|string $url
     * @param int|null    $count
     */
    public function __construct(string $name, ?string $url, ?int $count)
    {
        $this->name  = $name;
        $this->url   = $url;
        $this->count = $count;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return null|string
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * @param array $data
     *
     * @return Tag
     */
    public static function fromApi(array $data): self
    {
        return new self(
            $data['name'],
            $data['url'] ?? null,
            isset($data['count']) ? (int) $data['count'] : null
        );
    }
}
