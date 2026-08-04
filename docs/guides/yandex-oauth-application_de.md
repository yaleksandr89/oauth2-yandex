# Anwendung in Yandex OAuth registrieren

## Sprache auswählen

| Русский | English | Español | 中文 | Français | Deutsch |
|---|---|---|---|---|---|
| [Русский](./yandex-oauth-application.md) | [English](./yandex-oauth-application_en.md) | [Español](./yandex-oauth-application_es.md) | [中文](./yandex-oauth-application_zh.md) | [Français](./yandex-oauth-application_fr.md) | **Ausgewählt** |

[← Zurück zur README](../readme/README_de.md)

## Anwendung erstellen

1. Öffnen Sie [Yandex OAuth](https://oauth.yandex.com/).
2. Erstellen Sie eine Anwendung **zur Benutzerautorisierung**.
3. Wählen Sie die Plattform **Web services**.
4. Fügen Sie den Redirect URI hinzu, zu dem Yandex den Benutzer nach der Autorisierung zurückleitet.

Beispiel:

```text
https://example.com/oauth/yandex/callback
```

Scheme, Host, Port und Path müssen mit dem Callback der Anwendung übereinstimmen.

## Berechtigungen

Fordern Sie nur Berechtigungen an, die die Anwendung tatsächlich verwendet.

| Berechtigung | Daten |
|---|---|
| `login:info` | Login, Vorname, Nachname und Geschlecht |
| `login:email` | E-Mail-Adresse |
| `login:avatar` | Benutzerporträt |
| `login:birthday` | Geburtsdatum |
| `login:default_phone` | Telefonnummer |

Die Autorisierungs-URL kann enthalten:

- `scope` — erforderliche Berechtigungen;
- `optional_scope` — zusätzliche Berechtigungen, die der Benutzer ablehnen kann.

Wenn weder `scope` noch `optional_scope` übergeben wird, stellt Yandex den Token mit
den in den Anwendungseinstellungen ausgewählten Berechtigungen aus.

## Anwendungsdaten

Nach dem Erstellen der Anwendung stellt Yandex bereit:

```text
Client ID
Client Secret
```

Speichern Sie diese Werte in der Konfiguration oder in Umgebungsvariablen. Fügen Sie
`Client Secret` nicht einem Git-Repository, öffentlichen Dateien, Logs oder Fehlermeldungen hinzu.

Beispiel für Umgebungsvariablen:

```dotenv
YANDEX_CLIENT_ID=your-client-id
YANDEX_CLIENT_SECRET=your-client-secret
YANDEX_REDIRECT_URI=https://example.com/oauth/yandex/callback
```

## Callback

Bei erfolgreicher Autorisierung gibt Yandex zurück:

```text
?code=<authorization-code>&state=<state>
```

Bei Ablehnung oder Fehler:

```text
?error=<error-code>&error_description=<description>&state=<state>
```

Prüfen Sie `state` in beiden Fällen, bevor Sie den Callback weiterverarbeiten.

## Offizielle Dokumentation

- [Anwendung registrieren](https://yandex.com/dev/id/doc/en/register-client)
- [Code aus der URL abrufen](https://yandex.com/dev/id/doc/en/codes/code-url)
- [Benutzerdaten abrufen](https://yandex.com/dev/id/doc/en/user-information)
