# EVENTIFY: estado actual y continuidad

Actualizado 2026-10-08, America/Costa_Rica. Este archivo resume el estado vigente; [HISTORIAL.md](HISTORIAL.md) conserva íntegro el registro anterior, incluidos fallos superados. Consultar evidencias por bloque para los detalles. No sumar resultados de ejecuciones diferentes como si fueran una sola suite.

## Alcance y autorización

- Proyecto académico PHP/MySQL, HTML/CSS/JS/jQuery/AJAX; Linux de desarrollo y Docker Compose para entrega Linux/Windows. Sin gastos ni servicios de pago autorizados.
- Usuario autorizó construcción por bloques y commits/push progresivos. Repositorio privado [USUARIO_DESTINO/eventify](https://github.com/USUARIO_DESTINO/eventify), remoto SSH. No se modificó Código Tico.
- URL pública obligatoria, todavía sin proveedor validado/cuenta/despliegue. InfinityFree no seleccionada por MariaDB; candidata Render PHP + Aiven MySQL documentada en PUBLICACION.md, sin servicios creados. No certificar Windows ni defensa por inferencia.

## Código, servidores y versión

- Rama activa: codex/b07-destinos-existentes (B07 continúa abierto). Código actual dev.19; copia ensayada dev.16: 5249429. Base dev.11: 2ce983d; etiqueta anotada v0.1.0-dev.11 publicada y verificada por git ls-remote. Dev.10 (d82dab0) permanece etiquetado y forma parte de esta versión. Ninguna versión estable.
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
| Integración actual | verify-integration-linux.sh, 16:31:25 UTC, exit0: 219 comprobaciones MySQL/HTTP; migración003, 41 checks secuenciales y ocho aserciones de carreras meteorológicas | No certifica entorno público ni Windows |
| Concurrencia | Dos carreras de reserva, cuatro admin-reserva y dos de presupuesto meteorológico con procesos/conexiones/locks reales | No equivale a todas las carreras posibles |
| Persistencia QA | verify-qa-linux.sh, 15:40:41 UTC, exit 0: diez tablas, marcador, credenciales e imágenes conservados al recrear contenedores | No certifica copia limpia ni Windows |
| Restauración real | Restore aislado con igualdad exacta de diez tablas/archivos; existing-iAQixJNV exit0, Chrome8082: login/logout usuario/admin, historial, ticket/QR y dashboard | No se repitieron escrituras de reserva/admin en el destino restaurado; volúmenes conservados |
| Copia limpia Linux | ZIP dev.17: arranque y reserva/cancelación Chrome; recreación posterior16:41:44UTC exit0 conserva snapshots exactos de diez tablas, ocho reservas/ocho tickets y archivos | Windows y matriz de UI final pendientes |
| Chrome usuario actual | Login/logout, reserva gratuita de dos entradas → navegación automática al ticket → QR cargado; búsqueda AJAX SQL a 360 px | No provocar fallo de red ni repetir reservas para simular evidencia |
| API | Pronóstico Open-Meteo real visible para evento MySQL con presupuesto actualizado | Caída externa UI real pendiente |
| Chrome admin/permisos anteriores | Categoría desactivada, evento BORRADOR → PUBLICADO → CANCELADO, dashboard/filtros, USUARIO rechazado en /admin y ticket ajeno 404 | Finalización/edición con reservas y rutas restantes pendientes |
| Responsive | Ticket/QR 360/390/768/1280/1440 y dashboard 768 anteriores; catálogo/cuenta/registro 360 y registro 768 actuales sin desbordamiento | Registro observado, sin crear cuenta; foco/zoom/orientación/Firefox pendientes |
| Paquete dev.14 | ZIP real de 153 archivos, CRC y huellas correctas; 13 checks del generador | No incluye documentación posterior; sin arranque de copia limpia |
| PDF técnico | Borrador actualizado de 15 páginas generado/revisado, 21 RF/7 RNF y resultados actuales presentes | Revisión humana y criterios de entrega pendientes |

## Bloques y pendientes

- B01/B02: stack/esquema/migraciones/integración actual comprobados; copia limpia/portabilidad pendientes.
- B03–B06: implementación e integración aprobadas, navegador parcial; consultar MATRIZ.md.
- B07 activo: las verificaciones actuales de desarrollo, persistencia y restauración aprobaron. Continúan casos de interfaz, Firefox, instalación limpia Linux/Windows y revisión final.
- B08: GitHub privado operativo; TLS PDO preparado y guardas aprobadas en PHP objetivo. Negociación TLS externa, sesiones persistentes públicas, proveedor/URL y prueba externa pendientes. Presupuesto MySQL aprobado en integración secuencial/persistencia y carreras específicas de minuto/hora.
- B09: paquete de fuente y borrador técnico preparados; actualizar evidencia del PDF y paquete, completar entrega y revisión/preparación/defensa humana.

## Próxima acción

1. Continuar los casos restantes de navegador y revisión de seguridad; no repetir la suite extensa aprobada sin cambio/fallo.
2. Copia limpia Linux dev.17 ya arrancó y completó reserva/cancelación en Chrome; persistencia posterior al flujo y HTTP autenticado restaurado aprobados; continuar matriz de interfaz y Firefox. Mantener datos/volúmenes y secretos fuera de Git. Windows requiere equipo compatible real.
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

## Revisión dirigida de seguridad, 2026-10-08

SEGURIDAD.md registra licencias/procedencia/huella jQuery, Composer audit --locked sin plugins/scripts exit0 (sin avisos ni paquetes abandonados), revisión Auth/Session/CSRF/escape/cliente API y exclusiones Git actuales. Quince comprobaciones HTTP reales de lectura en copia8083 correctas: cabeceras/cookie HTTP local, archivos privados404, admin303 a login y métodos405. Primera expectativa302 corregida al comprobar303 y destino reales, sin modificar controles. No se escanearon imágenes ni todo el historial, ni se certificó HTTPS/TLS público; sin cambios de aplicación/VERSION/etiquetas o datos. Próximo trabajo independiente: actualizar documento técnico y continuar verificaciones restantes.

## Documento técnico actualizado, 2026-10-08

Fuente TECNICO.md/generador actualizados a dev.17: integración266/211, migración/persistencia/restore real de diez tablas, ZIP Linux160 archivos con reserva/cancelación, revisión dirigida de dependencias/15 checks HTTP y límites de cada evidencia. Conserva incidencias dev.15/16 y pendientes reales sin certificar entrega. PDF local regenerado15 páginas, renderizado con pdftoppm; revisión visual de todas las páginas en contacto y portada/tabla de pruebas en detalle. Presencia21 RF/7 RNF/266/211/dev.17 comprobada con pypdf. SHA-256 7ded2684a59fd7b552990fc6a8ee0c70765ef055176d8189baf689e5484e6e53. Sin cambio de aplicación/VERSION/etiquetas, ni nuevas suites de negocio; PDF/output y renders fuera de Git. Documento final/autoría y dominio/defensa humana siguen pendientes.

## Manual y preparación de entrega/defensa

README/INSTALACION actualizados a evidencia dev.17/266 unidades/211 MySQL y diez tablas, sin referencias que presenten como pendientes resultados ya comprobados. DEFENSA.md añade demo propuesta, doce temas con referencias de código, cuatro ejercicios sugeridos y registro humano vacío. VERIFICACION-WINDOWS.md prepara aceptación desde ZIP, versiones del host/backend y recorridos/persistencia/pruebas; Windows sigue sin ejecutar. Requisitos Docker oficiales revisados2026-10-08. Solo documentación, sin aplicación/VERSION/datos nuevos; no afirma participación/defensa ni compatibilidad real.

Paquete actualizado generado desde commit e03973238467: backups/entrega/eventify-desarrollo-e03973238467/eventify-source.zip, 163 archivos/CRC/huellas comprobados, guías Windows/defensa incluidas. Sigue código dev.17; documentación nueva no repite prueba Docker ni certifica Windows. PDF se entrega separado, no está incluido en este ZIP de fuente.

## Windows disponible: diagnóstico y sintaxis reales

Usuario mostró VM Windows11 en QEMU/KVM. Diagnóstico por agente QEMU existente: Enterprise LTSC Evaluation64bits/build26100, RAM4GiB, virtualización/SLAT expuestos; hostnestedY/host-passthrough y aproximadamente8GB físicos. WSL no instalado; Docker no encontrado en ruta habitual, sin auditoría de todos los perfiles. No se instaló software, cambió memoria, apagó/reinició VM ni copiaron secretos. PowerShell5.1.26100.1591 parser real: seis scripts Windows sin errores sintácticos, sin ejecutar sus operaciones. VERIFICACION-WINDOWS detalla evidencia y límite de recursos/soporte KVM; ejecución completa de EVENTIFY Windows pendiente. Sin cambio de aplicación/VERSION/etiquetas.

## Dev.18: concurrencia meteorológica preparada

B07/RF-15/RNF-04: tests/integration/weather-budget-races.php y worker incorporados a database.php. Dos casos, último permiso de minuto/hora, usan dos procesos y conexiones reales con barreras y lock de la fila retenido por el padre. Criterios: solapamiento, exactamente un ALLOW/un DENY, un incremento persistido y límite vigente en conexión nueva. Solo base eventify_test; fixture restaurada al terminar, archivos temporales propios retirados, sin proveedor externo ni cambios de negocio/esquema.

Agente ejecutó lint de los tres archivos afectados con PHP8.3 temporal, 44 reglas unitarias de presupuesto y git diff --check: correctos. Docker info denegado por socket; sudo -n docker info exige contraseña. No ejecutó carreras/MySQL8.4 y no se eleva el total confirmado de 211. Próxima intervención necesaria: bash scripts/verify-integration-linux.sh en terminal del usuario; comprobar storage/integration-status.txt y log redactado antes de cerrar esta brecha. VERSION dev.18 corresponde al incremento de pruebas, no a entrega estable. Windows, HTTP restaurado/persistencia de reserva nueva, Firefox y URL pública siguen pendientes.

## Dev.18: carreras meteorológicas aprobadas

Usuario ejecutó verify-integration-linux.sh: inicio2026-10-08T16:31:25Z, exit_code=0, log MySQL219 comprobaciones correctas y mensaje final. Incluye las ocho nuevas aserciones de carreras minuto/hora (dos procesos con conexiones independientes), sin cambiar controles ni efectuar llamadas externas. Suite anterior211 reemplazada por esta ejecución219, sin sumar ejecuciones. Etiqueta v0.1.0-dev.18/commit e5ab0e645ec33ae783b270266c30182ba5a41f1d publicados y referencias verificadas. Concurrencia específica meteorológica cerrada; B07 integral continúa abierto.

## Dev.19: continuación de destinos existentes preparada

verify-existing-linux.sh recibe IDs explícitos y exige ensayos previos exit0, configuración local/proyectos/puertos/base aislados, volúmenes existentes y etiquetas de propiedad. Copia limpia se pausa, fotografía, recrea, fotografía y compara; restaura web incluso ante fallo. Arranca el destino restaurado existente sin importar/sembrar/migrar/build; compara su snapshot con el posterior a restore y realiza seis checks HTTP200, sin certificar flujo autenticado. Mantiene registros/credenciales privados en storage/existing-ID. Sin cambios de negocio/esquema. Bash/diff y ocho comprobaciones de preflight simuladas correctos; argumento inválido rechazado. Docker real pendiente por sudo requerido en terminal.

Próximo comando: bash scripts/verify-existing-linux.sh 20261008160801-e96614c4 20261008154302-2b8eb996. No repetir pruebas219 ya aprobadas; leer status/snapshots y continuar navegador en8082. PDF/ZIP anteriores siguen referidos a dev.17 y requieren actualización final cuando se consolide evidencia.

## Dev.19: destinos existentes y navegador restaurado aprobados

Usuario ejecutó verify-existing-linux.sh con clean20261008160801-e96614c4 y restore20261008154302-2b8eb996. Registro storage/existing-iAQixJNV inicio2026-10-08T16:41:44Z/exit_code=0; mensaje final seisHTTP correctos. Comparación privada clean-before/after exacta de diez tablas/archivos, ocho reservas y ocho tickets (incluye nueva reserva/cancelación del flujo dev.17). Restore-current coincide exactamente con after original antes del navegador. No hubo importación/siembra/migración/build ni borrado de volúmenes.

Chrome en8082: usuario restaurado ingresó, mostró cinco experiencias (incluida reserva gratuita de dos entradas), abrió ticket existente y QR cargado (complete/naturalWidth>0), cerró sesión. Admin ingresó y dashboard mostró9 eventos/5 activos/6 reservas activas/17 entradas/18,28%/₡133.000; cerró sesión. No se modificaron reservas/eventos en el destino ni se fabricó evidencia por duplicar operaciones. Dos observaciones AX después del login agotaron tiempo; la observación posterior confirmó cuenta correcta sin repetir envío. Capturas privadas /tmp/eventify-restaurado-ticket.png y /tmp/eventify-restaurado-admin.png. Auth/sesiones generan su actividad normal, por lo que la igualdad registrada corresponde a antes del navegador.

Brechas específicas de persistencia posterior al flujo y recorrido HTTP autenticado restaurado cerradas con este alcance. B07 sigue por Firefox/Windows/casos restantes de UI/seguridad; B08 por URL/HTTPS/TLS público; B09 por PDF/ZIP finales y revisión/defensa humana. No se repitieron219 pruebas ni cambió VERSION/etiqueta dev.19 por documentación.

## Revisión dirigida Chrome: vacío, fechas y orientación

2026-10-08, copia limpia8083/Chrome154: búsqueda ZZZ-evento-inexistente-2026 mostró0 eventos/mensaje claro; limpiar restauró5 eventos. Desde2026-10-20/Hasta2026-10-10 mostró error de fecha inicial posterior a final; limpiar recuperó catálogo. Como las fechas lanzan AJAX al cambiar, el error conservó el resultado anterior/URL del último filtro válido, según catalog.js; no afirmar que el error actualiza URL o devuelve resultados nuevos.

Catálogo390x844 (scrollWidth375) y844x390 (829) sin desbordamiento. Detalle Cocina agotado en ambos tamaños, imagen cargada,0 espacios/mensaje y sin formulario de reserva; botón de reconsulta del clima no es reserva. Clima desactivado según ensayo, no una caída del proveedor. Captura privada /tmp/eventify-agotado-390.png revisada. Captura fullPage agotó tiempo; captura de superficie posterior funcionó y viewport temporal restablecido. Sin modificación de código/reglas/datos, sin repetir suites ni VERSION.

Firefox instalado157.0 (firefox --version). Inventario CUA ofrece Chrome/navegador integrado, sin Firefox ni control nativo disponible; no usar otros controladores para eludir esa limitación. RNF-06 sigue pendiente de flujo real en Firefox. Zoom, teclado/foco completo, respuestas AJAX tardías/timeout real y casos administrativos restantes continúan pendientes.

## Permisos y duplicidad: navegador integrado

2026-10-08: Chrome dejó de estar expuesto en inventario CUA; navegador integrado disponible. Pruebas siguientes en IAB sobre destino restaurado8082, no certifican Chrome/Firefox. Usuario ficticio ingresó y URL directa /admin/categorias, /admin/eventos/nuevo y /admin/reservas mostró rechazo de permisos, sin formularios/datos administrativos. Confirmar reserva de Encuentro de tecnología ya reservado produjo mensaje «Ya tiene una reserva activa para este evento»; historial posterior conserva cinco experiencias y ticket original33cce287... de dos entradas, sin tarjeta duplicada. No afirmar conteo MySQL nuevo por esta observación UI.

Tras logout ingresó otro usuario ficticio: ticket ajeno y /qr.svg mostraron página404 sin datos ni imagen. Sesión cerrada al terminar. Capturas privadas /tmp/eventify-reserva-duplicada.png y /tmp/eventify-qr-ajeno-denegado.png. Intento de reserva rechazado es la única escritura de negocio ensayada; no se cancelaron ni editaron registros. Actividad normal de sesiones/auth sigue prevista. No se repitió suite219 ni se cambió código/VERSION dev.19. Rutas administrativas adicionales, registro completo, timeout real, teclado/zoom, Firefox/Windows, URL y entrega final continúan pendientes.
