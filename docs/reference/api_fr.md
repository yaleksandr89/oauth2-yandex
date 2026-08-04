# Référence de l'API publique

## Choisir une langue

| Русский | English | Español | 中文 | Français | Deutsch |
|---|---|---|---|---|---|
| [Русский](./api.md) | [English](./api_en.md) | [Español](./api_es.md) | [中文](./api_zh.md) | **Sélectionné** | [Deutsch](./api_de.md) |

[← Retour au README](../readme/README_fr.md)

## `Yandex`

L'API OAuth principale est héritée de `league/oauth2-client`.

| Méthode | Rôle | Exemple |
|---|---|---|
| `getAuthorizationUrl(array $options = [])` | construit l'URL d'autorisation et crée `state` | `$provider->getAuthorizationUrl(['scope' => ['login:info']])` |
| `getState()` | renvoie le `state` généré | `$provider->getState()` |
| `getPkceCode()` | renvoie le code PKCE généré | `$provider->getPkceCode()` |
| `setPkceCode(string $code)` | restaure le code PKCE avant la requête de token | `$provider->setPkceCode($pkceCode)` |
| `getAccessToken(string $grant, array $options = [])` | obtient ou renouvelle un token | `$provider->getAccessToken('authorization_code', ['code' => $code])` |
| `getResourceOwner(AccessToken $token)` | demande le profil | `$provider->getResourceOwner($token)` |

## `YandexResourceOwner`

| Méthode | Résultat | Exemple |
|---|---|---|
| `getId()` | identifiant utilisateur | `$owner->getId()` |
| `getLogin()` | login | `$owner->getLogin()` |
| `getClientId()` | identifiant de l'application OAuth | `$owner->getClientId()` |
| `getPsuid()` | identifiant utilisateur dans le contexte de l'application OAuth | `$owner->getPsuid()` |
| `getOldSocialLogin()` | ancien social login ou `null` | `$owner->getOldSocialLogin()` |
| `getFirstName()` | prénom ou `null` | `$owner->getFirstName()` |
| `getLastName()` | nom ou `null` | `$owner->getLastName()` |
| `getDisplayName()` | nom affiché ou `null` | `$owner->getDisplayName()` |
| `getRealName()` | nom complet ou `null` | `$owner->getRealName()` |
| `getSex()` | `YandexSex` ou `null` | `$owner->getSex()?->value` |
| `getDefaultEmail()` | email principal ou `null` | `$owner->getDefaultEmail()` |
| `getEmails()` | liste d'emails | `$owner->getEmails()` |
| `getBirthday()` | chaîne de date ou `null` | `$owner->getBirthday()` |
| `getDefaultAvatar()` | `YandexAvatar` ou `null` | `$owner->getDefaultAvatar()?->getUrl()` |
| `getDefaultAvatarId()` | identifiant d'avatar ou `null` | `$owner->getDefaultAvatarId()` |
| `isAvatarEmpty()` | indicateur d'image par défaut ou `null` | `$owner->isAvatarEmpty()` |
| `getDefaultPhone()` | `YandexPhone` ou `null` | `$owner->getDefaultPhone()?->getNumber()` |
| `getAvatarUrl(YandexAvatarSize $size = YandexAvatarSize::Size200)` | URL de l'avatar ou `null` | `$owner->getAvatarUrl(YandexAvatarSize::Size100)` |
| `toArray()` | tableau original de la réponse Yandex | `$owner->toArray()` |

## `YandexAvatar`

| Méthode | Résultat | Exemple |
|---|---|---|
| `getId()` | identifiant de l'avatar | `$avatar->getId()` |
| `isEmpty()` | indicateur d'image par défaut | `$avatar->isEmpty()` |
| `getUrl(YandexAvatarSize $size = YandexAvatarSize::Size200)` | URL ou `null` | `$avatar->getUrl(YandexAvatarSize::Size100)` |
| `toArray()` | tableau avec `id` et `empty` | `$avatar->toArray()` |

Lorsque Yandex indique que l'avatar ID pointe vers l'image par défaut,
`getUrl()` renvoie `null`.

## `YandexPhone`

| Méthode | Résultat | Exemple |
|---|---|---|
| `getId()` | identifiant du téléphone | `$phone->getId()` |
| `getNumber()` | numéro de téléphone | `$phone->getNumber()` |
| `toArray()` | tableau avec `id` et `number` | `$phone->toArray()` |

## `YandexSex`

| Case | Valeur |
|---|---|
| `YandexSex::Male` | `male` |
| `YandexSex::Female` | `female` |

Si le sexe n'est pas fourni, `YandexResourceOwner::getSex()` renvoie `null`.

## `YandexAvatarSize`

| Case | Valeur Yandex | Taille |
|---|---|---|
| `YandexAvatarSize::Size28` | `islands-small` | 28×28 |
| `YandexAvatarSize::Size34` | `islands-34` | 34×34 |
| `YandexAvatarSize::Size42` | `islands-middle` | 42×42 |
| `YandexAvatarSize::Size50` | `islands-50` | 50×50 |
| `YandexAvatarSize::Size56` | `islands-retina-small` | 56×56 |
| `YandexAvatarSize::Size68` | `islands-68` | 68×68 |
| `YandexAvatarSize::Size75` | `islands-75` | 75×75 |
| `YandexAvatarSize::Size84` | `islands-retina-middle` | 84×84 |
| `YandexAvatarSize::Size100` | `islands-retina-50` | 100×100 |
| `YandexAvatarSize::Size200` | `islands-200` | 200×200 |
