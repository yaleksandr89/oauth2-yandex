# Mitwirken

## Sprache auswählen

| Русский | English | Español | 中文 | Français | Deutsch |
|---|---|---|---|---|---|
| [Русский](../../.github/CONTRIBUTING.md) | [English](./CONTRIBUTING_en.md) | [Español](./CONTRIBUTING_es.md) | [中文](./CONTRIBUTING_zh.md) | [Français](./CONTRIBUTING_fr.md) | **Deutsch** |

Vielen Dank, dass Sie OAuth2 Yandex verbessern möchten. Dieser Leitfaden hilft dabei, Änderungen vorzubereiten, die sich leichter prüfen, sicher integrieren und warten lassen.

## Vor dem Start

- Melden Sie einen reproduzierbaren Fehler über ein GitHub Issue.
- Erstellen Sie für eine neue Funktion oder Verbesserung einen Feature Request.
- Befolgen Sie bei Sicherheitsproblemen die [Sicherheitsrichtlinie](../../.github/SECURITY.md) und veröffentlichen Sie keine sensiblen Details.
- Besprechen Sie größere oder rückwärtsinkompatible Änderungen an der öffentlichen API, am Yandex-ID-OAuth-Vertrag oder an der Kompatibilität mit `league/oauth2-client` vor der Implementierung mit dem Maintainer in einem Issue.

## Package-Vertrag

- Das Package ist ein framework-unabhängiger Yandex-ID-Provider-Client für `league/oauth2-client`.
- `Yandex` erweitert `League\OAuth2\Client\Provider\AbstractProvider` und verwendet den standardmäßigen Grant- und Access-Token-Lifecycle von League.
- Das Benutzerprofil wird durch `YandexResourceOwner` dargestellt; typisierte Profilbestandteile werden durch `YandexAvatar`, `YandexPhone`, `YandexSex` und `YandexAvatarSize` dargestellt.
- Der Access Token für Profilanfragen wird über `Authorization: OAuth <token>` übertragen und darf nicht in der URL erscheinen.
- Provider-spezifische Autorisierungsparameter, Endpoints und Fehlerbehandlung müssen dem aktuellen Yandex-ID-Vertrag und der kompatiblen `league/oauth2-client`-API entsprechen.
- Ein leerer `scope` darf nicht als `scope=` serialisiert werden; explizit übergebene nicht leere Scopes müssen das erwartete Verhalten beibehalten.
- Das Package darf ohne eine separate, begründete Entscheidung keine framework-spezifische Dependency erhalten.
- Änderungen an der öffentlichen API müssen beabsichtigt sein und SemVer sowie die Rückwärtskompatibilität innerhalb der `1.x`-Linie berücksichtigen.
- Fügen Sie keine Abstraktionen, Storage-, Logging- oder Framework-Adapter und keine anderen Funktionen hinzu, die nicht zum gelösten Problem gehören.

## Branches

Verwenden Sie einen kurzen Namen, der den Zweck der Änderung beschreibt, zum Beispiel:

```text
feature/add-yandex-option
fix/oauth-error-handling
docs/update-yandex-guide
```

## Commits

Conventional Commits werden empfohlen. Beispiele:

```text
feat: add Yandex authorization option
fix: handle Yandex OAuth error
docs: clarify native OAuth flow
test: cover malformed profile response
chore: update CI configuration
```

## Lokale Prüfung

Installieren Sie die Dependencies und führen Sie die vollständigen Prüfungen aus:

```shell
composer install
composer check
```

Folgende gezielte Prüfungen stehen ebenfalls zur Verfügung:

```shell
composer test
composer analyse
composer cs
```

Führen Sie `composer coverage` separat aus, wenn ein Coverage-Bericht benötigt wird; dies ist nicht für jede Änderung erforderlich.

## Pull Request

Geben Sie in der Pull-Request-Beschreibung an:

- das Problem und die vorgenommene Änderung;
- die ausgeführten Prüfungen;
- Auswirkungen auf die öffentliche API, den Yandex-ID-OAuth-Vertrag oder die Kompatibilität mit `league/oauth2-client`;
- hinzugefügte oder aktualisierte Tests;
- Änderungen an der Dokumentation;
- ob die Übersetzungen von CONTRIBUTING und SECURITY synchronisiert wurden, falls sich diese Richtlinien geändert haben.

Prüfen Sie vor dem Absenden:

- Es wurden keine echten Client Secrets, authorization codes, access tokens, refresh tokens oder andere private Konfigurationen hinzugefügt.
- Echte Benutzerprofile oder andere personenbezogene Daten erscheinen nicht im Code, in Logs, Issues oder Testdaten.
- Fixtures und Examples verwenden synthetische und anonymisierte Werte.
- `vendor/`, `composer.lock`, PHPUnit-Cache und Coverage-Ausgaben wurden nicht in das Repository aufgenommen.
