<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\LastFm\Client;

use Nucleos\LastFm\Connection\ConnectionInterface;
use Nucleos\LastFm\Exception\ApiException;
use Nucleos\LastFm\Exception\NotFoundException;
use Nucleos\LastFm\Session\SessionInterface;

final class ApiClient implements ApiClientInterface
{
    private ConnectionInterface $connection;

    private string $apiKey;

    private string $sharedSecret;

    public function __construct(ConnectionInterface $connection, string $apiKey, string $sharedSecret)
    {
        $this->connection   = $connection;
        $this->apiKey       = $apiKey;
        $this->sharedSecret = $sharedSecret;
    }

    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    public function getSharedSecret(): string
    {
        return $this->sharedSecret;
    }

    public function signedCall(string $method, array $params = [], ?SessionInterface $session = null, $requestMethod = 'GET'): array
    {
        // Call parameter
        $callParams = [
            'method'  => $method,
            'api_key' => $this->apiKey,
        ];

        // Add session key
        if (null !== $session) {
            $callParams['sk'] = $session->getKey();
        }

        $params = array_merge($callParams, $params);
        $params = $this->filterNull($params);
        $params = $this->encodeUTF8($params);

        // Sign parameter
        $params['api_sig'] = $this->signParams($params);

        return $this->call($method, $params);
    }

    public function unsignedCall(string $method, array $params = [], string $requestMethod = 'GET'): array
    {
        // Call parameter
        $callParameter = [
            'method'  => $method,
            'api_key' => $this->apiKey,
        ];

        $params = array_merge($callParameter, $params);
        $params = $this->filterNull($params);
        $params = $this->encodeUTF8($params);

        return $this->call($method, $params);
    }

    /**
     * Filter null values.
     */
    private function filterNull(array $object): array
    {
        return array_filter($object, static function ($val): bool {
            return null !== $val;
        });
    }

    /**
     * Converts any string or array of strings to UTF8.
     *
     * @param mixed|mixed[] $object a String or an array
     *
     * @return mixed|mixed[] UTF8-string or array
     */
    private function encodeUTF8($object)
    {
        if (\is_array($object)) {
            return array_map([$this, 'encodeUTF8'], $object);
        }

        return mb_convert_encoding((string) $object, 'UTF-8', 'auto');
    }

    private function signParams(array $params): string
    {
        ksort($params);

        $signature = '';
        foreach ($params as $name => $value) {
            $signature .= $name.$value;
        }
        $signature .= $this->sharedSecret;

        return md5($signature);
    }

    private function call(string $method, array $params): array
    {
        try {
            return $this->connection->call($method, $params);
        } catch (ApiException $e) {
            if (6 === $e->getCode()) {
                throw new NotFoundException('No entity was found for your request.', $e->getCode(), $e);
            }

            throw $e;
        }
    }
}
