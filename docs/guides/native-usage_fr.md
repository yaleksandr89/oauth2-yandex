# Utilisation native

## Choisir une langue

| Русский | English | Español | 中文 | Français | Deutsch |
|---|---|---|---|---|---|
| [Русский](./native-usage.md) | [English](./native-usage_en.md) | [Español](./native-usage_es.md) | [中文](./native-usage_zh.md) | **Sélectionné** | [Deutsch](./native-usage_de.md) |

[← Retour au README](../readme/README_fr.md)

Cet exemple utilise directement `league/oauth2-client`, sans Symfony, Laravel
ni autre framework.

## Authorization Code Flow

```php
<?php

use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use Yaleksandr\OAuth2\Client\Provider\Yandex;
use Yaleksandr\OAuth2\Client\ValueObject\YandexAvatarSize;

session_start();

// 1. Create the provider.
$provider = new Yandex([
    'clientId' => 'fake-yandex-client-id',
    'clientSecret' => 'fake-yandex-client-secret',
    'redirectUri' => 'https://example.com/oauth/yandex/callback',
    'pkceMethod' => Yandex::PKCE_METHOD_S256,
]);

$isCallback = isset($_GET['code']) || isset($_GET['error']);

if (!$isCallback) {
    // 2. Build the authorization URL.
    $authorizationUrl = $provider->getAuthorizationUrl([
        'scope' => [
            'login:info',
            'login:email',
            'login:avatar',
            'login:default_phone',
        ],
    ]);

    // 3. Store state and the PKCE code until the user returns.
    $state = $provider->getState();
    $pkceCode = $provider->getPkceCode();

    if (
        !is_string($state)
        || $state === ''
        || !is_string($pkceCode)
        || $pkceCode === ''
    ) {
        throw new RuntimeException('OAuth state or PKCE code was not generated.');
    }

    $_SESSION['oauth2state'] = $state;
    $_SESSION['oauth2pkceCode'] = $pkceCode;

    // 4. Redirect the user to Yandex OAuth.
    header('Location: ' . $authorizationUrl);
    exit;
}

// 5. The user returned to the callback.
// Read the saved values and remove them from the session.
$expectedState = $_SESSION['oauth2state'] ?? null;
$pkceCode = $_SESSION['oauth2pkceCode'] ?? null;

unset($_SESSION['oauth2state'], $_SESSION['oauth2pkceCode']);

$receivedState = $_GET['state'] ?? null;

// 6. Validate state and reject a forged callback.
if (
    !is_string($receivedState)
    || !is_string($expectedState)
    || !hash_equals($expectedState, $receivedState)
) {
    throw new RuntimeException('Invalid OAuth state.');
}

// 7. Handle user denial or an error returned by Yandex.
if (isset($_GET['error'])) {
    $error = is_string($_GET['error']) ? $_GET['error'] : 'unknown_error';

    throw new RuntimeException('Yandex OAuth returned an error: ' . $error);
}

$code = $_GET['code'] ?? null;

if (!is_string($code) || $code === '') {
    throw new RuntimeException('Authorization code is missing.');
}

if (!is_string($pkceCode) || $pkceCode === '') {
    throw new RuntimeException('PKCE code is missing.');
}

// 8. Restore the PKCE code before exchanging the authorization code.
$provider->setPkceCode($pkceCode);

try {
    // 9. Exchange the authorization code for an access token.
    $token = $provider->getAccessToken('authorization_code', [
        'code' => $code,
    ]);

    // 10. Request the user profile.
    $owner = $provider->getResourceOwner($token);

    // 11. Use the typed profile data.
    $profile = [
        'id' => $owner->getId(),
        'login' => $owner->getLogin(),
        'sex' => $owner->getSex()?->value,
        'email' => $owner->getDefaultEmail(),
        'phone' => $owner->getDefaultPhone()?->toArray(),
        'avatar_url' => $owner
            ->getDefaultAvatar()
            ?->getUrl(YandexAvatarSize::Size200),
    ];
} catch (IdentityProviderException $exception) {
    throw new RuntimeException(
        'Unable to complete authorization through Yandex ID.',
        previous: $exception,
    );
}
```

Yandex prend en charge PKCE avec `S256` et `plain` ; utilisez `S256`.
Le code PKCE doit être conservé entre la création de l'URL d'autorisation et la requête de token.

## Paramètres d'autorisation supplémentaires

```php
$authorizationUrl = $provider->getAuthorizationUrl([
    'scope' => ['login:info', 'login:email'],
    'optional_scope' => 'login:avatar',
    'login_hint' => 'ivan.petrov@yandex.ru',
    'force_confirm' => 'yes',
]);
```

| Paramètre | Rôle |
|---|---|
| `scope` | permissions obligatoires |
| `optional_scope` | permissions supplémentaires que l'utilisateur peut refuser |
| `login_hint` | suggestion de login ou d'email |
| `force_confirm` | afficher de nouveau la sélection du compte et la confirmation des permissions |

Les permissions demandées doivent être activées dans la configuration de l'application OAuth.

## Renouvellement du token

```php
$refreshToken = $token->getRefreshToken();

if (!is_string($refreshToken) || $refreshToken === '') {
    throw new RuntimeException('Refresh token is missing.');
}

$updatedToken = $provider->getAccessToken('refresh_token', [
    'refresh_token' => $refreshToken,
]);
```

Yandex peut renvoyer un nouveau refresh token. S'il est présent, enregistrez la nouvelle valeur
à la place de l'ancienne.

## Sécurité

N'affichez pas et n'écrivez pas dans les logs :

- `Client Secret` ;
- authorization code ;
- access token ;
- refresh token ;
- le profil complet d'un utilisateur réel.
