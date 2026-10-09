# EVENTIFY: estado y continuidad

## Incremento dev.10 — respuesta de reservas y administración real

La rama de trabajo. RF-08/09 y RNF-04: reservations.js acota fetch/lectura JSON a 15 s mediante AbortController, bloquea submit concurrente y evita reenvío desde el mismo formulario ante resultado incierto (timeout/red/JSON/5xx). Mensaje manda consultar experiencias: abortar navegador no revierte la transacción del servidor. HTTP de negocio permite corregir datos; éxito navega al ticket manteniendo bloqueo durante navegación. No modifica backend, precios, locks, permisos o esquema. La causa exacta del primer atasco sigue sin demostrar.

Prueba dirigida tests/client/reservation-feedback.cjs: 14 comprobaciones correctas, transporte/DOM controlados, timeout tanto antes de respuesta como durante cuerpo, doble envío/Enter, error 422/500, JSON inválido, éxito y formulario inválido. Ejecutada con Node incluido en el entorno de pruebas; Node es solo herramienta de pruebas y no dependencia de aplicación/entrega. Comando reproducible con Node instalado: node tests/client/reservation-feedback.cjs. Sintaxis JS y git diff --check correctos. No equivale a navegador/MySQL ni sustituye suites previas. Imagen QA conserva JS dev.9 hasta reconstruir; no afirmar corrección activada aún.

Chrome 154.0.8037.97 en QA dev.9: crear categoría Prueba navegador B07 (id 5), evento ficticio (id 9) 20/11/2026 10:00, capacidad 5/precio 2500; borrador guardado, editar a PUBLICADO, búsqueda AJAX encontró una tarjeta con datos exactos. Cancelación desde administración conservó registro y detalle público, sin formulario de reserva y sin pronóstico; desactivar categoría conservó evento. Se verificaron mensajes de guardado y resultados visibles, sin repetir POST por árbol AX inicial aún desactualizado. Captura /tmp/eventify-qa-admin-event-cancelled.png; siguiente captura pública abajo en evidencias. Solo mutaciones ficticias propias en base QA; instancia 8080 intacta. Finalización, edición con reservas y permisos navegador completos siguen pendientes.

Próxima acción: reconstruir QA mediante bash scripts/start-qa-linux.sh para activar dev.10, luego comprobar JS servido y flujo de navegador. Requiere sudo del usuario porque sudo -n desde el entorno de verificación sigue rechazado; no pedir suites extensas ni cambiar permisos Docker. Continúan pendientes restore/copia limpia Linux/Windows, Firefox/matriz completa, URL pública/PDF/defensa. B07 sigue abierto.

## QA real: persistencia y Chrome — 2026-10-08

Dev.9: start-qa-linux.sh terminó con exit_code=0 (storage/qa/status.txt, inicio 14:02:55 UTC). Log confirma volúmenes QA nuevos y comparación aprobada tras recrear web/db: nueve tablas, marcador, credenciales e imágenes conservados. Supera el pendiente de persistencia QA anterior; no equivale a restauración ni instalación desde copia limpia del repositorio.

Chrome 154.0.8037.97 contra 127.0.0.1:8081 con PHP objetivo/MySQL real: búsqueda AJAX SQL mostró un evento; login/logout de usuario ficticio e ingreso de administrador correctos. Panel del Taller de SQL mostró pronóstico real y atribución Open-Meteo para fecha leída de MySQL. Cantidad 2 actualizó total a 30.000 colones.

Primera confirmación SQL quedó visualmente en «Procesando», pero el historial confirmó reserva guardada. No se repitió el POST. Cancelación posterior por UI: POST 200 y GET experiencias 200, lista CANCELADA conservando importe/ticket; catálogo volvió a 28 espacios. Segunda creación (Encuentro de tecnología gratuito) completó: POST /api/reservas 201, navegación automática a ticket 200 y QR SVG privado 200/cargado. Primera respuesta visual conserva incidencia abierta; causa no demostrada. Hubo también timeouts del control CDP/capturas, que no se atribuyen a PHP por inferencia.

Dashboard administrativo visible: 8 eventos conservados, 5 activos, 5 reservas activas, 15 entradas/93 espacios (16,13 %), ingreso potencial histórico 133.000 colones. Filtros SQL + otro@eventify.test + CANCELADA devolvieron dos reservas esperadas, incluida la creada/cancelada en este ensayo. No afirmar CRUD administrativo completo.

Ticket/QR comprobado a 360/390/768/1280/1440 px: scrollWidth inferior a innerWidth y QR cargado en todos. Dashboard a 768 sin desbordamiento. No cubre matriz completa de páginas, zoom/orientación/teclado ni Firefox. Capturas temporales /tmp/eventify-qa-ticket-mobile.png, /tmp/eventify-qa-ticket-desktop.png, /tmp/eventify-qa-cancel-mobile.png y /tmp/eventify-qa-admin-tablet.png. Tamaño del navegador restablecido al terminar.

Solo datos ficticios QA en 8081; instancia 8080 y esquema intactos. Credenciales/logs privados fuera de Git. Suites previas conservan evidencia de 177 reglas/QR y 170 checks de integración; no se repitieron por este registro documental. B07 permanece abierto. Próximo: investigar y acotar espera de confirmación, CRUD administrativo y matriz responsive/Firefox completos, exportación/restauración aislada y copia limpia Linux/Windows. URL pública/PDF/defensa siguen pendientes. Docker desde el entorno de verificación sigue requiriendo autorización sudo de la terminal del usuario; no cambiar permisos para eludirla.


Fecha de referencia: 2026-10-08, zona del usuario America/Costa_Rica.

## Resumen actual

- Usuario autorizó iniciar la construcción por bloques.
- B00: commit inicial creado: 44da548. Se conservó el snapshot documental que estaba en el índice.
- B01: stack Docker ejecutado por el usuario; salud PDO/MySQL 200 y nueve comprobaciones HTTP verificadas. Persistencia/reinicio todavía pendiente.
- Git local: rama de trabajo creada desde una referencia previa; identidad reutilizada de Código Tico y verificada con cuenta GitHub USUARIO_DESTINO. Código B01–B04 guardado como 0.1.0-dev.1; B05 en 0.1.0-dev.3 y B06 parcial en 0.1.0-dev.5 (categorías/eventos, dashboard y consulta de reservas). Ninguna es estable. Repositorio privado creado: USUARIO_DESTINO/eventify. Push inicial confirmado en main y la rama de trabajo, ambas a0e0102; etiquetas checkpoint-b00 → 44da548 y v0.1.0-dev.1 → a0e0102. Referencias verificadas mediante git ls-remote.
- Usuario autorizó versionado/push progresivo. Remoto: REPOSITORIO_DESTINO_PENDIENTE. No se modificó Código Tico ni se inventaron fechas de commits.
- Linux Mint 22.3: el usuario instaló Docker 29.1.3 y Compose 2.40.3. El entorno de verificación no tiene acceso directo al socket; sudo requiere contraseña interactiva en la terminal del usuario. PHP/MySQL objetivo se ejecutarán dentro de contenedores.
- Herramientas temporales de verificación: PHP CLI 8.3.6 y Compose 2.40.3 extraídos de paquetes Ubuntu en /tmp, sin instalación global.
- Stack objetivo PHP 8.4/Apache + MySQL 8.4 LTS actualizado y ejecutado en Linux mediante Docker. Correcciones de Composer y prueba de rollback incluidas en dev.7; suite vigente aprobada el 2026-10-08, 13:40:03 UTC (exit_code=0).
- Aplicación local disponible en http://localhost:8080 con override de desarrollo; conserva volúmenes. Las vistas temporales con fixtures de turnos anteriores no son el servidor de entrega.
- Verificado en imagen objetivo: sintaxis PHP, 177 comprobaciones de reglas, QR SVG/identificadores y conexión PDO/MySQL. Suite aislada vigente: 170 comprobaciones correctas de migraciones/siembra/integridad, autenticación/permisos HTTP, catálogo, reservas/tickets/cancelación, dashboard/filtros y gestión administrativa.
- Concurrencia de reservas comprobada con dos procesos/conexiones: último cupo y duplicado activo del mismo usuario. Carreras de administración contra reserva comprobadas en dev.8: ambos órdenes de aforo y cancelación; no confundirlas con esas dos pruebas aprobadas.
- B02 integración aprobada; B03–B06 integración pertinente aprobada, cierre completo pendiente de flujos navegador reales y cobertura restante. API Open-Meteo real comprobada independientemente; en tests se desactiva para mantener aislamiento.
- Windows: scripts preparados; instalación limpia en equipo compatible pendiente. Chrome/Firefox, tableta, persistencia/recreación, respaldo/restauración y URL pública pendientes.
- Dependencias QR locales instaladas en imagen objetivo desde composer.lock; QR privado y propiedad comprobados por HTTP. Código vigente etiquetado v0.1.0-dev.8 → 27c91bfe36255cb048a0cb1c7a0ff1cd1e516067; sigue siendo versión de desarrollo.
- Publicación: InfinityFree es candidata de investigación; PHP/MySQL real, API, secretos y límites aún no verificados. No se creó cuenta ni URL.

