# Интеграция с Symfony и KnpUOAuth2ClientBundle

## Выберите язык

| Русский | English | Español | 中文 | Français | Deutsch |
|---|---|---|---|---|---|
| **Выбран** | [English](./symfony-knpu-oauth2-client-bundle_en.md) | [Español](./symfony-knpu-oauth2-client-bundle_es.md) | [中文](./symfony-knpu-oauth2-client-bundle_zh.md) | [Français](./symfony-knpu-oauth2-client-bundle_fr.md) | [Deutsch](./symfony-knpu-oauth2-client-bundle_de.md) |

[← Вернуться к README](../../README.md)

Инструкция показывает использование пакета в Symfony через
[`knpuniversity/oauth2-client-bundle`](https://github.com/knpuniversity/oauth2-client-bundle).

## Конфигурация клиента

Пакет подключается как generic provider. Значение `type: yandex` относится
к другому provider-пакету и здесь не используется.

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

Локальные значения:

```dotenv
OAUTH_YANDEX_CLIENT_ID=your-client-id
OAUTH_YANDEX_CLIENT_SECRET=your-client-secret
```

Не добавляйте `.env.local` и реальные OAuth-реквизиты в репозиторий.

## Запуск авторизации

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
        // Callback обрабатывается authenticator или отдельным сервисом приложения.
    }
}
```

## Получение токена и профиля

В callback или authenticator:

```php
<?php

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use RuntimeException;
use Yaleksandr\OAuth2\Client\Provider\YandexResourceOwner;

$client = $clientRegistry->getClient('yandex_main');

// 1. Bundle проверяет callback и получает access token.
$accessToken = $client->getAccessToken();

// 2. Provider запрашивает профиль Yandex ID.
/** @var YandexResourceOwner $owner */
$owner = $client->fetchUserFromToken($accessToken);

// 3. Приложение использует типизированные поля профиля.
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

Bundle использует `state` для проверки OAuth callback. Не отключайте эту проверку
без отдельной архитектурной причины.

## PKCE

Сам provider поддерживает PKCE через API `league/oauth2-client`.
При использовании bundle приложение должно сохранить PKCE code перед redirect
и восстановить его перед запросом токена. Готовая схема зависит от того,
где именно приложение обрабатывает callback и хранит session state.

Нативный пример с PKCE приведён в
[отдельной инструкции](../guides/native-usage.md).
