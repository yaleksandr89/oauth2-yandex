# OAuth2 Yandex

[![Source Code](https://img.shields.io/badge/source-yaleksandr89%2Foauth2--yandex-blue.svg?style=flat-square)](https://github.com/yaleksandr89/oauth2-yandex)
[![CI](https://github.com/yaleksandr89/oauth2-yandex/actions/workflows/basic.yml/badge.svg)](https://github.com/yaleksandr89/oauth2-yandex/actions/workflows/basic.yml)
[![Codecov](https://codecov.io/gh/yaleksandr89/oauth2-yandex/graph/badge.svg)](https://codecov.io/gh/yaleksandr89/oauth2-yandex)
[![Latest Stable Version](https://img.shields.io/packagist/v/yaleksandr89/oauth2-yandex.svg?style=flat-square)](https://packagist.org/packages/yaleksandr89/oauth2-yandex)
[![Total Downloads](https://img.shields.io/packagist/dt/yaleksandr89/oauth2-yandex.svg?style=flat-square)](https://packagist.org/packages/yaleksandr89/oauth2-yandex)
[![PHP](https://img.shields.io/badge/PHP-8.3--8.5-777BB4.svg?style=flat-square&logo=php&logoColor=white)](https://www.php.net/)
[![League OAuth2 Client](https://img.shields.io/badge/league%2Foauth2--client-%5E2.9-4F5B93.svg?style=flat-square)](https://oauth2-client.thephpleague.com/)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](../../LICENSE)

<p align="center">
  <img
    src="../assets/oauth2-yandex-readme-cover.png"
    alt="OAuth2 Yandex — Yandex ID provider client for league/oauth2-client"
    width="100%"
  >
</p>

## 选择语言

| Русский | English | Español | 中文 | Français | Deutsch |
|---|---|---|---|---|---|
| [Русский](../../README.md) | [English](./README_en.md) | [Español](./README_es.md) | **已选择** | [Français](./README_fr.md) | [Deutsch](./README_de.md) |

`yaleksandr89/oauth2-yandex` 是面向
[`league/oauth2-client`](https://oauth2-client.thephpleague.com/) 的 Yandex ID provider 客户端。

该包实现 OAuth 2.0 Authorization Code Flow、token 获取与刷新，
以及通过 Yandex ID API 加载用户资料。

## 开始之前

安装包之前，请先注册 OAuth 应用、配置 Redirect URI，
并选择所需权限：

[在 Yandex OAuth 中注册应用](../guides/yandex-oauth-application_zh.md)

## 安装

```bash
composer require yaleksandr89/oauth2-yandex
```

## 配置

```php
<?php

use Yaleksandr\OAuth2\Client\Provider\Yandex;

$provider = new Yandex([
    'clientId' => 'fake-yandex-client-id',
    'clientSecret' => 'fake-yandex-client-secret',
    'redirectUri' => 'https://example.com/oauth/yandex/callback',
]);
```

请将 `Client ID` 和 `Client Secret` 保存在配置或环境变量中。
不要将 `Client Secret` 提交到仓库。

## 使用方法

完整的原生示例包含跳转到 Yandex、`state` 校验、PKCE、
callback 处理、用户资料获取、附加授权参数以及 token 刷新：

[使用 `league/oauth2-client` 的原生示例](../guides/native-usage_zh.md)

## Symfony 与 KnpUOAuth2ClientBundle

在 Symfony 中，该包通过 `knpuniversity/oauth2-client-bundle`
作为 generic provider 配置。单独的指南包含客户端配置、发起授权、
获取 token 和加载用户资料：

[Symfony 与 KnpUOAuth2ClientBundle 集成](../integrations/symfony-knpu-oauth2-client-bundle_zh.md)

## 可用方法

详细参考文档说明 `Yandex`、`YandexResourceOwner`、
`YandexAvatar`、`YandexPhone` 的公共方法，以及
`YandexSex` 和 `YandexAvatarSize` enum：

[公共 API 参考](../reference/api_zh.md)

## 头像尺寸

`YandexAvatarSize` 的值与
[Yandex ID 用户信息文档](https://yandex.com/dev/id/doc/en/user-information)一致。

| PHP case | Yandex 值 | 尺寸 |
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

`islands-*` 是 Yandex 在头像 CDN URL 中使用的内部尺寸标识；
PHP case 名称反映实际图片尺寸。

## 资料示例

<details>
<summary>显示 Yandex ID 响应结构</summary>

`YandexResourceOwner::toArray()` 返回原始响应结构。
以下示例使用虚构数据：

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
  "emails": ["ivan.petrov@example.com"],
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

响应内容取决于应用获得的权限。生日字段仍为字符串，
并且可能包含未知部分，例如 `0000-12-23`。

</details>

---

<p align="center">
  如果这个库解决了您的问题，请在 GitHub 上给仓库点星 — 这样其他开发者
更容易发现该项目。🤘
</p>