## Próxima acción necesaria

No pedir repetir la misma verificación sin un cambio o fallo que lo justifique: la ejecución vigente terminó correctamente. Continuar con B06/B07: navegador real con datos ficticios, matriz de requisitos y persistencia/instalación limpia. Las cuatro carreras administrativas ya pasaron. El acceso Docker sigue requiriendo sudo interactivo del usuario; solicitarlo solo cuando haga falta ejecutar nuevas pruebas/reinicios. No modificar permisos para evitarlo.

El script verify-dev-linux.sh guarda registro y resultado privados en storage/verification.log y storage/verification-status.txt, ignorados por Git y fuera de public. La contraseña se introduce solo en la terminal del usuario. Reinicios/instalaciones de prueba deben conservar volúmenes existentes y usar aislamiento para probar restauración.

## Pendientes no resueltos por inferencia

1. Próximos incrementos: commits/push/etiquetas progresivos, revisión y validación antes de versiones estables.
2. Windows/arquitectura/equipo receptor y posibilidad de validación real.
3. Proveedor público gratuito validado con MySQL, persistencia y API; localhost no reemplaza la URL pública.
4. Fecha de entrega, participación del estudiante y evidencia humana de defensa.
5. Adoptar definiciones de estados/ocupación/cancelación/QR al implementar cada bloque.

## Registro de bloques

| Bloque | Estado | Evidencia | Limitación |
| --- | --- | --- | --- |
| B00 | Commit creado | 44da548: documentación inicial | No certifica implementación ni entrega |
| B01 | Implementado parcialmente; cierre pendiente | [Evidencia B01](evidencias/B01.md), [arquitectura](ARQUITECTURA.md), [manual](INSTALACION.md) | Persistencia sin probar; Windows no probado |
| B02 | Implementado; cierre pendiente | [Modelo de datos](DATOS.md), [evidencia B02](evidencias/B02.md) | Suite MySQL aislada aprobada, 170 checks de integración total; ver B07 |
| B03 | Implementado; cierre pendiente | [Evidencia B03](evidencias/B03.md) | HTTP/login/roles/CSRF aprobados; navegador real pendiente |
| B04 | Implementación parcial | [Evidencia B04](evidencias/B04.md) | QR/HTTP/MySQL y dos carreras aprobados; navegador real pendiente |
| B05 | Implementación parcial | [Evidencia B05](evidencias/B05.md): catálogo/filtros/historial jQuery | API real independiente y catálogo MySQL/HTTP aprobados; navegador real completo pendiente |
| B06 | Implementación parcial | [Evidencia B06](evidencias/B06.md): categorías/eventos/estados | Dashboard/reservas/admin MySQL/HTTP y cuatro carreras aprobados; navegador real pendiente |
| B07 | En revisión | [Evidencia B07](evidencias/B07.md) | Suite vigente aprobada; portabilidad/persistencia/navegador pendientes |
| B08 | Pendiente | GitHub/publicación | Requiere autorizaciones externas y proveedor viable |
| B09 | Pendiente | PDF/paquete/defensa | Requiere versión final comprobada |

## Verificaciones de B01

- Compose config válido, redes/puertos comprobados; sintaxis de scripts Linux correcta.
- 11 archivos PHP con sintaxis correcta usando CLI temporal 8.3, no imagen objetivo 8.4.
- 4 comprobaciones de configuración + 7 de claves/persistencia de configuración/redes + 9 respuestas HTTP correctas; alcance limitado a B01.
- Navegador in-app: móvil 390×844 (una columna) y escritorio 1280×900 (tres), sin desbordamiento horizontal observado.
- Salud 503 segura cuando no hay MySQL; no afirmar conexión funcional.
- Arranque Docker y salud real comprobados; persistencia, Windows, URL pública y defensa pendientes.
- El usuario ejecutó la instalación de Docker. No se borraron datos, no hubo gastos, commits ni publicación.

## Verificaciones de B02

- 19 archivos PHP con sintaxis válida mediante CLI temporal 8.3.6.
- 4 comprobaciones de configuración y 19 de reglas correctas.
- Scripts Bash y Compose de pruebas comprobados estáticamente.
- Suite MySQL ejecutada por el usuario: llegó hasta HTTP y falló en la comprobación de emisión de cookie. Corrección preparada, suite completa aún no aprobada.
- Migraciones 001/002 ejecutadas; demo se ejecutó en la suite aislada antes del fallo HTTP. No cerrar B02 hasta repetir y completar la suite.

## Verificaciones de B03

- Sintaxis PHP válida con CLI temporal 8.3.6; 16 comprobaciones de registro/CSRF correctas. Total de unidades: 39.
- Nueve respuestas HTTP comprobadas en el Docker anterior a B03, salud 200.
- Suite MySQL ampliada ejecutada por el usuario: falló al esperar Set-Cookie en una sesión ya existente. Corrección de test y respuesta CSRF 403 preparadas; repetir.
- No marcar autenticación terminada hasta comprobar la imagen actualizada y navegador.

## Verificaciones de B04

- 19 unidades nuevas de cantidades/importes correctas; total vigente 58.
- Suite preparada con rollback y dos carreras de conexiones independientes; no ejecutada todavía.
- Sitio HTTP continúa respondiendo 500 ante CSRF inválido: versión anterior sigue activa. Activación del modo dev/corrección aún pendiente.

- QR SVG real generado con dependencia compatible en entorno temporal y decodificado con ZBar, identificador exacto sin datos personales. Plataforma del proyecto ^8.4 permanece intacta; imagen objetivo pendiente.
- Composer validate/audit correctos con advertencias documentadas; JS reservations.js con sintaxis válida.

## Plantilla después de cada bloque

```text
Fecha y bloque:
Objetivo y requisitos:
Archivos/decisiones modificados:
Pruebas y comandos ejecutados:
Resultados observados:
Pruebas no ejecutadas y motivo:
Impacto en datos y despliegue:
Commit/PR/URL, si existen:
Riesgos/pendientes:
Próxima acción:
```

Actualizar con hechos; evidencias extensas en docs/evidencias/Bxx.md. No duplicar estados contradictorios ni marcar compatibilidad/defensa sin comprobación.

## Versionado adoptado

Ver [VERSIONADO.md](VERSIONADO.md) y CHANGELOG.md. Workflow manual preparado; no se ejecutó ni se habilitó despliegue. Rollback real de código/datos pendiente de ensayo aislado.

Publicación inicial: https://github.com/USUARIO_DESTINO/eventify (privado). SSH autenticado y push verificado; sin .env/vendor/datos privados en el índice. CI manual preparado y no ejecutado. No se desplegó la aplicación ni se restauró/borró MySQL.

## Incremento B05 parcial — 2026-10-08

