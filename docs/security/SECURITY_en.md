# Security Policy

## Choose a language

| Русский | English | Español | 中文 | Français | Deutsch |
|---|---|---|---|---|---|
| [Русский](../../.github/SECURITY.md) | **Selected** | [Español](./SECURITY_es.md) | [中文](./SECURITY_zh.md) | [Français](./SECURITY_fr.md) | [Deutsch](./SECURITY_de.md) |

## Supported versions

Security fixes are released for the current stable `1.x` line.

| Version | Supported |
|---|---|
| `1.x` | Yes |

## What counts as a vulnerability

Security issues include, but are not limited to:

- disclosure, substitution, or unsafe transmission of an OAuth Client Secret,
  authorization code, access token, or refresh token;
- tokens appearing in URLs, logs, or error messages;
- bypassing OAuth response validation or accepting an invalid response as successful;
- accessing another user's data;
- incorrect mapping between a Yandex ID profile and a local account;
- profile-processing defects that may disclose user data.

Regular bugs, usage questions, and feature requests may be reported through GitHub
Issues when they do not contain sensitive data.

## Reporting a vulnerability

The preferred channel is GitHub Private Vulnerability Reporting:

1. Open the repository's **Security** tab.
2. Go to **Advisories**.
3. Select **Report a vulnerability**.
4. Submit the report without publishing details in a public issue.

Do not publish:

- Client Secrets;
- authorization codes;
- access or refresh tokens;
- real user personal data;
- a working exploit or details that enable reproduction before a fix is released.

If Private Vulnerability Reporting is unavailable, open a minimal public issue without
sensitive details and request a private communication channel.

## What to include

When possible, include:

- the affected version or commit;
- an impact description;
- minimal reproduction steps;
- expected and actual behavior;
- a sanitized request or response example;
- a possible fix, when known.

Do not use real tokens, secrets, or user data. Replace them with synthetic values.

## Report handling

The report will be acknowledged and assessed when possible. No fixed SLA is guaranteed.

Please coordinate disclosure with the maintainer before publishing details. After the
issue is confirmed, a fix and affected-version information will be prepared under a
coordinated disclosure process.

This project does not offer a bug bounty.
