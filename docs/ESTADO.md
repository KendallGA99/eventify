# EVENTIFY: estado actual y continuidad

Actualizado 2026-10-08, America/Costa_Rica. Este archivo resume el estado vigente; [HISTORIAL.md](HISTORIAL.md) conserva íntegro el registro anterior, incluidos fallos superados. Consultar evidencias por bloque para los detalles. No sumar resultados de ejecuciones diferentes como si fueran una sola suite.

## Alcance y autorización

- Proyecto académico PHP/MySQL, HTML/CSS/JS/jQuery/AJAX; Linux de desarrollo y Docker Compose para entrega Linux/Windows. Sin gastos ni servicios de pago autorizados.
- Usuario autorizó construcción por bloques y commits/push progresivos. Repositorio privado [USUARIO_DESTINO/eventify](https://github.com/USUARIO_DESTINO/eventify), remoto SSH. No se modificó Código Tico.
- URL pública obligatoria, todavía sin proveedor validado/cuenta/despliegue. InfinityFree no seleccionada por MariaDB; candidata Render PHP + Aiven MySQL documentada en PUBLICACION.md, sin servicios creados. No certificar Windows ni defensa por inferencia.

## Código, servidores y versión

- Rama activa: codex/b07-copia-limpia (preparación de entrega; B07 continúa abierto). Código actual dev.17; copia ensayada dev.16: 5249429. Base dev.11: 2ce983d; etiqueta anotada v0.1.0-dev.11 publicada y verificada por git ls-remote. Dev.10 (d82dab0) permanece etiquetado y forma parte de esta versión. Ninguna versión estable.
- main permanece en 3da680b74b41fa3da4069879edc1f649de9b97f2. Sin push forzado; etiquetas anteriores intactas. CI manual preparado, no ejecutado.
- Instancia normal: http://localhost:8080, override dev, volúmenes conservados. No sembrar allí para probar interfaz.
- QA: http://localhost:8081, proyecto Compose eventify-qa, base eventify_qa, datos ficticios/claves/volúmenes propios. Imagen con código copiado, sin bind de desarrollo.
- QA actualizado: migración 003 aplicada; reservations.js servido incluye AbortController y espera de 15000 ms de dev.10. Verificación conjunta de QA iniciada 15:42:38 UTC, exit_code=0 (repetición posterior también correcta).
- Docker 29.1.3 / Compose 2.40.3 instalados por usuario. Desde agente, socket denegado y sudo -n requiere contraseña. Autorización temporal de su terminal no se comparte con el agente; no cambiar grupos/socket/sudoers para eludirla.
- Secretos/resultados privados: storage/qa/qa.env, demo-credentials.json, status.txt/start.log; storage/verification-status.txt/verification.log e integration-status.txt/integration.log. Ignorados por Git y fuera de raíz web.

## Evidencia vigente

| Área | Comprobado | Límite |
| --- | --- | --- |
| Desarrollo actual | verify-dev-linux.sh, 15:39:53 UTC, exit 0: sintaxis PHP 8.4, QR, conexión y 266 comprobaciones unitarias | Las 40 guardas TLS prueban configuración, no negociación externa |
| Integración actual | 211 comprobaciones MySQL/HTTP correctas; migración 003 y 41 checks del presupuesto meteorológico incluidos | Sin carrera simultánea específica de weather_budget |
| Concurrencia | Dos carreras de reserva y cuatro admin-reserva con procesos/conexiones/locks reales | No equivale a todas las carreras posibles |
| Persistencia QA | verify-qa-linux.sh, 15:40:41 UTC, exit 0: diez tablas, marcador, credenciales e imágenes conservados al recrear contenedores | No certifica copia limpia ni Windows |
| Restauración real | restore-20261008154106-a2a37282, inicio 15:41:06 UTC, exit 0: exportación/importación MySQL y fingerprints before/after exactamente iguales, diez tablas incluida weather_budget | Sin recorrido HTTP del destino restaurado; volúmenes conservados |
| Chrome usuario actual | Login/logout, reserva gratuita de dos entradas → navegación automática al ticket → QR cargado; búsqueda AJAX SQL a 360 px | No provocar fallo de red ni repetir reservas para simular evidencia |
| API | Pronóstico Open-Meteo real visible para evento MySQL con presupuesto actualizado | Caída externa UI real pendiente |
| Chrome admin/permisos anteriores | Categoría desactivada, evento BORRADOR → PUBLICADO → CANCELADO, dashboard/filtros, USUARIO rechazado en /admin y ticket ajeno 404 | Finalización/edición con reservas y rutas restantes pendientes |
| Responsive | Ticket/QR 360/390/768/1280/1440 y dashboard 768 anteriores; catálogo/cuenta/registro 360 y registro 768 actuales sin desbordamiento | Registro observado, sin crear cuenta; foco/zoom/orientación/Firefox pendientes |
| Paquete dev.14 | ZIP real de 153 archivos, CRC y huellas correctas; 13 checks del generador | No incluye documentación posterior; sin arranque de copia limpia |
| PDF técnico | Borrador local de 14 páginas generado/revisado, 21 RF y 7 RNF presentes | Sus resultados de QA anteriores deben actualizarse antes de entregar |

## Bloques y pendientes

- B01/B02: stack/esquema/migraciones/integración actual comprobados; copia limpia/portabilidad pendientes.
- B03–B06: implementación e integración aprobadas, navegador parcial; consultar MATRIZ.md.
- B07 activo: las verificaciones actuales de desarrollo, persistencia y restauración aprobaron. Continúan casos de interfaz, Firefox, instalación limpia Linux/Windows y revisión final.
- B08: GitHub privado operativo; TLS PDO preparado y guardas aprobadas en PHP objetivo. Negociación TLS externa, sesiones persistentes públicas, proveedor/URL y prueba externa pendientes. Presupuesto MySQL aprobado en integración secuencial/persistencia, concurrencia específica pendiente.
- B09: paquete de fuente y borrador técnico preparados; actualizar evidencia del PDF y paquete, completar entrega y revisión/preparación/defensa humana.

## Próxima acción

1. Continuar los casos restantes de navegador y revisión de seguridad; no repetir la suite extensa aprobada sin cambio/fallo.
2. Copia limpia Linux dev.17 ya arrancó y completó reserva/cancelación en Chrome; resta comprobar una reserva nueva tras recreación y funcionamiento HTTP del destino restaurado. Mantener datos/volúmenes y secretos fuera de Git. Windows requiere equipo compatible real.
3. Actualizar el PDF técnico con esta evidencia y generar paquete desde una referencia posterior cuando corresponda.
4. Completar la viabilidad pública y obtener autorización de servicios concretos antes de crear cuentas/desplegar. La URL pública es obligatoria y sigue pendiente.

## Registro actual, 2026-10-08

El usuario ejecutó el comando conjunto en su terminal. El agente leyó ambos status con exit_code=0 y mensajes finales, y comparó privadamente los snapshots de restauración: diez tablas con igualdad exacta. No se publicaron logs, dumps ni credenciales. QA conserva las cuentas, reservas y fechas de demostración; el destino de restauración queda detenido con volúmenes conservados.

Chrome 154.0.8037.97: se inició sesión con cuenta ficticia existente usuario, se reservaron dos entradas gratuitas de Encuentro de tecnología, se abrió automáticamente el ticket y se cargó el QR. La reserva queda conservada como evidencia ficticia. Catálogo búsqueda SQL a 360 px produjo un único resultado. Cuenta y registro sin desbordamiento a 360; registro también a 768. Logout real correcto; al terminar se restableció el viewport y se dejó el catálogo. Capturas privadas temporales referidas en evidencias/B07.md.

Los mensajes de persistencia/restauración conservaban el texto histórico “nueve tablas” aunque sus snapshots incluían diez. Se corrige únicamente ese texto para referirse al esquema verificado, sin alterar comparación, datos ni migraciones. Este commit de verificación conserva VERSION/etiqueta dev.14; no crea versión estable. El registro anterior íntegro se trasladó a HISTORIAL.md para evitar que resultados ya superados se confundan con el estado actual.

Nueva ejecución detectada al revisar antes del commit: registros conjuntos sobrescritos con inicio 15:42:00 UTC (desarrollo) y 15:42:38 UTC (QA), ambos exit_code=0 y mensajes finales correctos. Desarrollo mantiene 266 unidades/211 MySQL. Restore adicional 20261008154302-2b8eb996, inicio 15:43:02 UTC, exit 0: snapshots de diez tablas exactamente iguales. No se suman resultados entre ejecuciones ni se eliminan destinos. Las horas de la tabla anterior identifican la primera ejecución observada.

## Incremento dev.15: ensayo de copia limpia preparado

Script test-clean-linux.sh y guía COPIA-LIMPIA.md: exportación exacta del commit a ZIP, ruta con espacios, secretos nuevos, proyecto/volúmenes separados y puerto 8083; arranque desde el script incluido, demostración explícita y recreación/comparación. Sin tocar instancia normal ni QA. No cambia reglas/esquema. Agente comprobó de nuevo Docker/sudo: socket denegado y sudo no interactivo exige contraseña; la ejecución real requiere la terminal del usuario. No se certifica instalación limpia ni navegador por la preparación.

Dev.15 publicado en rama codex/b07-copia-limpia y etiqueta v0.1.0-dev.15, commit 0e1537a738df. Bash/diff correctos; 13 comprobaciones previas del empaquetador aprobadas. ZIP real generado: backups/entrega/eventify-desarrollo-0e1537a738df/eventify-source.zip, 158 archivos; CRC, huellas y VERSION comprobados. No prueba arranque Docker. Próxima intervención estrictamente necesaria: ejecutar bash scripts/test-clean-linux.sh v0.1.0-dev.15 en terminal; agente leerá status/log privados y continuará navegador en 8083 si aprueba.

## Incidencia y corrección dev.16

Ensayos clean-20261008155351-f43d6a3b y clean-20261008155556-10008909 terminaron exit 255. Log muestra eventify-db-1/eventify-web-1 recreados: sudo descartó COMPOSE_PROJECT_NAME del script hijo. Esto afectó contenedores de la instancia normal, no sus volúmenes; no afirmar aislamiento/instalación limpia ni ausencia de impacto. Usuario confirmó Ctrl+C. Recuperación pendiente con start-dev-linux.sh desde repositorio original y .env original; no borrar bases/volúmenes ni resembrar. QA eventify-qa no es el proyecto observado en esos logs.

Dev.16 corrige start-linux.sh con --project validado y -p explícito incluso bajo sudo. Ensayo verifica config JSON: nombre, volúmenes no externos con prefijo, puerto8083 y ausencia de binds; rechaza script antiguo antes de up. Nueve comprobaciones con Docker/sudo simulados aprobadas, sintaxis Bash/diff correctos. Ensayo y recuperación reales siguen pendientes por sudo requerido en terminal. Etiqueta dev.15 se conserva por historial; no repetir su ensayo.

## Copia dev.16 y permisos corregidos dev.17

clean-20261008160307-84bddf1e (16:03:07 UTC) exit0: commit52494299fef8b63d2f408a52281da7f737dd97fe, ZIP159 archivos/sha464e3ab86345cd0ed8d0b6afcc7c874aae96cd0b41155136d2fe28ddecc6f5f4. Proyecto eventify-clean-ID correcto, migraciones001–003 y recreación/comparación aprobadas. Instancia normal8080 responde HTTP200 tras recuperación; esto no certifica todos sus datos/funciones. Copia8083 devuelve HTTP500 en / y /salud: instalación funcional no aprobada.

Se comprobó que Python extractall bajo umask077 creó archivos600/directorios700, copiados a imagen como root; Apache no puede leerlos. Dev.17 normaliza solo fuente a644/directorios755 y scripts ejecutables755 desde el ZIP antes de generar .env600; padre privado700 conservado. Prueba real de extracción temporal: siete guardas correctas; nueve de proyecto sudo simulado correctas; Bash/diff correctos. Nuevo arranque Docker/HTTP pendiente; no modificar permisos de secretos ni borrar destinos para resolverlo.

## Copia limpia dev.17 verificada, 2026-10-08

clean-20261008160801-e96614c4, inicio16:08:01 UTC/exit0: ZIP del commit b604f0eb3f26f1d9fa8141d9c7d0566d4474bdc5 (160 archivos, sha4e581bbfbe61b3e5af98a79ea7bdd40c57682b597e74c2b3fc1368137a9640a7). Log confirma proyecto aislado, migraciones001–003, demostración explícita y diez tablas/archivos conservados tras recreación. HTTP normal8080 y limpia8083 responden200. Fallo de permisos superado; destinos previos conservados, web del dev.16 detenido por usuario.

Chrome: login usuario ficticio, catálogo, reserva nueva de dos entradas gratuitas → ticket automático/QR cargado. Código ficticio ce3c48d29933151052ea0b8008b0111d; cancelación solo de esa reserva confirmó CANCELADA y mantuvo historial mediante AJAX. Login/logout de usuario y login admin correctos; dashboard muestra8 eventos/5 activos/4 reservas activas/14 entradas/15,05%/₡133.000 potenciales. API desactivada según ensayo y no bloqueó reserva. Capturas privadas /tmp/eventify-clean-ticket.png y /tmp/eventify-clean-cancelacion.png. No cambió la aplicación; documentación posterior no altera etiqueta dev.17.

Arranque y flujo central desde ZIP en ruta con espacios aprobados en Linux. La recreación comprobó datos iniciales antes de la reserva nueva, no su persistencia tras una segunda recreación. Windows/Firefox, HTTP del destino restaurado, casos restantes de UI/seguridad, TLS público/URL y documentación/defensa finales permanecen pendientes.
