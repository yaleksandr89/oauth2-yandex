# Public API reference

## Choose a language

| Русский | English | Español | 中文 | Français | Deutsch |
|---|---|---|---|---|---|
| [Русский](./api.md) | **Selected** | [Español](./api_es.md) | [中文](./api_zh.md) | [Français](./api_fr.md) | [Deutsch](./api_de.md) |

[← Back to README](../readme/README_en.md)

## `Yandex`

The core OAuth API is inherited from `league/oauth2-client`.

| Method | Purpose | Example |
|---|---|---|
| `getAuthorizationUrl(array $options = [])` | builds the authorization URL and creates `state` | `$provider->getAuthorizationUrl(['scope' => ['login:info']])` |
| `getState()` | returns the generated `state` | `$provider->getState()` |
| `getPkceCode()` | returns the generated PKCE code | `$provider->getPkceCode()` |
| `setPkceCode(string $code)` | restores the PKCE code before requesting a token | `$provider->setPkceCode($pkceCode)` |
| `getAccessToken(string $grant, array $options = [])` | obtains or refreshes a token | `$provider->getAccessToken('authorization_code', ['code' => $code])` |
| `getResourceOwner(AccessToken $token)` | requests the user profile | `$provider->getResourceOwner($token)` |

## `YandexResourceOwner`

| Method | Result | Example |
|---|---|---|
| `getId()` | user identifier | `$owner->getId()` |
| `getLogin()` | login | `$owner->getLogin()` |
| `getClientId()` | OAuth application identifier | `$owner->getClientId()` |
| `getPsuid()` | user identifier within the OAuth application context | `$owner->getPsuid()` |
| `getOldSocialLogin()` | legacy social login or `null` | `$owner->getOldSocialLogin()` |
| `getFirstName()` | first name or `null` | `$owner->getFirstName()` |
| `getLastName()` | last name or `null` | `$owner->getLastName()` |
| `getDisplayName()` | display name or `null` | `$owner->getDisplayName()` |
| `getRealName()` | full name or `null` | `$owner->getRealName()` |
| `getSex()` | `YandexSex` or `null` | `$owner->getSex()?->value` |
| `getDefaultEmail()` | primary email or `null` | `$owner->getDefaultEmail()` |
| `getEmails()` | email list | `$owner->getEmails()` |
| `getBirthday()` | date string or `null` | `$owner->getBirthday()` |
| `getDefaultAvatar()` | `YandexAvatar` or `null` | `$owner->getDefaultAvatar()?->getUrl()` |
| `getDefaultAvatarId()` | avatar identifier or `null` | `$owner->getDefaultAvatarId()` |
| `isAvatarEmpty()` | placeholder flag or `null` | `$owner->isAvatarEmpty()` |
| `getDefaultPhone()` | `YandexPhone` or `null` | `$owner->getDefaultPhone()?->getNumber()` |
| `getAvatarUrl(YandexAvatarSize $size = YandexAvatarSize::Size200)` | avatar URL or `null` | `$owner->getAvatarUrl(YandexAvatarSize::Size100)` |
| `toArray()` | original Yandex response array | `$owner->toArray()` |

## `YandexAvatar`

| Method | Result | Example |
|---|---|---|
| `getId()` | avatar identifier | `$avatar->getId()` |
| `isEmpty()` | placeholder flag | `$avatar->isEmpty()` |
| `getUrl(YandexAvatarSize $size = YandexAvatarSize::Size200)` | URL or `null` | `$avatar->getUrl(YandexAvatarSize::Size100)` |
| `toArray()` | array with `id` and `empty` | `$avatar->toArray()` |

When Yandex reports that the avatar ID points to the standard placeholder,
`getUrl()` returns `null`.

## `YandexPhone`

| Method | Result | Example |
|---|---|---|
| `getId()` | phone identifier | `$phone->getId()` |
| `getNumber()` | phone number | `$phone->getNumber()` |
| `toArray()` | array with `id` and `number` | `$phone->toArray()` |

## `YandexSex`

| Case | Value |
|---|---|
| `YandexSex::Male` | `male` |
| `YandexSex::Female` | `female` |

When sex is not provided, `YandexResourceOwner::getSex()` returns `null`.

## `YandexAvatarSize`

| Case | Yandex value | Size |
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
