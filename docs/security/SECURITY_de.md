# Sicherheitsrichtlinie

## Sprache auswählen

| Русский | English | Español | 中文 | Français | Deutsch |
|---|---|---|---|---|---|
| [Русский](../../SECURITY.md) | [English](./SECURITY_en.md) | [Español](./SECURITY_es.md) | [中文](./SECURITY_zh.md) | [Français](./SECURITY_fr.md) | **Ausgewählt** |

## Unterstützte Versionen

Sicherheitskorrekturen werden für die aktuelle stabile `1.x`-Linie veröffentlicht.

| Version | Unterstützt |
|---|---|
| `1.x` | Ja |

## Was als Sicherheitslücke gilt

Zu Sicherheitsproblemen gehören insbesondere:

- Offenlegung, Ersetzung oder unsichere Übertragung eines OAuth Client Secret,
  authorization code, access token oder refresh token;
- Tokens in URLs, Protokollen oder Fehlermeldungen;
- Umgehung der OAuth-Antwortvalidierung oder Annahme einer ungültigen Antwort als Erfolg;
- Zugriff auf Daten eines anderen Benutzers;
- falsche Zuordnung zwischen einem Yandex-ID-Profil und einem lokalen Konto;
- Fehler bei der Profilverarbeitung, durch die Benutzerdaten offengelegt werden können.

Normale Fehler, Nutzungsfragen und Funktionswünsche können über GitHub Issues gemeldet
werden, sofern sie keine sensiblen Daten enthalten.

## Eine Sicherheitslücke melden

Der bevorzugte Kanal ist GitHub Private Vulnerability Reporting:

1. Öffnen Sie den Tab **Security** des Repositorys.
2. Gehen Sie zu **Advisories**.
3. Wählen Sie **Report a vulnerability**.
4. Senden Sie den Bericht, ohne Details in einem öffentlichen Issue zu veröffentlichen.

Veröffentlichen Sie keine:

- Client Secrets;
- authorization codes;
- access tokens oder refresh tokens;
- echten personenbezogenen Benutzerdaten;
- funktionsfähigen Exploits oder Details, die eine Reproduktion vor Veröffentlichung
  der Korrektur ermöglichen.

Wenn Private Vulnerability Reporting nicht verfügbar ist, erstellen Sie ein minimales
öffentliches Issue ohne sensible Daten und bitten Sie um einen privaten Kommunikationskanal.

## Inhalt des Berichts

Geben Sie nach Möglichkeit Folgendes an:

- die betroffene Version oder den betroffenen Commit;
- eine Beschreibung der Auswirkungen;
- minimale Schritte zur Reproduktion;
- erwartetes und tatsächliches Verhalten;
- ein bereinigtes Beispiel einer Anfrage oder Antwort;
- einen möglichen Lösungsvorschlag, falls bekannt.

Verwenden Sie keine echten Tokens, Secrets oder Benutzerdaten. Ersetzen Sie diese durch
fiktive Werte.

## Bearbeitung des Berichts

Der Bericht wird nach Möglichkeit bestätigt und bewertet. Eine feste SLA wird nicht
garantiert.

Bitte stimmen Sie die Offenlegung mit dem Maintainer ab, bevor Sie Details veröffentlichen.
Nach Bestätigung der Sicherheitslücke werden eine Korrektur und Informationen zu den
betroffenen Versionen im Rahmen einer koordinierten Offenlegung vorbereitet.

Dieses Projekt bietet kein Bug-Bounty-Programm.
