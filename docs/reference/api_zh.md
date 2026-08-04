# 公共 API 参考

## 选择语言

| Русский | English | Español | 中文 | Français | Deutsch |
|---|---|---|---|---|---|
| [Русский](./api.md) | [English](./api_en.md) | [Español](./api_es.md) | **已选择** | [Français](./api_fr.md) | [Deutsch](./api_de.md) |

[← 返回 README](../readme/README_zh.md)

## `Yandex`

核心 OAuth API 继承自 `league/oauth2-client`。

| 方法 | 用途 | 示例 |
|---|---|---|
| `getAuthorizationUrl(array $options = [])` | 生成授权 URL 并创建 `state` | `$provider->getAuthorizationUrl(['scope' => ['login:info']])` |
| `getState()` | 返回生成的 `state` | `$provider->getState()` |
| `getPkceCode()` | 返回生成的 PKCE code | `$provider->getPkceCode()` |
| `setPkceCode(string $code)` | 在请求 token 前恢复 PKCE code | `$provider->setPkceCode($pkceCode)` |
| `getAccessToken(string $grant, array $options = [])` | 获取或刷新 token | `$provider->getAccessToken('authorization_code', ['code' => $code])` |
| `getResourceOwner(AccessToken $token)` | 请求用户资料 | `$provider->getResourceOwner($token)` |

## `YandexResourceOwner`

| 方法 | 结果 | 示例 |
|---|---|---|
| `getId()` | 用户标识符 | `$owner->getId()` |
| `getLogin()` | login | `$owner->getLogin()` |
| `getClientId()` | OAuth 应用标识符 | `$owner->getClientId()` |
| `getPsuid()` | OAuth 应用上下文中的用户标识符 | `$owner->getPsuid()` |
| `getOldSocialLogin()` | 旧 social login 或 `null` | `$owner->getOldSocialLogin()` |
| `getFirstName()` | 名或 `null` | `$owner->getFirstName()` |
| `getLastName()` | 姓或 `null` | `$owner->getLastName()` |
| `getDisplayName()` | 显示名称或 `null` | `$owner->getDisplayName()` |
| `getRealName()` | 完整姓名或 `null` | `$owner->getRealName()` |
| `getSex()` | `YandexSex` 或 `null` | `$owner->getSex()?->value` |
| `getDefaultEmail()` | 主要 email 或 `null` | `$owner->getDefaultEmail()` |
| `getEmails()` | email 列表 | `$owner->getEmails()` |
| `getBirthday()` | 日期字符串或 `null` | `$owner->getBirthday()` |
| `getDefaultAvatar()` | `YandexAvatar` 或 `null` | `$owner->getDefaultAvatar()?->getUrl()` |
| `getDefaultAvatarId()` | 头像标识符或 `null` | `$owner->getDefaultAvatarId()` |
| `isAvatarEmpty()` | 占位图标志或 `null` | `$owner->isAvatarEmpty()` |
| `getDefaultPhone()` | `YandexPhone` 或 `null` | `$owner->getDefaultPhone()?->getNumber()` |
| `getAvatarUrl(YandexAvatarSize $size = YandexAvatarSize::Size200)` | 头像 URL 或 `null` | `$owner->getAvatarUrl(YandexAvatarSize::Size100)` |
| `toArray()` | Yandex 原始响应数组 | `$owner->toArray()` |

## `YandexAvatar`

| 方法 | 结果 | 示例 |
|---|---|---|
| `getId()` | 头像标识符 | `$avatar->getId()` |
| `isEmpty()` | 占位图标志 | `$avatar->isEmpty()` |
| `getUrl(YandexAvatarSize $size = YandexAvatarSize::Size200)` | URL 或 `null` | `$avatar->getUrl(YandexAvatarSize::Size100)` |
| `toArray()` | 包含 `id` 和 `empty` 的数组 | `$avatar->toArray()` |

如果 Yandex 表示 avatar ID 指向默认占位图，
`getUrl()` 返回 `null`。

## `YandexPhone`

| 方法 | 结果 | 示例 |
|---|---|---|
| `getId()` | 电话标识符 | `$phone->getId()` |
| `getNumber()` | 电话号码 | `$phone->getNumber()` |
| `toArray()` | 包含 `id` 和 `number` 的数组 | `$phone->toArray()` |

## `YandexSex`

| Case | 值 |
|---|---|
| `YandexSex::Male` | `male` |
| `YandexSex::Female` | `female` |

未提供性别时，`YandexResourceOwner::getSex()` 返回 `null`。

## `YandexAvatarSize`

| Case | Yandex 值 | 尺寸 |
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
