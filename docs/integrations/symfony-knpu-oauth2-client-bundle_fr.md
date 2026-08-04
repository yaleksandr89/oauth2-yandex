# Intégration Symfony et KnpUOAuth2ClientBundle

## Choisir une langue

| Русский | English | Español | 中文 | Français | Deutsch |
|---|---|---|---|---|---|
| [Русский](./symfony-knpu-oauth2-client-bundle.md) | [English](./symfony-knpu-oauth2-client-bundle_en.md) | [Español](./symfony-knpu-oauth2-client-bundle_es.md) | [中文](./symfony-knpu-oauth2-client-bundle_zh.md) | **Sélectionné** | [Deutsch](./symfony-knpu-oauth2-client-bundle_de.md) |

[← Retour au README](../readme/README_fr.md)

Ce guide montre comment utiliser le package dans Symfony via
[`knpuniversity/oauth2-client-bundle`](https://github.com/knpuniversity/oauth2-client-bundle).

## Configuration du client

Le package est configuré comme generic provider. La valeur `type: yandex` appartient
à un autre provider et n'est pas utilisée ici.

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

Valeurs locales :

```dotenv
OAUTH_YANDEX_CLIENT_ID=your-client-id
OAUTH_YANDEX_CLIENT_SECRET=your-client-secret
```

N'ajoutez pas `.env.local` ni de vrais identifiants OAuth au dépôt.

## Démarrer l'autorisation

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

## Obtenir le token et le profil

Dans un callback ou un authenticator :

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

Le bundle utilise `state` pour valider le callback OAuth. Ne désactivez pas ce contrôle
sans raison architecturale distincte.

## PKCE

Le provider prend en charge PKCE via l'API `league/oauth2-client`.
Avec le bundle, l'application doit enregistrer le code PKCE avant le redirect
et le restaurer avant la requête de token. L'implémentation exacte dépend de
l'endroit où le callback est traité et où l'état de session est conservé.

Un exemple PKCE natif est disponible dans le
[guide séparé](../guides/native-usage_fr.md).
