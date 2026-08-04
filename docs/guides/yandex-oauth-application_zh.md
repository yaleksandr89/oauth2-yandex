# 在 Yandex OAuth 中注册应用

## 选择语言

| Русский | English | Español | 中文 | Français | Deutsch |
|---|---|---|---|---|---|
| [Русский](./yandex-oauth-application.md) | [English](./yandex-oauth-application_en.md) | [Español](./yandex-oauth-application_es.md) | **已选择** | [Français](./yandex-oauth-application_fr.md) | [Deutsch](./yandex-oauth-application_de.md) |

[← 返回 README](../readme/README_zh.md)

## 创建应用

1. 打开 [Yandex OAuth](https://oauth.yandex.com/)。
2. 创建一个**用于用户授权**的应用。
3. 选择 **Web services** 平台。
4. 添加授权完成后 Yandex 将用户返回到的 Redirect URI。

示例：

```text
https://example.com/oauth/yandex/callback
```

scheme、host、port 和 path 必须与应用的 callback 完全一致。

## 权限

仅请求应用实际使用的权限。

| 权限 | 数据 |
|---|---|
| `login:info` | login、名、姓和性别 |
| `login:email` | 电子邮件地址 |
| `login:avatar` | 用户头像 |
| `login:birthday` | 生日 |
| `login:default_phone` | 电话号码 |

authorization URL 可以包含：

- `scope` — 应用必需的权限；
- `optional_scope` — 用户可以拒绝的附加权限。

若未传递 `scope` 和 `optional_scope`，Yandex 将按照应用设置中
选择的权限签发 token。

## 应用凭据

创建应用后，Yandex 会提供：

```text
Client ID
Client Secret
```

请将它们保存在配置或环境变量中。不要将 `Client Secret` 写入 Git 仓库、
公开配置文件、日志或错误消息。

环境变量示例：

```dotenv
YANDEX_CLIENT_ID=your-client-id
YANDEX_CLIENT_SECRET=your-client-secret
YANDEX_REDIRECT_URI=https://example.com/oauth/yandex/callback
```

## Callback

授权成功时，Yandex 返回：

```text
?code=<authorization-code>&state=<state>
```

用户拒绝或发生错误时：

```text
?error=<error-code>&error_description=<description>&state=<state>
```

在继续处理 callback 之前，两种场景都必须校验 `state`。

## 官方文档

- [注册应用](https://yandex.com/dev/id/doc/en/register-client)
- [从 URL 获取授权码](https://yandex.com/dev/id/doc/en/codes/code-url)
- [获取用户信息](https://yandex.com/dev/id/doc/en/user-information)
