<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\LastFm\Service;

use Nucleos\LastFm\Client\ApiClientInterface;
use Nucleos\LastFm\Exception\ApiException;
use Nucleos\LastFm\Exception\NotFoundException;
use Nucleos\LastFm\Session\Session;
use Nucleos\LastFm\Session\SessionInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

final class AuthService implements LoggerAwareInterface, AuthServiceInterface
{
    /**
     * @var LoggerInterface
     */
    private LoggerInterface|NullLogger $logger;

    private string $authUrl;

    private ApiClientInterface $client;

    public function __construct(ApiClientInterface $connection, string $authUrl = 'http://www.last.fm/api/auth/')
    {
        $this->client  = $connection;
        $this->authUrl = $authUrl;
        $this->logger  = new NullLogger();
    }

    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }

    public function createSession(string $token): ?SessionInterface
    {
        try {
            $response = $this->client->signedCall('auth.getSession', [
                'token' => $token,
            ]);

            return new Session($response['session']['name'], $response['session']['key'], $response['session']['subscriber']);
        } catch (ApiException $e) {
            $this->logger->warning(\sprintf('Error getting session for "%s" token.', $token), [
                'exception' => $e,
            ]);
        } catch (NotFoundException $e) {
            $this->logger->info(\sprintf('No session was found for "%s" token.', $token), [
                'exception' => $e,
            ]);
        }

        return null;
    }

    public function createToken(): ?string
    {
        $response = $this->client->signedCall('auth.getToken');

        return $response['token'] ?? null;
    }

    public function getAuthUrl(string $callbackUrl): string
    {
        return $this->authUrl.'?api_key='.$this->client->getApiKey().'&cb='.$callbackUrl;
    }
}
