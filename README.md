# OAuth2 Yandex

[![Source Code](https://img.shields.io/badge/source-yaleksandr89%2Foauth2--yandex-blue.svg?style=flat-square)](https://github.com/yaleksandr89/oauth2-yandex)
[![Baseline CI](https://img.shields.io/github/actions/workflow/status/yaleksandr89/oauth2-yandex/basic.yml?branch=master&style=flat-square&label=CI)](https://github.com/yaleksandr89/oauth2-yandex/actions/workflows/basic.yml)
[![PHP](https://img.shields.io/badge/PHP-8.3--8.5-777BB4.svg?style=flat-square&logo=php&logoColor=white)](https://www.php.net/)
[![League OAuth2 Client](https://img.shields.io/badge/league%2Foauth2--client-%5E2.9-4F5B93.svg?style=flat-square)](https://oauth2-client.thephpleague.com/)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)

## Выберите язык

| Русский | English | Español | 中文 | Français | Deutsch |
|---|---|---|---|---|---|
| **Выбран** | [English](./docs/readme/README_en.md) | [Español](./docs/readme/README_es.md) | [中文](./docs/readme/README_zh.md) | [Français](./docs/readme/README_fr.md) | [Deutsch](./docs/readme/README_de.md) |

`yaleksandr89/oauth2-yandex` — provider-клиент Yandex ID для
[`league/oauth2-client`](https://oauth2-client.thephpleague.com/).

Пакет реализует OAuth 2.0 Authorization Code Flow, получение и обновление токенов,
а также загрузку профиля пользователя через API Yandex ID.

## Требования

- PHP `8.3`, `8.4` или `8.5`;
- `league/oauth2-client` `^2.9`.

## Установка

```bash
composer require yaleksandr89/oauth2-yandex
```

## Создание приложения в Яндекс OAuth

1. Откройте [Яндекс OAuth](https://oauth.yandex.ru/) и создайте приложение
   **для авторизации пользователей**.
2. Выберите платформу **Веб-сервисы**.
3. Добавьте точный Redirect URI вашего callback, например:

   ```text
   https://example.com/oauth/yandex/callback
   ```

4. Выберите только те права, которые действительно использует приложение.
5. Сохраните `Client ID` и `Client Secret` вне репозитория и открытых конфигурационных файлов.

Основные права Yandex ID:

| Право | Данные |
|---|---|
| `login:info` | логин, имя, фамилия и пол |
| `login:email` | адрес электронной почты |
| `login:avatar` | портрет пользователя |
| `login:birthday` | дата рождения |
| `login:default_phone` | номер телефона |

Если `scope` и `optional_scope` не переданы, Yandex выдаёт токен с правами,
выбранными в настройках приложения. Provider не добавляет пустой параметр `scope=`
в authorization URL.

## Конфигурация

```php
<?php

declare(strict_types=1);

use Yaleksandr\OAuth2\Client\Provider\Yandex;

$provider = new Yandex([
    'clientId' => $_ENV['YANDEX_CLIENT_ID'],
    'clientSecret' => $_ENV['YANDEX_CLIENT_SECRET'],
    'redirectUri' => 'https://example.com/oauth/yandex/callback',
]);
```

## Использование

### Authorization Code Flow

Пример ниже:

- создаёт и проверяет `state`;
- обрабатывает ошибку, возвращённую Yandex в callback;
- использует PKCE с методом `S256`;
- получает токен и профиль пользователя.

```php
<?php

declare(strict_types=1);

use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use Yaleksandr\OAuth2\Client\Provider\Yandex;
use Yaleksandr\OAuth2\Client\ValueObject\YandexAvatarSize;

session_start();

$provider = new Yandex([
    'clientId' => $_ENV['YANDEX_CLIENT_ID'],
    'clientSecret' => $_ENV['YANDEX_CLIENT_SECRET'],
    'redirectUri' => 'https://example.com/oauth/yandex/callback',
    'pkceMethod' => Yandex::PKCE_METHOD_S256,
]);

$isCallback = isset($_GET['code']) || isset($_GET['error']);

if (!$isCallback) {
    $authorizationUrl = $provider->getAuthorizationUrl([
        'scope' => [
            'login:info',
            'login:email',
            'login:avatar',
            'login:default_phone',
        ],
    ]);

    $pkceCode = $provider->getPkceCode();

    if (!is_string($pkceCode) || $pkceCode === '') {
        throw new RuntimeException('PKCE code was not generated.');
    }

    $_SESSION['oauth2state'] = $provider->getState();
    $_SESSION['oauth2pkceCode'] = $pkceCode;

    header('Location: ' . $authorizationUrl);
    exit;
}

$state = $_GET['state'] ?? null;
$expectedState = $_SESSION['oauth2state'] ?? null;
$pkceCode = $_SESSION['oauth2pkceCode'] ?? null;

unset($_SESSION['oauth2state'], $_SESSION['oauth2pkceCode']);

if (
    !is_string($state)
    || !is_string($expectedState)
    || !hash_equals($expectedState, $state)
) {
    throw new RuntimeException('Invalid OAuth state.');
}

if (isset($_GET['error'])) {
    $error = is_string($_GET['error']) ? $_GET['error'] : 'unknown_error';
    $description = $_GET['error_description'] ?? null;

    if (!is_string($description) || $description === '') {
        $description = $error;
    }

    throw new RuntimeException('Yandex OAuth error: ' . $description);
}

$code = $_GET['code'] ?? null;

if (!is_string($code) || $code === '') {
    throw new RuntimeException('Authorization code is missing.');
}

if (!is_string($pkceCode) || $pkceCode === '') {
    throw new RuntimeException('PKCE code is missing.');
}

$provider->setPkceCode($pkceCode);

try {
    $token = $provider->getAccessToken('authorization_code', [
        'code' => $code,
    ]);

    $owner = $provider->getResourceOwner($token);

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

Yandex поддерживает PKCE с методами `S256` и `plain`; предпочтителен `S256`.
Код PKCE нужно сохранить между созданием authorization URL и обменом authorization code
на токен.

### Дополнительные параметры авторизации

Provider передаёт поддерживаемые Yandex параметры в authorization URL:

```php
$authorizationUrl = $provider->getAuthorizationUrl([
    'scope' => ['login:info', 'login:email'],
    'optional_scope' => 'login:avatar',
    'login_hint' => 'ivan.petrov@yandex.ru',
    'force_confirm' => 'yes',
]);
```

- `scope` — основной список запрашиваемых прав из настроек приложения;
- `optional_scope` — права, которые пользователь может не предоставлять;
- `login_hint` — подсказка аккаунта;
- `force_confirm` — повторный показ экрана выбора аккаунта и подтверждения доступа.

### Обновление токена

```php
$token = $provider->getAccessToken('refresh_token', [
    'refresh_token' => $refreshToken,
]);
```

Если Yandex вернул новый refresh token, сохраняйте новое значение вместо предыдущего.

### Получение профиля

```php
$owner = $provider->getResourceOwner($token);

$id = $owner->getId();
$email = $owner->getDefaultEmail();
$phone = $owner->getDefaultPhone();
$avatar = $owner->getDefaultAvatar();
```

Не выводите и не сохраняйте в логах access token, refresh token, authorization code,
Client Secret или полный профиль реального пользователя.

## Symfony и KnpUOAuth2ClientBundle

Пакет подключается как generic provider. Значение `type: yandex` в
KnpUOAuth2ClientBundle относится к другому provider-пакету и здесь не используется.

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

Запуск авторизации:

```php
return $clientRegistry
    ->getClient('yandex_main')
    ->redirect(['login:info', 'login:email']);
```

Получение токена и профиля:

```php
$client = $clientRegistry->getClient('yandex_main');
$token = $client->getAccessToken();

/** @var \Yaleksandr\OAuth2\Client\Provider\YandexResourceOwner $owner */
$owner = $client->fetchUserFromToken($token);
```

`use_state: true` оставляйте включённым. В приведённой конфигурации PKCE не включён;
для него нужно отдельно настроить сохранение и восстановление PKCE code между redirect
и callback.

## Доступные методы

### `Yandex`

Основной OAuth API наследуется от `league/oauth2-client`:

| Метод | Назначение | Пример |
|---|---|---|
| `getAuthorizationUrl(array $options = [])` | формирует URL авторизации и создаёт `state` | `$provider->getAuthorizationUrl(['scope' => ['login:info']])` |
| `getState()` | возвращает созданный `state` | `$provider->getState()` |
| `getPkceCode()` | возвращает созданный PKCE code | `$provider->getPkceCode()` |
| `setPkceCode(string $code)` | восстанавливает PKCE code перед запросом токена | `$provider->setPkceCode($pkceCode)` |
| `getAccessToken(string $grant, array $options = [])` | получает или обновляет токен | `$provider->getAccessToken('authorization_code', ['code' => $code])` |
| `getResourceOwner(AccessToken $token)` | запрашивает профиль | `$provider->getResourceOwner($token)` |

### `YandexResourceOwner`

| Метод | Результат | Пример |
|---|---|---|
| `getId()` | идентификатор пользователя | `$owner->getId()` |
| `getLogin()` | логин | `$owner->getLogin()` |
| `getClientId()` | идентификатор OAuth-приложения | `$owner->getClientId()` |
| `getPsuid()` | идентификатор пользователя в контексте OAuth-приложения | `$owner->getPsuid()` |
| `getOldSocialLogin()` | прежний social login или `null` | `$owner->getOldSocialLogin()` |
| `getFirstName()` | имя или `null` | `$owner->getFirstName()` |
| `getLastName()` | фамилия или `null` | `$owner->getLastName()` |
| `getDisplayName()` | отображаемое имя или `null` | `$owner->getDisplayName()` |
| `getRealName()` | полное имя или `null` | `$owner->getRealName()` |
| `getSex()` | `YandexSex` или `null` | `$owner->getSex()?->value` |
| `getDefaultEmail()` | основной email или `null` | `$owner->getDefaultEmail()` |
| `getEmails()` | список email | `$owner->getEmails()` |
| `getBirthday()` | строка даты или `null` | `$owner->getBirthday()` |
| `getDefaultAvatar()` | `YandexAvatar` или `null` | `$owner->getDefaultAvatar()?->getUrl()` |
| `getDefaultAvatarId()` | идентификатор аватара или `null` | `$owner->getDefaultAvatarId()` |
| `isAvatarEmpty()` | признак заглушки или `null` | `$owner->isAvatarEmpty()` |
| `getDefaultPhone()` | `YandexPhone` или `null` | `$owner->getDefaultPhone()?->getNumber()` |
| `getAvatarUrl(YandexAvatarSize $size = YandexAvatarSize::Size200)` | URL аватара или `null` | `$owner->getAvatarUrl(YandexAvatarSize::Size100)` |
| `toArray()` | исходный массив ответа Yandex | `$owner->toArray()` |

### `YandexAvatar`

| Метод | Результат | Пример |
|---|---|---|
| `getId()` | идентификатор аватара | `$avatar->getId()` |
| `isEmpty()` | признак заглушки | `$avatar->isEmpty()` |
| `getUrl(YandexAvatarSize $size = YandexAvatarSize::Size200)` | URL или `null` | `$avatar->getUrl(YandexAvatarSize::Size100)` |
| `toArray()` | массив с `id` и `empty` | `$avatar->toArray()` |

Если Yandex сообщил, что ID указывает на стандартную заглушку, `getUrl()` возвращает
`null`.

### `YandexPhone`

| Метод | Результат | Пример |
|---|---|---|
| `getId()` | идентификатор телефона | `$phone->getId()` |
| `getNumber()` | номер телефона | `$phone->getNumber()` |
| `toArray()` | массив с `id` и `number` | `$phone->toArray()` |

### `YandexSex`

| Case | Значение |
|---|---|
| `YandexSex::Male` | `male` |
| `YandexSex::Female` | `female` |

Если пол не указан, `YandexResourceOwner::getSex()` возвращает `null`.

## Размеры аватара

Размеры `YandexAvatarSize` соответствуют
[документации Yandex ID о данных пользователя](https://yandex.com/dev/id/doc/ru/user-information).

| PHP case | Значение Yandex | Размер |
|---|---|---|
| `Size28` | `islands-small` | 28×28 |
| `Size34` | `islands-34` | 34×34 |
| `Size42` | `islands-middle` | 42×42 |
| `Size50` | `islands-50` | 50×50 |
| `Size56` | `islands-retina-small` | 56×56 |
| `Size68` | `islands-68` | 68×68 |
| `Size75` | `islands-75` | 75×75 |
| `Size84` | `islands-retina-middle` | 84×84 |
| `Size100` | `islands-retina-50` | 100×100 |
| `Size200` | `islands-200` | 200×200 |

`islands-*` — служебные значения размера, которые Yandex использует в URL CDN
аватаров; PHP cases названы по фактическому размеру изображения.

## Пример профиля

`YandexResourceOwner::toArray()` возвращает исходную структуру ответа Yandex.
Ниже использованы вымышленные данные:

```json
{
  "id": "100000000000000",
  "login": "ivan.petrov",
  "client_id": "0123456789abcdef0123456789abcdef",
  "display_name": "Иван Петров",
  "real_name": "Иван Петров",
  "first_name": "Иван",
  "last_name": "Петров",
  "sex": "male",
  "default_email": "ivan.petrov@example.com",
  "emails": [
    "ivan.petrov@example.com"
  ],
  "birthday": "0000-12-23",
  "default_avatar_id": "12345/example-avatar-id",
  "is_avatar_empty": false,
  "default_phone": {
    "id": 12345678,
    "number": "+79001234567"
  },
  "psuid": "1.ABCdef.example-user-specific-id"
}
```

Состав ответа зависит от прав, выданных приложению. Дата рождения остаётся строкой
и может содержать неизвестные компоненты, например `0000-12-23`.

Если библиотека помогла решить задачу, поставьте звезду на GitHub — так проект будет
проще найти другим разработчикам.

Сообщения об уязвимостях принимаются по правилам [SECURITY.md](SECURITY.md).
