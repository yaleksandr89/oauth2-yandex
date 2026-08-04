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

## Choisir une langue

| Русский | English | Español | 中文 | Français | Deutsch |
|---|---|---|---|---|---|
| [Русский](../../README.md) | [English](./README_en.md) | [Español](./README_es.md) | [中文](./README_zh.md) | **Sélectionné** | [Deutsch](./README_de.md) |

`yaleksandr89/oauth2-yandex` est un provider Yandex ID pour
[`league/oauth2-client`](https://oauth2-client.thephpleague.com/).

Le package implémente l'OAuth 2.0 Authorization Code Flow, l'obtention et le
renouvellement des tokens, ainsi que le chargement du profil utilisateur via l'API Yandex ID.

## Avant de commencer

Enregistrez une application OAuth, configurez le Redirect URI
et sélectionnez les permissions nécessaires avant d'installer le package :

[Enregistrer une application dans Yandex OAuth](../guides/yandex-oauth-application_fr.md)

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

Stockez `Client ID` et `Client Secret` dans la configuration ou des variables d'environnement.
N'ajoutez pas `Client Secret` au dépôt.

## Utilisation

L'exemple natif complet couvre la redirection vers Yandex, la validation de `state`,
PKCE, le traitement du callback, la récupération du profil, les paramètres
d'autorisation supplémentaires et le renouvellement du token :

[Utilisation native avec `league/oauth2-client`](../guides/native-usage_fr.md)

## Symfony et KnpUOAuth2ClientBundle

Dans Symfony, le package est configuré comme generic provider via
`knpuniversity/oauth2-client-bundle`. Le guide dédié inclut la configuration du client,
le lancement de l'autorisation, l'obtention du token et le chargement du profil :

[Intégration Symfony et KnpUOAuth2ClientBundle](../integrations/symfony-knpu-oauth2-client-bundle_fr.md)

## Méthodes disponibles

La référence détaillée décrit les méthodes publiques de `Yandex`,
`YandexResourceOwner`, `YandexAvatar` et `YandexPhone`, ainsi que les enums
`YandexSex` et `YandexAvatarSize` :

[Référence de l'API publique](../reference/api_fr.md)

## Tailles d'avatar

Les valeurs de `YandexAvatarSize` correspondent à la
[documentation Yandex ID sur les données utilisateur](https://yandex.com/dev/id/doc/en/user-information).

| PHP case | Valeur Yandex | Taille |
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

Les valeurs `islands-*` sont des identifiants techniques de taille utilisés par Yandex
dans les URL du CDN d'avatars ; les cases PHP reflètent les dimensions réelles.

## Exemple de profil

<details>
<summary>Afficher la structure de réponse Yandex ID</summary>

`YandexResourceOwner::toArray()` renvoie la structure originale de la réponse.
L'exemple ci-dessous utilise des données fictives :

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

Le contenu de la réponse dépend des permissions accordées à l'application.
La date de naissance reste une chaîne et peut contenir des composants inconnus,
par exemple `0000-12-23`.

</details>

---

<p align="center">
  Si la bibliothèque vous a aidé à résoudre un problème, ajoutez une étoile sur GitHub —
le projet sera ainsi plus facile à découvrir par d'autres développeurs. 🤘
</p>
