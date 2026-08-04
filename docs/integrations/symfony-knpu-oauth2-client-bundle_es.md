# Integración con Symfony y KnpUOAuth2ClientBundle

## Elija un idioma

| Русский | English | Español | 中文 | Français | Deutsch |
|---|---|---|---|---|---|
| [Русский](./symfony-knpu-oauth2-client-bundle.md) | [English](./symfony-knpu-oauth2-client-bundle_en.md) | **Seleccionado** | [中文](./symfony-knpu-oauth2-client-bundle_zh.md) | [Français](./symfony-knpu-oauth2-client-bundle_fr.md) | [Deutsch](./symfony-knpu-oauth2-client-bundle_de.md) |

[← Volver al README](../readme/README_es.md)

Esta guía muestra cómo utilizar el paquete en Symfony mediante
[`knpuniversity/oauth2-client-bundle`](https://github.com/knpuniversity/oauth2-client-bundle).

## Configuración del cliente

El paquete se configura como generic provider. El valor `type: yandex` pertenece
a otro provider y no se utiliza aquí.

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

Valores locales:

```dotenv
OAUTH_YANDEX_CLIENT_ID=your-client-id
OAUTH_YANDEX_CLIENT_SECRET=your-client-secret
```

No incluya `.env.local` ni credenciales OAuth reales en el repositorio.

## Inicio de la autorización

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

## Obtención del token y del perfil

Dentro del callback o authenticator:

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

El bundle utiliza `state` para validar el callback OAuth. No desactive esta comprobación
sin una razón arquitectónica específica.

## PKCE

El provider admite PKCE mediante la API de `league/oauth2-client`.
Al utilizar el bundle, la aplicación debe guardar el código PKCE antes del redirect
y restaurarlo antes de solicitar el token. La implementación concreta depende de
dónde se procese el callback y dónde se almacene el estado de sesión.

El ejemplo nativo con PKCE está disponible en la
[guía independiente](../guides/native-usage_es.md).
