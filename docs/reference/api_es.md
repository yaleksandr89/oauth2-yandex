# Referencia de la API pública

## Elija un idioma

| Русский | English | Español | 中文 | Français | Deutsch |
|---|---|---|---|---|---|
| [Русский](./api.md) | [English](./api_en.md) | **Seleccionado** | [中文](./api_zh.md) | [Français](./api_fr.md) | [Deutsch](./api_de.md) |

[← Volver al README](../readme/README_es.md)

## `Yandex`

La API OAuth principal se hereda de `league/oauth2-client`.

| Método | Finalidad | Ejemplo |
|---|---|---|
| `getAuthorizationUrl(array $options = [])` | genera la URL de autorización y crea `state` | `$provider->getAuthorizationUrl(['scope' => ['login:info']])` |
| `getState()` | devuelve el `state` generado | `$provider->getState()` |
| `getPkceCode()` | devuelve el código PKCE generado | `$provider->getPkceCode()` |
| `setPkceCode(string $code)` | restaura el código PKCE antes de solicitar el token | `$provider->setPkceCode($pkceCode)` |
| `getAccessToken(string $grant, array $options = [])` | obtiene o renueva un token | `$provider->getAccessToken('authorization_code', ['code' => $code])` |
| `getResourceOwner(AccessToken $token)` | solicita el perfil | `$provider->getResourceOwner($token)` |

## `YandexResourceOwner`

| Método | Resultado | Ejemplo |
|---|---|---|
| `getId()` | identificador del usuario | `$owner->getId()` |
| `getLogin()` | login | `$owner->getLogin()` |
| `getClientId()` | identificador de la aplicación OAuth | `$owner->getClientId()` |
| `getPsuid()` | identificador del usuario en el contexto de la aplicación OAuth | `$owner->getPsuid()` |
| `getOldSocialLogin()` | social login anterior o `null` | `$owner->getOldSocialLogin()` |
| `getFirstName()` | nombre o `null` | `$owner->getFirstName()` |
| `getLastName()` | apellido o `null` | `$owner->getLastName()` |
| `getDisplayName()` | nombre visible o `null` | `$owner->getDisplayName()` |
| `getRealName()` | nombre completo o `null` | `$owner->getRealName()` |
| `getSex()` | `YandexSex` o `null` | `$owner->getSex()?->value` |
| `getDefaultEmail()` | email principal o `null` | `$owner->getDefaultEmail()` |
| `getEmails()` | lista de emails | `$owner->getEmails()` |
| `getBirthday()` | cadena de fecha o `null` | `$owner->getBirthday()` |
| `getDefaultAvatar()` | `YandexAvatar` o `null` | `$owner->getDefaultAvatar()?->getUrl()` |
| `getDefaultAvatarId()` | identificador del avatar o `null` | `$owner->getDefaultAvatarId()` |
| `isAvatarEmpty()` | indicador de placeholder o `null` | `$owner->isAvatarEmpty()` |
| `getDefaultPhone()` | `YandexPhone` o `null` | `$owner->getDefaultPhone()?->getNumber()` |
| `getAvatarUrl(YandexAvatarSize $size = YandexAvatarSize::Size200)` | URL del avatar o `null` | `$owner->getAvatarUrl(YandexAvatarSize::Size100)` |
| `toArray()` | array original de la respuesta de Yandex | `$owner->toArray()` |

## `YandexAvatar`

| Método | Resultado | Ejemplo |
|---|---|---|
| `getId()` | identificador del avatar | `$avatar->getId()` |
| `isEmpty()` | indicador de placeholder | `$avatar->isEmpty()` |
| `getUrl(YandexAvatarSize $size = YandexAvatarSize::Size200)` | URL o `null` | `$avatar->getUrl(YandexAvatarSize::Size100)` |
| `toArray()` | array con `id` y `empty` | `$avatar->toArray()` |

Cuando Yandex indica que el avatar ID apunta al placeholder estándar,
`getUrl()` devuelve `null`.

## `YandexPhone`

| Método | Resultado | Ejemplo |
|---|---|---|
| `getId()` | identificador del teléfono | `$phone->getId()` |
| `getNumber()` | número de teléfono | `$phone->getNumber()` |
| `toArray()` | array con `id` y `number` | `$phone->toArray()` |

## `YandexSex`

| Case | Valor |
|---|---|
| `YandexSex::Male` | `male` |
| `YandexSex::Female` | `female` |

Si no se proporciona el sexo, `YandexResourceOwner::getSex()` devuelve `null`.

## `YandexAvatarSize`

| Case | Valor de Yandex | Tamaño |
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
