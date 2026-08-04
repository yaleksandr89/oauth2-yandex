# Política de seguridad

## Elija un idioma

| Русский | English | Español | 中文 | Français | Deutsch |
|---|---|---|---|---|---|
| [Русский](../../SECURITY.md) | [English](./SECURITY_en.md) | **Seleccionado** | [中文](./SECURITY_zh.md) | [Français](./SECURITY_fr.md) | [Deutsch](./SECURITY_de.md) |

## Versiones compatibles

Las correcciones de seguridad se publican para la línea estable `1.x` actual.

| Versión | Compatibilidad |
|---|---|
| `1.x` | Sí |

## Qué se considera una vulnerabilidad

Los problemas de seguridad incluyen, entre otros:

- divulgación, sustitución o transmisión insegura de un OAuth Client Secret, código de
  autorización, access token o refresh token;
- aparición de tokens en URL, registros o mensajes de error;
- omisión de la validación de una respuesta OAuth o aceptación de una respuesta no válida;
- acceso a los datos de otro usuario;
- asociación incorrecta entre un perfil de Yandex ID y una cuenta local;
- errores al procesar el perfil que puedan revelar datos del usuario.

Los errores normales, las preguntas de uso y las solicitudes de funciones pueden
publicarse en GitHub Issues cuando no contengan datos sensibles.

## Cómo informar de una vulnerabilidad

El canal preferido es GitHub Private Vulnerability Reporting:

1. Abra la pestaña **Security** del repositorio.
2. Vaya a **Advisories**.
3. Seleccione **Report a vulnerability**.
4. Envíe el informe sin publicar detalles en un issue público.

No publique:

- Client Secrets;
- códigos de autorización;
- access tokens o refresh tokens;
- datos personales reales de usuarios;
- un exploit funcional o detalles que permitan reproducirlo antes de publicar la corrección.

Si Private Vulnerability Reporting no está disponible, abra un issue público mínimo,
sin datos sensibles, y solicite un canal privado de comunicación.

## Qué incluir

Cuando sea posible, incluya:

- la versión o el commit afectados;
- una descripción del impacto;
- pasos mínimos de reproducción;
- el comportamiento esperado y el real;
- un ejemplo anonimizado de solicitud o respuesta;
- una posible solución, si se conoce.

No utilice tokens, secretos ni datos reales. Sustitúyalos por valores ficticios.

## Gestión del informe

El informe se confirmará y evaluará cuando sea posible. No se garantiza un SLA fijo.

Coordine la divulgación con el mantenedor antes de publicar detalles. Tras confirmar
la vulnerabilidad, se prepararán la corrección y la información sobre las versiones
afectadas mediante un proceso de divulgación coordinada.

Este proyecto no ofrece recompensas por vulnerabilidades.
