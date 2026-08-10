# Politique de sécurité

## Choisir une langue

| Русский | English | Español | 中文 | Français | Deutsch |
|---|---|---|---|---|---|
| [Русский](../../.github/SECURITY.md) | [English](./SECURITY_en.md) | [Español](./SECURITY_es.md) | [中文](./SECURITY_zh.md) | **Sélectionné** | [Deutsch](./SECURITY_de.md) |

## Versions prises en charge

Les correctifs de sécurité sont publiés pour la branche stable `1.x` actuelle.

| Version | Prise en charge |
|---|---|
| `1.x` | Oui |

## Ce qui constitue une vulnérabilité

Les problèmes de sécurité comprennent notamment :

- la divulgation, la substitution ou la transmission non sécurisée d'un OAuth Client
  Secret, d'un authorization code, d'un access token ou d'un refresh token ;
- la présence de tokens dans les URL, les journaux ou les messages d'erreur ;
- le contournement de la validation d'une réponse OAuth ou l'acceptation d'une réponse
  invalide comme réussie ;
- l'accès aux données d'un autre utilisateur ;
- une association incorrecte entre un profil Yandex ID et un compte local ;
- un défaut de traitement du profil pouvant divulguer des données utilisateur.

Les bogues ordinaires, questions d'utilisation et demandes de fonctionnalités peuvent
être publiés dans GitHub Issues lorsqu'ils ne contiennent aucune donnée sensible.

## Signaler une vulnérabilité

Le canal privilégié est GitHub Private Vulnerability Reporting :

1. Ouvrez l'onglet **Security** du dépôt.
2. Accédez à **Advisories**.
3. Sélectionnez **Report a vulnerability**.
4. Envoyez le rapport sans publier les détails dans une issue publique.

Ne publiez pas :

- de Client Secrets ;
- d'authorization codes ;
- d'access tokens ou de refresh tokens ;
- de données personnelles réelles ;
- d'exploit fonctionnel ni de détails permettant de reproduire l'attaque avant la
  publication du correctif.

Si Private Vulnerability Reporting n'est pas disponible, ouvrez une issue publique
minimale sans données sensibles et demandez un canal de communication privé.

## Contenu du rapport

Dans la mesure du possible, indiquez :

- la version ou le commit concerné ;
- une description de l'impact ;
- les étapes minimales de reproduction ;
- le comportement attendu et le comportement observé ;
- un exemple de requête ou de réponse anonymisé ;
- une proposition de correction, si elle est connue.

N'utilisez pas de tokens, secrets ou données utilisateur réels. Remplacez-les par des
valeurs fictives.

## Traitement du rapport

Le rapport sera confirmé et évalué lorsque cela sera possible. Aucun SLA fixe n'est
garanti.

Veuillez coordonner la divulgation avec le mainteneur avant de publier les détails.
Après confirmation, un correctif et les informations sur les versions affectées seront
préparés selon un processus de divulgation coordonnée.

Ce projet ne propose pas de programme de récompense pour les vulnérabilités.
