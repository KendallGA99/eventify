# Propuesta concreta para autorizar publicación académica

Preparada 2026-10-08; usuario autorizó expresamente esta propuesta respondiendo «dale» el 2026-10-08. Todavía sin servicios externos creados ni aplicación publicada. Consulta operativa para B08/RT-09/ENT-A/D; aplicar solo el alcance aprobado. Referencia de código revisada: `6baa754` / `v0.1.0-dev.24`; evidencia de cuentas dev.22, TLS válido/CA/hostname dev.21–22 y servidor sin TLS dev.24. B07 y entrega final siguen abiertos.

## Acción propuesta y costo

Crear o utilizar cuentas propiedad del usuario en Render y Aiven, crear **un servicio Render Web Service Free** para EVENTIFY y **un servicio Aiven MySQL Free**, conectar Render al repositorio privado `USUARIO_DESTINO/eventify` con acceso limitado a ese repositorio y publicar la aplicación académica bajo HTTPS. Crear únicamente cuentas/datos ficticios de evaluación, con claves nuevas entregadas de forma privada. El repositorio conserva su visibilidad privada.

Objetivo de gasto autorizado: **₡0**. No agregar tarjeta, planes pagados, créditos trial, dominios de pago ni automatismos de gasto. Si la cuenta/panel no ofrece el plan gratuito adecuado, detener la creación correspondiente e informar. Revisar los permisos de integración reales antes de aceptar; si exige acceso adicional, no ampliarlo por inferencia.

El usuario realiza personalmente login, MFA, CAPTCHA y aceptación de términos cuando se requieran. No solicitar contraseñas/tokens por chat. La autorización de la propuesta permitiría configurar los servicios y publicar exclusivamente EVENTIFY; no abarca mensajería a terceros ni cambios en otros proyectos.

## Configuración revisable

| Componente | Configuración prevista |
| --- | --- |
| GitHub | Repositorio privado `USUARIO_DESTINO/eventify`; referencia `6baa754` para código; despliegue manual, sin publicar repositorio |
| Render | Web Service, Docker, plan Free, `Dockerfile.public`, contexto raíz, sin discos pagados; health path `/salud`; puerto 10000 |
| Aiven | MySQL, plan Free, base nueva `eventify_evaluacion`, sin importar bases locales; registrar versión real y compatibilidad antes de migrar |
| PHP | `APP_ENV=production`, `APP_SECRET` nuevo generado criptográficamente, `PORT=10000` |
| MySQL | `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` tomados del servicio aprobado; no publicar valores |
| TLS | `DB_TLS_MODE=verify_identity`; CA auténtica del proveedor como Secret File `mysql-ca.pem`; `DB_TLS_CA=/etc/secrets/mysql-ca.pem` en Render; verificar montaje real |
| API | Open-Meteo; habilitar y comprobar desde el proveedor antes de cerrar RF-15; presupuesto durable en MySQL |
| Persistencia | Datos y hashes en MySQL; galería en imagen; sesiones/cache efímeras, reingreso después de reinicio |

No asumir que el nombre propuesto o región están disponibles. Elegir regiones compatibles con la oferta gratuita real; nombres identificables como EVENTIFY académico. No desplegar una base que ya tenga datos ni reutilizar credenciales del ensayo TLS local.

## Orden de ejecución después de autorizar

1. Inspeccionar cuentas/panel, cuotas compartidas y planes sin tarjeta. Confirmar servicio MySQL nuevo y versión/motor compatibles con las migraciones (CHECK, columnas generadas y collation). Guardar evidencia redactada.
2. Obtener CA y credenciales de ejecución en almacenamiento privado. Construir la imagen local del código indicado con Dockerfile.public; conectar por TLS al servicio nuevo y aplicar migraciones con `php bin/migrate.php` en contenedor CLI aislado. Nunca usar la base normal/QA/restaurada.
3. Generar plan nuevo de tres cuentas ficticias mediante prepare-evaluation.py y ejecutar provision-evaluation.php explícitamente contra esa base nueva, antes de publicar. Montar plan solo en ese job; no en web, imagen ni Git. Los comandos de CLI existentes fueron probados en MySQL/TLS aislado; la ejecución externa sigue pendiente.
4. Crear/configurar Render con secretos de ejecución y CA, acceso GitHub restringido y referencia de código indicada. El arranque aplica migraciones idempotentes y falla antes de servir si TLS/BD/configuración son inválidos. No ejecutar DemoSeeder en production.
5. Verificar HTTPS público, cookies/sesión, permisos, ambos roles, catálogo, AJAX, reserva/ticket/QR/cancelación, administración y API. Crear eventos ficticios con el administrador; no copiar datos personales.
6. Comprobar reinicio/redeploy, persistencia de registros/galería/presupuesto y reautenticación. Registrar URL, SHA, cuotas y resultados; ENT-A/RT-09 continúan pendientes hasta esa evidencia. Completar respaldo/recuperación externos y entregar acceso por canal privado autorizado.

Render Free no ofrece shell ni jobs puntuales: el aprovisionamiento inicial se hace desde el contenedor CLI local contra MySQL externo, antes del primer despliegue web. No habilitar un endpoint instalador ni cambiar a un plan de pago para obtener shell.

## Límites gratuitos contrastados

Fuentes oficiales reabiertas 2026-10-08: [Render Free](https://render.com/docs/free) documenta suspensión tras 15 minutos inactivo, filesystem efímero, cuotas compartidas y posibles suspensiones por tráfico saliente. [Aiven MySQL Free](https://aiven.io/docs/products/mysql/concepts/mysql-free-tier) documenta uso sin tarjeta/sin plazo fijo, un MySQL por organización, 1 GB de disco y posible apagado por inactividad. No garantizan disponibilidad continua para evaluación.

[Secret Files de Render](https://render.com/docs/configure-environment-variables#secret-files) documenta archivos disponibles en ejecución bajo `/etc/secrets/`; comprobar el archivo real con Docker antes de aceptar despliegue. No pasar CA/credenciales como argumentos de construcción ni copiarlas a la imagen.

Si cualquiera de los proveedores requiere tarjeta o pago, no continuar ese paso. Si la combinación gratuita no funciona con MySQL real y la carga académica, registrar la incompatibilidad y mantener la URL obligatoria pendiente; la ejecución local no la reemplaza.

## Autorización recibida

El usuario autorizó expresamente la creación/configuración de estos servicios gratuitos, la integración Render–GitHub limitada al repositorio indicado y la publicación HTTPS de EVENTIFY con datos ficticios al responder «dale» a la solicitud concreta. No repetir esta aprobación mientras no cambie el alcance; registrar y resolver permisos adicionales que aparezcan en el panel antes de aceptarlos. AGENTS.md exige «No emitir mensajes, subir datos personales, crear servicios externos, publicar repositorios o activar gastos sin autorización correspondiente». La necesidad de autorización se limita aquí a servicios externos/integración/publicación; no es una nueva aprobación del desarrollo local ya autorizado.


## Inicio de ejecución

Navegador integrado: Render Dashboard redirige a login; se eligió GitHub y abrió su formulario de acceso para Render. Cuenta prevista USUARIO_DESTINO; no se introdujo contraseña ni se otorgaron permisos. Aiven Console muestra login, sin sesión disponible. Pestañas conservadas para que el usuario complete login/registro y términos si procede. Aún no se pudieron inspeccionar cuotas/servicios/planes de sus cuentas. No crear servicios ni certificar publicación por haber abierto las páginas.
