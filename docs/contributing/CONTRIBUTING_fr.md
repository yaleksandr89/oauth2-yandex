# Contribuer

## Choisir une langue

| Русский | English | Español | 中文 | Français | Deutsch |
|---|---|---|---|---|---|
| [Русский](../../.github/CONTRIBUTING.md) | [English](./CONTRIBUTING_en.md) | [Español](./CONTRIBUTING_es.md) | [中文](./CONTRIBUTING_zh.md) | **Français** | [Deutsch](./CONTRIBUTING_de.md) |

Merci de vouloir améliorer OAuth2 Yandex. Ce guide vous aidera à préparer une modification plus facile à relire, à intégrer en toute sécurité et à maintenir.

## Avant de commencer

- Signalez un bogue reproductible via une GitHub Issue.
- Créez une feature request pour une nouvelle fonctionnalité ou une amélioration.
- Pour un problème de sécurité, suivez la [politique de sécurité](../../.github/SECURITY.md) et ne publiez pas de détails sensibles.
- Discutez d'abord avec le mainteneur, dans une Issue, des changements importants ou incompatibles avec les versions précédentes qui touchent l'API publique, le contrat OAuth de Yandex ID ou la compatibilité avec `league/oauth2-client`.

## Contrat du package

- Le package est un provider client Yandex ID indépendant des frameworks pour `league/oauth2-client`.
- `Yandex` étend `League\OAuth2\Client\Provider\AbstractProvider` et utilise le cycle standard de grants et d'access tokens de League.
- Le profil utilisateur est représenté par `YandexResourceOwner` ; ses parties typées sont représentées par `YandexAvatar`, `YandexPhone`, `YandexSex` et `YandexAvatarSize`.
- L'access token utilisé pour demander le profil est transmis via `Authorization: OAuth <token>` et ne doit pas apparaître dans l'URL.
- Les paramètres d'autorisation spécifiques au provider, les endpoints et la gestion des erreurs doivent respecter le contrat Yandex ID actuel ainsi que l'API compatible de `league/oauth2-client`.
- Un `scope` vide ne doit pas être sérialisé sous la forme `scope=` ; les scopes non vides fournis explicitement doivent conserver le comportement attendu.
- Le package ne doit pas recevoir de dépendance spécifique à un framework sans décision séparée et justifiée.
- Les changements de l'API publique doivent être intentionnels et tenir compte de SemVer et de la compatibilité ascendante au sein de la ligne `1.x`.
- N'ajoutez pas d'abstractions, de stockage, de logging, d'adaptateurs de framework ou d'autres fonctionnalités sans rapport avec le problème traité.

## Branches

Utilisez un nom court qui reflète l'objectif de la modification, par exemple :

```text
feature/add-yandex-option
fix/oauth-error-handling
docs/update-yandex-guide
```

## Commits

Conventional Commits est recommandé. Exemples :

```text
feat: add Yandex authorization option
fix: handle Yandex OAuth error
docs: clarify native OAuth flow
test: cover malformed profile response
chore: update CI configuration
```

## Vérification locale

Installez les dépendances et exécutez l'ensemble des vérifications :

```shell
composer install
composer check
```

Les vérifications ciblées suivantes sont également disponibles :

```shell
composer test
composer analyse
composer cs
```

Exécutez `composer coverage` séparément lorsqu'un rapport de couverture est nécessaire ; il n'est pas requis pour chaque modification.

## Pull Request

Dans la description du Pull Request, indiquez :

- le problème et la modification apportée ;
- les vérifications effectuées ;
- l'impact sur l'API publique, le contrat OAuth de Yandex ID ou la compatibilité avec `league/oauth2-client` ;
- les tests ajoutés ou mis à jour ;
- les modifications de documentation ;
- si les traductions de CONTRIBUTING et SECURITY ont été synchronisées lorsque ces politiques ont changé.

Avant l'envoi, vérifiez que :

- aucun Client Secret, authorization code, access token, refresh token réel ni aucune autre configuration privée n'a été ajouté ;
- aucun profil utilisateur réel ni autre donnée personnelle ne figure dans le code, les logs, les Issues ou les données de test ;
- les fixtures et examples utilisent des valeurs synthétiques et anonymisées ;
- `vendor/`, `composer.lock`, le cache PHPUnit et les résultats de couverture n'ont pas été ajoutés au dépôt.
