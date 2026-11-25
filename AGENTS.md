# OAuth2 Yandex: правила работы с репозиторием

## Область и архитектура

- Независимая Composer-библиотека `yaleksandr89/oauth2-yandex`, namespace `Yaleksandr\OAuth2\Client\`, default branch `master`. Один корневой AGENTS.md; nested instructions только при доказанном отдельном контракте.
- Composer support: PHP `^8.3`, `league/oauth2-client: ^2.9`, `psr/http-message: ^1.1 || ^2.0`. До начала разработки проверить latest stable PHP официально; поддерживаемый floor не менять без решения владельца. PHPStan проверяет floor 8.3; поддержка runtime и инструментария проверяется отдельно.
- `Yandex` расширяет `AbstractProvider`; использовать штатный lifecycle League, не реализовывать OAuth самостоятельно. `YandexResourceOwner` и value objects представляют типизированный профиль. Конфигурация credentials, redirect/callback, session, state, PKCE и локальная авторизация принадлежат приложению (composition root); не вводить framework adapter, storage, logging, retry/fallback или новые runtime зависимости без согласования.
- Сохранять OAuth endpoints, правила scope, HTTP/error semantics, передачу access token только через `Authorization: OAuth`, типы и правила профиля. Не раскрывать токены, Client Secret, authorization code, реальные профили или необработанные ошибки пользователям. Fixtures и тесты синтетические, HTTP через mock, без обращений к реальному Yandex.

## Стиль

- PHP-FIG PER Coding Style 3.1 + обязательный PSR-1; PSR-12 — predecessor. `.php-cs-fixer.dist.php` обеспечивает поддерживаемые машинные правила `@PER-CS` (проверять tooling gap вручную); при конфликте остановиться и согласовать. Не применять PHP 8.4+ синтаксис к коду, поддерживающему 8.3: сохранять `(new Foo())->method()`.
- Strict types, PSR-4, LF, 4 пробела, явные native types/visibility, strict comparison, английские identifiers, `final`/`readonly` по контракту. Не менять поведение ради форматирования.
- Meaningful first-party PHP comments и PHPDoc: все строки `RU:` затем семантически эквивалентные `EN:`; не дублировать machine-readable tags и не добавлять комментарии, повторяющие код. PHPUnit identifiers English.

## Документация

- RU canonical: root README.md, `.github/CONTRIBUTING.md`, `.github/SECURITY.md`, тематические `docs/langs/ru/`; EN/ES/ZH/FR/DE в `docs/langs/<lang>/`. Сохранять все примеры, предупреждения, технические идентификаторы, ссылки и смысл; компактная Mermaid-диаграмма во всех README. Проверять ссылки по exact paths, GitHub rendering и исходники примеров. Не переносить cover из `docs/assets/` и не менять изображения без решения владельца.
- LICENSE, SECURITY, FUNDING — owner-controlled; согласованные tracked changes только через Git, не через GitHub editor. Не публиковать чувствительные данные.

## Quality gates и безопасность

- `composer test`, `composer analyse`, `composer cs:check`, `composer cs:fix`, `composer check` (strict validate, audit, style, static analysis, tests); diagnostic `composer coverage` сохраняется. CI: full checks на проверенных версиях PHP, lowest-dependencies на floor, latest runtime отдельно при необходимости. Не отключать проверки и не обходить ограничения инструментария. Composer library без tracked lockfile; `vendor/`, caches, coverage не коммитить.
- До write: read-before-write, exact allowlist, non-goals, security/dependency/runtime triage, acceptance. Изменение token/state/redirect/HTTP/error/profile boundary или CI/dependency permissions требует focused review и meaningful regression tests. Новая зависимость — official source, maintenance, compatibility, security, necessity и owner approval; не обновлять unrelated dependencies.
- До write-producing команд проверить entrypoint, effective user, ownership и side effects. Никаких broad chmod/chown/delete, production, secrets или реальных OAuth запросов. После change: полный diff, `git diff --check` (staged: `git diff --cached --check`), релевантные gates, итоговый Git state; PASS только по evidence. REVIEW: ACCEPTED / NOT ACCEPTED, одна consolidated correction после полного review.

## Compatibility и Git

- Публичный пакет: до изменения API/behavior/platform support получить owner decision о BC, migration/deprecation или breaking release. Без speculative shims; текущий контракт сохранять до решения.
- Remote policy `READ_ONLY_FOR_AI`. Никаких commit/push/PR/merge/rebase/reset/restore/clean/stash/force/tag/Release/settings/production operations без явного разрешения. Владелец делает Git и release. После merge проверить exact remote SHA, green CI, затем signed tag на тот же SHA, actual tag target/signature и Release/Packagist. Не считать публикацию доказанной по одному target_commitish.
