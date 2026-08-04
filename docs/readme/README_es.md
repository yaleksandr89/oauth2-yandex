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

## Elija un idioma

| Русский | English | Español | 中文 | Français | Deutsch |
|---|---|---|---|---|---|
| [Русский](../../README.md) | [English](./README_en.md) | **Seleccionado** | [中文](./README_zh.md) | [Français](./README_fr.md) | [Deutsch](./README_de.md) |

`yaleksandr89/oauth2-yandex` es un provider de Yandex ID para
[`league/oauth2-client`](https://oauth2-client.thephpleague.com/).

El paquete implementa OAuth 2.0 Authorization Code Flow, la obtención y renovación
de tokens y la carga del perfil del usuario mediante la API de Yandex ID.

## Antes de comenzar

Registre una aplicación OAuth, configure el Redirect URI
y seleccione los permisos necesarios antes de instalar el paquete:

[Registrar una aplicación en Yandex OAuth](../guides/yandex-oauth-application_es.md)

## Instalación

```bash
composer require yaleksandr89/oauth2-yandex
```

## Configuración

```php
<?php

use Yaleksandr\OAuth2\Client\Provider\Yandex;

$provider = new Yandex([
    'clientId' => 'fake-yandex-client-id',
    'clientSecret' => 'fake-yandex-client-secret',
    'redirectUri' => 'https://example.com/oauth/yandex/callback',
]);
```

Guarde `Client ID` y `Client Secret` en la configuración o en variables de entorno.
No añada `Client Secret` al repositorio.

## Uso

El ejemplo nativo completo muestra la redirección a Yandex, la validación de `state`,
PKCE, el procesamiento del callback, la obtención del perfil, parámetros adicionales
de autorización y la renovación del token:

[Uso nativo con `league/oauth2-client`](../guides/native-usage_es.md)

## Symfony y KnpUOAuth2ClientBundle

En Symfony, el paquete se configura como generic provider mediante
`knpuniversity/oauth2-client-bundle`. La guía incluye la configuración del cliente,
el inicio de la autorización, la obtención del token y la carga del perfil:

[Integración con Symfony y KnpUOAuth2ClientBundle](../integrations/symfony-knpu-oauth2-client-bundle_es.md)

## Métodos disponibles

La referencia detallada describe los métodos públicos de `Yandex`,
`YandexResourceOwner`, `YandexAvatar` y `YandexPhone`, así como los enums
`YandexSex` y `YandexAvatarSize`:

[Referencia de la API pública](../reference/api_es.md)

## Tamaños del avatar

Los valores de `YandexAvatarSize` corresponden a la
[documentación de Yandex ID sobre datos del usuario](https://yandex.com/dev/id/doc/en/user-information).

| PHP case | Valor de Yandex | Tamaño |
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

Los valores `islands-*` son identificadores internos de tamaño utilizados por Yandex
en las URL del CDN de avatares; los cases de PHP reflejan el tamaño real de la imagen.

## Ejemplo de perfil

<details>
<summary>Mostrar la estructura de la respuesta de Yandex ID</summary>

`YandexResourceOwner::toArray()` devuelve la estructura original de la respuesta.
El ejemplo utiliza datos ficticios:

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

El contenido de la respuesta depende de los permisos concedidos a la aplicación.
La fecha de nacimiento sigue siendo una cadena y puede contener componentes desconocidos,
por ejemplo `0000-12-23`.

</details>

---

<p align="center">
  Si la biblioteca le ayudó a resolver una tarea, añada una estrella en GitHub — así será
más fácil que otros desarrolladores encuentren el proyecto. 🤘
</p>
