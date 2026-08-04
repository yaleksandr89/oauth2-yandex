# Symfony 与 KnpUOAuth2ClientBundle 集成

## 选择语言

| Русский | English | Español | 中文 | Français | Deutsch |
|---|---|---|---|---|---|
| [Русский](./symfony-knpu-oauth2-client-bundle.md) | [English](./symfony-knpu-oauth2-client-bundle_en.md) | [Español](./symfony-knpu-oauth2-client-bundle_es.md) | **已选择** | [Français](./symfony-knpu-oauth2-client-bundle_fr.md) | [Deutsch](./symfony-knpu-oauth2-client-bundle_de.md) |

[← 返回 README](../readme/README_zh.md)

本指南说明如何在 Symfony 中通过
[`knpuniversity/oauth2-client-bundle`](https://github.com/knpuniversity/oauth2-client-bundle) 使用该包。

## 客户端配置

该包作为 generic provider 配置。`type: yandex` 属于另一个 provider 包，
这里不使用。

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

本地配置值：

```dotenv
OAUTH_YANDEX_CLIENT_ID=your-client-id
OAUTH_YANDEX_CLIENT_SECRET=your-client-secret
```

不要将 `.env.local` 或真实 OAuth 凭据提交到仓库。

## 发起授权

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

## 获取 token 和用户资料

在 callback 或 authenticator 中：

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

Bundle 使用 `state` 校验 OAuth callback。除非有明确的架构原因，
不要关闭该检查。

## PKCE

Provider 通过 `league/oauth2-client` API 支持 PKCE。
使用 bundle 时，应用必须在 redirect 前保存 PKCE code，
并在请求 token 前恢复它。具体实现取决于 callback 的处理位置
以及 session state 的存储方式。

原生 PKCE 示例位于
[单独指南](../guides/native-usage_zh.md)。