Objetivo: RF-04/05/06/07/10, RT-02/03, RNF-01/04/05; búsqueda/filtros y actualización de experiencias. Se implementaron categorías/fechas/cupos/estado combinables, paginación, tarjetas con imagen/estados, detalle público cancelado/finalizado sin reservas y fragmentos AJAX escapados. Dos funciones significativas jQuery y cuatro operaciones AJAX preparadas; API externa pendiente. Sin migraciones ni modificación de datos existentes.

Pruebas: 23 unidades nuevas correctas en PHP temporal 8.3.6 (total de reglas vigente 81), sintaxis PHP/JS/Bash correcta, git diff --check correcto. Navegador Chromium in-app con fixture temporal aislada (sin MySQL): búsqueda vacía, filtros, limpiar, error de rango, historial/cancelación dinámica correctos; móvil 390×844 y escritorio 1280×900 sin desbordamiento observado; consola sin errores. Capturas temporales /tmp/eventify-b05-mobile.png y /tmp/eventify-b05-desktop.png. No certifican MySQL, Chrome/Firefox ni imagen objetivo.

La suite HTTP/MySQL incorpora validación de filtros combinados, estados, comodín literal y propiedad del historial; ejecución pendiente por sudo interactivo. storage/verification-status.txt todavía no existe al comprobar este incremento. No cerrar B02–B05 ni declarar estable hasta tener evidencia real. Próximo: integración B04/B05, API externa útil y administración.

Versionado del incremento: push confirmado y referencias verificadas con git ls-remote. La rama de trabajo y etiqueta v0.1.0-dev.2 apuntan al commit de código bbae88b; main conserva 3da680b. Las 81 comprobaciones de reglas se repitieron correctamente en este turno. Commits documentales posteriores no mueven la etiqueta.

## API meteorológica B05 — 2026-10-08

RF-15/RF-07, RNF-04/05 y SEG-06: Open-Meteo integrado en endpoint propio GET /api/eventos/{slug}/clima, coordenadas solo de evento público MySQL y destino HTTPS fijo. Panel jQuery independiente del formulario; no sesión/token/datos personales enviados al proveedor. Pronóstico diario en Costa Rica, horizonte hoy a +15 días; cancelados/finalizados/sin coordenadas/fuera del alcance no consultan. El proveedor gratuito se usa solo para evaluación no comercial, con atribución CC BY 4.0; revisar antes de otro uso.

Caché privada 1 h; errores con espera 10 min; datos anteriores etiquetados y máximo 6 h; bloqueo no bloqueante y presupuesto compartido 30 llamadas/h y 10/min. HTTP con verificación TLS, sin redirecciones, timeout de lectura 3 s y respuesta limitada a 128 KiB. WEATHER_ENABLED=false permite desactivar; pruebas Docker aisladas fuerzan false. No hay nuevas migraciones ni cuentas/gastos.

Evidencia ejecutada: 34 reglas de clima en PHP temporal 8.3.6, sintaxis PHP/JS/Bash y revisión diff. Consulta REAL HTTPS del cliente PHP a San José/2026-10-10: datos recibidos y guardados en /tmp/eventify-weather-live-result.json, luego visibles en panel real renderizado con fixture de evento sin MySQL. Navegador in-app: pronóstico/atribución, fallo simulado con botón reserva habilitado y fecha lejana sin datos correctos. Móvil 390×844 sin desbordamiento, consola sin errores. Capturas /tmp/eventify-weather-desktop.png, failure.png y mobile.png. Esta evidencia no equivale a imagen PHP 8.4 ni evento leído de MySQL. Total de reglas: 115, repetidas correctamente en este turno. Compose normal/test/dev válidos con variables ficticias y --env-file /dev/null, sin leer secretos ni requerir daemon.

storage/verification-status.txt sigue ausente al comprobar; mantener pendientes de integración y no cerrar B04/B05. Próximo: ejecutar verify-dev-linux.sh, comprobar flujo real y continuar B06 administración. Push confirmado: la rama de trabajo y etiqueta v0.1.0-dev.3 → 9ad4df4; git ls-remote comprobado. main sigue 3da680b. Commits documentales posteriores no mueven la etiqueta.

## Administración B06 parcial — 2026-10-08

RF-17/18/19/21, RF-03 y RNF-01/03/04: creación/consulta/edición/desactivación de categorías, creación/consulta/edición de eventos y cancelación/finalización separadas. Control ADMINISTRADOR y CSRF en servidor en todas las acciones; rutas de estado solo POST. Precio decimal exacto/cero, capacidad positiva, fecha Costa Rica con segundos conservados, coordenadas opcionales validadas y galería local de imágenes con lista permitida. Listas paginadas de 50. Sin nuevos uploads ni servicios externos.

Decisiones de protección: no eliminación física; categoría desactivada conserva eventos y no se ofrece para nuevos; eventos cancelados/finalizados son históricos y no reactivables por edición. Slug de evento inmutable; fecha y retorno a borrador bloqueados cuando hay cualquier reserva histórica. Aforo comprobado contra cantidades CONFIRMADAS y estado coordinado con fila del evento FOR UPDATE, igual que reservar/cancelar. Precio histórico de reservas no se modifica. Creación/edición con fecha futura; finalizar solo tras el inicio. Son decisiones adicionales, no todas exigencias literales del PDF.

Verificado: 37 reglas de AdminInput correctas (total 152), sintaxis PHP completa, Bash y diff correctos. Vista real con fixtures sin MySQL: formularios/eventos/categorías, galería, escritorio 1280×900 (dos columnas) y móvil 390×844 (una), ancho 1265/375 sin exceder viewport, consola sin errores. Capturas temporales /tmp/eventify-admin-desktop.png, mobile.png y categories.png. No se guardaron eventos desde estas fixtures ni se verificó autorización real en ellas.

Pruebas MySQL/HTTP ampliadas para crear/editar, reducir aforo, preservar precio/fecha/historial, desactivar categoría, cancelar/finalizar e idempotencia, permiso directo, CSRF y escape. Preparadas sin ejecutar. Carreras admin contra reserva todavía requieren ampliación/verificación. Dashboard y consulta administrativa de reservas pendientes. No cerrar B06 ni declarar estable. storage/verification-status.txt sigue ausente; activación Docker necesita terminal del usuario con sudo. Sin migraciones ni borrado de datos.

Push confirmado y git ls-remote verificado: la rama de trabajo y etiqueta v0.1.0-dev.4 → 779d4e9. main conserva 3da680b. Las 152 comprobaciones de reglas se repitieron correctamente en este turno con PHP temporal 8.3.6. Commits documentales posteriores conservan la etiqueta.

## Dashboard y consulta de reservas B06 — 2026-10-08

RF-16/20, D-03 y RNF-01/03/04: cinco indicadores base, tres derivados (evento/categoría con más entradas e ingresos potenciales). Un SELECT MySQL con CTE reúne todos los indicadores en el mismo snapshot e instante UTC: activos PUBLICADO/futuros, reservas CONFIRMADAS de esos eventos, ocupación ponderada suma entradas/suma capacidad. Cero agregado produce 0,00 %. Total eventos incluye todos los conservados. Rankings por entradas, empates señalados y menor ID determinista; categorías desactivadas con actividad histórica siguen contando. Potencial suma el total histórico de reservas activas y se etiqueta expresamente como distinto de pagos recibidos.

/admin/reservas permite filtros combinados de nombre de evento, nombre/correo de usuario y estado; LIKE con comodines literales y consultas preparadas. 50 por página y orden fecha/ID descendentes; detalle /admin/reservas/{id} conserva precio, total, estado, fecha y código ticket. Consultas solo GET/HEAD, ADMINISTRADOR recién verificado en servidor, no-store. No se añade edición ni se amplía acceso a QR privado de otros usuarios. Sin migraciones ni cambios de datos existentes.

