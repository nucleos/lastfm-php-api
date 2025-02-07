<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\LastFm\Connection;

use Exception;
use Nucleos\LastFm\Exception\ApiException;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class PsrClientConnection implements ConnectionInterface
{
    private ClientInterface $client;

    private RequestFactoryInterface $requestFactory;

    private string $endpoint;

    /**
     * Initialize client.
     */
    public function __construct(ClientInterface $client, RequestFactoryInterface $messageFactory, string $endpoint = ConnectionInterface::DEFAULT_ENDPOINT)
    {
        $this->client         = $client;
        $this->requestFactory = $messageFactory;
        $this->endpoint       = $endpoint;
    }

    public function getPageBody(string $url, array $params = [], string $method = 'GET'): ?string
    {
        $request  = $this->createRequest($method, $url, $params);

        try {
            $response = $this->client->sendRequest($request);
        } catch (ClientExceptionInterface $e) {
            throw new ApiException(
                \sprintf('Error fetching page body for url: %s', (string) $request->getUri()),
                500,
                $e
            );
        }

        if ($response->getStatusCode() >= 400) {
            return null;
        }

        return $response->getBody()->getContents();
    }

    public function call(string $url, array $params = [], string $method = 'GET'): array
    {
        $params  = array_merge($params, ['format' => 'json']);
        $request = $this->createRequest($method, $this->endpoint, $params);

        try {
            $response = $this->client->sendRequest($request);

            // Parse response
            return $this->parseResponse($response);
        } catch (ApiException $e) {
            throw $e;
        } catch (Exception $e) {
            throw new ApiException('Technical error occurred.', 500, $e);
        } catch (ClientExceptionInterface $e) {
            throw new ApiException('Technical error occurred.', 500, $e);
        }
    }

    private function createRequest(string $method, string $url, array $params): RequestInterface
    {
        $query = http_build_query($params);

        return $this->requestFactory->createRequest($method, $url.'?'.$query);
    }

    /**
     * @throws ApiException
     */
    private function parseResponse(ResponseInterface $response): array
    {
        $array = json_decode($response->getBody()->getContents(), true);

        if (\is_array($array) && \array_key_exists('error', $array) && \array_key_exists('message', $array)) {
            throw new ApiException($array['message'], $array['error']);
        }

        return $array;
    }
}
