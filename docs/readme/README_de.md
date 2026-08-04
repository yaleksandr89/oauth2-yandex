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

## Sprache auswählen

| Русский | English | Español | 中文 | Français | Deutsch |
|---|---|---|---|---|---|
| [Русский](../../README.md) | [English](./README_en.md) | [Español](./README_es.md) | [中文](./README_zh.md) | [Français](./README_fr.md) | **Ausgewählt** |

`yaleksandr89/oauth2-yandex` ist ein Yandex-ID-Provider für
[`league/oauth2-client`](https://oauth2-client.thephpleague.com/).

Das Paket implementiert den OAuth 2.0 Authorization Code Flow, das Abrufen und
Aktualisieren von Tokens sowie das Laden des Benutzerprofils über die Yandex-ID-API.

## Vorbereitung

Registrieren Sie vor der Installation eine OAuth-Anwendung, konfigurieren Sie den Redirect URI
und wählen Sie die benötigten Berechtigungen aus:

[Anwendung in Yandex OAuth registrieren](../guides/yandex-oauth-application_de.md)

## Installation

```bash
composer require yaleksandr89/oauth2-yandex
```

## Konfiguration

```php
<?php

use Yaleksandr\OAuth2\Client\Provider\Yandex;

$provider = new Yandex([
    'clientId' => 'fake-yandex-client-id',
    'clientSecret' => 'fake-yandex-client-secret',
    'redirectUri' => 'https://example.com/oauth/yandex/callback',
]);
```

Speichern Sie `Client ID` und `Client Secret` in der Konfiguration oder in Umgebungsvariablen.
Fügen Sie `Client Secret` nicht dem Repository hinzu.

## Verwendung

Das vollständige native Beispiel zeigt die Weiterleitung zu Yandex, die Prüfung von `state`,
PKCE, die Callback-Verarbeitung, das Laden des Profils, zusätzliche
Autorisierungsparameter und die Token-Aktualisierung:

[Native Verwendung mit `league/oauth2-client`](../guides/native-usage_de.md)

## Symfony und KnpUOAuth2ClientBundle

In Symfony wird das Paket über `knpuniversity/oauth2-client-bundle`
als generic provider konfiguriert. Die separate Anleitung enthält Client-Konfiguration,
Start der Autorisierung, Token-Abruf und Laden des Profils:

[Integration mit Symfony und KnpUOAuth2ClientBundle](../integrations/symfony-knpu-oauth2-client-bundle_de.md)

## Verfügbare Methoden

Die ausführliche Referenz beschreibt die öffentlichen Methoden von `Yandex`,
`YandexResourceOwner`, `YandexAvatar` und `YandexPhone` sowie die Enums
`YandexSex` und `YandexAvatarSize`:

[Referenz der öffentlichen API](../reference/api_de.md)

## Avatargrößen

Die Werte von `YandexAvatarSize` entsprechen der
[Yandex-ID-Dokumentation zu Benutzerdaten](https://yandex.com/dev/id/doc/en/user-information).

| PHP case | Yandex-Wert | Größe |
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

`islands-*` sind technische Größenkennungen, die Yandex in Avatar-CDN-URLs verwendet;
die PHP-Cases sind nach den tatsächlichen Bildabmessungen benannt.

## Profilbeispiel

<details>
<summary>Struktur der Yandex-ID-Antwort anzeigen</summary>

`YandexResourceOwner::toArray()` gibt die ursprüngliche Antwortstruktur zurück.
Das folgende Beispiel verwendet fiktive Daten:

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

Der Inhalt der Antwort hängt von den der Anwendung gewährten Berechtigungen ab.
Das Geburtsdatum bleibt eine Zeichenkette und kann unbekannte Bestandteile enthalten,
zum Beispiel `0000-12-23`.

</details>

---

<p align="center">
  Wenn die Bibliothek bei der Lösung einer Aufgabe geholfen hat, geben Sie dem Repository
einen GitHub-Stern — so finden andere Entwickler das Projekt leichter. 🤘
</p>