Ejecución real: 25 unidades nuevas, total 177 de reglas correctas con PHP temporal 8.3.6; sintaxis PHP completa/Bash y git diff --check correctos. Navegador Chromium in-app con fixture fuera del repositorio y datos ficticios: dashboard, envío/preservación de filtros y detalle, escritorio 1280×900 y móvil 390×844; anchos observados 1265/375 sin desbordamiento, consola sin errores. Capturas /tmp/eventify-dashboard-desktop.png y mobile.png, admin-reservations-mobile.png y admin-reservation-detail.png. La fixture verifica vistas, no filtros SQL, permisos ni persistencia.

Prueba aislada preparada antes de siembra: dashboard vacío, seis eventos en estados/fechas diferentes, 21 entradas/202 espacios = 10,40 %, tres reservas activas y ₡2.200,00 histórico con precio actual diferente; empates, filtros literales/correo, detalle, 53 canceladas paginadas 50+3 y rollback de fixtures. HTTP ampliado para rechazar rol usuario, filtrar/consultar como administrador, filtro inválido, inexistente y POST prohibido. Estas pruebas MySQL/HTTP NO ejecutadas. sudo -n docker info volvió a requerir contraseña; storage/verification-status.txt ausente. Mantener B06 abierto, Windows/URL pública y concurrencia pendientes. Próximo: integración Docker actualizada y carreras administrativas; comprobar luego instalación/persistencia.

Push confirmado y referencias verificadas con git ls-remote: la rama de trabajo y v0.1.0-dev.5 → b5bc7846bb5580b0c6c09621c34b340687e9e3e8. main conserva 3da680b74b41fa3da4069879edc1f649de9b97f2. Etiqueta de desarrollo, sin ejecutar CI ni desplegar. Commits documentales posteriores no mueven la etiqueta.

## Corrección de construcción — 2026-10-08, dev.6

El usuario ejecutó verify-dev-linux.sh; resultado REAL storage/verification-status.txt: inicio 2026-10-08T13:31:03Z, exit_code=1. Log: Composer 2.10.3 descargado con checksum correcto; instalación detenida al extraer dasprid/enum porque la imagen carece de ext-zip y unzip/7z. Falló durante build, antes del arranque/migraciones/pruebas de esta ejecución; no equivale al fallo HTTP anterior ni a prueba aprobada. El aviso buildx/Bake no fue la causa fatal.

Corrección Dockerfile: apt-get install --no-install-recommends unzip solo dentro de la capa de construcción, Composer conserva lock/checksum/plugins/scripts deshabilitados/plataforma, purge de unzip y limpieza de índices apt al terminar. No instalar paquetes en host ni ampliar permisos Docker; no modificar base/volúmenes. Objetivo RT-06/U-01: desbloquear reconstrucción reproducible del stack con dependencias QR.

Verificado después de editar: Compose normal/dev y aislado válidos con variables ficticias y --env-file /dev/null; Bash -n verify-dev-linux.sh y git diff --check correctos. No repetir unidades sin cambios PHP ni afirmar build corregido hasta ejecutarlo. sudo -n todavía requiere contraseña; se pidió repetir el script en terminal del usuario. B02–B06, imagen objetivo y B07 permanecen abiertos.

Push de corrección confirmado y git ls-remote verificado: la rama de trabajo y v0.1.0-dev.6 → 5fe196b6fa0bf398f68504a61019c1e334b91f4b. main conserva 3da680b. No se ejecutó CI ni se desplegó; etiqueta conserva el código y este registro documental no la mueve.

## Integración Docker real y corrección de prueba — 2026-10-08, dev.7

Nueva ejecución REAL del usuario: storage/verification-status.txt inicio 2026-10-08T13:34:51Z, exit_code=1. Esta vez build dev.6 completó, Composer/QR instalados, arranque/migraciones completados, 177 comprobaciones de reglas y generación/validación QR correctas en imagen objetivo; sintaxis/configuración/conexión MySQL correctas. La suite MySQL aislada llegó hasta reservations.php, después de los datos conocidos de admin-read.php, migraciones/demo/integridad/auth, catalog.php y http-auth.php. Por el orden de ejecución y ausencia de excepciones anteriores, esas comprobaciones completaron, pero la suite completa NO está aprobada.

Error registrado: «Evento no reservable: cocina-agotado (PDOException)». La etiqueta era la última aserción exitosa, no necesariamente la operación fallida. Siguiente instrucción: CREATE TRIGGER fail_ticket. Hipótesis fundada: privilegios globales exigidos por binlog activo (MySQL 8.4); log anterior no guardó SQLSTATE/código, por lo que no afirmar diagnóstico exacto. Referencia oficial https://dev.mysql.com/doc/refman/8.4/en/replication-options-binary-log.html. Avisos de inicialización temporal MySQL no son por sí mismos el fallo.

Corrección de la prueba: solo APP_ENV=test/DB_DATABASE=eventify_test, renombrar temporalmente tickets a isolated_ticket_storage conservando tabla/datos, provocar fallo 1146 al crear ticket y restaurar en finally. Verificar rollback, transacción cerrada y recuentos de reservas/tickets intactos. Sin nuevas facultades globales ni cambios del servicio de reservas, base local o volúmenes. Diagnóstico seguro con etiqueta de fase, SQLSTATE validado, código numérico y cantidad de checks anteriores; sin imprimir mensajes/consultas/trazas/credenciales.

Verificado ahora: sintaxis de ambos PHP modificados y diff correctos; nueve comprobaciones HTTP de tests/smoke_http.py contra stack REAL http://127.0.0.1:8080 correctas (salud 200, rutas privadas de archivos 404, POST salud 405). Repetición de integración/carreras/servicio administrativo pendiente; no cerrar B02–B07. Docker sigue requiriendo sudo interactivo; no alterar permisos. Próximo: repetir verify-dev-linux.sh, examinar códigos reales si falla y continuar revisión navegador/persistencia.

Push confirmado y git ls-remote verificado: la rama de trabajo y v0.1.0-dev.7 → 994ec41361e2cb45bdfe6c40207773e760c21467; main conserva 3da680b. Commits documentales posteriores no mueven etiqueta; sin CI ni despliegue.

## Suite vigente aprobada — 2026-10-08

Evidencia leída directamente: storage/verification-status.txt inicio 2026-10-08T13:40:03Z, exit_code=0; verification.log termina «Actualización y verificaciones de desarrollo terminadas correctamente». 177 reglas correctas, QR generación/identificadores y 152 checks MySQL/HTTP correctos. Build, arranque/migraciones y salud completaron. Código sin modificaciones locales al recibir resultado: dev.7, etiqueta 994ec41, HEAD documental d696a66.

Los fallos anteriores se conservan como historial, pero sus pendientes de reconstrucción/integración quedan supersedidos por esta ejecución. CREATE TRIGGER fue sustituido por fallo de tabla aislada/restauración; rollback conservó reservas/tickets y pasaron ambos procesos de carrera de reservas. AdminReadRepository probó el dataset conocido/empates/filtros/paginación y AdminService CRUD/aforo/precio/historial/estados. No se publicó log privado ni credenciales.

Revisión documentada en evidencias/B07.md con límites reales. No marcar versión estable ni entrega completa: persisten carreras administrativas, flujos navegador reales Chrome/Firefox/tableta, instalación limpia Linux/Windows, persistencia/exportación/restauración, URL pública/PDF/defensa. No es necesario ejecutar nada nuevo del usuario por este registro documental.

## Carreras administrativas preparadas — 2026-10-08, dev.8

Objetivo RF-09/19/21 y D-07: demostrar aforo/historial correctos cuando modificar/cancelar y reservar se solapan. Se inspeccionaron locks de AdminService y ReservationService; ambos bloquean la misma fila de evento FOR UPDATE antes de comprobar/escribir. Sin modificar servicios de aplicación.

Suite admin-races.php + admin-race-worker.php, solo APP_ENV=test/DB_DATABASE=eventify_test, cuatro eventos nuevos aislados: (1) bajar 2→1 antes de intentar reservar 2: administración OK/reserva CONFLICT; (2) reservar 2 antes de bajar 2→1: reserva OK/admin CONFLICT, capacidad 2; (3) cancelar antes de nueva reserva: admin OK/reserva CONFLICT, reserva/ticket anterior intactos; (4) reservar antes de cancelar: ambas OK, evento CANCELADO con ambas reservas/tickets e importe histórico intactos. 18 aserciones adicionales previstas; resultado total esperado 170 solo si realmente pasan.

