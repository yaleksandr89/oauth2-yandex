# 原生使用

## 选择语言

| Русский | English | Español | 中文 | Français | Deutsch |
|---|---|---|---|---|---|
| [Русский](./native-usage.md) | [English](./native-usage_en.md) | [Español](./native-usage_es.md) | **已选择** | [Français](./native-usage_fr.md) | [Deutsch](./native-usage_de.md) |

[← 返回 README](../readme/README_zh.md)

该示例直接使用 `league/oauth2-client`，不依赖 Symfony、Laravel
或其他框架。

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

Yandex 支持 `S256` 和 `plain` 两种 PKCE 方法；请使用 `S256`。
必须在创建 authorization URL 与请求 token 之间保存 PKCE code。

## 附加授权参数

```php
$authorizationUrl = $provider->getAuthorizationUrl([
    'scope' => ['login:info', 'login:email'],
    'optional_scope' => 'login:avatar',
    'login_hint' => 'ivan.petrov@yandex.ru',
    'force_confirm' => 'yes',
]);
```

| 参数 | 用途 |
|---|---|
| `scope` | 必需权限 |
| `optional_scope` | 用户可以拒绝的附加权限 |
| `login_hint` | login 或 email 提示 |
| `force_confirm` | 再次显示账号选择和权限确认 |

请求的权限必须已在 OAuth 应用设置中启用。

## 刷新 token

```php
$refreshToken = $token->getRefreshToken();

if (!is_string($refreshToken) || $refreshToken === '') {
    throw new RuntimeException('Refresh token is missing.');
}

$updatedToken = $provider->getAccessToken('refresh_token', [
    'refresh_token' => $refreshToken,
]);
```

Yandex 可能返回新的 refresh token。若返回，请保存新值并替换旧值。

## 安全

不要输出或写入日志：

- `Client Secret`；
- authorization code；
- access token；
- refresh token；
- 真实用户的完整资料。
