# Справочник публичного API

## Выберите язык

| Русский | English | Español | 中文 | Français | Deutsch |
|---|---|---|---|---|---|
| **Выбран** | [English](./api_en.md) | [Español](./api_es.md) | [中文](./api_zh.md) | [Français](./api_fr.md) | [Deutsch](./api_de.md) |

[← Вернуться к README](../../README.md)

## `Yandex`

Основной OAuth API наследуется от `league/oauth2-client`.

| Метод | Назначение | Пример |
|---|---|---|
| `getAuthorizationUrl(array $options = [])` | формирует URL авторизации и создаёт `state` | `$provider->getAuthorizationUrl(['scope' => ['login:info']])` |
| `getState()` | возвращает созданный `state` | `$provider->getState()` |
| `getPkceCode()` | возвращает созданный PKCE code | `$provider->getPkceCode()` |
| `setPkceCode(string $code)` | восстанавливает PKCE code перед запросом токена | `$provider->setPkceCode($pkceCode)` |
| `getAccessToken(string $grant, array $options = [])` | получает или обновляет токен | `$provider->getAccessToken('authorization_code', ['code' => $code])` |
| `getResourceOwner(AccessToken $token)` | запрашивает профиль | `$provider->getResourceOwner($token)` |

## `YandexResourceOwner`

| Метод | Результат | Пример |
|---|---|---|
| `getId()` | идентификатор пользователя | `$owner->getId()` |
| `getLogin()` | логин | `$owner->getLogin()` |
| `getClientId()` | идентификатор OAuth-приложения | `$owner->getClientId()` |
| `getPsuid()` | идентификатор пользователя в контексте OAuth-приложения | `$owner->getPsuid()` |
| `getOldSocialLogin()` | прежний social login или `null` | `$owner->getOldSocialLogin()` |
| `getFirstName()` | имя или `null` | `$owner->getFirstName()` |
| `getLastName()` | фамилия или `null` | `$owner->getLastName()` |
| `getDisplayName()` | отображаемое имя или `null` | `$owner->getDisplayName()` |
| `getRealName()` | полное имя или `null` | `$owner->getRealName()` |
| `getSex()` | `YandexSex` или `null` | `$owner->getSex()?->value` |
| `getDefaultEmail()` | основной email или `null` | `$owner->getDefaultEmail()` |
| `getEmails()` | список email | `$owner->getEmails()` |
| `getBirthday()` | строка даты или `null` | `$owner->getBirthday()` |
| `getDefaultAvatar()` | `YandexAvatar` или `null` | `$owner->getDefaultAvatar()?->getUrl()` |
| `getDefaultAvatarId()` | идентификатор аватара или `null` | `$owner->getDefaultAvatarId()` |
| `isAvatarEmpty()` | признак заглушки или `null` | `$owner->isAvatarEmpty()` |
| `getDefaultPhone()` | `YandexPhone` или `null` | `$owner->getDefaultPhone()?->getNumber()` |
| `getAvatarUrl(YandexAvatarSize $size = YandexAvatarSize::Size200)` | URL аватара или `null` | `$owner->getAvatarUrl(YandexAvatarSize::Size100)` |
| `toArray()` | исходный массив ответа Yandex | `$owner->toArray()` |

## `YandexAvatar`

| Метод | Результат | Пример |
|---|---|---|
| `getId()` | идентификатор аватара | `$avatar->getId()` |
| `isEmpty()` | признак заглушки | `$avatar->isEmpty()` |
| `getUrl(YandexAvatarSize $size = YandexAvatarSize::Size200)` | URL или `null` | `$avatar->getUrl(YandexAvatarSize::Size100)` |
| `toArray()` | массив с `id` и `empty` | `$avatar->toArray()` |

Если Yandex сообщил, что avatar ID указывает на стандартную заглушку,
`getUrl()` возвращает `null`.

## `YandexPhone`

| Метод | Результат | Пример |
|---|---|---|
| `getId()` | идентификатор телефона | `$phone->getId()` |
| `getNumber()` | номер телефона | `$phone->getNumber()` |
| `toArray()` | массив с `id` и `number` | `$phone->toArray()` |

## `YandexSex`

| Case | Значение |
|---|---|
| `YandexSex::Male` | `male` |
| `YandexSex::Female` | `female` |

Если пол не указан, `YandexResourceOwner::getSex()` возвращает `null`.

## `YandexAvatarSize`

| Case | Значение Yandex | Размер |
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