Instrumentación exclusiva de worker mediante PDO::ATTR_STATEMENT_CLASS y subclase PDOStatement: ejecutar el SELECT FOR UPDATE real y pausar el primero después de adquirirlo; iniciar segundo proceso y esperar señal antes de su SELECT. Ambos deben seguir ejecutándose antes de liberar la barrera. No simula resultado SQL ni modifica transacciones/reglas del servicio. Barreras/tiempos acotados y limpieza solo de temporales propios. Referencia API oficial https://www.php.net/manual/en/pdo.setattribute.php.

Verificado: sintaxis de los PHP afectados, Bash -n scripts/verify-integration-linux.sh y diff correctos; worker cargado con extensión PDO en PHP temporal 8.3.6 rechazó ejecución fuera de test (exit 1, sin conectar ni emitir datos). No equivale a ejecutar constructor/consultas MySQL. Nuevas carreras pendientes de Docker sudo interactivo; se pidió al usuario ejecutar bash scripts/verify-integration-linux.sh. Este wrapper guarda storage/integration-status.txt e integration.log privados y reutiliza test-database-linux.sh; evita reconstruir/repetir unidades sin cambios de aplicación. verification-status.txt anterior exit_code=0 permanece como evidencia dev.7, no certificar dev.8 con él.

No se alteró base local, esquema, volúmenes ni permisos. Próximo: leer integration-status/log, corregir fallos si existen y registrar evidencia; seguir navegador/persistencia/Windows/publicación. No cerrar B06 aún.

Push confirmado y referencias verificadas con git ls-remote: la rama de trabajo y v0.1.0-dev.8 → 27c91bfe36255cb048a0cb1c7a0ff1cd1e516067; main conserva 3da680b. integration-status.txt todavía ausente al comprobar. Sin CI/despliegue; registros posteriores no mueven etiqueta.

## Carreras administrativas aprobadas — 2026-10-08

Evidencia REAL leída de storage/integration-status.txt: inicio 2026-10-08T13:52:00Z, exit_code=0. integration.log: «MySQL: 170 comprobaciones correctas», tests exit 0 y «Verificaciones de integración terminadas correctamente». Comando ejecutado por el usuario: bash scripts/verify-integration-linux.sh. Código dev.8 en 27c91bf, HEAD documental eb5640b, árbol limpio al leer. No copiar registros privados a Git.

Pasaron las 152 comprobaciones previas y 18 nuevas: cuatro carreras con conexión/proceso separados y solapamiento comprobado bajo lock real; aforo primero/reserva primero, cancelación primero/reserva primero. Resultados esperados, capacidad/cantidad/estado, un ticket por reserva conservada e historial monetario anterior intactos. Sin cambios de aplicación, permisos globales, esquema o volúmenes locales. La suite de reglas 177 y QR sigue teniendo evidencia de la ejecución dev.7 anterior; este comando dirigido no los repitió y no se suman falsamente al resultado de integración.

Queda superada la ejecución pendiente de carreras descrita arriba. B06 conserva pendiente el flujo navegador real; B07 conserva Chrome/Firefox/tableta, instalación limpia Linux/Windows, persistencia y respaldo/restauración. URL pública, PDF y defensa siguen pendientes. Registrar esta evidencia documental en GitHub sin mover etiquetas ni main. No pedir repetir pruebas por este cambio de documentos.

## QA y persistencia preparados — 2026-10-08, dev.9

B07, U-01/03, RT-06 y RNF-02/06. Catálogo real en 8080 observado vacío; no sembrar/mutar la instancia existente para poder probar navegador. Nuevo start-qa-linux.sh usa Compose normal (imagen con código copiado, sin override dev) con --env-file privado explícito y -p eventify-qa, puerto 127.0.0.1:8081, base eventify_qa, volúmenes propios. Elimina variables de aplicación heredadas solo del proceso del script para que el entorno del host no cambie el destino. No usa .env de la instancia normal ni borra volúmenes.

Primera configuración QA genera claves aleatorias con openssl, archivo privado storage/qa/qa.env; repetición conserva claves/datos/fechas. Migraciones/siembra opcional se invocan explícitamente; DemoSeeder conserva registros y rechaza conflictos en lugar de sobrescribir. No se puede afirmar copia limpia si ya existían volúmenes QA; registrar qué se observe al ejecutar.

qa-persistence.php guard APP_ENV=local/DB_DATABASE=eventify_qa y verificación SELECT DATABASE(); nueve tablas leídas en snapshot transaccional y orden por PK, guardando solo recuentos/hash (ninguna fila/clave impresa). Compara marcador privado y hash de demo-credentials.json, al menos tres imágenes locales. before crea baseline privado, script recrea web/db del proyecto QA con --force-recreate sin borrar volúmenes, after exige igualdad completa. Detecta archivos faltantes/no legibles y galería incompleta. Después copia credenciales desde contenedor a storage/qa/demo-credentials.json por redirección privada, modo 600; no aparecen en pantalla/log. Registro privado storage/qa/status.txt/start.log.

Verificado: sintaxis PHP de helper y Bash del script, Compose válido con variables ficticias/--env-file /dev/null/-p eventify-qa y diff correcto. NO ejecutar/afirmar persistencia antes del resultado real. sudo -n todavía requiere contraseña; se pidió ejecutar bash scripts/start-qa-linux.sh en terminal del usuario. Los tests anteriores siguen aprobados; no repetirlos por estos helpers de QA. Próximo: leer status/log, comprobar comparación real y navegar 8081 en Chrome (Firefox depende superficie/equipo disponible), flujo usuario/admin, clima, QR y responsive. Windows/exportación/restauración/URL/PDF/defensa siguen pendientes.

Push confirmado y git ls-remote verificado: la rama de trabajo y v0.1.0-dev.9 → 903ee113cda0061aa67d2a5530be53d7de51de84; main conserva 3da680b. storage/qa/status.txt ausente al comprobar; sin CI/despliegue público. Etiqueta no se mueve por registros documentales posteriores.


## Estado anterior a la verificación real de las 15:39 UTC, 2026-10-08

# EVENTIFY: estado actual y continuidad

Actualizado 2026-10-08, America/Costa_Rica. Este archivo resume el estado vigente; [HISTORIAL.md](HISTORIAL.md) conserva íntegro el registro anterior, incluidos fallos superados. Consultar evidencias por bloque para los detalles. No sumar resultados de ejecuciones diferentes como si fueran una sola suite.

## Alcance y autorización

