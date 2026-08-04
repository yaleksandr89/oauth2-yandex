# Нативное использование

[← Вернуться к README](../../README.md)

Пример использует `league/oauth2-client` напрямую, без Symfony, Laravel
и других фреймворков.

В коде указаны вымышленные OAuth-реквизиты. В реальном приложении загружайте
их из конфигурации или переменных окружения.

## Authorization Code Flow

```php
<?php

use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use Yaleksandr\OAuth2\Client\Provider\Yandex;
use Yaleksandr\OAuth2\Client\ValueObject\YandexAvatarSize;

session_start();

// 1. Создаём provider.
// В реальном приложении значения нужно получать из конфигурации.
$provider = new Yandex([
    'clientId' => '00000000000000000000000000000000',
    'clientSecret' => 'fake-yandex-client-secret',
    'redirectUri' => 'https://example.com/oauth/yandex/callback',
    'pkceMethod' => Yandex::PKCE_METHOD_S256,
]);

$isCallback = isset($_GET['code']) || isset($_GET['error']);

if (!$isCallback) {
    // 2. Первый запрос: формируем URL авторизации.
    $authorizationUrl = $provider->getAuthorizationUrl([
        'scope' => [
            'login:info',
            'login:email',
            'login:avatar',
            'login:default_phone',
        ],
    ]);

    // 3. Сохраняем state и PKCE code до возврата пользователя.
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

    // 4. Отправляем пользователя на страницу Yandex OAuth.
    header('Location: ' . $authorizationUrl);
    exit;
}

// 5. Пользователь вернулся в callback.
// Сначала достаём сохранённые значения и удаляем их из сессии.
$expectedState = $_SESSION['oauth2state'] ?? null;
$pkceCode = $_SESSION['oauth2pkceCode'] ?? null;

unset($_SESSION['oauth2state'], $_SESSION['oauth2pkceCode']);

$receivedState = $_GET['state'] ?? null;

// 6. Проверяем state и отклоняем поддельный callback.
if (
    !is_string($receivedState)
    || !is_string($expectedState)
    || !hash_equals($expectedState, $receivedState)
) {
    throw new RuntimeException('Invalid OAuth state.');
}

// 7. Обрабатываем отказ пользователя или ошибку Yandex.
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

// 8. Восстанавливаем PKCE code перед обменом authorization code на токен.
$provider->setPkceCode($pkceCode);

try {
    // 9. Обмениваем authorization code на access token.
    $token = $provider->getAccessToken('authorization_code', [
        'code' => $code,
    ]);

    // 10. Запрашиваем профиль пользователя.
    $owner = $provider->getResourceOwner($token);

    // 11. Используем типизированные данные профиля.
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
        'Не удалось завершить авторизацию через Yandex ID.',
        previous: $exception,
    );
}
```

Yandex поддерживает PKCE с методами `S256` и `plain`; используйте `S256`.
PKCE code должен быть сохранён между созданием authorization URL и запросом токена.

## Дополнительные параметры авторизации

```php
$authorizationUrl = $provider->getAuthorizationUrl([
    'scope' => ['login:info', 'login:email'],
    'optional_scope' => 'login:avatar',
    'login_hint' => 'ivan.petrov@yandex.ru',
    'force_confirm' => 'yes',
]);
```

| Параметр | Назначение |
|---|---|
| `scope` | основные запрашиваемые права |
| `optional_scope` | дополнительные права, от которых пользователь может отказаться |
| `login_hint` | подсказка логина или email |
| `force_confirm` | повторный показ выбора аккаунта и подтверждения доступа |

Права должны входить в список, разрешённый в настройках OAuth-приложения.

## Обновление токена

```php
$refreshToken = $token->getRefreshToken();

if (!is_string($refreshToken) || $refreshToken === '') {
    throw new RuntimeException('Refresh token is missing.');
}

$updatedToken = $provider->getAccessToken('refresh_token', [
    'refresh_token' => $refreshToken,
]);
```

Yandex может вернуть новый refresh token. Если он присутствует, сохраняйте новое
значение вместо предыдущего.

## Безопасность

Не выводите и не сохраняйте в логах:

- `Client Secret`;
- authorization code;
- access token;
- refresh token;
- полный профиль реального пользователя.
