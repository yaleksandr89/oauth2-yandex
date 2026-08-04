# Registrar una aplicación en Yandex OAuth

## Elija un idioma

| Русский | English | Español | 中文 | Français | Deutsch |
|---|---|---|---|---|---|
| [Русский](./yandex-oauth-application.md) | [English](./yandex-oauth-application_en.md) | **Seleccionado** | [中文](./yandex-oauth-application_zh.md) | [Français](./yandex-oauth-application_fr.md) | [Deutsch](./yandex-oauth-application_de.md) |

[← Volver al README](../readme/README_es.md)

## Crear una aplicación

1. Abra [Yandex OAuth](https://oauth.yandex.com/).
2. Cree una aplicación **para autorizar usuarios**.
3. Seleccione la plataforma **Web services**.
4. Añada el Redirect URI al que Yandex devolverá al usuario después de la autorización.

Ejemplo:

```text
https://example.com/oauth/yandex/callback
```

El scheme, host, port y path deben coincidir con el callback de la aplicación.

## Permisos

Solicite únicamente los permisos que la aplicación utiliza realmente.

| Permiso | Datos |
|---|---|
| `login:info` | login, nombre, apellido y sexo |
| `login:email` | dirección de correo |
| `login:avatar` | retrato del usuario |
| `login:birthday` | fecha de nacimiento |
| `login:default_phone` | número de teléfono |

La URL de autorización puede incluir:

- `scope` — permisos necesarios para la aplicación;
- `optional_scope` — permisos adicionales que el usuario puede rechazar.

Si no se envían `scope` ni `optional_scope`, Yandex emite el token con
los permisos seleccionados en la configuración de la aplicación.

## Credenciales de la aplicación

Después de crear la aplicación, Yandex proporciona:

```text
Client ID
Client Secret
```

Guárdelos en la configuración o en variables de entorno. No incluya `Client Secret`
en el repositorio Git, archivos públicos, logs ni mensajes de error.

Ejemplo de variables de entorno:

```dotenv
YANDEX_CLIENT_ID=your-client-id
YANDEX_CLIENT_SECRET=your-client-secret
YANDEX_REDIRECT_URI=https://example.com/oauth/yandex/callback
```

## Callback

Cuando la autorización finaliza correctamente, Yandex devuelve:

```text
?code=<authorization-code>&state=<state>
```

En caso de rechazo o error:

```text
?error=<error-code>&error_description=<description>&state=<state>
```

Valide `state` en ambos escenarios antes de continuar con el callback.

## Documentación oficial

- [Registrar una aplicación](https://yandex.com/dev/id/doc/en/register-client)
- [Obtener el código desde la URL](https://yandex.com/dev/id/doc/en/codes/code-url)
- [Obtener datos del usuario](https://yandex.com/dev/id/doc/en/user-information)
