# Contribuir

## Elija un idioma

| Русский | English | Español | 中文 | Français | Deutsch |
|---|---|---|---|---|---|
| [Русский](../../.github/CONTRIBUTING.md) | [English](./CONTRIBUTING_en.md) | **Español** | [中文](./CONTRIBUTING_zh.md) | [Français](./CONTRIBUTING_fr.md) | [Deutsch](./CONTRIBUTING_de.md) |

Gracias por querer mejorar OAuth2 Yandex. Esta guía le ayudará a preparar un cambio que sea más fácil de revisar, integrar de forma segura y mantener.

## Antes de empezar

- Informe de un error reproducible mediante un GitHub Issue.
- Cree una feature request para una nueva función o mejora.
- Para un problema de seguridad, siga la [política de seguridad](../../.github/SECURITY.md) y no publique detalles sensibles.
- Comente primero con el mantenedor, mediante un Issue, los cambios grandes o incompatibles con versiones anteriores que afecten a la API pública, al contrato OAuth de Yandex ID o a la compatibilidad con `league/oauth2-client`.

## Contrato del paquete

- El paquete es un provider client de Yandex ID independiente de frameworks para `league/oauth2-client`.
- `Yandex` extiende `League\OAuth2\Client\Provider\AbstractProvider` y utiliza el ciclo estándar de grants y access tokens de League.
- El perfil de usuario se representa mediante `YandexResourceOwner`; las partes tipadas del perfil se representan mediante `YandexAvatar`, `YandexPhone`, `YandexSex` y `YandexAvatarSize`.
- El access token utilizado para solicitar el perfil se envía mediante `Authorization: OAuth <token>` y no debe aparecer en la URL.
- Los parámetros de autorización específicos del provider, los endpoints y el tratamiento de errores deben respetar el contrato actual de Yandex ID y la API compatible de `league/oauth2-client`.
- Un `scope` vacío no debe serializarse como `scope=`; los scopes no vacíos proporcionados explícitamente deben conservar el comportamiento esperado.
- El paquete no debe adquirir una dependencia específica de un framework sin una decisión independiente y justificada.
- Los cambios de la API pública deben ser intencionados y tener en cuenta SemVer y la compatibilidad hacia atrás dentro de la línea `1.x`.
- No añada abstracciones, almacenamiento, logging, adaptadores de frameworks ni funciones que no estén relacionadas con el problema que se está resolviendo.

## Ramas

Utilice un nombre corto que refleje el propósito del cambio, por ejemplo:

```text
feature/add-yandex-option
fix/oauth-error-handling
docs/update-yandex-guide
```

## Commits

Se recomienda Conventional Commits. Ejemplos:

```text
feat: add Yandex authorization option
fix: handle Yandex OAuth error
docs: clarify native OAuth flow
test: cover malformed profile response
chore: update CI configuration
```

## Comprobación local

Instale las dependencias y ejecute el conjunto general de comprobaciones:

```shell
composer install
composer check
```

También están disponibles estas comprobaciones específicas:

```shell
composer test
composer analyse
composer cs
```

Ejecute `composer coverage` por separado cuando necesite un informe de cobertura; no es obligatorio para cada cambio.

## Pull Request

En la descripción del Pull Request indique:

- el problema y el cambio realizado;
- las comprobaciones ejecutadas;
- el impacto en la API pública, el contrato OAuth de Yandex ID o la compatibilidad con `league/oauth2-client`;
- las pruebas añadidas o actualizadas;
- los cambios de documentación;
- si se sincronizaron las traducciones de CONTRIBUTING y SECURITY cuando cambiaron estas políticas.

Antes de enviarlo, asegúrese de que:

- no se añadieron Client Secret, authorization code, access token, refresh token reales ni otra configuración privada;
- no aparecen perfiles reales de usuarios ni otros datos personales en el código, los logs, los Issues o los datos de prueba;
- fixtures y examples usan valores sintéticos y anonimizados;
- `vendor/`, `composer.lock`, la caché de PHPUnit y los resultados de cobertura no se añadieron al repositorio.
