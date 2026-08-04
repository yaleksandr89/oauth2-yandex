# Enregistrer une application dans Yandex OAuth

## Choisir une langue

| Русский | English | Español | 中文 | Français | Deutsch |
|---|---|---|---|---|---|
| [Русский](./yandex-oauth-application.md) | [English](./yandex-oauth-application_en.md) | [Español](./yandex-oauth-application_es.md) | [中文](./yandex-oauth-application_zh.md) | **Sélectionné** | [Deutsch](./yandex-oauth-application_de.md) |

[← Retour au README](../readme/README_fr.md)

## Créer une application

1. Ouvrez [Yandex OAuth](https://oauth.yandex.com/).
2. Créez une application **pour l'autorisation des utilisateurs**.
3. Sélectionnez la plateforme **Web services**.
4. Ajoutez le Redirect URI vers lequel Yandex renverra l'utilisateur après l'autorisation.

Exemple :

```text
https://example.com/oauth/yandex/callback
```

Le scheme, le host, le port et le path doivent correspondre au callback de l'application.

## Permissions

Ne demandez que les permissions réellement utilisées par l'application.

| Permission | Données |
|---|---|
| `login:info` | login, prénom, nom et sexe |
| `login:email` | adresse e-mail |
| `login:avatar` | portrait de l'utilisateur |
| `login:birthday` | date de naissance |
| `login:default_phone` | numéro de téléphone |

L'URL d'autorisation peut inclure :

- `scope` — permissions requises par l'application ;
- `optional_scope` — permissions supplémentaires que l'utilisateur peut refuser.

Si `scope` et `optional_scope` ne sont pas fournis, Yandex émet le token avec
les permissions sélectionnées dans les paramètres de l'application.

## Identifiants de l'application

Après la création de l'application, Yandex fournit :

```text
Client ID
Client Secret
```

Stockez-les dans la configuration ou dans des variables d'environnement. N'ajoutez pas
`Client Secret` au dépôt Git, aux fichiers publics, aux logs ou aux messages d'erreur.

Exemple de variables d'environnement :

```dotenv
YANDEX_CLIENT_ID=your-client-id
YANDEX_CLIENT_SECRET=your-client-secret
YANDEX_REDIRECT_URI=https://example.com/oauth/yandex/callback
```

## Callback

Après une autorisation réussie, Yandex renvoie :

```text
?code=<authorization-code>&state=<state>
```

En cas de refus ou d'erreur :

```text
?error=<error-code>&error_description=<description>&state=<state>
```

Validez `state` dans les deux scénarios avant de poursuivre le traitement du callback.

## Documentation officielle

- [Enregistrer une application](https://yandex.com/dev/id/doc/en/register-client)
- [Obtenir le code depuis l'URL](https://yandex.com/dev/id/doc/en/codes/code-url)
- [Obtenir les informations utilisateur](https://yandex.com/dev/id/doc/en/user-information)
