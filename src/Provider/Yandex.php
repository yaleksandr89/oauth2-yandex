<?php

declare(strict_types=1);

namespace Yaleksandr\OAuth2\Client\Provider;

use InvalidArgumentException;
use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Token\AccessTokenInterface;
use Psr\Http\Message\ResponseInterface;
use UnexpectedValueException;

final class Yandex extends AbstractProvider
{
    #[\Override]
    public function getBaseAuthorizationUrl(): string
    {
        return 'https://oauth.yandex.com/authorize';
    }

    /**
     * @param array<string, mixed> $params
     */
    #[\Override]
    public function getBaseAccessTokenUrl(array $params): string
    {
        return 'https://oauth.yandex.com/token';
    }

    #[\Override]
    public function getResourceOwnerDetailsUrl(AccessToken $token): string
    {
        return 'https://login.yandex.ru/info';
    }

    /**
     * @return list<string>
     */
    #[\Override]
    protected function getDefaultScopes(): array
    {
        return [];
    }

    /**
     * @param array<string, mixed> $options
     * @return array<string, mixed>
     */
    #[\Override]
    protected function getAuthorizationParameters(array $options): array
    {
        $parentParameters = parent::getAuthorizationParameters($options);
        $parameters = [];

        foreach ($parentParameters as $key => $value) {
            if (!is_string($key)) {
                throw new UnexpectedValueException('Authorization parameter keys must be strings.');
            }

            $parameters[$key] = $value;
        }

        unset($parameters['approval_prompt']);

        $scope = $parameters['scope'] ?? null;

        if ($scope === null || $scope === '') {
            unset($parameters['scope']);
        }

        return $parameters;
    }

    /**
     * @param array<string, mixed>|scalar|null $data
     * @throws IdentityProviderException
     */
    #[\Override]
    protected function checkResponse(ResponseInterface $response, mixed $data): void
    {
        $statusCode = $response->getStatusCode();
        $hasProviderError = is_array($data) && array_key_exists('error', $data);

        if ($statusCode < 400 && !$hasProviderError) {
            return;
        }

        throw new IdentityProviderException(
            $this->getErrorMessage($response, $data),
            $statusCode >= 400 ? $statusCode : 0,
            $data,
        );
    }

    /**
     * @param array<string, mixed> $response
     */
    #[\Override]
    protected function createResourceOwner(array $response, AccessToken $token): YandexResourceOwner
    {
        return new YandexResourceOwner($response);
    }

    /**
     * @return array{Accept: string}
     */
    #[\Override]
    protected function getDefaultHeaders(): array
    {
        return ['Accept' => 'application/json'];
    }

    /**
     * @return array{Authorization: string}
     */
    #[\Override]
    protected function getAuthorizationHeaders(mixed $token = null): array
    {
        if ($token instanceof AccessTokenInterface) {
            $token = $token->getToken();
        }

        if (!is_string($token) || $token === '') {
            throw new InvalidArgumentException('A non-empty OAuth access token is required.');
        }

        return ['Authorization' => 'OAuth ' . $token];
    }

    /**
     * @param array<string, mixed>|scalar|null $data
     */
    private function getErrorMessage(ResponseInterface $response, mixed $data): string
    {
        if (is_array($data)) {
            foreach (['error_description', 'error'] as $field) {
                $value = $data[$field] ?? null;

                if (is_scalar($value) && trim((string)$value) !== '') {
                    return (string)$value;
                }
            }
        } elseif (is_scalar($data) && trim((string)$data) !== '') {
            return (string)$data;
        }

        $reason = trim($response->getReasonPhrase());

        return $reason !== '' ? $reason : 'Yandex OAuth request failed.';
    }
}
