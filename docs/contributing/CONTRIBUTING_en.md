# Contributing

## Choose a language

| Русский | English | Español | 中文 | Français | Deutsch |
|---|---|---|---|---|---|
| [Русский](../../.github/CONTRIBUTING.md) | **English** | [Español](./CONTRIBUTING_es.md) | [中文](./CONTRIBUTING_zh.md) | [Français](./CONTRIBUTING_fr.md) | [Deutsch](./CONTRIBUTING_de.md) |

Thank you for wanting to improve OAuth2 Yandex. This guide will help you prepare a change that is easier to review, integrate safely, and maintain.

## Before you start

- Report a reproducible bug through a GitHub Issue.
- Create a feature request for a new feature or improvement.
- For a security issue, follow the [security policy](../../.github/SECURITY.md) and do not publish sensitive details.
- Discuss large or backward-incompatible changes to the public API, the Yandex ID OAuth contract, or `league/oauth2-client` compatibility with the maintainer in an Issue before implementation.

## Package contract

- The package is a framework-agnostic Yandex ID provider client for `league/oauth2-client`.
- `Yandex` extends `League\OAuth2\Client\Provider\AbstractProvider` and uses the standard League grant and access-token lifecycle.
- The user profile is represented by `YandexResourceOwner`; typed profile parts are represented by `YandexAvatar`, `YandexPhone`, `YandexSex`, and `YandexAvatarSize`.
- The access token used for profile requests is sent through `Authorization: OAuth <token>` and must not appear in the URL.
- Provider-specific authorization parameters, endpoints, and error handling must follow the current Yandex ID contract and the compatible `league/oauth2-client` API.
- An empty `scope` must not be serialized as `scope=`; explicitly supplied non-empty scopes must preserve the expected behavior.
- The package must not gain a framework-specific dependency without a separate justified decision.
- Public API changes must be intentional and account for SemVer and backward compatibility within the `1.x` line.
- Do not add abstractions, storage, logging, framework adapters, or other features unrelated to the problem being solved.

## Branches

Use a short name that reflects the purpose of the change, for example:

```text
feature/add-yandex-option
fix/oauth-error-handling
docs/update-yandex-guide
```

## Commits

Conventional Commits are recommended. Examples:

```text
feat: add Yandex authorization option
fix: handle Yandex OAuth error
docs: clarify native OAuth flow
test: cover malformed profile response
chore: update CI configuration
```

## Local checks

Install dependencies and run the aggregate checks:

```shell
composer install
composer check
```

The following focused checks are available:

```shell
composer test
composer analyse
composer cs
```

Run `composer coverage` separately when a coverage report is needed; it is not required for every change.

## Pull Request

In the Pull Request description, include:

- the problem and the change made;
- checks performed;
- impact on the public API, the Yandex ID OAuth contract, or `league/oauth2-client` compatibility;
- tests added or updated;
- documentation changes;
- whether the CONTRIBUTING and SECURITY translations were synchronized if those policies changed.

Before submitting, make sure:

- no real Client Secret, authorization code, access token, refresh token, or other private configuration was added;
- no real user profiles or other personal data appear in code, logs, Issues, or test data;
- fixtures and examples use synthetic and sanitized values;
- `vendor/`, `composer.lock`, PHPUnit cache, and coverage output were not added to the repository.