- Proyecto académico PHP/MySQL, HTML/CSS/JS/jQuery/AJAX; Linux de desarrollo y Docker Compose para entrega Linux/Windows. Sin gastos ni servicios de pago autorizados.
- Usuario autorizó construcción por bloques y commits/push progresivos. Repositorio privado [USUARIO_DESTINO/eventify](https://github.com/USUARIO_DESTINO/eventify), remoto SSH. No se modificó Código Tico.
- URL pública obligatoria, todavía sin proveedor validado/cuenta/despliegue. InfinityFree no seleccionada por MariaDB; candidata Render PHP + Aiven MySQL documentada en PUBLICACION.md, sin servicios creados. No certificar Windows ni defensa por inferencia.

## Código, servidores y versión

- Rama activa: la rama de trabajo (documentación; B07 continúa abierto). Código de referencia dev.14: c915142. Base dev.11: 2ce983d; etiqueta anotada v0.1.0-dev.11 publicada y verificada por git ls-remote. Dev.10 (d82dab0) permanece etiquetado y forma parte de esta versión. Ninguna versión estable.
- main permanece en 3da680b74b41fa3da4069879edc1f649de9b97f2. Sin push forzado; etiquetas anteriores intactas. CI manual preparado, no ejecutado.
- Instancia normal: http://localhost:8080, override dev, volúmenes conservados. No sembrar allí para probar interfaz.
- QA: http://localhost:8081, proyecto Compose eventify-qa, base eventify_qa, datos ficticios/claves/volúmenes propios. Imagen con código copiado, sin bind de desarrollo.
- QA todavía sirve reservations.js dev.9 al comprobar en este turno: no contiene AbortController/15000. status QA conserva inicio 14:02:55 UTC, exit_code=0. Dev.10 no activado aún.
- Docker 29.1.3 / Compose 2.40.3 instalados por usuario. Desde el entorno de verificación, socket denegado y sudo -n requiere contraseña. Autorización temporal de su terminal no se comparte con el entorno de verificación; no cambiar grupos/socket/sudoers para eludirla.
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
- B08: GitHub privado operativo; viabilidad documental Render/Aiven revisada en PUBLICACION.md. TLS PDO preparado en dev.12 con guardas unitarias; negociación real, sesiones públicas y publicación/pruebas externas pendientes; presupuesto API MySQL preparado en dev.13, integración real pendiente.
- B09: paquete/PDF técnico/cuentas de evaluación/preparación y defensa humana pendientes.

## Próxima acción

1. Activar versión actual y ensayar restore: usuario ejecuta bash scripts/verify-qa-linux.sh desde el proyecto (sustituye el comando anterior start-qa-linux.sh). Se revisarán storage/qa/verification-status.txt/log, registros restore-ID y JS servido. No pedir suites extensas sin cambio/fallo.
2. Continuar navegador: confirmación actualizada, permisos restantes, administración con reservas, finalización y matriz responsive/Firefox.
3. Exportación/restauración QA preparada en dev.11: comprobarla realmente. Después preparar entrega y copia limpia Linux/Windows en equipo compatible; ensayo QA no certifica transferencia final.
4. Preparar adaptaciones de PUBLICACION.md y verificarlas; después obtener autorización externa para los servicios gratuitos. Completar documentación técnica y entrega.

El arranque QA conserva sus claves/volúmenes y datos ficticios creados (categoría 5/evento 9, reservas de pruebas); no resetear para repetir. Cuenta actual Chrome: usuario@eventify.test. No guardar claves en docs/Git. Las capturas locales temporales se relacionan en evidencias/B07.md.

## Registro de este turno

QA continúa en dev.9: JS sin AbortController/15000 y status anterior comprobados. Dev.11 prepara bin/qa-transfer.php, test-restore-qa-linux.sh y wrapper verify-qa-linux.sh; ver RESPALDO.md/evidencias B07. Guardas de base/entorno/destino vacío; proyectos/volúmenes nuevos, claves nuevas, pausa temporal solo QA web, reinicio ante fallos, snapshots/SQL/archivos privados. Transfiere credenciales demo/marcador; galería del código se compara; sesiones/cache/secretos del entorno no se transfieren. No ejecutado contra Docker/MySQL por falta de sudo en el entorno de verificación. Sin mutaciones de datos locales. Pruebas estáticas/40 simuladas correctas; código/etiqueta dev.11 publicados, referencias verificadas. El nuevo comando conjunto permite activar dev.10 y probar restore con una intervención.

## Viabilidad pública revisada

Fuentes oficiales consultadas 2026-10-08: Render admite PHP Docker pero su disco gratuito es efímero; Aiven documenta MySQL Free sin tarjeta/plazo fijo, con límites/inactividad. Combinación candidata, conexión entre proveedores y versión real aún no probadas. InfinityFree usa MariaDB según administrador (17-10-2025); no seleccionar para MySQL obligatorio. Inspección del código detectó TLS externo ausente en Database.php, sesiones y presupuesto weather en disco, DemoSeeder bloqueado correctamente en production. PUBLICACION.md detalla secuencia y aceptación. No cambiaron código, datos, VERSION ni etiquetas; solo documentación. QA todavía sin verification-status/log nuevos; no certificar restore real.

Push documental B08 verificado: la rama de trabajo → 1a724513f0c2a43e2690347a7cdea9bf50d12060, HEAD y git ls-remote coincidentes. Enlaces locales y git diff --check correctos. Código/tag dev.11 conservados, sin nuevas pruebas de aplicación por tratarse solo de documentación.

## Incremento dev.12: configuración TLS MySQL

RNF-04/RT-09: DatabaseConfig valida DSN/entorno/modo/CA antes de conectar; production exige verificación del servidor, sin opción de degradar TLS. Database comprueba Ssl_cipher antes de configurar la zona horaria y devolver PDO. Local/test mantienen conexión interna sin CA por defecto. Sin migraciones ni mutaciones de datos ni servicios externos.

40 comprobaciones unitarias de configuración/guardas correctas con PHP 8.3 temporal, extensiones PDO/mysqlnd/pdo_mysql/ctype reales y CA efímera. Prueba añadida a scripts Linux/Windows. Sintaxis PHP y Bash/diff correctos. PHP 8.4 Docker, negociación TLS con servidor válido/CA errónea/hostname erróneo/sin TLS y regresión MySQL local pendientes. Estos resultados no se suman a las 177 reglas antiguas ni certifican nueva versión en ejecución. QA sigue sin registro verification-status nuevo; sudo no interactivo del entorno de verificación denegado.

Código dev.12 publicado y verificado: f0dbe0faa2eebb066a308cac2c318269bce2a696, la rama de trabajo y etiqueta anotada v0.1.0-dev.12 (referencia peeled coincidente). Dev.11 y main conservados. Sin CI ni despliegue público.

## Incremento dev.13: presupuesto meteorológico persistente

B08/RNF-04/RT-09: nueva tabla weather_budget mediante migración aditiva 003, sin alterar firmas anteriores. WeatherController inyecta WeatherBudget PDO en EventWeather: una fila global, transacción/FOR UPDATE, 30 intentos/h y 10/min, consume antes de llamar; rechaza reloj atrasado o transacción ajena, falla cerrado ante errores. Caché efímera puede perderse, pero no debe reiniciar cuota guardada en MySQL. Sin servicios externos nuevos ni cambios de datos ejecutados.

Ejecutado: PHP 8.3 temporal, 44 checks de política y 39 de clima correctos; 11 archivos PHP con sintaxis correcta, Bash/diff correctos; 40 checks de orquestación restauración con Docker simulado correctos. Integración MySQL ampliada/preparada, no ejecutada; concurrencia/persistencia real pendientes. Los snapshots QA/restore ahora incluyen diez tablas. Restore, migración 003 y versión actual aún no aplicados: el entorno de verificación sigue sin sudo no interactivo y no existen registros QA nuevos.

Próxima verificación necesaria por el cambio de conexión/esquema: bash scripts/verify-dev-linux.sh y, si termina bien, bash scripts/verify-qa-linux.sh. El primero cubre unidades/TLS-config/integración MySQL local; el segundo actualiza QA y ensaya restore. Ninguno certifica negociación TLS con proveedor ni Windows. Conservar registros privados; no borrar volúmenes ni historial. Tras aplicar 003, revisar compatibilidad antes de volver a versiones que no contienen esa migración.

Dev.13 publicado y comprobado con git ls-remote: la rama de trabajo y etiqueta anotada v0.1.0-dev.13 → cfb914bee7fe394940d38d002b7b63fcf6d3caf3. main/dev.12/etiquetas anteriores conservados. Sin CI ni migraciones ejecutadas.

## Incremento dev.14: paquete de fuente

B09/U-03/ENT-B: package-source.py exporta commit Git exacto a ZIP de desarrollo y manifiesto SHA-256 por archivo, sin copiar carpeta local ni datos MySQL. Guardas de archivos/secretos por nombre, links/submódulos, cambios sin commit, referencia completa y no sobrescritura. Datos, restauración y credenciales se entregan separadamente. Python/Git solo en generador; receptor sigue Docker Linux/Windows del manual.

13 checks de empaquetado correctos con repositorios temporales ficticios; git diff --check correcto. No cambia aplicación ni esquema respecto de dev.13. Exportación real/integridad se registrarán tras el commit. Docker sigue sin registros nuevos: verification-status conserva 13:40:03 UTC y QA no tiene verification-status; sudo no interactivo denegado. Migración 003, integración actual y restore reales siguen pendientes.

Dev.14 publicado: c915142b7c902a0024bba574aa3477643e4a11a2, la rama de trabajo y etiqueta anotada v0.1.0-dev.14, referencias remotas coincidentes. ZIP real generado en backups/entrega/eventify-desarrollo-c915142b7c90/eventify-source.zip: 258958 bytes, 153 archivos con huellas correctas, VERSION/migración 003/manual presentes; sha256sum -c correcto. SHA-256: 2971946c8fd9a53d9eebd8317ac7ad692d9349634e9eb57699f62b86247e4bdb. Archivo ignorado por Git; no se sube ZIP ni datos privados. Es exportación de fuente, no arranque de copia limpia ni entrega final. El ZIP refleja el estado del commit etiquetado; este registro documental posterior no cambia esa etiqueta.

## B09: documento técnico borrador

Fuente editable docs/TECNICO.md y generador scripts/build-technical-pdf.py (ReportLab, solo documentación). PDF local output/pdf/EVENTIFY_Documento_Tecnico_Borrador.pdf: 14 páginas. Contiene portada sin autoría inventada, índice con páginas, descripción/tecnologías, arquitectura y ER vectoriales, navegación, diccionario de diez tablas, 21 RF y 7 RNF, historias/aceptación, seguridad, endpoints/AJAX/API, instalación/transferencia, evidencia y preparación de defensa/referencias.

Generado con Python/ReportLab del runtime de documentación, renderizado con pdftoppm y revisado visualmente en todas las páginas mediante hoja de contacto, con detalle de diccionario y tabla RNF. Pypdf comprobó presencia de 21 RF y 7 RNF. No se inventaron pruebas ni participación humana. PDF SHA-256: 04b28cd454bc9eb05184426330212efd32412acfe265b2e8186799c233ecf5d8. output y PNG temporales ignorados por Git; fuente/generador versionables. Borrador, no ENT-C final aprobado; faltan actualización de resultados, responsable/docente y revisión humana.

Sin cambios de aplicación, VERSION ni etiquetas dev.14. El ZIP dev.14 anterior no contiene estos documentos posteriores; regenerar paquete de una referencia nueva cuando proceda. Docker mantiene registros antiguos y QA sin verification-status nuevo; sudo no interactivo sigue denegado. No certificar actualización/restauración actuales.


## Registro de continuidad consolidado hasta dev.22

Registro anterior trasladado íntegramente desde ESTADO.md el 2026-10-08. Las referencias a pruebas pendientes describen el momento de cada incremento; consultar ESTADO.md para el resultado vigente.

## Registro actual, 2026-10-08

El usuario ejecutó el comando conjunto en su terminal. Se revisaron ambos status con exit_code=0 y mensajes finales, y se compararon privadamente los snapshots de restauración: diez tablas con igualdad exacta. No se publicaron logs, dumps ni credenciales. QA conserva las cuentas, reservas y fechas de demostración; el destino de restauración queda detenido con volúmenes conservados.

Chrome 154.0.8037.97: se inició sesión con cuenta ficticia existente usuario, se reservaron dos entradas gratuitas de Encuentro de tecnología, se abrió automáticamente el ticket y se cargó el QR. La reserva queda conservada como evidencia ficticia. Catálogo búsqueda SQL a 360 px produjo un único resultado. Cuenta y registro sin desbordamiento a 360; registro también a 768. Logout real correcto; al terminar se restableció el viewport y se dejó el catálogo. Capturas privadas temporales referidas en evidencias/B07.md.

Los mensajes de persistencia/restauración conservaban el texto histórico “nueve tablas” aunque sus snapshots incluían diez. Se corrige únicamente ese texto para referirse al esquema verificado, sin alterar comparación, datos ni migraciones. Este commit de verificación conserva VERSION/etiqueta dev.14; no crea versión estable. El registro anterior íntegro se trasladó a HISTORIAL.md para evitar que resultados ya superados se confundan con el estado actual.

Nueva ejecución detectada al revisar antes del commit: registros conjuntos sobrescritos con inicio 15:42:00 UTC (desarrollo) y 15:42:38 UTC (QA), ambos exit_code=0 y mensajes finales correctos. Desarrollo mantiene 266 unidades/211 MySQL. Restore adicional 20261008154302-2b8eb996, inicio 15:43:02 UTC, exit 0: snapshots de diez tablas exactamente iguales. No se suman resultados entre ejecuciones ni se eliminan destinos. Las horas de la tabla anterior identifican la primera ejecución observada.

## Incremento dev.15: ensayo de copia limpia preparado

Script test-clean-linux.sh y guía COPIA-LIMPIA.md: exportación exacta del commit a ZIP, ruta con espacios, secretos nuevos, proyecto/volúmenes separados y puerto 8083; arranque desde el script incluido, demostración explícita y recreación/comparación. Sin tocar instancia normal ni QA. No cambia reglas/esquema. Se comprobó de nuevo Docker/sudo: socket denegado y sudo no interactivo exige contraseña; la ejecución real requiere la terminal del usuario. No se certifica instalación limpia ni navegador por la preparación.

Dev.15 publicado en la rama de trabajo y etiqueta v0.1.0-dev.15, commit 0e1537a738df. Bash/diff correctos; 13 comprobaciones previas del empaquetador aprobadas. ZIP real generado: backups/entrega/eventify-desarrollo-0e1537a738df/eventify-source.zip, 158 archivos; CRC, huellas y VERSION comprobados. No prueba arranque Docker. Próxima intervención estrictamente necesaria: ejecutar bash scripts/test-clean-linux.sh v0.1.0-dev.15 en terminal; se revisarán status/log privados y se continuará en navegador en 8083 si aprueba.

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

Usuario mostró VM Windows11 en QEMU/KVM. Diagnóstico por el canal de integración QEMU existente: Enterprise LTSC Evaluation64bits/build26100, RAM4GiB, virtualización/SLAT expuestos; hostnestedY/host-passthrough y aproximadamente8GB físicos. WSL no instalado; Docker no encontrado en ruta habitual, sin auditoría de todos los perfiles. No se instaló software, cambió memoria, apagó/reinició VM ni copiaron secretos. PowerShell5.1.26100.1591 parser real: seis scripts Windows sin errores sintácticos, sin ejecutar sus operaciones. VERIFICACION-WINDOWS detalla evidencia y límite de recursos/soporte KVM; ejecución completa de EVENTIFY Windows pendiente. Sin cambio de aplicación/VERSION/etiquetas.

## Dev.18: concurrencia meteorológica preparada

B07/RF-15/RNF-04: tests/integration/weather-budget-races.php y worker incorporados a database.php. Dos casos, último permiso de minuto/hora, usan dos procesos y conexiones reales con barreras y lock de la fila retenido por el padre. Criterios: solapamiento, exactamente un ALLOW/un DENY, un incremento persistido y límite vigente en conexión nueva. Solo base eventify_test; fixture restaurada al terminar, archivos temporales propios retirados, sin proveedor externo ni cambios de negocio/esquema.

Se ejecutó lint de los tres archivos afectados con PHP8.3 temporal, 44 reglas unitarias de presupuesto y git diff --check: correctos. Docker info denegado por socket; sudo -n docker info exige contraseña. No ejecutó carreras/MySQL8.4 y no se eleva el total confirmado de 211. Próxima intervención necesaria: bash scripts/verify-integration-linux.sh en terminal del usuario; comprobar storage/integration-status.txt y log redactado antes de cerrar esta brecha. VERSION dev.18 corresponde al incremento de pruebas, no a entrega estable. Windows, HTTP restaurado/persistencia de reserva nueva, Firefox y URL pública siguen pendientes.

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

## B08: condiciones públicas y aceptación actualizadas

2026-10-08: documentos oficiales Render Free/web-services y Aiven MySQL Free reabiertos, combinación sigue candidata documental, sin conexión entre proveedores ni URL probadas. PUBLICACION actualizado a219 checks/carreras/restore reales; DESPLIEGUE-PUBLICO detalla puertas de aceptación/configuración/TLS/cuentas/persistencia y permisos. Decisión propia para instancia académica única: sesiones PHP de archivos, reautenticación tras reinicios efímeros; entre peticiones siguen persistiendo normalmente. CookieSecure ya obligatoria enproduction. No se modifica código ni VERSION/dev.19 ni se crean cuentas/servicios/gastos.

Faltan arranque público que falle antes de servir ante migración/configuración inválidas, CA privada en ejecución, aprovisionamiento explícito y TLS externo. Se requiere implementarlos/probarlos antes de pedir autorización de creación de servicios concretos. URL/Firefox/Windows/entrega final permanecen pendientes. Fuentes enlazadas en documentos; nueva revisión no garantiza cuota disponible en la cuenta ni disponibilidad continua.

## Dev.20: arranque público implementado y prueba preparada

Dockerfile.public conserva runtime/dependencias del Dockerfile local y agrega entrypoint público. bin/public-preflight.php exigeproduction, variablesDB/TLS explícitas y secreto≥32 caracteres, valida DatabaseConfig sin conectar. Entry validaPORT10000 por defecto, prepara storage parawww-data, Listen/VirtualHost, configtestApache y migración existente antes de servir; error en cualquier paso aborta. Sin seeder/reset/import ni endpoint instalador. Dockerfile/Compose locales conservados, reglas/esquema sin cambios.

python3 tests/orchestration/public_start.py:25 comprobaciones simuladas (puerto válido/inválido, orden y fallos de preflight/Apache/migración) correctas; Bash/diff correctos. RuntimePHP temporal previo no existe y Docker sigue requiriendo sudo en terminal: no se ejecutó lintPHP ni build real en este incremento. scripts/test-public-linux.sh prepara build separado, lintPHP8.4 y tres rechazos reales con --network none, sin puertos/volúmenes/claves reales. Comando necesario: bash scripts/test-public-linux.sh. Registros storage/public-start-ID privados; no repetir219 integración sin cambios de reglas. Arranque positivo/TLS externo/cuentas únicas/URL siguen pendientes.

## Dev.20: imagen pública y rechazos reales aprobados

Usuario ejecutó test-public-linux.sh. Registro storage/public-start-vxriCKan, inicio2026-10-08T19:23:27Z/exit_code=0. Docker construyó18 pasos y etiquetó eventify-public-test:0.1.0-dev.20, imagenbcc9357d2751. PHP8.4 dentro de imagen: public-preflight.php sin errores sintácticos. Tres contenedores --network none/sin puertos/volúmenes rechazaron configuración ausente, APP_ENV=local yPORTinválido; logs individuales contienen mensajes previstos sin valores privados. Mensaje final y status comprobados durante la revisión.

Se superó la brecha de build/lintPHP/rechazos reales de dev.20. No se ejecutó arranque positivo, configtestApache real con configuración válida, migración pública, handshakeTLS ni URL externa. Aprovisionamiento explícito de cuentas únicas también pendiente. Sin cambios de aplicación/VERSION/etiquetas por documentar resultado; no repetir219 integración. Próximo incremento técnico: ensayo aislado TLS/arranque positivo antes de servicios externos y aprovisionamiento controlado, conservando bases existentes.

## Dev.21: ensayo TLS/arranque válido preparado

Compose separado docker-compose.tls-test.yml: proyecto aleatorio eventify-tls-ID, MySQL8.4 con certificados propios/require_secure_transport, DBeventify_tls_test en tmpfs, red interna, sin puertos ni volúmenes persistentes. Scripts generan claves nuevas/certificados ficticios2 días en directorio privado, no CA real externa. Guardas de config efectivo comprueban aislamiento/base/montajes antes de iniciar. Limpieza solo del proyecto creado, datos efímeros previstos del ensayo; archivos/logs/certificados privados se conservan. No toca eventify/QA/restore/clean.

Probe production mediante Database real: TLS válido con tres migraciones; CA ajena y hostnameincorrecto deben lanzarPDOException; Apache /salud e /ingresarHTTP200 interno; recrear solo web y confirmar tres migraciones/conexión nuevamente. No se sembrarán cuentas ni harán reservas. OpenSSL host real verificó positivo/dosnegativos y permisos700/600; Compose config efectivo comprobó nombre/redtmpfs/sinpuertos, Bash/diff correctos. Docker/PHP/TLS real del ensayo sin ejecutar; no afirmar negociación externa ni servidor sinTLS probado (este caso aún falta).

Próximo comando: bash scripts/test-public-tls-linux.sh. Requiere sudo en terminal del usuario; resultados storage/public-tls-ID privados. Aprovisionamiento, TLSproveedor/HTTPS/URL, Firefox/Windows y entrega final siguen pendientes.

## Dev.21: TLS y arranque público aislados aprobados

Usuario ejecutó test-public-tls-linux.sh. Registro storage/public-tls-LeWppmpW, inicio2026-10-08T19:31:50Z/exit_code=0, proyecto eventify-tls-8114c17f00f1. Log confirma build/healthy, lintPHP8.4 del probe, Database real conTLS/certificado válido/MySQL8.4 y tres migraciones, CAajena y hostnameincorrecto rechazados porPDOException, HTTPinterno /salud e /ingresar200, recreación solo web y nuevo positivo. web.log confirma Configuración pública validada, ApacheSyntaxOK y esquema actualizado sin repetir migraciones tras recrear.

Sin puertos públicos ni servicios externos; solo proyecto/red/tmpfs efímeros del ensayo retirados, certificados/logs privados conservados. Sin cambio de controles/datos existentes/VERSION ni repetir219 checks. Se cierran con este alcance negociaciónTLS local y arranque público positivo/recreación. TLS conproveedor/HTTPS público, servidor sinTLS, aprovisionamiento único, URL, Windows/Firefox y entrega final siguen pendientes.

## Dev.22: aprovisionamiento explícito de evaluación preparado

prepare-evaluation.py genera directorio nuevo700/JSON600 con administrador y dosusuarios ficticios/claves hex128bits independientes; no las imprime ni conecta. CLI provision-evaluation.php exige --confirm-evaluation-fixture, archivo privado fuera depublic/≤8KB, production/TLS mediante Database y plan fijo válido. EvaluationProvisioner usaGET_LOCK/transacciónSERIALIZABLE/lectura bloqueante deusers: tabla vacía o rechazo, INSERT conhashPASSWORD_DEFAULT y roles migrados, rollback antefallo. No UPDATE ni reset ni seederauto ni cambio deDemoSeeder. No guarda claves en imagen/MySQL/log ni monta elplan enweb.

Generador probado con dosplanes temporales: seisclaves distintas/roles/formato/permisos/no sobrescritura correctos (primer ensayo detectó Path.open sinopener, corregido a os.open600 y rerun aprobado). Bash/diff correctos. Probe aislado production/eventify_tls_test comprueba3 cuentas/roles/actividad/password_verify/hash distinto deplaintext, rechazo repetición/local/planinválido sin cambio; ejecutado antes/después de recrearweb. Estos casos PHP/MySQL aún no ejecutados, ni lintPHPnuevo; test-public-tls-linux.sh ampliado los prepara en proyecto efímero.

Próximo comando completo: bash scripts/test-public-tls-linux.sh. No crea servicios/cuentas externos ni modifica bases existentes; claves del ensayo privadas en storage/public-tls-ID/evaluation/accounts.json. Procedimiento público debe ejecutarse antes de abrir acceso a terceros y entregar plan original por canal privado autorizado; pérdida de ese plan no autoriza sobrescribir cuentas ni quitarprotecciones. URL/HTTPSproveedor/caso sinTLS/Windows/Firefox y entrega final pendientes.
