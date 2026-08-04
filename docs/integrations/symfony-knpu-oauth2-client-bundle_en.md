# Symfony and KnpUOAuth2ClientBundle integration

## Choose a language

| Русский | English | Español | 中文 | Français | Deutsch |
|---|---|---|---|---|---|
| [Русский](./symfony-knpu-oauth2-client-bundle.md) | **Selected** | [Español](./symfony-knpu-oauth2-client-bundle_es.md) | [中文](./symfony-knpu-oauth2-client-bundle_zh.md) | [Français](./symfony-knpu-oauth2-client-bundle_fr.md) | [Deutsch](./symfony-knpu-oauth2-client-bundle_de.md) |

[← Back to README](../readme/README_en.md)

This guide shows how to use the package in Symfony through
[`knpuniversity/oauth2-client-bundle`](https://github.com/knpuniversity/oauth2-client-bundle).

## Client configuration

The package is configured as a generic provider. The `type: yandex` value belongs
to another provider package and is not used here.

```yaml
# config/packages/knpu_oauth2_client.yaml
knpu_oauth2_client:
    clients:
        yandex_main:
            type: generic
            provider_class: Yaleksandr\OAuth2\Client\Provider\Yandex

            client_id: '%env(OAUTH_YANDEX_CLIENT_ID)%'
            client_secret: '%env(OAUTH_YANDEX_CLIENT_SECRET)%'

            redirect_route: connect_yandex_check
            redirect_params: {}
            use_state: true
```

Local values:

```dotenv
OAUTH_YANDEX_CLIENT_ID=your-client-id
OAUTH_YANDEX_CLIENT_SECRET=your-client-secret
```

Do not commit `.env.local` or real OAuth credentials.

## Starting authorization

```php
<?php

namespace App\Controller;

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Attribute\Route;

final class YandexController extends AbstractController
{
    #[Route('/connect/yandex', name: 'connect_yandex_start')]
    public function connect(ClientRegistry $clientRegistry): RedirectResponse
    {
        return $clientRegistry
            ->getClient('yandex_main')
            ->redirect(['login:info', 'login:email']);
    }

    #[Route('/connect/yandex/check', name: 'connect_yandex_check')]
    public function check(): void
    {
        // The callback is handled by the application's authenticator or service.
    }
}
```

## Retrieving the token and profile

Inside a callback or authenticator:

```php
<?php

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use RuntimeException;
use Yaleksandr\OAuth2\Client\Provider\YandexResourceOwner;

$client = $clientRegistry->getClient('yandex_main');

// 1. The bundle validates the callback and obtains an access token.
$accessToken = $client->getAccessToken();

// 2. The provider requests the Yandex ID profile.
/** @var YandexResourceOwner $owner */
$owner = $client->fetchUserFromToken($accessToken);

// 3. The application uses the typed profile fields.
$email = $owner->getDefaultEmail();

if ($email === null) {
    throw new RuntimeException('Yandex ID did not return an email address.');
}

$profile = [
    'id' => $owner->getId(),
    'login' => $owner->getLogin(),
    'email' => $email,
    'name' => $owner->getRealName()
        ?? $owner->getDisplayName()
        ?? $owner->getLogin(),
    'phone' => $owner->getDefaultPhone()?->toArray(),
    'avatar_url' => $owner->getDefaultAvatar()?->getUrl(),
];
```

## State

```yaml
use_state: true
```

The bundle uses `state` to validate the OAuth callback. Do not disable this check
without a separate architectural reason.

## PKCE

The provider supports PKCE through the `league/oauth2-client` API.
When the bundle is used, the application must store the PKCE code before redirect
and restore it before requesting the token. The exact implementation depends on
where the callback is handled and where session state is stored.

A native PKCE example is available in the
[separate guide](../guides/native-usage_en.md).
