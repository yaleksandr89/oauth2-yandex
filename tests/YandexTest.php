<?php

declare(strict_types=1);

namespace Yaleksandr\OAuth2\Client\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Token\AccessToken;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use RuntimeException;
use UnexpectedValueException;
use Yaleksandr\OAuth2\Client\Provider\Yandex;
use Yaleksandr\OAuth2\Client\Provider\YandexResourceOwner;

final class YandexTest extends TestCase
{
    public function testEndpointsAndAuthorizationParameters(): void
    {
        $provider = self::provider(new MockHandler());

        self::assertSame('https://oauth.yandex.com/authorize', $provider->getBaseAuthorizationUrl());
        self::assertSame('https://oauth.yandex.com/token', $provider->getBaseAccessTokenUrl([]));
        self::assertSame(
            'https://login.yandex.ru/info',
            $provider->getResourceOwnerDetailsUrl(new AccessToken(['access_token' => 'secret-token'])),
        );

        $parameters = self::queryParameters($provider->getAuthorizationUrl([
            'state' => 'known-state',
            'scope' => ['login:info', 'login:email'],
        ]));

        self::assertSame('client-id', $parameters['client_id']);
        self::assertSame('code', $parameters['response_type']);
        self::assertSame('https://client.example/callback', $parameters['redirect_uri']);
        self::assertSame('known-state', $parameters['state']);
        self::assertSame('login:info,login:email', $parameters['scope']);
        self::assertArrayNotHasKey('approval_prompt', $parameters);
        self::assertArrayNotHasKey('optional_scope', $parameters);

        $defaultParameters = self::queryParameters($provider->getAuthorizationUrl());
        self::assertArrayNotHasKey('scope', $defaultParameters);

        $explicitlyEmptyParameters = self::queryParameters($provider->getAuthorizationUrl([
            'scope' => [],
            'optional_scope' => 'login:avatar',
        ]));
        self::assertArrayNotHasKey('scope', $explicitlyEmptyParameters);
        self::assertSame('login:avatar', $explicitlyEmptyParameters['optional_scope']);
    }

    public function testAccessTokenRequestUsesLeaguePostFormFlow(): void
    {
        $mock = new MockHandler([
            new Response(200, ['Content-Type' => 'application/json'], self::fixture('access-token.json')),
        ]);
        $provider = self::provider($mock);

        $token = $provider->getAccessToken('authorization_code', [
            'code' => 'authorization-code',
            'scope' => ['login:info', 'login:email'],
        ]);

        self::assertSame('test-access-token', $token->getToken());

        $request = self::lastRequest($mock);
        self::assertSame('POST', $request->getMethod());
        self::assertSame('https://oauth.yandex.com/token', (string) $request->getUri());
        self::assertSame('application/x-www-form-urlencoded', $request->getHeaderLine('Content-Type'));
        self::assertSame('application/json', $request->getHeaderLine('Accept'));

        parse_str((string) $request->getBody(), $body);
        self::assertSame('authorization_code', $body['grant_type']);
        self::assertSame('authorization-code', $body['code']);
        self::assertSame('client-id', $body['client_id']);
        self::assertSame('client-secret', $body['client_secret']);
        self::assertSame('login:info,login:email', $body['scope']);
    }

    public function testResourceOwnerRequestKeepsTokenOutOfUrlAndUsesOAuthHeader(): void
    {
        $mock = new MockHandler([
            new Response(200, ['Content-Type' => 'application/json'], self::fixture('user-info-minimal.json')),
        ]);
        $provider = self::provider($mock);

        $owner = $provider->getResourceOwner(new AccessToken(['access_token' => 'secret-token']));

        self::assertInstanceOf(YandexResourceOwner::class, $owner);
        self::assertSame('1000034426', $owner->getId());

        $request = self::lastRequest($mock);
        self::assertSame('GET', $request->getMethod());
        self::assertSame('https://login.yandex.ru/info', (string) $request->getUri());
        self::assertSame('', $request->getUri()->getQuery());
        self::assertStringNotContainsString('secret-token', (string) $request->getUri());
        self::assertSame('OAuth secret-token', $request->getHeaderLine('Authorization'));
        self::assertSame('application/json', $request->getHeaderLine('Accept'));
    }

    public function testJsonProviderErrorUsesDescriptionAndPreservesPayload(): void
    {
        $payload = self::fixtureData('access-token-error.json');
        $provider = self::provider(new MockHandler([
            new Response(200, ['Content-Type' => 'application/json'], self::fixture('access-token-error.json')),
        ]));

        $exception = self::captureIdentityProviderException(
            static fn () => $provider->getAccessToken('authorization_code', ['code' => 'bad-code']),
        );

        self::assertSame('The authorization code is invalid or expired.', $exception->getMessage());
        self::assertSame(0, $exception->getCode());
        self::assertSame($payload, $exception->getResponseBody());
    }

