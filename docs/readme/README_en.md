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

## Choose a language

| Русский | English | Español | 中文 | Français | Deutsch |
|---|---|---|---|---|---|
| [Русский](../../README.md) | **Selected** | [Español](./README_es.md) | [中文](./README_zh.md) | [Français](./README_fr.md) | [Deutsch](./README_de.md) |

`yaleksandr89/oauth2-yandex` is a Yandex ID provider client for
[`league/oauth2-client`](https://oauth2-client.thephpleague.com/).

The package implements the OAuth 2.0 Authorization Code Flow, token retrieval and refresh,
and Yandex ID user profile loading.

## Before you start

Register an OAuth application, configure the Redirect URI,
and select the required permissions before installing the package:

[Registering an application in Yandex OAuth](../guides/yandex-oauth-application_en.md)

## Installation

```bash
composer require yaleksandr89/oauth2-yandex
```

## Configuration

```php
<?php

use Yaleksandr\OAuth2\Client\Provider\Yandex;

$provider = new Yandex([
    'clientId' => 'fake-yandex-client-id',
    'clientSecret' => 'fake-yandex-client-secret',
    'redirectUri' => 'https://example.com/oauth/yandex/callback',
]);
```

Store `Client ID` and `Client Secret` in configuration or environment variables.
Do not commit `Client Secret` to the repository.

## Usage

The complete native example covers the Yandex redirect, `state` validation, PKCE,
callback handling, profile retrieval, additional authorization parameters,
and token refresh:

[Native usage with `league/oauth2-client`](../guides/native-usage_en.md)

## Symfony and KnpUOAuth2ClientBundle

In Symfony, the package is configured as a generic provider through
`knpuniversity/oauth2-client-bundle`. The dedicated guide includes client configuration,
authorization startup, token retrieval, and profile loading:

[Symfony and KnpUOAuth2ClientBundle integration](../integrations/symfony-knpu-oauth2-client-bundle_en.md)

## Available methods

The detailed reference documents the public methods of `Yandex`,
`YandexResourceOwner`, `YandexAvatar`, and `YandexPhone`, as well as the
`YandexSex` and `YandexAvatarSize` enums:

[Public API reference](../reference/api_en.md)

## Avatar sizes

The `YandexAvatarSize` values match the
[Yandex ID user information documentation](https://yandex.com/dev/id/doc/en/user-information).

| PHP case | Yandex value | Size |
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

`islands-*` values are service size identifiers used by Yandex in avatar CDN URLs;
PHP case names reflect the actual image dimensions.

## Profile example

<details>
<summary>Show the Yandex ID response structure</summary>

`YandexResourceOwner::toArray()` returns the original response structure.
The example below uses synthetic data:

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

The response content depends on the permissions granted to the application.
The birthday remains a string and may contain unknown components, for example `0000-12-23`.

</details>

---

<p align="center">
  If the library helped you solve a problem, give the repository a GitHub star — it helps
other developers discover the project. 🤘
</p>
