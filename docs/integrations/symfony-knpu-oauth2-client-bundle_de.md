# Integration mit Symfony und KnpUOAuth2ClientBundle

## Sprache auswählen

| Русский | English | Español | 中文 | Français | Deutsch |
|---|---|---|---|---|---|
| [Русский](./symfony-knpu-oauth2-client-bundle.md) | [English](./symfony-knpu-oauth2-client-bundle_en.md) | [Español](./symfony-knpu-oauth2-client-bundle_es.md) | [中文](./symfony-knpu-oauth2-client-bundle_zh.md) | [Français](./symfony-knpu-oauth2-client-bundle_fr.md) | **Ausgewählt** |

[← Zurück zur README](../readme/README_de.md)

Diese Anleitung zeigt die Verwendung des Pakets in Symfony über
[`knpuniversity/oauth2-client-bundle`](https://github.com/knpuniversity/oauth2-client-bundle).

## Client-Konfiguration

Das Paket wird als generic provider konfiguriert. Der Wert `type: yandex` gehört
zu einem anderen Provider-Paket und wird hier nicht verwendet.

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

Lokale Werte:

```dotenv
OAUTH_YANDEX_CLIENT_ID=your-client-id
OAUTH_YANDEX_CLIENT_SECRET=your-client-secret
```

Fügen Sie `.env.local` und echte OAuth-Zugangsdaten nicht dem Repository hinzu.

## Autorisierung starten

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

## Token und Profil abrufen

In einem Callback oder Authenticator:

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

Das Bundle verwendet `state` zur Prüfung des OAuth-Callbacks. Deaktivieren Sie diese Prüfung
nicht ohne einen separaten architektonischen Grund.

## PKCE

Der Provider unterstützt PKCE über die API von `league/oauth2-client`.
Bei Verwendung des Bundles muss die Anwendung den PKCE-Code vor dem Redirect speichern
und vor der Token-Anfrage wiederherstellen. Die konkrete Umsetzung hängt davon ab,
wo der Callback verarbeitet und der Session-State gespeichert wird.

Ein natives PKCE-Beispiel finden Sie in der
[separaten Anleitung](../guides/native-usage_de.md).