    public function testHttpStatusErrorWithoutProviderErrorUsesReasonPhrase(): void
    {
        $provider = self::provider(new MockHandler([
            new Response(503, ['Content-Type' => 'application/json'], '{}', '1.1', 'Service Unavailable'),
        ]));

        $exception = self::captureIdentityProviderException(
            static fn () => $provider->getResourceOwner(new AccessToken(['access_token' => 'token'])),
        );

        self::assertSame('Service Unavailable', $exception->getMessage());
        self::assertSame(503, $exception->getCode());
        self::assertSame([], $exception->getResponseBody());
    }

    public function testScalarErrorBodyIsHandledSafely(): void
    {
        $provider = self::provider(new MockHandler([
            new Response(502, ['Content-Type' => 'application/json'], '"temporarily unavailable"'),
        ]));

        $exception = self::captureIdentityProviderException(
            static fn () => $provider->getResourceOwner(new AccessToken(['access_token' => 'token'])),
        );

        self::assertSame('temporarily unavailable', $exception->getMessage());
        self::assertSame(502, $exception->getCode());
        self::assertSame('temporarily unavailable', $exception->getResponseBody());
    }

    public function testUserInfoProviderErrorIsConvertedToLeagueException(): void
    {
        $provider = self::provider(new MockHandler([
            new Response(200, ['Content-Type' => 'application/json'], self::fixture('user-info-error.json')),
        ]));

        $exception = self::captureIdentityProviderException(
            static fn () => $provider->getResourceOwner(new AccessToken(['access_token' => 'token'])),
        );

        self::assertSame('The OAuth token is invalid.', $exception->getMessage());
        self::assertSame(0, $exception->getCode());
        self::assertSame(self::fixtureData('user-info-error.json'), $exception->getResponseBody());
    }

    public function testArrayErrorDescriptionsAndInvalidOAuthTokensAreHandledSafely(): void
    {
        $provider = self::provider(new MockHandler([
            new Response(
                400,
                ['Content-Type' => 'application/json'],
                '{"error_description":[],"error":"invalid_request"}',
            ),
        ]));

        $exception = self::captureIdentityProviderException(
            static fn () => $provider->getAccessToken('authorization_code', ['code' => 'bad-code']),
        );

        self::assertSame('invalid_request', $exception->getMessage());
        self::assertSame(400, $exception->getCode());

        $token = self::createStub(\League\OAuth2\Client\Token\AccessTokenInterface::class);
        $token->method('getToken')->willReturn('');

        $this->expectException(\InvalidArgumentException::class);
        $provider->getHeaders($token);
    }

    public function testAuthorizationParametersRejectUnexpectedNumericKeys(): void
    {
        $this->expectException(UnexpectedValueException::class);
        self::provider(new MockHandler())->getAuthorizationUrl([0 => 'unexpected']);
    }

    private static function provider(MockHandler $mock): Yandex
    {
        return new Yandex(
            [
                'clientId' => 'client-id',
                'clientSecret' => 'client-secret',
                'redirectUri' => 'https://client.example/callback',
            ],
            [
                'httpClient' => new Client([
                    'handler' => $mock,
                    'http_errors' => false,
                ]),
            ],
        );
    }

    /**
     * @return array<string, string>
     */
    private static function queryParameters(string $url): array
    {
        $query = parse_url($url, PHP_URL_QUERY);

        if (!is_string($query)) {
            throw new RuntimeException('Authorization URL has no query string.');
        }

        parse_str($query, $parameters);

        /** @var array<string, string> $parameters */
        return $parameters;
    }

    private static function lastRequest(MockHandler $mock): RequestInterface
    {
        $request = $mock->getLastRequest();

        if (!$request instanceof RequestInterface) {
            throw new RuntimeException('No request was recorded by the mock handler.');
        }

        return $request;
    }

    private static function fixture(string $name): string
    {
        $contents = file_get_contents(__DIR__ . '/Fixtures/' . $name);

        if ($contents === false) {
            throw new RuntimeException(sprintf('Unable to read fixture "%s".', $name));
        }

        return $contents;
    }

    /**
     * @return array<string, mixed>
     */
    private static function fixtureData(string $name): array
    {
        $data = json_decode(self::fixture($name), true, 512, JSON_THROW_ON_ERROR);

        if (!is_array($data)) {
            throw new RuntimeException(sprintf('Fixture "%s" is not a JSON object.', $name));
        }

        /** @var array<string, mixed> $data */
        return $data;
    }

    /**
     * @param callable(): mixed $operation
     */
    private static function captureIdentityProviderException(callable $operation): IdentityProviderException
    {
        try {
            $operation();
        } catch (IdentityProviderException $exception) {
            return $exception;
        }

        self::fail('Expected an IdentityProviderException.');
    }
}
