# Участие в разработке

## Выберите язык

| Русский | English | Español | 中文 | Français | Deutsch |
|---|---|---|---|---|---|
| **Русский** | [English](../docs/contributing/CONTRIBUTING_en.md) | [Español](../docs/contributing/CONTRIBUTING_es.md) | [中文](../docs/contributing/CONTRIBUTING_zh.md) | [Français](../docs/contributing/CONTRIBUTING_fr.md) | [Deutsch](../docs/contributing/CONTRIBUTING_de.md) |

Спасибо за желание улучшить OAuth2 Yandex. Это руководство поможет подготовить изменение, которое проще проверить, безопасно интегрировать и поддерживать.

## Перед началом

- О воспроизводимой ошибке сообщите через GitHub Issue.
- Для новой возможности или улучшения создайте feature request.
- При проблеме безопасности следуйте [политике безопасности](SECURITY.md) и не публикуйте чувствительные детали.
- Крупные или нарушающие обратную совместимость изменения публичного API, OAuth-контракта Yandex ID либо совместимости с `league/oauth2-client` сначала согласуйте с сопровождающим через Issue.

## Контракт пакета

- Пакет является framework-agnostic provider-клиентом Yandex ID для `league/oauth2-client`.
- `Yandex` расширяет `League\OAuth2\Client\Provider\AbstractProvider` и использует стандартный lifecycle grants и access tokens библиотеки League.
- Профиль пользователя представлен `YandexResourceOwner`; отдельные типизированные части профиля представлены `YandexAvatar`, `YandexPhone`, `YandexSex` и `YandexAvatarSize`.
- Access token при запросе профиля передаётся через `Authorization: OAuth <token>` и не должен попадать в URL.
- Provider-specific authorization parameters, endpoints и обработка ошибок должны соответствовать актуальному контракту Yandex ID и совместимому API `league/oauth2-client`.
- Пустой `scope` не должен сериализоваться как `scope=`; явно переданные непустые scopes должны сохранять ожидаемое поведение.
- Пакет не должен получать framework-specific dependency без отдельного обоснованного решения.
- Изменения публичного API должны быть намеренными и учитывать SemVer и обратную совместимость линии `1.x`.
- Не добавляйте abstractions, storage, logging, framework adapters или другие возможности, не относящиеся к решаемой задаче.

## Ветки

Используйте короткое имя, отражающее назначение изменения, например:

```text
feature/add-yandex-option
fix/oauth-error-handling
docs/update-yandex-guide
```

## Коммиты

Рекомендуется формат Conventional Commits. Примеры:

```text
feat: add Yandex authorization option
fix: handle Yandex OAuth error
docs: clarify native OAuth flow
test: cover malformed profile response
chore: update CI configuration
```

## Локальная проверка

Установите зависимости и запустите общий набор проверок:

```shell
composer install
composer check
```

Для целевой проверки доступны:

```shell
composer test
composer analyse
composer cs
```

`composer coverage` можно запустить отдельно, когда нужен отчёт о покрытии; он не обязателен для каждого изменения.

## Pull Request

В описании Pull Request укажите:

- проблему и внесённое изменение;
- выполненные проверки;
- влияние на публичный API, OAuth-контракт Yandex ID или совместимость с `league/oauth2-client`;
- добавленные или обновлённые тесты;
- изменения документации;
- синхронизированы ли переводы CONTRIBUTING и SECURITY, если эти политики менялись.

Перед отправкой убедитесь:

- реальные Client Secret, authorization code, access token, refresh token и другая приватная конфигурация не добавлены;
- реальные пользовательские профили и другие персональные данные не попали в код, логи, Issues или тестовые данные;
- fixtures и examples используют синтетические и обезличенные значения;
- `vendor/`, `composer.lock`, PHPUnit cache и результаты покрытия не добавлены в репозиторий.
