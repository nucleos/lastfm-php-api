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
use Nucleos\LastFm\Exception\ApiException;


final class Chart
{
    private DateTimeImmutable $from;

    private DateTimeImmutable $to;

    public function __construct(DateTimeImmutable $from, DateTimeImmutable $to)
    {
        $this->from = $from;
        $this->to   = $to;
    }

    public function getFrom(): DateTimeImmutable
    {
        return $this->from;
    }

    public function getTo(): DateTimeImmutable
    {
        return $this->to;
    }

    /**
     * @throws ApiException
     */
    public static function fromApi(array $data): self
    {
        $fromDate = DateTimeImmutable::createFromFormat('U', $data['from']);

        if (false === $fromDate) {
            throw new ApiException('Error fetching from date');
        }

        $toDate = DateTimeImmutable::createFromFormat('U', $data['to']);

        if (false === $toDate) {
            throw new ApiException('Error fetching to date');
        }

        return new self($fromDate, $toDate);
    }
}
