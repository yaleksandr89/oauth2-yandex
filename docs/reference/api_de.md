# Referenz der öffentlichen API

## Sprache auswählen

| Русский | English | Español | 中文 | Français | Deutsch |
|---|---|---|---|---|---|
| [Русский](./api.md) | [English](./api_en.md) | [Español](./api_es.md) | [中文](./api_zh.md) | [Français](./api_fr.md) | **Ausgewählt** |

[← Zurück zur README](../readme/README_de.md)

## `Yandex`

Die grundlegende OAuth-API wird von `league/oauth2-client` geerbt.

| Methode | Zweck | Beispiel |
|---|---|---|
| `getAuthorizationUrl(array $options = [])` | erstellt die Autorisierungs-URL und erzeugt `state` | `$provider->getAuthorizationUrl(['scope' => ['login:info']])` |
| `getState()` | gibt den erzeugten `state` zurück | `$provider->getState()` |
| `getPkceCode()` | gibt den erzeugten PKCE-Code zurück | `$provider->getPkceCode()` |
| `setPkceCode(string $code)` | stellt den PKCE-Code vor der Token-Anfrage wieder her | `$provider->setPkceCode($pkceCode)` |
| `getAccessToken(string $grant, array $options = [])` | ruft einen Token ab oder aktualisiert ihn | `$provider->getAccessToken('authorization_code', ['code' => $code])` |
| `getResourceOwner(AccessToken $token)` | ruft das Profil ab | `$provider->getResourceOwner($token)` |

## `YandexResourceOwner`

| Methode | Ergebnis | Beispiel |
|---|---|---|
| `getId()` | Benutzerkennung | `$owner->getId()` |
| `getLogin()` | Login | `$owner->getLogin()` |
| `getClientId()` | Kennung der OAuth-Anwendung | `$owner->getClientId()` |
| `getPsuid()` | Benutzerkennung im Kontext der OAuth-Anwendung | `$owner->getPsuid()` |
| `getOldSocialLogin()` | früherer Social Login oder `null` | `$owner->getOldSocialLogin()` |
| `getFirstName()` | Vorname oder `null` | `$owner->getFirstName()` |
| `getLastName()` | Nachname oder `null` | `$owner->getLastName()` |
| `getDisplayName()` | Anzeigename oder `null` | `$owner->getDisplayName()` |
| `getRealName()` | vollständiger Name oder `null` | `$owner->getRealName()` |
| `getSex()` | `YandexSex` oder `null` | `$owner->getSex()?->value` |
| `getDefaultEmail()` | primäre E-Mail oder `null` | `$owner->getDefaultEmail()` |
| `getEmails()` | E-Mail-Liste | `$owner->getEmails()` |
| `getBirthday()` | Datumszeichenkette oder `null` | `$owner->getBirthday()` |
| `getDefaultAvatar()` | `YandexAvatar` oder `null` | `$owner->getDefaultAvatar()?->getUrl()` |
| `getDefaultAvatarId()` | Avatar-Kennung oder `null` | `$owner->getDefaultAvatarId()` |
| `isAvatarEmpty()` | Platzhalter-Markierung oder `null` | `$owner->isAvatarEmpty()` |
| `getDefaultPhone()` | `YandexPhone` oder `null` | `$owner->getDefaultPhone()?->getNumber()` |
| `getAvatarUrl(YandexAvatarSize $size = YandexAvatarSize::Size200)` | Avatar-URL oder `null` | `$owner->getAvatarUrl(YandexAvatarSize::Size100)` |
| `toArray()` | ursprüngliches Yandex-Antwortarray | `$owner->toArray()` |

## `YandexAvatar`

| Methode | Ergebnis | Beispiel |
|---|---|---|
| `getId()` | Avatar-Kennung | `$avatar->getId()` |
| `isEmpty()` | Platzhalter-Markierung | `$avatar->isEmpty()` |
| `getUrl(YandexAvatarSize $size = YandexAvatarSize::Size200)` | URL oder `null` | `$avatar->getUrl(YandexAvatarSize::Size100)` |
| `toArray()` | Array mit `id` und `empty` | `$avatar->toArray()` |

Wenn Yandex meldet, dass die Avatar-ID auf das Standard-Platzhalterbild verweist,
gibt `getUrl()` `null` zurück.

## `YandexPhone`

| Methode | Ergebnis | Beispiel |
|---|---|---|
| `getId()` | Telefonkennung | `$phone->getId()` |
| `getNumber()` | Telefonnummer | `$phone->getNumber()` |
| `toArray()` | Array mit `id` und `number` | `$phone->toArray()` |

## `YandexSex`

| Case | Wert |
|---|---|
| `YandexSex::Male` | `male` |
| `YandexSex::Female` | `female` |

Wenn kein Geschlecht angegeben ist, gibt `YandexResourceOwner::getSex()` `null` zurück.

## `YandexAvatarSize`

| Case | Yandex-Wert | Größe |
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
