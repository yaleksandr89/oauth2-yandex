# Registering an application in Yandex OAuth

## Choose a language

| Русский | English | Español | 中文 | Français | Deutsch |
|---|---|---|---|---|---|
| [Русский](./yandex-oauth-application.md) | **Selected** | [Español](./yandex-oauth-application_es.md) | [中文](./yandex-oauth-application_zh.md) | [Français](./yandex-oauth-application_fr.md) | [Deutsch](./yandex-oauth-application_de.md) |

[← Back to README](../readme/README_en.md)

## Creating an application

1. Open [Yandex OAuth](https://oauth.yandex.com/).
2. Create an application **for user authorization**.
3. Select the **Web services** platform.
4. Add the Redirect URI to which Yandex will return the user after authorization.

Example:

```text
https://example.com/oauth/yandex/callback
```

The scheme, host, port, and path must match your application's callback.

## Permissions

Request only the permissions that the application actually uses.

| Permission | Data |
|---|---|
| `login:info` | login, first name, last name, and sex |
| `login:email` | email address |
| `login:avatar` | user portrait |
| `login:birthday` | birthday |
| `login:default_phone` | phone number |

The authorization URL can include:

- `scope` — permissions required by the application;
- `optional_scope` — additional permissions the user may decline.

When neither `scope` nor `optional_scope` is supplied, Yandex issues a token with
the permissions selected in the application settings.

## Application credentials

After the application is created, Yandex provides:

```text
Client ID
Client Secret
```

Store them in configuration or environment variables. Do not put `Client Secret`
in a Git repository, public configuration files, logs, or error messages.

Environment variable example:

```dotenv
YANDEX_CLIENT_ID=your-client-id
YANDEX_CLIENT_SECRET=your-client-secret
YANDEX_REDIRECT_URI=https://example.com/oauth/yandex/callback
```

## Callback

On successful authorization, Yandex returns:

```text
?code=<authorization-code>&state=<state>
```

On denial or error:

```text
?error=<error-code>&error_description=<description>&state=<state>
```

Validate `state` in both scenarios before processing the callback further.

## Official documentation

- [Registering an application](https://yandex.com/dev/id/doc/en/register-client)
- [Getting a code from the URL](https://yandex.com/dev/id/doc/en/codes/code-url)
- [Getting user information](https://yandex.com/dev/id/doc/en/user-information)
