# EVENTIFY: estado actual y continuidad

Actualizado 2026-10-08, America/Costa_Rica. Este archivo resume el estado vigente; [HISTORIAL.md](HISTORIAL.md) conserva íntegro el registro anterior, incluidos fallos superados. Consultar evidencias por bloque para los detalles. No sumar resultados de ejecuciones diferentes como si fueran una sola suite.

## Alcance y autorización

- Proyecto académico PHP/MySQL, HTML/CSS/JS/jQuery/AJAX; Linux de desarrollo y Docker Compose para entrega Linux/Windows. Sin gastos ni servicios de pago autorizados.
- Usuario autorizó construcción por bloques y commits/push progresivos. Repositorio privado [USUARIO_DESTINO/eventify](https://github.com/USUARIO_DESTINO/eventify), remoto SSH. No se modificó Código Tico.
- URL pública obligatoria, todavía sin proveedor validado/cuenta/despliegue. InfinityFree no seleccionada por MariaDB; candidata Render PHP + Aiven MySQL documentada en PUBLICACION.md, sin servicios creados. No certificar Windows ni defensa por inferencia.

## Código, servidores y versión

- Rama activa: codex/b08-mysql-tls (preparación técnica; B07 continúa abierto). Base dev.11: 2ce983d; etiqueta anotada v0.1.0-dev.11 publicada y verificada por git ls-remote. Dev.10 (d82dab0) permanece etiquetado y forma parte de esta versión. Ninguna versión estable.
- main permanece en 3da680b74b41fa3da4069879edc1f649de9b97f2. Sin push forzado; etiquetas anteriores intactas. CI manual preparado, no ejecutado.
- Instancia normal: http://localhost:8080, override dev, volúmenes conservados. No sembrar allí para probar interfaz.
- QA: http://localhost:8081, proyecto Compose eventify-qa, base eventify_qa, datos ficticios/claves/volúmenes propios. Imagen con código copiado, sin bind de desarrollo.
- QA todavía sirve reservations.js dev.9 al comprobar en este turno: no contiene AbortController/15000. status QA conserva inicio 14:02:55 UTC, exit_code=0. Dev.10 no activado aún.
- Docker 29.1.3 / Compose 2.40.3 instalados por usuario. Desde agente, socket denegado y sudo -n requiere contraseña. Autorización temporal de su terminal no se comparte con el agente; no cambiar grupos/socket/sudoers para eludirla.
- Secretos/resultados privados: storage/qa/qa.env, demo-credentials.json, status.txt/start.log; storage/verification-status.txt/verification.log e integration-status.txt/integration.log. Ignorados por Git y fuera de raíz web.

## Evidencia vigente

| Área | Comprobado | Límite |
| --- | --- | --- |
| Reglas/PHP/QR | verify-dev-linux.sh, 13:40:03 UTC, exit 0: 177 reglas, sintaxis/QR/PDO MySQL | No demuestra entrega completa |
| Integración | verify-integration-linux.sh, 13:52:00 UTC, exit 0: 170 checks MySQL/HTTP | No incluye navegador ni Windows |
| Concurrencia | Dos carreras reserva (último cupo/duplicado) y cuatro admin-reserva (dos órdenes de aforo/cancelación) con conexiones/procesos/lock reales | Conservar evidencia, no repetir sin cambio/fallo |
| Persistencia QA | start-qa-linux.sh, 14:02:55 UTC, exit 0: volúmenes nuevos y recreación web/db conservan nueve tablas, marcador, credenciales e imágenes | No equivale a restore ni copia limpia del repo |
| Chrome usuario | 154.0.8037.97: búsqueda AJAX, login/logout, total dinámico, reserva gratuita POST 201 → ticket/QR 200, cancelación POST/actualización historial 200 | Primera reserva SQL guardada, UI quedó Procesando; causa no demostrada |
| API | Pronóstico Open-Meteo real visible para evento de MySQL, con atribución | Errores/cuotas probados en reglas/fixtures; fallo real UI pendiente |
| Chrome admin | Categoría creada/desactivada y evento nuevo BORRADOR → PUBLICADO → CANCELADO; búsqueda AJAX lo encontró, detalle se conserva sin reserva/pronóstico. Dashboard/filtros y detalle histórico observados | Finalización/edición con reservas/matriz de permisos completa pendientes |
| Chrome permisos | Logout → /admin conduce a login; USUARIO rechazado en /admin; ticket de otra cuenta muestra 404 sin contenido | No certificar todas las rutas/HTTPS por estos tres casos |
| Responsive | Ticket/QR 360/390/768/1280/1440 sin desbordamiento; dashboard 768 | Formularios/páginas restantes, zoom/orientación/foco y Firefox pendientes |
| Cliente dev.10 | node tests/client/reservation-feedback.cjs: 14 checks correctos; sintaxis JS/diff correctos | Transporte/DOM controlados, no navegador objetivo ni MySQL |
| Transferencia dev.11 | PHP/Bash/Compose estáticos correctos; tres guardas rechazadas y 40 checks de orquestación aprobados con Docker simulado | Dump/import MySQL real, comparación real y flujo restaurado pendientes |

## Cambio dev.10 pendiente de activar

Confirmación AJAX acotada a 15 s, incluyendo lectura JSON. Previene doble submit; ante timeout/red/JSON/5xx orienta a consultar experiencias y bloquea reenvío desde ese formulario. Abortar la espera no revierte una transacción ya confirmada. Errores de negocio permiten corregir datos; éxito mantiene navegación al ticket. Backend/esquema/locks/precios/permisos intactos. Node solo herramienta de prueba, sin dependencia nueva de aplicación o entrega.

## Bloques y pendientes

- B00: documentación/versionado inicial. B01/B02: stack/esquema/integración comprobados; cierre de portabilidad/copia limpia pendiente.
- B03–B06: implementados con integración aprobada y navegador parcial; consultar [MATRIZ.md](MATRIZ.md) para cobertura y faltantes.
- B07 activo: QA/seguridad/concurrencia/persistencia comprobadas parcialmente; sigue abierto.
- B08: GitHub privado operativo; viabilidad documental Render/Aiven revisada en PUBLICACION.md. TLS PDO preparado en dev.12 con guardas unitarias; negociación real, almacenamiento de sesiones/presupuesto API y publicación/pruebas externas pendientes.
- B09: paquete/PDF técnico/cuentas de evaluación/preparación y defensa humana pendientes.

## Próxima acción

1. Activar versión actual y ensayar restore: usuario ejecuta bash scripts/verify-qa-linux.sh desde el proyecto (sustituye el comando anterior start-qa-linux.sh). El agente leerá storage/qa/verification-status.txt/log, registros restore-ID y JS servido. No pedir suites extensas sin cambio/fallo.
2. Continuar navegador: confirmación actualizada, permisos restantes, administración con reservas, finalización y matriz responsive/Firefox.
3. Exportación/restauración QA preparada en dev.11: comprobarla realmente. Después preparar entrega y copia limpia Linux/Windows en equipo compatible; ensayo QA no certifica transferencia final.
4. Preparar adaptaciones de PUBLICACION.md y verificarlas; después obtener autorización externa para los servicios gratuitos. Completar documentación técnica y entrega.

El arranque QA conserva sus claves/volúmenes y datos ficticios creados (categoría 5/evento 9, reservas de pruebas); no resetear para repetir. Cuenta actual Chrome: usuario@eventify.test. No guardar claves en docs/Git. Las capturas locales temporales se relacionan en evidencias/B07.md.

## Registro de este turno

QA continúa en dev.9: JS sin AbortController/15000 y status anterior comprobados. Dev.11 prepara bin/qa-transfer.php, test-restore-qa-linux.sh y wrapper verify-qa-linux.sh; ver RESPALDO.md/evidencias B07. Guardas de base/entorno/destino vacío; proyectos/volúmenes nuevos, claves nuevas, pausa temporal solo QA web, reinicio ante fallos, snapshots/SQL/archivos privados. Transfiere credenciales demo/marcador; galería del código se compara; sesiones/cache/secretos del entorno no se transfieren. No ejecutado contra Docker/MySQL por falta de sudo del agente. Sin mutaciones de datos locales. Pruebas estáticas/40 simuladas correctas; código/etiqueta dev.11 publicados, referencias verificadas. El nuevo comando conjunto permite activar dev.10 y probar restore con una intervención.

## Viabilidad pública revisada

Fuentes oficiales consultadas 2026-10-08: Render admite PHP Docker pero su disco gratuito es efímero; Aiven documenta MySQL Free sin tarjeta/plazo fijo, con límites/inactividad. Combinación candidata, conexión entre proveedores y versión real aún no probadas. InfinityFree usa MariaDB según administrador (17-10-2025); no seleccionar para MySQL obligatorio. Inspección del código detectó TLS externo ausente en Database.php, sesiones y presupuesto weather en disco, DemoSeeder bloqueado correctamente en production. PUBLICACION.md detalla secuencia y aceptación. No cambiaron código, datos, VERSION ni etiquetas; solo documentación. QA todavía sin verification-status/log nuevos; no certificar restore real.

Push documental B08 verificado: codex/b08-publicacion-plan → 1a724513f0c2a43e2690347a7cdea9bf50d12060, HEAD y git ls-remote coincidentes. Enlaces locales y git diff --check correctos. Código/tag dev.11 conservados, sin nuevas pruebas de aplicación por tratarse solo de documentación.

## Incremento dev.12: configuración TLS MySQL

RNF-04/RT-09: DatabaseConfig valida DSN/entorno/modo/CA antes de conectar; production exige verificación del servidor, sin opción de degradar TLS. Database comprueba Ssl_cipher antes de configurar la zona horaria y devolver PDO. Local/test mantienen conexión interna sin CA por defecto. Sin migraciones ni mutaciones de datos ni servicios externos.

40 comprobaciones unitarias de configuración/guardas correctas con PHP 8.3 temporal, extensiones PDO/mysqlnd/pdo_mysql/ctype reales y CA efímera. Prueba añadida a scripts Linux/Windows. Sintaxis PHP y Bash/diff correctos. PHP 8.4 Docker, negociación TLS con servidor válido/CA errónea/hostname erróneo/sin TLS y regresión MySQL local pendientes. Estos resultados no se suman a las 177 reglas antiguas ni certifican nueva versión en ejecución. QA sigue sin registro verification-status nuevo; sudo no interactivo del agente denegado.

Código dev.12 publicado y verificado: f0dbe0faa2eebb066a308cac2c318269bce2a696, rama codex/b08-mysql-tls y etiqueta anotada v0.1.0-dev.12 (referencia peeled coincidente). Dev.11 y main conservados. Sin CI ni despliegue público.
