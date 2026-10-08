# EVENTIFY: estado actual y continuidad

Actualizado 2026-10-08, America/Costa_Rica. Este archivo resume el estado vigente; [HISTORIAL.md](HISTORIAL.md) conserva íntegro el registro anterior, incluidos fallos superados. Consultar evidencias por bloque para los detalles. No sumar resultados de ejecuciones diferentes como si fueran una sola suite.

## Alcance y autorización

- Proyecto académico PHP/MySQL, HTML/CSS/JS/jQuery/AJAX; Linux de desarrollo y Docker Compose para entrega Linux/Windows. Sin gastos ni servicios de pago autorizados.
- Usuario autorizó construcción por bloques y commits/push progresivos. Repositorio privado [USUARIO_DESTINO/eventify](https://github.com/USUARIO_DESTINO/eventify), remoto SSH. No se modificó Código Tico.
- URL pública obligatoria, todavía sin proveedor validado/cuenta/despliegue. InfinityFree solo candidata de investigación. No certificar Windows ni defensa por inferencia.

## Código, servidores y versión

- Rama activa: codex/b07-reservation-feedback. Código dev.10: d82dab04780c2cf8201d3a85fb1d30b0e6778a9e; etiqueta anotada v0.1.0-dev.10 publicada y verificada por git ls-remote. Ninguna versión estable.
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

## Cambio dev.10 pendiente de activar

Confirmación AJAX acotada a 15 s, incluyendo lectura JSON. Previene doble submit; ante timeout/red/JSON/5xx orienta a consultar experiencias y bloquea reenvío desde ese formulario. Abortar la espera no revierte una transacción ya confirmada. Errores de negocio permiten corregir datos; éxito mantiene navegación al ticket. Backend/esquema/locks/precios/permisos intactos. Node solo herramienta de prueba, sin dependencia nueva de aplicación o entrega.

## Bloques y pendientes

- B00: documentación/versionado inicial. B01/B02: stack/esquema/integración comprobados; cierre de portabilidad/copia limpia pendiente.
- B03–B06: implementados con integración aprobada y navegador parcial; consultar [MATRIZ.md](MATRIZ.md) para cobertura y faltantes.
- B07 activo: QA/seguridad/concurrencia/persistencia comprobadas parcialmente; sigue abierto.
- B08: GitHub privado operativo; publicación pública pendiente.
- B09: paquete/PDF técnico/cuentas de evaluación/preparación y defensa humana pendientes.

## Próxima acción

1. Activar dev.10: usuario ejecuta bash scripts/start-qa-linux.sh desde el proyecto. El agente comprobará automáticamente status/log y JS servido; no pedir suites extensas ni repetir prueba anterior sin motivo.
2. Continuar navegador: confirmación actualizada, permisos restantes, administración con reservas, finalización y matriz responsive/Firefox.
3. Implementar y comprobar exportación/restauración en destino aislado; copia limpia Linux y Windows en equipo compatible.
4. Validar proveedor gratuito PHP/MySQL/API/persistencia y autorizar publicación cuando exista propuesta concreta; completar documentación técnica y entrega.

El arranque QA conserva sus claves/volúmenes y datos ficticios creados (categoría 5/evento 9, reservas de pruebas); no resetear para repetir. Cuenta actual Chrome: usuario@eventify.test. No guardar claves en docs/Git. Las capturas locales temporales se relacionan en evidencias/B07.md.

## Registro de este turno

QA continúa en dev.9: ausencia de JS actualizado y registro antiguo comprobados. Tres controles de acceso verificados en Chrome real, captura /tmp/eventify-qa-ticket-owner-denied.png. Sin mutaciones nuevas de MySQL salvo sesiones/login/logout. HISTORIAL conserva el archivo anterior íntegro; MATRIZ aporta trazabilidad de RF-01 a RF-21 y RNF/entrega. Solo documentación modificada: no repetir suites por estos cambios.
