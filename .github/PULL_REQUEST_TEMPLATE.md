## What changed / Что изменено

<!-- Describe the problem and the change that solves it. / Опишите проблему и изменение, которое её решает. -->

## How to verify / Как проверить

<!-- List the checks and tests you ran. / Перечислите выполненные проверки и тесты. -->

## Public API and OAuth contract / Public API и OAuth-контракт

<!-- Describe any impact on public compatibility, the Yandex ID OAuth contract, league/oauth2-client compatibility, token handling, or profile mapping. / Опишите влияние на публичную совместимость, OAuth-контракт Yandex ID, совместимость с league/oauth2-client, работу с токенами или маппинг профиля. -->

## Checklist / Чек-лист

- [ ] No Client Secret, authorization code, access token, refresh token, or real user data is included. / Client Secret, authorization code, access token, refresh token и реальные пользовательские данные не добавлены.
- [ ] `composer check` passes when code, tests, dependencies, or quality configuration are affected. / `composer check` проходит, если затронуты код, тесты, зависимости или quality configuration.
- [ ] Relevant tests for package-owned behavior were added or updated when behavior changed. / При изменении поведения добавлены или обновлены релевантные тесты поведения, за которое отвечает пакет.
- [ ] Yandex ID and `league/oauth2-client` contracts were rechecked when provider behavior changed. / При изменении поведения provider повторно проверены контракты Yandex ID и `league/oauth2-client`.
- [ ] Documentation was updated when behavior or the public API changed. / Документация обновлена при изменении поведения или публичного API.
- [ ] SemVer and public compatibility impact was considered. / Учтено влияние на SemVer и публичную совместимость.
- [ ] CONTRIBUTING and SECURITY translations were synchronized if those policies changed. / Переводы CONTRIBUTING и SECURITY синхронизированы, если эти политики изменялись.
