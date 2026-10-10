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


# Registro preservado al preparar dev.32 (8 de octubre de 2026)

# EVENTIFY: estado actual y continuidad

Actualizado 2026-10-08, America/Costa_Rica. Referencia operativa resumida; [HISTORIAL.md](HISTORIAL.md) conserva los registros anteriores íntegros, incluidas incidencias y resultados superados. Consultar evidencias por bloque. No sumar pruebas de ejecuciones diferentes.

## Alcance y autorización

- Proyecto académico PHP/MySQL, HTML/CSS/JS/jQuery/AJAX; desarrollo Linux, entrega Docker Compose Linux/Windows. Sin gastos autorizados.
- Construcción por bloques y commits/push progresivos autorizados. Repositorio privado [USUARIO_DESTINO/eventify](https://github.com/USUARIO_DESTINO/eventify), remoto SSH. No modificar Código Tico.
- URL pública publicada: https://eventify-academico.onrender.com; aceptación final todavía parcial. Usuario autorizó Render Free + Aiven MySQL Free e integración Render limitada a USUARIO_DESTINO/eventify al responder «dale» el 2026-10-08; gasto máximo ₡0, sin tarjeta ni planes pagados. MySQL Free y Render Free creados; dev.26 Live verificada. No certificar Windows, publicación ni defensa por inferencia.

## Versión e instancias

- VERSION `0.1.0-dev.32` (portada y revisión editorial; aplicación igual a dev.30, otros avisos pendientes); aplicación Render dev.30 `390b1ef` Live; dev.24 `6baa754` y etiqueta `v0.1.0-dev.24` publicados y referencias remotas verificadas (ensayo sin TLS aprobado); dev.23 `3badc85`/`v0.1.0-dev.23` publicado, ensayo parcial y fallo del evaluador; cuentas verificadas en dev.22 (`4947823`, etiqueta `v0.1.0-dev.22`). Ninguna versión estable; CI manual preparado, no ejecutado.
- `main`: `3da680b74b41fa3da4069879edc1f649de9b97f2`. No reescribir historial ni hacer push forzado. Rollback de código no restaura MySQL.
- Destinos existentes, según última verificación: normal `localhost:8080`; QA `localhost:8081` / `eventify-qa`; copia limpia `localhost:8083` / `eventify-clean-20261008160801-e96614c4`; restauración `localhost:8082` / `eventify-restore-20261008154302-2b8eb996`. No afirmar disponibilidad actual sin consultar; conservar sus volúmenes y datos.
- Docker 29.1.3 / Compose 2.40.3. El entorno de verificación no tiene acceso al socket y sudo no interactivo exige contraseña; las operaciones Docker necesarias se ejecutan en terminal del usuario. No modificar grupos/socket/sudoers para eludirlo.
- Credenciales, planes, certificados, registros y snapshots privados en `storage/` y respaldos en `backups/`, ignorados por Git y fuera de la raíz pública. No imprimir claves ni adjuntar estos archivos al repositorio.

## Evidencia vigente

| Área | Resultado confirmado | Alcance pendiente |
| --- | --- | --- |
| Desarrollo | PHP 8.4, sintaxis, QR, conexión y 266 comprobaciones unitarias; última ejecución registrada 15:42:00 UTC, exit 0 | No incluye por inferencia todos los archivos incorporados después |
| Integración | 16:31:25 UTC, exit 0: 219 comprobaciones MySQL/HTTP, tres migraciones; carreras de reserva, administración y presupuesto meteorológico | No sumar las pruebas TLS a las 219; no certifica Windows ni publicación |
| Persistencia y restauración | QA conserva diez tablas y archivos; restauración aislada exacta. `existing-iAQixJNV`, 16:41:44 UTC, exit 0: copia limpia conserva diez tablas, ocho reservas/ocho tickets tras recrear; restauración coincide antes del navegador | Conservar destinos; pruebas específicas no equivalen a todos los escenarios |
| Copia limpia Linux | ZIP dev.28 en ruta con espacios: arranque, roles, reserva/ticket/QR, cancelación/historial; persistencia posterior a reserva/cancelación: diez tablas y archivos idénticos, historial/ticket/QR visibles | Windows y aceptación completa pendientes |
| Navegador | Chrome: login/logout de ambos roles, reserva/ticket/QR, cancelación, dashboard y filtros; restauración autenticada. IAB: permisos administrativos, reserva duplicada y ticket/QR ajenos rechazados | Teclado/zoom, fallo de red real y casos administrativos restantes; Firefox pendiente |
| Diseño adaptable y API | Catálogo, cuenta, registro, ticket/QR y dashboard observados en tamaños indicados en B07; orientación móvil sin desbordamiento. Open-Meteo real visible en QA | Matriz final Chrome/Firefox y caída externa real en UI pendientes |
| Windows | PowerShell 5.1 real: seis scripts sin errores de parser; VM Windows 11 LTSC Evaluation de 4 GiB identificada | Docker/WSL y ejecución completa sin verificar; recursos/soporte limitan el ensayo |
| Seguridad | Composer audit sin avisos, jQuery verificado, 81 controles HTTPS públicos y 505 blobs sin secretos conocidos | Bases PHP/MySQL escaneadas con avisos; imagen construida, correcciones/alcanzabilidad y revisión final pendientes |
| Arranque público aislado | dev.20: imagen PHP 8.4 construida, tres rechazos reales y 25 guardas simuladas. dev.21: MySQL 8.4 con TLS, CA/hostname incorrectos rechazados, tres migraciones, Apache/HTTP interno y recreación aprobados | No demuestra proveedor externo ni URL HTTPS; servidor sin TLS aprobado en dev.24 |
| Cuentas dev.22 | `public-tls-5NcIVnVj`, 19:39:57 UTC, exit 0: CLI creó tres cuentas ficticias con roles/hashes válidos; repetición, entorno local y plan inválido rechazados sin cambios; mismas cuentas válidas tras recrear web | No certifica login público ni concurrencia de aprovisionamiento |
| Documentos y paquete | PDF borrador dev.31: 15 páginas renderizadas y revisadas; ZIP dev.31 exportado y ZIP dev.28 probado preservado | ZIP dev.28 con integridad e instalación limpia Linux comprobadas; paquete final, Windows y revisión/defensa humana pendientes |

## Bloques y próximo trabajo

- B01/B02: stack, esquema y migraciones comprobados. Portabilidad final permanece abierta.
- B03–B06: implementación e integración aprobadas; aceptación de navegador parcial. Consultar [MATRIZ.md](MATRIZ.md).
- B07 abierto: completar Firefox/Windows y casos restantes de UI/seguridad. No repetir la suite extensa sin cambios, fallos o dudas relevantes.
- B08 activo: GitHub privado y preparación pública operativos; TLS y cuentas comprobados en ensayo aislado. Ensayo sin TLS dev.24 aprobado. Propuesta PUBLICACION-SOLICITUD.md autorizada. Render conectado solo a EVENTIFY y servicio Free publicado. MySQL/Render Free, HTTPS y flujos centrales externos verificados en dev.25; parche dev.30 publicado con persistencia y controles HTTPS aprobados; Pronóstico público útil y 81 controles HTTPS aprobados; revisión final pendiente.
- B09 abierto: actualizar documento técnico y paquete cuando la evidencia se consolide; entrega final exige instalación del receptor, entregables completos y evidencia humana real.

## Último resultado y límites

Se revisaron `storage/public-tls-5NcIVnVj/status.txt` y `test.log`: inicio `2026-10-08T19:39:57Z`, `exit_code=0`, proyecto `eventify-tls-ee40bb5ec1e6`. PHP 8.4/MySQL 8.4 con TLS válido, tres migraciones, rechazos de CA/hostname y cuentas verificados antes y después de recrear únicamente web. El CLI de aprovisionamiento fue ejecutado de forma explícita; no existe siembra automática pública. Los hashes se comprobaron mediante password_verify, sin revelar claves.

El ensayo no expuso puertos y retiró solo sus contenedores/red/base tmpfs efímeros previstos; certificados, plan y registros privados se conservan. No modificó bases existentes ni creó cuentas externas. HTTP interno no prueba HTTPS público; roles/hashes no prueban login de navegador. Sin repetir las 219 pruebas de negocio ni cambiar VERSION/etiqueta por documentar este resultado.

## Incremento dev.23 preparado: servidor sin TLS

`docker-compose.no-tls-test.yml`, `tests/integration/public-no-tls.php` y `scripts/test-public-no-tls-linux.sh`: servidor MySQL 8.4 con tls_version vacío, control TCP sin cifrado y base vacía, rechazo mediante Database production con CA válida, arranque público que debe salir con error antes de servir/migrar y control final. Proyecto aleatorio propio, red interna, tmpfs, sin puertos/volúmenes persistentes; retira solo recursos efímeros propios y conserva registros privados. No cambia las políticas de seguridad ni bases existentes.

Compose config real sin daemon verificó aislamiento/configuración; Bash y diff correctos. Manual oficial MySQL 8.4 contrastado antes de ejecutar (opciones antiguas skip-ssl/have_ssl eliminadas; ensayo usa tls_version). PHP nuevo y MySQL real pendientes: `docker info` denegado; `sudo -n docker info` requiere contraseña. Necesario en terminal: `bash scripts/test-public-no-tls-linux.sh`. Leer `storage/public-no-tls-ID/status.txt` y logs antes de afirmar aprobado. No repetir la suite 219 para este incremento aislado.

## Dev.23 ejecutado y corrección dev.24

`storage/public-no-tls-HVqp1VtN`, inicio `2026-10-08T19:50:39Z`, exit_code=1; proyecto `eventify-no-tls-34356d6fced7`. Build y sintaxis PHP 8.4 aprobados. Primer probe: MySQL 8.4 disponible sin TLS, Database production rechaza y base vacía. rejection.log: preflight válido, Apache Syntax OK y error de conexión previsto. Script abortó al exigir código 1 del wrapper Compose; no registró el valor observado ni ejecutó el probe posterior. No afirmar suite completa aprobada ni causa concreta del código. Proyecto/red efímeros retirados; bases existentes conservadas.

Dev.24 conserva el job de rechazo hasta inspeccionar su State real: detenido, ExitCode 1, sin OOM/error y log previsto, sin servir/migrar. Código de Compose queda registrado aparte. Limpieza limitada al nombre y etiqueta del proyecto generado. Ocho controles del evaluador (incluido rechazo de estados activo/exitoso/OOM/runtime/migración/Apache) y Bash/diff correctos. Nueva ejecución Docker pendiente; socket denegado y sudo no interactivo requieren terminal del usuario. Comando: `bash scripts/test-public-no-tls-linux.sh`.

## Dev.24: rechazo sin TLS aprobado

`storage/public-no-tls-QnTPzu56`, inicio `2026-10-08T19:55:27Z`, exit_code=0; proyecto `eventify-no-tls-bedcc85fc265`. PHP 8.4 sin errores sintácticos. Dos probes antes/después del arranque confirman MySQL 8.4 disponible sin TLS, rechazo por Database production y base vacía. State real del job: exited, Running=false, ExitCode=1, OOMKilled=false, Error vacío; terminó 19:55:41 UTC. Log confirma preflight/Apache válidos y error de conexión previsto, sin migraciones ni Apache sirviendo. Compose agotó la espera (código 124), registrado aparte; no confundir ese timeout con fallo del rechazo de la aplicación.

Retirados únicamente job, db y red efímeros propios; plan/certificados/registros privados conservados y bases existentes intactas. Control servidor sin TLS cerrado con este alcance; sin nueva suite 219 ni cambio de VERSION/etiqueta por documentar. Proveedor/HTTPS/URL pública, Windows/Firefox y entrega final pendientes. Propuesta revisable [PUBLICACION-SOLICITUD.md](PUBLICACION-SOLICITUD.md); no crear servicios antes de autorización concreta.

## B08: publicación autorizada; acceso pendiente

Usuario respondió «dale» a propuesta concreta Render Free/Aiven MySQL Free, integración GitHub solo repositorio privado EVENTIFY y publicación HTTPS con datos ficticios/sin tarjeta/pagos. Se abrieron pestañas del navegador integrado: Render→GitHub login y Aiven Console login. Sin sesión previa; usuario debe completar acceso/registro, términos y MFA/CAPTCHA si aparecen, sin compartir claves por chat. Si aparece pantalla de permisos Render, dejarla para inspección; no ampliar a otros repositorios. Pestañas marcadas para continuidad.

No se crearon cuentas/servicios ni se conectó GitHub todavía; cuota, disponibilidad de Free y versión de MySQL de la cuenta no inspeccionadas. Autorización vigente: no volver a preguntar por la misma propuesta. Continuar desde las pestañas existentes una vez autenticadas; no eludir login ni modificar Docker/socket. URL pública sigue pendiente.


## Continuidad del registro externo

2026-10-08, después del «listo» del usuario: pestaña Render en `/register/github`, correo precargado por GitHub (no registrado aquí), texto «By signing up you agree to our terms of service» y botón Create Account. GitHub avanzó al formulario final de registro, pero no hay panel Render ni cuenta finalizada verificados. No se pulsó Create Account: usuario debe revisar y aceptar términos personalmente conforme al procedimiento aprobado.

Aiven continúa sin sesión; se abrió el enlace visible Sign up. Ofrece registro GitHub/Google/Microsoft/email y menciona créditos incluidos; no se activó servicio trial ni plan pagado. Usuario debe completar acceso/registro, sin tarjeta ni creación de servicios. Luego revisar disponibilidad de MySQL Free real y permisos GitHub únicamente EVENTIFY. Pestañas preservadas para continuidad; no se crearon servicios ni se otorgó acceso nuevo al repositorio. Autorización de publicación sigue vigente, sin necesidad de repetirla.


## B08: Render accesible; integración restringida y Aiven pendientes

2026-10-08: tras el siguiente «listo», Render tenía abiertas páginas promocionales. Se volvió al Dashboard y se inició acceso con la sesión GitHub existente: panel My Workspace y formulario New Web Service visibles. No hay servicio creado ni plan/cuotas inspeccionados todavía. Botón Connect GitHub no abrió ventana utilizable; se usó enlace oficial de instalación documentado en [Render Git Provider](https://render.com/docs/git-provider): github.com/apps/render/installations/new.

Instalación de Render en USUARIO_DESTINO preparada con **Only select repositories** y **un único USUARIO_DESTINO/eventify** seleccionado. No se pulsó Install. La app solicita lectura de código/metadata/Dependabot y lectura-escritura de actions/checks/status/deployments/environments/issues/PRs/hooks/workflows; email se solicita individualmente. Usuario debe aceptar este alcance concreto antes de instalar. No se amplió a todos los repositorios ni a Código Tico. Pestaña conservada para revisión.

Aiven quedó en OAuth GitHub con Full access a datos personales y explicación «read and write all user data» (emails privados/perfil/followers). Se canceló la solicitud; no se concedieron permisos. Se preparó alternativa `/signup/email`, sin introducir nombre/email/contraseña ni enviar formulario. Usuario debe completar personalmente credencial/términos y llegar al panel; no activar servicios trial ni añadir tarjeta. Pestaña conservada.

Autorización general Render Free/Aiven MySQL Free vigente, ₡0; siguen pendientes instalación concreta, cuenta Aiven, plan/version/CA externos, publicación y aceptación. No certificar URL ni servicios por preparar formularios. Capturas privadas temporales /tmp/eventify-render-permisos.png y /tmp/eventify-aiven-correo.png; no se añaden a Git.


## B08: integración Render confirmada y formulario Free preparado

2026-10-08: al retomar, formulario antiguo Install seguía abierto; pulsarlo con selección única mostró «GitHub App has already been installed». Se inspeccionó instalación existente Render 169376729: tenía All repositories seleccionado y Render listaba Código Tico y otros repositorios. Se corrigió según alcance autorizado: Only select repositories → USUARIO_DESTINO/eventify → Save. Nueva navegación a configuración confirmó All=false, Only selected=true y único USUARIO_DESTINO/eventify. Render recargado mostró solo EVENTIFY; no se modificó código de otros proyectos ni otras integraciones.

Formulario New Web Service preparado, sin pulsar Deploy: nombre eventify-academico, Docker, la rama de trabajo, Dockerfile.public, health /salud, Auto-Deploy Off, contexto/raíz predeterminados. Plan inicial estaba en $7/mes; se seleccionó Free y UI confirmó $0/mes, 0.1 CPU/512MB. No se creó servicio ni se añadió tarjeta. No tomar formulario no enviado como configuración persistida o URL desplegada; verificar campos de nuevo al retomar. Región provisional Ohio, ajustar si la oferta MySQL requiere otra decisión.

Aiven aún muestra formulario signup/email vacío y Register with email deshabilitado. Usuario debe completar nombre/correo/contraseña y términos, verificar correo si procede y llegar al panel, sin tarjeta ni crear servicios. No se vuelve a pedir autorización general. Después inspeccionar MySQL Free, versión/CA y preparar base nueva antes de desplegar. Publicación real/aceptación externa continúan pendientes.

Captura privada de alcance guardado /tmp/eventify-render-acceso-restringido.png; formulario /tmp/eventify-render-preparado-free.png. Pestañas marcadas para continuidad, sin almacenar claves ni capturas en Git.


## B08: MySQL Free creado en Aiven

2026-10-08: registro por correo completado por el usuario; consola autenticada. Se omitió la configuración inicial con PostgreSQL Developer de prueba. Create service → MySQL tenía Professional seleccionado; se cambió explícitamente a Free: precio $0/Free, free-1-1gb, un CPU/1 GB RAM/1 GB almacenamiento, North America, apagado por inactividad. Se creó eventify-mysql dentro del alcance autorizado; Overview confirma MySQL 8.4, Free y asignación DigitalOcean sfo. No se añadió tarjeta ni se creó servicio Developer/Professional. La cuenta muestra créditos de prueba incluidos en registro; el servicio creado es Free independiente de esos créditos.

Estado observado Building: aún no confirma conexión disponible. CA real copiada a storage/aiven-evaluacion/mysql-ca.pem con permisos 600, directorio 700 ignorado por Git; PEM y fechas válidas mediante openssl. Primer Copy password devolvió vacío durante construcción, descartado como credencial no disponible; no se imprimen secretos. Plan de tres cuentas ficticias preparado con generador existente (JSON600), sin crear cuentas todavía. Job privado preparar.sh/probe.php preparado: guardas MySQL8.4/TLS/identidad/collation/base nueva vacía, migraciones y CLI explícito, sin borrar ni restaurar bases. Bash -n aprobado, PHP/job externo pendiente; todavía no hay production.env válido ni base eventify_evaluacion creada.

Render conserva formulario Free, repo/branch/Dockerfile.public/health originales; región cambiada a Oregon para aproximarla al sfo asignado por Aiven. No se pulsó Deploy ni se cargaron secretos. El acceso Docker del entorno de verificación sigue denegado; sudo no interactivo no disponible. Verificar servidor Running, crear base nueva y guardar credenciales reales antes de solicitar un único comando necesario al usuario. No publicar antes de TLS/migraciones/cuentas externos.


## B08: servidor disponible y base externa nueva confirmada

Aiven pasó a Running/MySQL8.4.11; Create database → eventify_evaluacion mostró mensaje de creación y fila real en Databases, conservando defaultdb. Credencial disponible copiada posteriormente y configuration production.env600 generada fuera de Git: CA real /etc/secrets/mysql-ca.pem, verify_identity, APP_SECRET único. No imprimir ni versionar valores. Captura /tmp/eventify-aiven-base-creada.png muestra base y Running sin claves.

Job privado storage/aiven-evaluacion/preparar.sh listo, Bash-n aprobado; registra preparacion.log/status.txt privados. Requiere Docker en terminal del usuario por socket denegado/sudo interactivo; comando con ruta: bash storage/aiven-evaluacion/preparar.sh. Primero verifica base vacía/TLS/versión/collation y después migra/aprovisiona tres cuentas/repite migración idempotente. No repetir una vez aprovisionado sin inspeccionar: guarda base vacía rechaza repeticiones. PHP y conexión externa aún no ejecutados. Usa credencial administrativa del proveedor solo para preparación; revisar usuario/privilegios de aplicación antes de Render. Render sin Deploy ni URL, aceptación pública pendiente.


## B08: preparación MySQL externa aprobada y entrega local independiente

Leídos status.txt/preparacion.log de storage/aiven-evaluacion: inicio 2026-10-08T20:29:33Z, exit_code=0. Imagen pública dev24 construida; probe PHP confirma MySQL8.4, TLS con identidad/CA, collation compatible y base nueva vacía. Migraciones001–003 aplicadas, tres cuentas ficticias creadas por CLI explícito y segunda migración sin repetición. No demuestra aún URL HTTPS, login público, permisos mínimos del usuario de aplicación o persistencia externa tras redeploy. No repetir job de preparación sobre base poblada. Render sigue sin desplegar.

Usuario reafirma entrega sencilla al compañero por paquete, sin asociaciones/cuentas externas. Confirmado en Compose DB_HOST=db y servicio MySQL local; start-windows genera configuración/inicia/migra sin Render/Aiven/GitHub. INSTALACION documenta separación: ZIP local independiente, Docker/virtualización e Internet inicial requeridos, cuentas EVENTIFY internas, URL pública adicional. No transferir cuentas personales ni secretos públicos en el paquete. Windows completo y paquete final limpio siguen pendientes. Documentación revisada con diff --check; sin cambios de código ni repetición de suite extensa.


## B08: usuario de aplicación limitado preparado

Consola Users confirma solo avnadmin; documentación oficial Aiven identifica cuenta administrativa y privilegios iniciales amplios de nuevos service users. Antes de Render se preparó job privado separado permisos.sh: CREATE USER eventify_app nuevo con REQUIRE SSL (sin reset/rotación/revocación ajena), GRANT SELECT/INSERT/UPDATE/DELETE/CREATE únicamente sobre la base de evaluación; guion escapa el guion bajo del nombre para evitar patrones de base. CREATE permite el CREATE TABLE IF NOT EXISTS del runner; no concede DROP/ALTER/GRANT OPTION/globales. Conserva snapshot de tres cuentas internas; segunda conexión application.env verifica TLS/grants y migraciones ya aplicadas. Archivos/password aleatorio 600 ignorados por Git, no incluidos en imagen/paquete.

Bash-n y exclusión Git aprobados; PHP/job y concesión real pendientes. Docker directo/sudo-n siguen denegados, necesita ejecución en terminal: bash storage/aiven-evaluacion/permisos.sh. Resultado permisos-status.txt/permisos.log privado. No repetir ciegamente si hay fallo o usuario existente; inspeccionar sin borrar. No cargar credencial administrativa en Render; formulario/publicación siguen pendientes hasta confirmar usuario limitado. Fuente: https://aiven.io/docs/products/mysql/howto/manage-service-users y manual MySQL8.4 creación de cuentas.


## B08: fallo de permisos diagnosticado y corregido; Render iniciado

permisos-status exit1, inicio20:35:36UTC: sintaxis PHP válida y CREATE USER/GRANT ejecutados, snapshot de tres cuentas conservado; probe posterior rechazado. No repetir CREATE USER. Diagnóstico independiente PyMySQL1.1.2 (herramienta temporal privada, no dependencia de la aplicación) con CA/hostname verificadas: @@partial_revokes=1 y SQLmode ANSI_QUOTES; escape añadido al guion bajo produjo permiso sobre nombre literal incorrecto. Se concedieron cinco permisos sobre eventify_evaluacion literal y retiraron únicamente los permisos erróneos del usuario eventify_app creado para este ensayo; no modificar avnadmin ni usuarios ajenos. Primer REVOKE tuvo1141 por escape del identificador; nueva consulta information_schema confirmó nombre real y segunda operación exacta aprobada. Tres cuentas/hashes conservados.

Conexión independiente como eventify_app confirma Ssl_cipher, hostname/CA, USAGE global solamente y SELECT/INSERT/UPDATE/DELETE/CREATE en una sola base, tres cuentas y tres migraciones. Evidencia privada permisos-python.json. No afirmar probe PHP aprobado: validación en arranque Render pendiente. permisos.sh bloqueado preventivamente para no repetir creación.

Render preparado con application.env (usuario limitado, nunca avnadmin), SecretFile mysql-ca.pem real, Free$0, Oregon, Docker/Dockerfile.public, la rama de trabajo, /salud, AutoDeployOff. Deploy web service pulsado dentro de publicación previamente autorizada; esperar resultado visible e inspeccionar versión/logs/HTTPS antes de cerrar. No nueva VERSION por cambios de documentación/configuración privada.


## B08: dev.25 Live, flujos públicos y persistencia real

Servicio Render srv-db3vutm7bikc73ajj6og, URL https://eventify-academico.onrender.com; Free, Docker, Oregon. Primer deploy dep-db3vutu7bikc73ajj920/69c9d4e cancelado: construcción/migración PHP8.4 correctas, /salud HTTP503 bajo Apache. Se preparó dev.25/fb461ade36531e3c61710321372e3b307eba6977: copia CA de ejecución para www-data (700/600 fuera de public), preflight y health como trabajador antes de servir. No rebajar TLS ni cambiar SQL/datos. Bash/diff correctos; ensayo TLS aislado de esta modificación aún pendiente.

Despliegue manual dep-db400dui0phs73eg6dng: Deploy succeeded|Live, 2026-10-08 14:42:31CST, duración32.1s; logs confirman preflight root/trabajador, configtest, esquema actualizado sin repetir migraciones y Apache operativo. Código fb461ad coincide con referencia desplegada. No versión estable ni suite219 repetida.

Navegador IAB HTTPS real: portada/catálogo; login administrador, dashboard, crear categoría y evento ficticio publicado para12oct18h, capacidad30/precio2500; logout; login usuario, reservaAJAX de2 entradas/total5000, ticket/QR visible, historial; cancelaciónAJAX conserva registro y catálogo vuelve a30espacios. Clima externo mostró fallo controlado y reserva continuó: acredita manejo de error, no pronóstico exitoso desde Render. Usuario rechazado al navegar directamente a /admin. Datos ficticios explícitos, sin pagos reales.

Restart service real confirmado en Events:8oct14:46CST. Snapshot privado antes/después con conexión TLS independiente coincide en filas/sha256 de diez tablas; reserva cancelada/ticket/evento/usuarios conservados. Sesión expiró por almacenamiento efímero de Render; nuevo login funciona e historial conserva cancelación. No prometer sesión persistente ni equiparar restart a todas las variantes de redeploy/restauración. Archivos snapshot-before/after-restart.json privados. Screenshot /tmp/eventify-publicado.png y ticket privado /tmp/eventify-public-ticket.png; no subir capturas/secretos a Git. Sesión final cerrada; pestaña catálogo entregable.

Pendientes para aceptación final: pronóstico exitoso/proveedor y diagnóstico de fallo; cookies HTTPS/CSRF/propiedad externos completos; nueva copia limpia y Windows/Firefox reales; actualización PDF/ZIP final, revisión seguridad/imágenes/historial y evidencia humana. No solicitar intervención para Docker si diagnóstico independiente seguro permite avanzar; herramienta PyMySQL temporal no sustituye PHP/MySQL ni se entrega como dependencia.


## B08/B09: redacción natural y diagnóstico del clima dev.26

Usuario pide documentos/comentarios claros y naturales para facilitar exposición. AGENTS incorpora pauta permanente: explicar decisiones y reglas, evitar comentarios obvios/frases genéricas, mantener legibilidad y registro real de aportes/pruebas. README actualizado a dev.25/publicación y entrega local independiente; DEFENSA más directa, sin atribuir preparación no realizada. Comentarios WeatherBudget/EventWeather explican caché, concurrencia, cuota y reinicios. Comprobación de líneas sin comentarios confirmó instrucciones ejecutables idénticas en esos dos archivos; diff-check aprobado. Revisión editorial completa del paquete aún pendiente.

Clima: una nueva consulta IAB pública falla controladamente; llamada independiente desde Linux HTTP200 con timezone/unidades correctas y fechas8–23oct. MySQL weather_budget muestra intentos consumidos(2/hora,1/minuto), sin prueba de cuota agotada. No atribuir aún causa a Render. Dev.26/cabd6f3: WeatherClient expandido para lectura y logs solo motivos constantes/códigoHTTP(numérico), nunca URL/coordenadas/respuesta/trace; TLS/timeout3/límite128KiB conservados. Dockerfile.public agrega lint y tests unitarios existentes de clima/presupuesto en build, sin usar MySQL ni proveedor real. Despliegue manual solicitado; resultados de construcción, HTTP y diagnóstico pendientes. No declarar suite ni corrección aprobada antes de evidencia.


## B08: verificación de dev.26 y límite del proveedor

Render confirma Deploy succeeded | Live para cabd6f335d8952332ce6d0a71fcc515bb6f5b176, despliegue dep-db4076rbc2fs73c5vrg0, 2026-10-08 14:56:59 CST, duración 43,4 s. Construcción PHP con lint aprobado y salida real: Clima 39 comprobaciones correctas; Presupuesto meteorológico 44. Arranque conserva esquema sin repetir migraciones y /salud HTTP200. No equivale a repetir toda la suite de integración.

Nueva visita IAB al evento ficticio devuelve fallo controlado. Logs de aplicación a 14:59:40 CST: eventify_weather reason=http_status status=429. El proveedor responde, pero rechaza por límite; no hay evidencia de fallo TLS o de agotamiento del presupuesto propio. No se conoce qué límite exacto del proveedor se alcanzó. IP compartida es una hipótesis, no una causa comprobada. La documentación oficial describe límites gratuitos y posibles cuotas compartidas por IP: https://open-meteo.com/en/pricing y https://github.com/open-meteo/open-meteo/discussions/115. No cambiar destino, aumentar reintentos ni contratar un servicio para eludirlo.

Evidencia visual /tmp/eventify-clima-diagnostico-dev26.png, fuera de Git. RF-15 en publicación continúa pendiente de una respuesta útil real. Próxima acción: evaluar una integración gratuita compatible si persiste el límite, manteniendo el manejo de errores, presupuesto cero y entrega local independiente. Revisión editorial inicial concluida; revisión completa de documento técnico/paquete sigue pendiente. Dev.26 es una versión de desarrollo, no una entrega estable.


## B07/B08: controles HTTPS e historial, dev.27

Objetivo: verificar RNF-03/04, RF-03/12/14 y SEG-01/03 en la web publicada, sin crear datos de negocio ni cambiar permisos. Revisados Session, AuthController, ReservationController, rutas y consultas de propiedad antes del ensayo.

Ejecutado: `python3 tests/public_security.py --base https://eventify-academico.onrender.com --accounts storage/aiven-evaluacion/cuentas/accounts.json`. Resultado: 81 comprobaciones correctas contra dev.26. Cuenta ficticia propietaria y segunda cuenta: login con sesión regenerada, cookies Secure/HttpOnly/SameSite=Lax/path, CSP/nosniff/frame DENY, cuenta no-store, CSRF ausente y token de otra sesión rechazados, API privada sin login401, GET de escritura405, archivos privados404, ticket/QR ajenos404, historial ajeno sin ticket, administración403, logout y reutilización de sesión destruida rechazados. Todos los POST de negocio se enviaron con CSRF inválido; no se enviaron reservas ni cancelaciones válidas. Login/logout generan actividad de autenticación y sesiones. El ensayo no demuestra todos los escenarios de ataque ni la matriz de navegadores.

Sintaxis Python y diff-check aprobados. Script usa biblioteca estándar, HTTPS validado, no sigue redirecciones ni imprime claves/cookies/respuestas. Las cuentas son entrada privada; no forman parte de Git ni del paquete. El receptor no necesita Python para ejecutar la aplicación, únicamente para este ensayo opcional.

Revisión de historial de todas las referencias locales: 505 blobs únicos comparados con secretos reales conocidos de los dos entornos privados y las tres cuentas de evaluación; cero coincidencias. Sin .env privados, storage privado o backups en objetos del historial. Es una comprobación dirigida, no un detector universal de secretos ni escáner de vulnerabilidades de imágenes.

Alternativa meteorológica revisada: MET Norway Locationforecast. Requiere identificación real en User-Agent, atribución CC BY4.0, caché respetando Expires/Last-Modified, compresión y gestión de redirecciones. No es compatible directamente con el adaptador diario actual: series horarias y horizonte distinto. No se activó, no se enviaron coordenadas ni se cambió proveedor. Fuentes: https://docs.api.met.no/doc/TermsOfService y https://docs.api.met.no/doc/ForecastJSON.html. Mantener RF-15 público pendiente; preparar adaptación y pruebas antes de reemplazar la integración.

Dev.27 añade pruebas/documentación; aplicación pública permanece en dev.26, sin nuevo despliegue necesario para este incremento. Próximos pendientes: respuesta útil real de API, imagen/dependencias, Firefox/Windows y actualización PDF/paquete. No marcar B07/B08 completos.


## B09: documento técnico actualizado, dev.28

Objetivo ENT-C/U-03: explicar la versión actual, la entrega local independiente y la evidencia sin atribuir al estudiante una defensa no realizada. Revisados requisitos de PDF, documento técnico, instalación, empaquetador, estado y scripts. El documento conserva RF-01–21, RNF-01–07, historias/aceptación, navegación, arquitectura, ER, diccionario, reglas, seguridad, API, despliegue y conclusiones.

TECNICO actualizado: MySQL/Aiven y Render reales, TLS/usuario limitado, sesiones efímeras, persistencia y restauración comprobadas, 219 controles de integración, 81 HTTPS, límite HTTP429 y pendientes Windows/Firefox/API/defensa. Quitadas afirmaciones antiguas de que no existía publicación ni pruebas de TLS. Portada y cabecera PDF toman VERSION; no se cambió lógica de aplicación ni esquema.

Ejecutado generador con Python del runtime y ReportLab. PDF output/pdf/EVENTIFY_Documento_Tecnico_Borrador.pdf: 15 páginas A4, sin contraseña, índice y secciones presentes, caracteres dentro del área de página. Poppler renderizó las 15 páginas; inspección visual de tres hojas de contacto confirmó tablas, diagramas y paginación sin recortes/solapamientos. Texto mantiene estado de borrador y los datos de estudiante/docente pendientes.

Empaquetador actualizado solo en instrucciones de pendientes; 13 comprobaciones con repositorios ficticios aprobadas. PDF separado del ZIP; código se exportará por commit, no desde la carpeta con secretos. Exportación e integridad dev.28 todavía pendientes en esta entrada. No repetir suite MySQL por cambios de documentos. Instalación limpia de este incremento y ejecución Windows siguen abiertas.


### Paquete dev.28 exportado y comprobado

Exportado b92ed44968ac9116b35bf003a41486930f7e0d71 por scripts/package-source.py --ref b92ed44 --output-dir output/entrega. Salida output/entrega/eventify-desarrollo-b92ed44968ac/eventify-source.zip: 185 archivos, versión dev.28, CRC aprobado, manifiesto/huellas SHA-256 coincidentes. SHA-256 ZIP: 798941953798e19e758804cae02289be11ec3508ecd2d6d3e9a640f6a13330f5. Comparación de contenido del ZIP contra secretos privados conocidos sin coincidencias. No contiene Git, .env reales, cuentas de evaluación, volúmenes o datos MySQL. PDF dev.28 se entrega separado. No se ha arrancado este ZIP ni ejecutado en Windows; preservado el paquete anterior. No confundir integridad con instalación aprobada.


## B07/B08: ensayo limpio preparado y pronóstico público confirmado

Docker directo devuelve permiso denegado; sudo -n devuelve restricción no new privileges del entorno de verificación. No cambiar socket, grupos ni protecciones. Puerto8083 ocupado por instancia anterior;8084 sin conexión activa. Preparado wrapper privado storage/verificacion-zip-dev28/verificar.sh: etiqueta dev.28 fija, comprueba huella del ZIP entregado, usa puerto8084/proyecto y volúmenes propios; ejecuta arranque del ZIP, sintaxis PHP, 39 checks clima/44 presupuesto, demostración explícita y recreación/persistencia. Conserva destinos. Bash-n y siete comprobaciones de extracción aprobadas; ejecución Docker solicitada en terminal, todavía sin resultado. Comando: bash storage/verificacion-zip-dev28/verificar.sh v0.1.0-dev.28. No marcar instalación aprobada hasta leer status/log y verificar navegador.

En paralelo, GET HTTPS real a /api/eventos/taller-tecnologia-demo/clima devuelve HTTP200/status available. IAB muestra pronóstico diario para12oct:17,3–24,1°C y probabilidad máxima de precipitación100%, consulta8oct18:22CST, atribución Open-Meteo/CC BY4.0 y aviso orientativo. Captura /tmp/eventify-pronostico-publico.png revisada visualmente. RF-15 cuenta ahora con respuesta útil real en la publicación, además de fallo429 previamente gestionado. Sin cambios de proveedor, código, cuotas, TLS o planes. No atribuir causa exacta a la recuperación ni prometer ausencia de futuros límites.

Este resultado posterior supera el pendiente de pronóstico de los registros anteriores. PDF/ZIPdev.28 conservan el corte previo que describe el429; actualización de evidencia final necesaria antes de entregar como definitivo. Windows, Firefox, imagen y ensayo nuevo siguen abiertos. Solo documentación de evidencia; sin VERSION nueva ni despliegue.


## B07/B09: copia limpia dev.28 ejecutada y revisada

Usuario confirmó ejecución. Registros storage/clean-20261009002340-886e6d4b: inicio2026-10-09T00:23:40Z (8oct18:23:40CST), exit_code0, proyecto eventify-clean-20261009002340-886e6d4b, puerto8084. reference.json coincide con b92ed44968ac9116b35bf003a41486930f7e0d71 y ZIP SHA798941953798e19e758804cae02289be11ec3508ecd2d6d3e9a640f6a13330f5,185archivos. Arranque desde fuente limpia en ruta con espacios, migraciones, sintaxis,39checks clima/44presupuesto y demostración explícita aprobados. Recreación inicial de web/db conserva tablas, archivos, marcador, credenciales e imágenes. Sin tocar destinos anteriores.

IAB http://localhost:8084: login usuario, historial inicial, evento Encuentro de tecnología40espacios, reservaAJAX de2entradas/precio0, navegación automática al ticket/QR, cancelaciónAJAX y registro CANCELADA en historial; detalle vuelve a40espacios. Ticket1fbd7f58b20c3bbc55257af9ac2911226b024635e9361a224c09757026065ed6, reserva a64611159370e489f1a78bf119e07867. Login administrador/dashboard y consulta reservas confirma nueva reserva8 cancelada; logout de ambos roles. Datos ficticios, sin pagos. Capturas temporales /tmp/eventify-dev28-ticket.png y /tmp/eventify-dev28-cancelacion.png, fuera de Git. QR revisado visualmente.

Alcance: instalación limpia y flujo central Linux dev.28 aprobados con estos casos. El clima está desactivado en este ensayo, su éxito público tiene evidencia separada. La recreación ocurrió antes de esta nueva reserva: no afirmar persistencia de la reserva8 después de otra recreación. Windows/Firefox, imagen y aceptación completa siguen pendientes. Copia y volúmenes conservados; sesión final cerrada. Solo evidencia/documentación, sin versión nueva ni suite extensa repetida.


## B07: persistencia posterior a reserva preparada

Preparado storage/verificacion-zip-dev28/persistencia.sh, limitado al proyecto existente eventify-clean-20261009002340-886e6d4b/puerto8084. Valida configuración, entorno local, base eventify_qa y propiedad de ambos volúmenes antes de detener web. Toma snapshot consistente, recrea web/db sin borrar volúmenes, detiene web para otro snapshot, compara exactamente, reinicia web y comprueba tres rutas HTTP. Trap intenta restaurar web si el ensayo falla. No importación, migración ni siembra; no toca destino restaurado ni instancias anteriores. Bash-n aprobado; ejecución solicitada al usuario por restricción Docker/sudo, todavía sin resultado. No repetir el ensayo limpio completo ni crear otro ZIP para este control.

SEGURIDAD y VERIFICACION-WINDOWS aclaran resultados vigentes: cookies HTTPS/TLS externos ya comprobados, sesiones Render efímeras y PowerShell real no equivalente a instalación Windows. Sin cambios de código ni versión. Pendientes concretos: snapshot posterior de reserva8, Firefox, Windows compatible, escaneo de imágenes y sincronización final PDF/paquete/evidencia humana.


## B07: persistencia dev.28 posterior a reserva aprobada

RNF-01/02, RF-12/14 y U-03: registro privado storage/existing-wq5XDtXK, inicio 2026-10-09T00:28:53Z (8 de octubre, 18:28:53 CST), exit_code=0. El ensayo actuó únicamente sobre eventify-clean-20261009002340-886e6d4b, puerto 8084. Los snapshots antes/después de recrear web y MySQL son idénticos: diez tablas, ocho reservas, ocho tickets y archivos duraderos. Sin nueva siembra, importación ni eliminación de volúmenes. Tres rutas HTTP respondieron correctamente; destinos anteriores conservados.

Después de la recreación, IAB confirmó login del usuario ficticio, historial con la reserva de dos entradas CANCELADA y acceso a su mismo ticket. QR completo visible y aviso de ticket sin vigencia; captura revisada /tmp/eventify-dev28-persistencia.png, fuera de Git. Logout comprobado al finalizar. Esto completa el ensayo de persistencia posterior al flujo central de la copia limpia Linux dev.28, pendiente en la entrada anterior. No certifica Windows, Firefox ni todos los escenarios de aceptación.

Solo documentación de evidencia; VERSION permanece dev.28 y no se cambia la aplicación pública. Restan matriz de navegador, ejecución Windows compatible, escaneo de imágenes y sincronización final de PDF/paquete, además de preparación y defensa humanas.


## B07: imágenes base analizadas; avisos pendientes de revisión

SEG-09/RNF-03: Trivy 0.75.0 oficial con checksum comprobado. Dos análisis remotos linux/amd64 terminaron exit0; base de avisos del 8 de octubre. PHP/Apache: 2015 filas/1227 identificadores, dos críticos y145altos; MySQL y auxiliares:69filas/69identificadores, un crítico y29altos. No interpretar exit0 como aprobación ni conteos como ataques demostrados. Detalle, fuentes primarias y límites en SEGURIDAD.md. Digests de etiquetas oficiales actuales coinciden con los fijados; no se cambió stack ni despliegue.

Revisión inicial distingue libxml2, kernel/cabeceras y Go de gosu; no se descartan sin comprobar uso. Imagen final de EVENTIFY aún sin analizar. Preparado wrapper privado storage/image-audit/exportar-imagen.sh: valida el proyecto de la copia limpia8084 y exporta únicamente su imagen por ID, sin volúmenes ni configuración del contenedor, sin detener servicios ni sobrescribir exportación existente. Bash-n aprobado. Se necesita terminal del usuario solo por acceso Docker/sudo restringido; después se puede escanear el tar.

Sin cambios de aplicación ni VERSION nueva; evidencia registrada, seguridad/B07 siguen abiertos.


## B07/SEG-09: imagen dev.28 escaneada y dev.29 preparado

Usuario exportó la imagen real de la copia limpia8084, sin datos/volúmenes. Cadena de digests OCI confirmada con el config auditado. `storage/image-audit/tool/trivy image --disable-telemetry --skip-db-update --scanners vuln --cache-dir storage/image-audit/cache --timeout 5m --format json --output storage/image-audit/eventify-dev28.json --input storage/image-audit/eventify-dev28.tar` terminó exit0. Mismos2015avisos Debian de base; Composer vendor detectado sin avisos. Detalle de digests y límites en SEGURIDAD.md.

Ambos paquetes xz/liblzma siguen en versión anterior: dev.29 fija corrección Debian5.8.1-1+deb13u2 en Dockerfile local/público. Imagen PHP/MySQL base y datos intactos; Render conserva dev.26 y copia limpia conserva dev.28. Nuevo scripts/test-image-linux.sh construye imágenes aisladas, verifica paquetes, sintaxis/unidades y exporta para escaneo posterior. Bash-n y diff correctos. Construcción/pruebas/escaneo de dev.29 pendientes del comando Docker en terminal del usuario; no marcar estable ni publicar automáticamente.

Próxima acción: bash scripts/test-image-linux.sh. Leer storage/image-check-ID/status.txt/test.log y escanear ambos tar antes de dar el parche por comprobado. Sin reinstalar o detener el stack existente.


## B07: corrección del nombre de imagen, dev.30

Ensayo image-check-sFMEIgyi: exit_code=125. Docker rechazó eventify-image-check-sFMEIgyi-local por mayúsculas en el repositorio antes de construir; no llegó a pruebas, exportación ni actualización de servicios. Advertencia legacy builder separada, no causa del rechazo.

Script normaliza el identificador de imagen con expansión Bash a minúsculas y conserva el directorio/log original. Bash-n y tres casos de formato, incluido sFMEIgyi, correctos. No equivalen a construir ni ejecutar PHP. VERSION dev.30 conserva el parche xz anterior; repetir scripts/test-image-linux.sh en terminal del usuario por restricción del servicio Docker. Registros fallidos preservados.


## B07: construcción y escaneo dev.30 aprobados para el parche xz

Usuario ejecutó scripts/test-image-linux.sh. Registro storage/image-check-APnvo9xh:inicio2026-10-09T00:58:15Z/exit0, versióndev.30, commit390b1ef6dcbb9a9d76eaad8a910a80da364bb637 y árbol limpio. Local/pública construidas con paquetes corregidos comprobados por dpkg; cada una pasa sintaxis,266unidades y QR. Pruebas sin red/puertos/montajes/MySQL; no repetir ni sumar con integración219.

Se ejecutó el mismo comando Trivy --input utilizado para dev.28, con --disable-telemetry --skip-db-update --scanners vuln, cambiando entrada a cada tar del registro y salida a local-scan.json/public-scan.json. Ambos procesos exit0; cadena OCI de exportación/config verificada. Comparación:2015→2011avisos por imagen, cuatro filas xz/liblzma desaparecidas y cero añadidas; bibliotecas Composer sin avisos. Dos críticos y145altos de la base permanecen, no marcar seguridad completa. Detalles en SEGURIDAD.md.

Parche concreto de runtime comprobado; VERSION/etiqueta dev.30 conservados. Render sigue dev.26, copia limpia8084 sigue dev.28. No publicar/recrear destinos por inferencia. Próximos pendientes: revisión de alcanzabilidad/correcciones restantes, Firefox/Windows y casos finales de aceptación, sincronización PDF/paquete y evidencia humana de defensa.


## B07: alcance de avisos y teclado del registro

Dev.30: inventario de módulos en las dos imágenes exportadas, aplicando whiteouts de capas OCI, guardado privadamente en modules-review.json. Los seis avisos altos Apache requieren módulos no habilitados. Contraste oficial: corrección upstream2.4.69, Debian Trixie aún afectado en los avisos consultados. No eliminar avisos ni mezclar paquetes sid. TicketQr construye SVG directamente y no usa el SvgImageBackEnd/XMLWriter encontrado en vendor. Revisión estática de entradas XML, streams de clima y ejecución de comandos; alcance y pendientes en AVISOS-IMAGENES.md. No equivale a comprobar runtime ni resolver todos los avisos.

IAB/copia limpia8084 dev.28,390×844: navegación Tab desde marca/Inicio a nombre, correo, contraseña, confirmación y botón; foco visible3px y sin desbordamiento horizontal. Enter con formulario vacío muestra Completa este campo y devuelve foco a full_name; no datos ingresados ni cuenta creada. Captura /tmp/eventify-registro-teclado.png revisada; viewport restaurado y pestaña final inicio sin sesión. Verifica teclado/validación nativa de este formulario, no registro válido ni accesibilidad completa. CUA ofrece Chrome/IAB, sin Firefox en inventario; Firefox real permanece pendiente.

Solo documentación/evidencia; VERSION dev.30 conservada. Pendientes: seguridad restante, casos de UI/zoom, Firefox/Windows, sincronización de entregables y defensa humana.


## B09: documento actualizado y casos de cierre

README, PLAN, MATRIZ y TECNICO actualizados para distinguir código dev.30, publicación dev.26 y ZIP dev.28 probado. Quitadas referencias operativas antiguas de pronóstico público pendiente, recreación Linux pendiente y herramientas de pruebas no elegidas. Se conserva el historial de etapas anteriores. CIERRE.md organiza doce casos restantes con criterio/evidencia: interfaz/errores, teclado/zoom, Firefox, Windows, avisos, publicación de la referencia elegida, paquete definitivo y revisión/defensa humanas. No cuenta su preparación como ejecución.

Generador PDF ejecutado con Python/ReportLab del runtime. output/pdf/EVENTIFY_Documento_Tecnico_Borrador.pdf:15páginasA4, portada/cabecera dev.30, texto con RF-21/RNF-07 y evidencia actual. Poppler renderizó las15páginas; tres hojas de contacto revisadas visualmente, tablas/diagramas sin recortes ni solapamientos. Pdfplumber no encontró caracteres fuera de página. Borrador dev.28 anterior conservado fuera de Git. Datos de estudiante/docente y revisión humana pendientes.

Sin cambios de aplicación, versión nueva, despliegue ni ZIP nuevo; no repetir integración MySQL por documentos. PDF es borrador actualizado, no declaración de entrega final aprobada.


## B09: revisión editorial dev.31

Documentos y guías revisados con español natural, comandos desde la raíz del proyecto y explicaciones centradas en las reglas de EVENTIFY. Se conservan los identificadores de commits, huellas, resultados reales y pendientes de aceptación. No se atribuye al estudiante una revisión o defensa sin evidencia. La aplicación conserva el comportamiento de dev.30; este incremento modifica documentación y VERSION.

Verificación editorial: búsqueda en archivos versionados sin menciones a herramientas de redacción; identificadores históricos conservados y git diff --check correcto. PDF dev.31 regenerado:15 páginas A4, texto/requisitos y límites de página comprobados; todas las páginas renderizadas y revisadas visualmente. Borrador dev.30 conservado. No se repiten pruebas de aplicación porque no cambió su código.

Dev.31 publicada:4088cbe34aef3e9c7ec12ead72b9781fd61efac8; rama y etiqueta resuelta comprobadas en remoto. Paquete output/entrega/eventify-desarrollo-4088cbe34aef/eventify-source.zip:CRC correcto, VERSION dev.31 y documentación revisada sin menciones a herramientas de redacción. SHA-256:52a0028febe8263a52cf8be1238201a4effb38323bbbeec968fef6814383c36b. Guía adjunta actualizada para reflejar el pronóstico público comprobado. El ZIP incluye fuente y migraciones, sin base existente ni claves personales; no se ha instalado esta nueva referencia en Linux o Windows. PDF generado aparte y conservado como borrador.


## B07 / C-01: registro completo comprobado en navegador

RF-01/02/03 y RNF-03/04. IAB en la copia limpia Linux http://localhost:8084, dev.28: datos ficticios de nombre/correo example.invalid y contraseña aleatoria guardados únicamente en storage/registro-browser/cuenta.json, permisos600. Confirmación distinta devuelve «Las contraseñas no coinciden» y vacía los campos de contraseña; alta válida dirige a /ingresar?registro=ok con aviso de cuenta creada. Repetición del mismo correo devuelve «Ese correo ya está registrado». Login incorrecto muestra mensaje genérico; login correcto muestra identidad de la cuenta en /cuenta. Acceso directo a /admin rechazado por el servidor. Logout seguido de /cuenta exige nuevo ingreso. Cuenta de prueba conservada; no reservas nuevas, bases eliminadas ni cambios externos.

Revisados AuthController, Auth, RegistrationInput, Session, vistas y rutas; git diff entre b92ed44968ac9116b35bf003a41486930f7e0d71 y HEAD no muestra diferencias en estos archivos. Auth asigna USUARIO desde el servidor y utiliza consultas preparadas. Esta ejecución verifica el recorrido del navegador; hashes, renovación de sesión y peticiones manipuladas cuentan con pruebas de integración anteriores, no se presentan como nuevas consultas MySQL. Captura /tmp/eventify-registro-cuenta.png revisada visualmente, sin contraseñas. Sesión final cerrada.

C-01 de navegador completado con este alcance. No certifica Firefox, Windows ni la instalación del ZIP dev.31. Sin cambios funcionales ni VERSION nueva; no repetir suite extensa. Siguiente caso: C-02, filtros combinados y estados AJAX.


## B07 / C-02: catálogo y estados AJAX comprobados

RF-05/06, RT-02/03 y RNF-04. IAB, copia limpia Linux dev.28 en localhost:8084, sin sesión ni escritura de datos. Búsqueda SQL + Tecnología + fecha15oct2026 + Con espacios devuelve únicamente Taller de SQL,28espacios. Cambiar categoría a Cultura conserva los otros criterios y devuelve cero resultados con mensaje útil. Limpiar restablece campos/selectores y cinco eventos; Agotados devuelve únicamente Cocina. Fecha inicial18oct con final15oct rechazada con mensaje específico, sin sustituir resultados válidos.

Prueba de red controlada únicamente en esta pestaña: Network.emulateNetworkConditions con latencia1800ms. Durante la solicitud, aria-busy=true y «Buscando eventos…». En una misma secuencia se enviaron SQL y Encuentro: eventos CDP registran solicitud SQL663263.282, loadingFailed/canceled=true/ERR_ABORTED, y solicitud Encuentro663263.283 con loadingFinished. Resultado final y URL corresponden a Encuentro de tecnología; la consulta anterior no reaparece. Esto verifica cancelación de solicitud pendiente, no la entrega deliberada de un callback antiguo pese al aborto.

Modo offline temporal en la pestaña: una nueva búsqueda SQL falla y conserva Encuentro; aviso «No pudimos actualizar los eventos. Se conservan los resultados anteriores; vuelva a intentar». aria-busy vuelve a null. Restaurada la red sin latencia, reintento devuelve Taller de SQL. Captura /tmp/eventify-catalogo-error.png revisada visualmente. Al terminar se limpiaron filtros y desactivó Network; no queda emulación de red. No se desconectó el host ni se probó el fallo en Render.

Revisados catálogo JS, controlador, filtro y repositorio PDO: validación de parámetros/rango en servidor y consultas preparadas. git diff de estos archivos y catalog-results entre dev.28 y HEAD sin diferencias. C-02 comprobado con este alcance; no certifica Firefox, Windows ni otra instalación del ZIP. Sin cambio funcional/VERSION ni repetición de integración MySQL. Siguiente caso C-03: resultado desconocido al confirmar una reserva, en evaluación aislada.


## B07 / C-03: respuesta perdida después de confirmar una reserva

RF-08/09/11/12/14 y RNF-04. IAB, copia limpia Linux dev.28 en localhost:8084, cuenta ficticia creada en C-01. Historial inicialmente vacío y Encuentro de tecnología con40espacios. Una única confirmación de1entrada/precio0. Interceptada solo la respuesta Fetch de /api/reservas: HTTP201 observado antes de fallar su entrega con ConnectionClosed. No se cortó la petición antes de llegar al servidor ni se provocó el fallo en Render.

Formulario muestra «No pudimos confirmar el resultado. Consulte sus experiencias antes de intentarlo de nuevo» y Confirmar reserva permanece disabled; sin navegación automática ni reenvío. En Mis experiencias aparece una sola reserva CONFIRMADA de1entrada/total0. Ticket y QR visibles, código de reserva886d3be053575e3785110ca1bcba376c. Detalle actualizado muestra39espacios y una segunda consulta de historial conserva un único enlace al ticket. Código de ticketc8777d4cd7e9c32811beea2fa143178b6c5a6cb49100c6fbebb901e8c4a3f8e9. Cuenta/reserva/ticket conservados; consume un cupo ficticio en este destino. Logout al finalizar.

El control de navegador rechazó inicialmente el patrón sin resourceType y posteriormente Fetch.disable. Se ajustó a resourceType Fetch y se retiró la interceptación mediante Fetch.enable con patterns vacío. Una interrupción del guion entre enviar e inspeccionar se resolvió leyendo la respuesta retenida, sin volver a pulsar confirmar. Al terminar no quedan respuestas retenidas ni patrones activos. Capturas /tmp/eventify-reserva-incierta.png y /tmp/eventify-reserva-recuperada.png revisadas visualmente, sin contraseñas.

Revisados reservations.js, experiences.js, ReservationController y ReservationService: commit transaccional precede a la respuesta201; abortar la espera no revierte MySQL. git diff de estos archivos entre dev.28 y HEAD sin cambios. Esta prueba verifica resultado desconocido y consulta posterior mediante PHP/MySQL; no repite auditoría SQL, concurrencia ni reinicio de contenedores. C-03 cerrado con este alcance. Sin cambios funcionales/VERSION ni suite extensa. Próxima acción C-04: estados de la API en la interfaz, sin agotar la cuota real.


## B07 / C-04: estados del pronóstico en la interfaz

RF-15/RNF-04. IAB, copia limpia Linux dev.28, Taller de SQL/localhost:8084, cuenta ficticia autenticada. Endpoint real del entorno devuelve disabled. Para verificar otros estados sin llamar al proveedor, se interceptó únicamente la petición XHR /api/eventos/taller-sql/clima en etapa Request. Respuestas de prueba con JSON200: unavailable, busy, out_of_range, stale y available; HTTP429 artificial separado. Valores20–26°C/40% usados en stale/available son ficticios, no pronóstico real ni nueva evidencia pública.

Unavailable/busy muestran mensaje y botón de reintento. Out_of_range comunica horizonte15días y oculta reintento. Stale muestra valores anteriores con aviso de actualización fallida; available muestra valores/fecha/advertencia orientativa. HTTP429 artificial pasa al error genérico, sin valores y con reintento habilitado. En todos los casos aria-busy se retira y Confirmar reserva sigue habilitado. No se enviaron reservas ni se consumió cuota externa.

Timeout: petición XHR retenida sin respuesta; durante carga aria-busy=true, reintento deshabilitado y reserva habilitada. El límite propio8000ms termina en mensaje seguro, sin valores, aria-busy ausente y reintento habilitado. La primera espera del verificador venció antes del timeout de la aplicación; observación posterior confirmó el resultado, sin reenviar petición. Interceptación retirada con Fetch.enable/patterns vacío. «Consultar de nuevo» obtiene disabled real del entorno; sesión cerrada. Captura /tmp/eventify-clima-timeout-prueba.png revisada visualmente. No quedan datos ficticios mostrados ni interceptaciones activas.

WeatherController, EventWeather y weather.js revisados y sin diferencias entre dev.28 y HEAD. El servidor convierte fallo/cuota del proveedor en unavailable o stale, no expone su respuesta429 cruda; la prueba429 anterior verifica además error HTTP del endpoint. Reglas PHP y presupuesto cuentan con evidencia previa; esta ejecución es de interfaz, no una nueva caída real del proveedor ni una prueba de su timeout3s. Éxito y429 reales públicos conservan su evidencia anterior. C-04 de interfaz comprobado con este alcance; Windows/Firefox y aceptación final siguen pendientes. Sin cambio funcional/VERSION ni suite extensa. Siguiente C-05: casos administrativos adversos en navegador.


## B07 / C-05: validaciones administrativas y conservación

RF-17/18/19/20 y RNF-03/04. IAB/dev.28, copia limpia8084, administrador ficticio. Taller de SQL: intento de capacidad1 rechazado por ser menor que entradas confirmadas; precio-1 rechazado; fecha15→16oct rechazada por historial; PUBLICADO→BORRADOR rechazado por historial. Finalizar evento futuro rechazado. Mensajes específicos visibles y controles de servidor revisados en AdminController/AdminService/AdminInput.

Después de los cuatro primeros rechazos, GET nuevo del formulario compara todos sus campos originales y devuelve igualdad exacta: capacidad30, precio15000.00, fecha15oct18:24:14 y PUBLICADO. La primera comprobación con reload repetía el POST inválido y conservaba valores del intento; se corrigió navegando al listado y abriendo un GET nuevo. No confundir datos reenviados con valores guardados. El rechazo de Finalizar reconsulta el registro y conserva PUBLICADO.

Categorías: creación con nombre/identificador Tecnología/tecnologia existentes rechazada, listado conserva cuatro categorías. Identificador «NO valido» bloqueado por patrón HTML con mensaje nativo; no enviado al servidor en este recorrido. Validación de slug en PHP cuenta con pruebas previas, no se presenta como nueva petición manipulada. Consulta administrativa filtrada por Taller de SQL mantiene dos reservas: confirmada2entradas/total30000 y cancelada1entrada/15000. Detalle de reserva1 conserva precio histórico15000, total30000, fecha y ticket.

Usuario ficticio sin privilegios: accesos directos a /admin/eventos/1/editar, /admin/categorias/1/editar y /admin/reservas/1 rechazados con mensaje de permiso. Logout de ambos roles. No actualizaciones válidas, categorías nuevas, cancelaciones ni borrado de datos. Archivos relacionados sin diferencias entre dev.28 y HEAD. Capturas /tmp/eventify-admin-historial.png y /tmp/eventify-admin-permiso-denegado.png; primera revisada visualmente. Sin cambio funcional/VERSION ni repetición de carreras MySQL. C-05 comprobado para estos casos; no equivale a toda combinación adversa o Firefox/Windows. Próximo C-06: teclado y zoom.

Incidencia de inspección: un comando imprimió por error credenciales ficticias del archivo local de demostración. No se copiaron valores a documentos ni Git; archivo original permanece privado e ignorado. La lectura posterior utilizó las credenciales sin mostrarlas. No confundirlas con las cuentas externas de evaluación.


## B07 / C-06: teclado y redistribución, resultado parcial

IAB/Linux, copia limpia dev.28 en localhost:8084. Catálogo, detalle/reserva, historial y ticket revisados a390,768,1280 y640px; medidas DOM no muestran desbordamiento horizontal. La captura compuesta del historial sugirió una columna comprimida; la observación directa a390px mostró tarjeta327px sobre contenido327px y lectura normal. A640px main625px/tarjeta577px. Algunas capturas tomadas inmediatamente tras cambiar viewport quedaron deformadas; no se usan como evidencia de un defecto de CSS y no se modificó la aplicación.

Tab con locators en detalle: Eventos, Mis experiencias, Mi cuenta, Open-Meteo, CC BY4.0, cantidad y Confirmar reserva; contorno3px visible. Historial: navegación, Actualizar, Consultar ticket y Cancelar reserva, mismo foco. Ticket: navegación y Volver. Se alcanza BODY al salir del último control; no equivale a un control sin foco. Una primera secuencia de teclas globales del detalle no avanzó; se sustituyó por locators y se confirmó el recorrido, sin enviar formularios. Captura /tmp/eventify-reserva-foco.png revisada visualmente. No reservas ni cancelaciones nuevas.

El atajo de zoom del navegador integrado no cambió innerWidth/devicePixelRatio/visualViewport.scale; no se presenta el ensayo640px como zoom real200%. C-06 permanece parcial: faltan zoom real, calendario nativo y panel completo. Firefox y Windows pendientes.

## B09: datos confirmados de portada

Usuario confirmó Estudiante Kendall Gamboa Arce y docente Rita Robles Loaiza. Nombres incorporados a fuente y generador PDF dev.32. Fecha de entrega no proporcionada; no inferirla de la fecha de corte. Sin nueva evidencia Windows ni de defensa. Paquete actualizado preparado desde una referencia Git explícita, sin bases ni credenciales; el cambio es documental y la aplicación sigue siendo dev.30.


## Ensayo final preparado sin sustituir instalaciones anteriores

Dev.32 permite puerto explícito en test-clean-linux.sh; 8085 elegido para no ocupar8083/8084. Extracción real del ZIP ficticio bajo umask077: siete controles correctos con puerto8085; argumentos08085/65536 rechazados antes de Docker; Bash-n y diff correctos. No certifica un nuevo stack ni Windows. test-image-linux.sh agrega apache2ctl -M sin red/montajes/puertos sobre ambas imágenes: guarda inventario y rechaza los seis módulos con avisos revisados si estuvieran activos. Sintaxis comprobada; ejecución real nueva pendiente por acceso denegado al socket Docker.

Intervención necesaria: desde la carpeta del proyecto, bash scripts/test-image-linux.sh y bash scripts/test-clean-linux.sh v0.1.0-dev.32 8085. Los guiones conservan logs privados, imágenes y destino; no borran bases ni volúmenes existentes. Revisión de los demás avisos sigue abierta.


## B08 / C-10: parche dev.30 publicado y persistencia comprobada

Render Free, servicio srv-db3vutm7bikc73ajj6og: despliegue manual del commit390b1ef6dcbb9a9d76eaad8a910a80da364bb637 (v0.1.0-dev.30), no latest ni cambio de rama/plan/permisos. Deploy dep-db44q9cs728c739ikr20, inicio8oct2026 20:10:45CST, estado Deploy succeeded/Live y duración35s. Build instala liblzma5/xz-utils5.8.1-1+deb13u2, sintaxis correcta y unidades de clima39/presupuesto44 aprobadas. Sin nueva migración, siembra ni escritura de negocio.

Snapshots TLS privados antes y después: mismas filas y huellas en diez tablas; archivos snapshot-before-dev30.json y snapshot-after-dev30.json en storage/aiven-evaluacion. Comparación hecha antes de los logins de verificación para no confundir contadores de autenticación con datos perdidos. tests/public_security.py contra el origen autorizado:81 comprobaciones correctas, sin escrituras de negocio. Incluye dos usuarios, sesiones, permisos/tickets ajenos, CSRF y HTTPS; no incluye login administrador ni una reserva nueva en esta ejecución. Captura /tmp/eventify-render-dev30.png revisada visualmente, estado Live y SHA visibles. La referencia anterior cabd6f3 permanece disponible; Git no restaura MySQL.

C-10 avanza para esta referencia probada; no certifica aplicación del paquete documental dev.32 ni cierre de seguridad, Windows/Firefox o aceptación completa. Render conserva plan Free y AutoDeployOff; no asociar sus cuentas al receptor para instalar el ZIP local.

Pronóstico posterior al despliegue observado en IAB: Taller de tecnología de evaluación, fecha12oct; temperatura17,3–24,1°C/precipitación100%, consultado8oct20:13CST. Respuesta útil real, no fixture; reserva disponible. Captura /tmp/eventify-publico-dev30.png revisada visualmente.


---

## Archivo íntegro del estado anterior: corte dev.37, 9 de octubre de 2026

# EVENTIFY: estado actual y continuidad

Corte: 9 de octubre de 2026, America/Costa_Rica. Resumen operativo; [HISTORIAL.md](HISTORIAL.md) conserva los registros anteriores íntegros. Leer este archivo, el bloque activo de PLAN y los requisitos afectados al retomar. Los resultados siguientes tienen versiones y alcances distintos; no sumarlos como una sola suite.

## Alcance y autorizaciones

- Aplicación académica PHP/MySQL, HTML/CSS/JS/jQuery/AJAX y sesiones. Desarrollo Linux; entrega local Docker Compose Linux/Windows. No modificar Código Tico.
- Commits y push progresivos autorizados el 2026-10-08; repositorio privado [USUARIO_DESTINO/eventify](https://github.com/USUARIO_DESTINO/eventify), remoto SSH. Sin push forzado ni reescritura.
- Publicación Render Free/Aiven MySQL Free autorizada expresamente: integración GitHub solo EVENTIFY, presupuesto ₡0, sin tarjeta ni planes pagados. No necesita nuevas cuentas de proveedores el receptor del paquete local.
- Estudiante confirmado: Kendall Gamboa Arce. Docente confirmada: Rita Robles Loaiza. La fecha de entrega se omite por indicación del usuario. Defensa y comprensión humanas pendientes.

## Versiones y destinos

- Código/documentación actual: **0.1.0-dev.37**, rama `codex/b07-experiencia-final`; experiencia/perfil comprobados:225 controles MySQL/HTTP,30registro/errores yrecorrido IAB real aprobados sobre ab710a6. Dev.36, rama `codex/b07-acceso-denegado`, commit funcional `6c47cb0`, conserva la evidencia previa. Dev.35/36 mejoran páginas de rechazo y error; actualización de web8086 y22 controles HTTP reales comprobados. Dev.34, rama `codex/b07-runtime-aislado`, desde `58e3b66`, conserva su evidencia de runtime. Dev.34 incorpora limpieza de herramientas ensayada en imágenes derivadas; construcción final y TLS aprobados sobre 6d41df5. Aplicación PHP igual a dev.30; dev.33 tiene parche gawk de MySQL construido, integración/TLS y escaneo comprobados; las instalaciones existentes conservan su versión anterior. Ninguna versión estable.
- `main` conservada: `3da680b74b41fa3da4069879edc1f649de9b97f2`. VERSIONADO contiene la política y referencias anteriores. Git recupera código; no restaura MySQL.
- Web pública: https://eventify-academico.onrender.com, **dev.37**, commit `7c3a3e28435566349efcd4b2a66eace89de978d2`. Render `srv-db3vutm7bikc73ajj6og`, Free/Oregon/AutoDeployOff; misma rama de despliegue existente.
- Último deploy: `dep-db4n8cbncjis73c7m4t0`, 9 de octubre, 17:09:37 CST, **Deploy succeeded/Live**, 34,8 s. Referencia anterior `390b1ef` conservada para rollback.
- Copia limpia anterior: `localhost:8085`, dev.32 `1dd0ed8dec5a15db82c9543a4ce803b492c52915`, proyecto `eventify-clean-20261009022129-10f84d13`. Dev.28/8084 conservada.
- Instalación `localhost:8086`: web actualizada a dev.37 sobre `ab710a6`; base y volúmenes conservados. La instalación limpia y persistencia anteriores se comprobaron con dev.33 `f4d6c5a05011c57e47295b06cedf11129a052681`, proyecto `eventify-clean-20261009035638-598f411d`. Detalle del recorrido al final.
- Otros destinos conservados según última verificación anterior: normal8080, QA8081, restauración8082, copia limpia8083. No afirmar disponibilidad actual sin consultar. No detenerlos ni borrar volúmenes para reutilizar puertos.
- Docker29.1.3/Compose2.40.3. Esta sesión no tiene acceso al socket; sudo no interactivo tampoco lo resuelve. No cambiar grupos/socket/sudoers. Operaciones Docker necesarias se ejecutan en terminal del usuario.
- Configuración, cuentas, CA, registros y snapshots privados en storage/backups, ignorados por Git. Nunca imprimir credenciales ni incluirlos en el ZIP.

## Evidencia vigente

| Área | Resultado comprobado | Límite |
| --- | --- | --- |
| Imágenes dev.32 | Ambas construidas, sintaxis PHP8.4, 266 comprobaciones unitarias y QR; xz/liblzma corregido y módulos Apache revisados ausentes en ejecución. image-check-ERw4lEwZ, exit0. | Escaneo conserva145HIGH/2CRITICAL por imagen; revisión no cerrada. |
| MySQL/HTTP dev.33 | 219 comprobaciones sobre imagen MySQL corregida, tres migraciones; concurrencia de reserva, administración y presupuesto meteorológico. | No certifica Windows ni toda la publicación. |
| Copia limpia Linux dev.32 | ZIP exacto, ruta con espacios, migraciones y persistencia inicial; ambos roles, reserva/ticket/QR, cancelación e historial comprobados en8085. clean-20261009022129-10f84d13, exit0. | Recreación posterior al flujo y consulta de historial/ticket aprobadas; Windows pendiente. |
| Copia limpia Linux dev.33 | Fuente f4d6c5a exacta, recorrido8086 y recreación posterior; diez tablas/cinco archivos idénticos, historial y QR conservados. dev33-persistence-lBxRJbW3,exit0. | Windows/Firefox/zoom y seguridad siguen pendientes; no versión estable. |
| Restauración | Diez tablas y archivos comparados en destino aislado; registros privados conservados. | No prueba toda transferencia a Windows. |
| Publicación dev.30 | Parche construido en Render; sintaxis, 39 pruebas de clima y44 de presupuesto en build. Snapshots antes/después idénticos en diez tablas. | No incluye nueva reserva/admin en esta ejecución. |
| HTTPS dev.30 | 81 controles posteriores correctos: dos usuarios, sesiones, CSRF, rutas privadas y tickets ajenos; sin escrituras de negocio. | No es auditoría universal ni revisión completa de imágenes. |
| API pública dev.30 | Pronóstico útil real después del deploy: 17,3–24,1°C/100% para evento ficticio del12oct; consultado8oct20:13CST. | Puede variar o fallar; no garantiza disponibilidad permanente. |
| Dependencias/secretos | Composer sin avisos, jQuery verificado y505 blobs sin secretos conocidos en ejecución anterior. | No prueba ausencia de avisos futuros o secretos desconocidos. |
| Windows | PowerShell5.1 real: seis scripts sin errores de parser. VM Win11 LTSC Evaluation identificada,4GiB. | Sin Docker/WSL ni ejecución completa; hace falta host compatible. |
| Documentación | PDF dev.37 de 16 páginas, nombres confirmados, experiencia y pruebas actualizadas; páginas renderizadas y texto dentro de página. | Borrador: revisión final, Windows y defensa humanas pendientes. |

Snapshots de publicación: `storage/aiven-evaluacion/snapshot-before-dev30.json` y `snapshot-after-dev30.json`, comparados antes de los logins de prueba. Capturas revisadas `/tmp/eventify-render-dev30.png` y `/tmp/eventify-publico-dev30.png`. No se cambiaron permisos, plan, base ni cuentas externas durante el deploy. Detalles en evidencias/B08.

## Aceptación de interfaz

C-01 a C-05 comprobados en IAB sobre dev.28; archivos de aplicación afectados sin diferencias respecto a la versión posterior. Detalles y límites en [evidencias/B07.md](evidencias/B07.md):

- Registro: confirmación distinta, alta válida ficticia, duplicado, login incorrecto/correcto, permisos por URL y logout.
- Catálogo: filtros combinados, vacío, fechas invertidas, carga, cancelación de consulta pendiente, desconexión de pestaña y recuperación.
- Reserva: una sola respuesta201 perdida después del commit; confirmar deshabilitado, consulta de historial/ticket y cupos40→39. Reserva ficticia conservada; no repetirla.
- Clima: fixtures locales de error/busy/horizonte/caché/éxito/HTTP429/timeout8s; sin llamadas al proveedor. Patrones retirados. Evidencia real pública separada.
- Administración: aforo bajo reservas, precio negativo, cambio de fecha/estado con historial, finalización futura, categoría duplicada y permisos ajenos; valores conservados.
- C-06 parcial: redistribución a390/768/1280/640px; foco visible y recorrido de detalle/reserva, historial y ticket. Una captura deformada del historial se descartó mediante observación directa; no se cambió CSS. Atajo de zoom IAB no modificó escala: **no certifica zoom200%**. Panel administrativo también recorrido con foco visible; faltan calendario nativo y zoom real.

Incidencia anterior de inspección: se imprimieron por error credenciales ficticias de la copia local; no se copiaron a Git/documentación. Detalle conservado en B07/HISTORIAL. No confundirlas con cuentas externas.

## Bloques y pendientes reales

- B01/B02 comprobados; B03–B06 implementados con integración y aceptación parcial de navegador.
- B07 abierto: completar compatibilidad Firefox en el alcance pendiente, Windows y revisión de avisos de imágenes. Zoom200% deja de bloquear por indicación del usuario; adaptación y recorrido IAB dev.36 registrados al final. [AVISOS-IMAGENES.md](AVISOS-IMAGENES.md) conserva condiciones y dudas: Apache/XML/kernel/curl/gosu/Python; no ocultar avisos. No repetir suites extensas sin cambio o duda concreta.
- B08: servicio real actualizado a dev.37; TLS, 112 controles HTTPS y datos conservados. La última respuesta útil del clima corresponde a dev.30; dev.37 mostró indisponibilidad y conservó la reserva. Detalle al final.
- B09: portada/manual/PDF y exportación de desarrollo dev.37 preparados; instalación final, receptor y ensayo humano pendientes. [CIERRE.md](CIERRE.md) organiza los casos; no marcar estable por tener un ZIP.

## Próxima acción necesaria

Dev.32 aprobó construcción de imágenes, instalación limpia Linux y persistencia posterior al recorrido del navegador. Ya no hace falta repetir los dos ensayos ni el helper para esta referencia. Resultado `storage/dev32-persistence-UMW4MUkC`, inicio20:28:23CST/exit0: snapshots idénticos de diez tablas y cinco archivos, ocho reservas y ocho tickets. Login posterior confirma historialCANCELADA, ticket/QR y datos conservados. Captura /tmp/eventify-dev32-persistencia.png revisada.

Continuar revisión de seguridad y aceptación de la versión final, compatibilidad de navegador y Windows en equipo compatible; zoom200% no requerido por el usuario. Disponibilidad de Windows/Docker Desktop pendiente de confirmar. La fecha de entrega no es necesaria y deja de ser un pendiente. Defensa/participación humanas abiertas. No certificar entrega completa ni estabilidad por haber aprobado Linux.

## Exportación dev.32 confirmada

Etiqueta v0.1.0-dev.32 y rama publicadas; commit1dd0ed8dec5a15db82c9543a4ce803b492c52915, etiqueta anotada0c0c1b1ff0a8f3359a137a91083b1f6f19482734; refs remotas verificadas. Fuente en output/entrega/eventify-desarrollo-1dd0ed8dec5a/eventify-source.zip,188 archivos, SHA25677157fa2ef2e419fa8a234daeef2e196514eab1f5ddac3248d7ee20af436d8e9. Paquete de revisión con ese ZIP, PDF, guía, manifiesto y huellas: output/entrega/eventify-dev32-revision.zip, SHA25675a91451ca8e3f92272e5666b355bfe6d0ba83b8bee971fca51b8982efeaee18. Integridad/CRC comprobados; no instalación nueva ni entrega final certificada. PDF15 páginas con nombres/versión correctos, portada/evidencia revisadas y texto dentro de página. Registro posterior a la etiqueta solo documenta resultados, no cambia el paquete.

Ampliación C-06: panel administrativo dev.28 a390px, ocho enlaces recorridos mediante Tab y contorno3px visible; seis tarjetas327px y scrollWidth375 sobre viewport390. Captura /tmp/eventify-admin-teclado-dev28.png revisada. Sin formularios enviados ni cambios de negocio; sesión cerrada al finalizar.

Nuevo recorrido dev.32: reserva ficticia f5e075eec15b3bd971c1d487078aae79 cancelada y conservada; cupos40→38→40, ticket/QR sin vigencia. Ambos roles y logout correctos. Evidencia y límites en B07.

Intento de zoom en Chrome real (conector disponible): Ctrl+plus no cambió innerWidth1920/devicePixelRatio1/visualViewport.scale1. No se certifica200%. Inventario actual expone Chrome e IAB, no Firefox. Ensayo Firefox/zoom requiere interacción directa del usuario o un acceso compatible; no sustituirlo por otra ejecución IAB.

Paquete con fuente idéntica a la probada en Linux, PDF actualizado y resultados posteriores: output/entrega/eventify-dev32-linux-comprobado.zip, SHA256 83441a710cb0fdfd36c70a8b46b3681036cb9c79e23de41e72559e04d7dac18b. Se conservó el ZIP fuente188 archivos y su manifiesto originales; los documentos adjuntos explican que el estado interno de ese ZIP es del corte anterior. Integridad/CRC correctos. No es certificación Windows ni versión estable.

## Revisión de seguridad dev.33

Fecha de entrega omitida por indicación del usuario. Análisis exacto gosu amd64 del digest MySQL: capas y huella verificadas, binario oficial1.19/Go1.24.6; govulncheck1.8.0 sin exclusiones, ninguna función vulnerable detectada. Conserva57 coincidencias de módulo/paquete y47 filas Trivy; no auditoría de MySQL completo. ACL/ncurses/montajes/XML/Python contrastados; inventario completo por paquete en INVENTARIO-IMAGENES. Detalles, fuentes y evidencia privada en AVISOS-IMAGENES.

Oracle publica parche compatible para los tres avisos gawk. Dockerfile.mysql y cuatro Compose preparados, servidor/volúmenes sin cambiar. Ensayo scripts/test-mysql-image-linux.sh exige versión RPM y prueba integración, TLS correcto y rechazo sin TLS en destinos efímeros. Bash-n y git diff --check correctos; cuatro configuraciones Compose examinadas sin daemon: build correcto, MySQL sin puertos/privilegios y ensayos con tmpfs/sin volúmenes persistentes. PDF dev.33 de15 páginas: portada y ampliación revisadas, texto dentro de página. Construcción real y escaneo pendientes de ejecución Docker en terminal. No actualizar8080/8085 ni marcar dev.33 estable. Paquetes dev.32 anteriores conservan su evidencia e integridad originales.

Dev.33 publicada y referencias verificadas con git ls-remote: commit2f5ab7f5735eb7a4e58ff725d14f5ae9ea8afc03, etiqueta anotada b2ca86ad5474146423e752b793e6afd443d4f5d9, rama codex/b07-avisos-restantes. Etiqueta resuelta al mismo commit. Sin despliegue, construcción real todavía pendiente; main intacta.

## Parche gawk dev.33 comprobado

mysql-image-check-gpZ3It99, inicio8oct20:52:50CST, commit3489d430bf94c649c7bf7e61d6fb0e95fb531a33, árbol limpio, exit0. gawk5.1.0-6.el9_8.1 y servidor8.4.11. Integración219 controles correctos; public-tls-w378H7sm y public-no-tls-dKB7YKgK exit0, controles TLS/cuentas/recreación y rechazo/base vacía aprobados. Destinos efímeros retirados; instalaciones normales/copias existentes y Aiven conservadas.

Trivy0.75.0 sobre imagen exportada:69→66 avisos, desaparecen únicamente los tres gawk, ningún identificador/paquete nuevo con la misma DB. Restantes1CRITICAL/29HIGH/29MEDIUM/6LOW/1UNKNOWN, sin exclusiones. El pendiente de construcción/regresión/escaneo anterior queda superado para esta referencia. Comparación privada mysql-scan.json/comparison.json. Curl ampliado con condiciones y diferencias de severidad del proveedor en AVISOS-IMAGENES; no se modifican reportes. No hace falta repetir el ensayo del parche por esta actualización documental. Siguen pendientes los grupos no evaluados, Windows/Firefox/zoom y el paquete final; dev.33 no es estable.

PDF actualizado:15 páginas, resultado dev.33 y límites correctos; ampliación de seguridad renderizada/revisada, texto dentro de página. La actualización documental no cambia imágenes ni requiere repetir integración.


## Ampliación de avisos base

Revisión adicional documentada en AVISOS-BASE: 24 identificadores y 66 filas cotejados con el escaneo conservado (60 MEDIUM / 6 UNKNOWN). glibc: modos fopen fijos, conversiones/wordexp/DNS de depuración sin ruta directa encontrada, nscd ausente del inventario; limitaciones de llamadas nativas conservadas. zlib/Expat: discrepancias de rangos no resueltas; sin cambiar severidades. SQLite no es base usada por EVENTIFY; avisos dependen de su Session Extension, distinta de las sesiones PHP. Pod::Text/kernel sin severidad examinados sin declararlos inocuos.

Inspección estática de once ELF setuid/setgid en la imagen local dev.32: ninguno tiene RPATH/RUNPATH, configuración de imagen sin LOCALDOMAIN. Host actual kernel 7.0.0-38-generic, hardlinks protegidos y configuración search/domain sin valores largos; solo lecturas agregadas, sin contenido DNS o variables privadas. Falta verificar DNS efectivo de contenedores/proveedor y parches del kernel real; consulta Ubuntu sin resultado verificable. No cambios funcionales, builds o nuevas suites. Corregido conteo libxml2 a 7 HIGH / 1 CRITICAL / 2 MEDIUM / 2 LOW según JSON. Dev.33/etiqueta conservadas; revisión general y entrega final siguen abiertas.


## Apache y tar: revisión adicional

Doce identificadores / 39 filas MEDIUM contrastados con Debian y el informe conservado. Ocho avisos Apache requieren módulos no cargados; rewrite está presente, pero falta la expresión lookahead necesaria para el noveno. Tar sí interviene en restauración QA: se revisaron formato no incremental, opciones y procedencia del archivo; un directorio nuevo o snapshots no prueban seguridad frente a archivos maliciosos. Detalles y límites en AVISOS-APACHE-TAR. Sin cambios funcionales ni repetición de suites. Continúan pendientes los demás grupos, DNS/kernel reales, Firefox/zoom, Windows y paquete final.


## curl: diez avisos medios contrastados

AVISOS-CURL coteja diez identificadores / veinte filas MEDIUM con fuentes originales y Debian. WeatherClient usa streams PHP; Composer y los ensayos sí ejecutan CLI. Avisos de callbacks, Digest/mTLS y reset se distinguen de los comandos utilizados;80229 también afecta CLI y permanece abierto por proveedores OpenSSL/pool. Configuración predeterminada curl y proxies externos no auditados; la huella Composer no parchea fallos de memoria durante descarga. Sin cambios funcionales ni suites repetidas.


## Paquete de revisión dev.33 actualizado

Fuente exacta f4d6c5a05011c57e47295b06cedf11129a052681:194 archivos, exportados por scripts/package-source.py; ZIP fuente SHA2567a23817f91ecad6453eda169ed09b17df5e3b3125149780ce195e724703cd897. Paquete output/entrega/eventify-dev33-revision.zip, SHA2565d0f663c66e0e34d02c5ab2ce4ffff0e6bf640a4d6d70f11e12a80dff4b2171b: fuente, manifiesto, PDF conservado y guía con límites. CRC y todas las huellas internas comprobadas; Dockerfile.mysql y guion Windows presentes. No contiene datos/credenciales de proveedores. La ejecución local no necesita Render/Aiven/GitHub; Docker/Compose e Internet inicial siguen siendo requisitos del host. PDF conserva su corte previo y las últimas revisiones de seguridad están en MD. No se instaló este paquete exacto: esa prueba y Windows/Firefox/zoom siguen pendientes. Paquetes anteriores conservados; no versión estable.


## Herramientas y cobertura MEDIUM PHP/Apache

AVISOS-HERRAMIENTAS añade13 identificadores /70 filas MEDIUM contrastados con Debian. Búsqueda de invocaciones y188 paquetes del inventario examinados; distingue servicios ausentes de bibliotecas presentes y llamadas nativas aún no auditadas. Cotejo conjunto:976 filas MEDIUM =189 fuera de linux-libc-dev con identificadores documentados +787 de cabeceras del kernel, sin evaluación individual. No confundir cobertura con remediación; LOW y condiciones nativas/entorno siguen pendientes. Fuentes/cobertura privadas en storage/image-audit/tools-medium-review. Sin cambios funcionales ni nuevas suites.

Docker info sigue devolviendo1 en esta sesión; no modificar permisos. Puerto8086 libre al comprobarlo, sin reservarlo ni detener destinos. Próximo ensayo: scripts/test-clean-linux.sh con commit f4d6c5a05011c57e47295b06cedf11129a052681 y puerto8086, para instalar la misma fuente194 archivos del paquete dev.33. No ejecutado todavía; resultados no se presuponen.


## Instalación y recorrido dev.33 comprobados

Ensayo clean-20261009035638-598f411d, inicio8oct21:56:38CST, exit0. Fuente f4d6c5a05011c57e47295b06cedf11129a052681,194 archivos; SHA256 del ZIP7a23817f91ecad6453eda169ed09b17df5e3b3125149780ce195e724703cd897 idéntico al paquete de revisión. Proyecto eventify-clean-20261009035638-598f411d,localhost8086, ruta con espacios. Tres migraciones aplicadas; demo creada únicamente en destino nuevo. qa-persistence confirma tablas, marcador, credenciales e imágenes conservados tras recreación, antes del recorrido de negocio.

Navegador IAB sobre8086: login de usuario, filtro AJAX Encuentro con un resultado, reserva de dos entradas gratuitas y navegación a ticket con QR. Reserva6a15f6dedaf22a5018d499ce09d07161, ticket85471e40c94c7ed6285d65b8caef043bc2fc03f2e59794dd737a38a0699d4446. Cupos40→38→40 tras cancelación; historialCANCELADA y ticket sin vigencia permanecen visibles. Clima desactivado en QA conserva la reserva disponible, sin llamadas externas. Acceso admin rechazado para usuario común; cuenta administrativa abre panel con estadísticas. Ambas sesiones cerradas y ticket requiere login después de logout. Captura /tmp/eventify-dev33-ticket-cancelado.png revisada.

No se modificó código o configuración durante este recorrido. Solo se creó/canceló una reserva ficticia en la copia aislada; instalaciones anteriores y servicio público conservados. Persistencia ensayada antes del recorrido; recreación posterior a esta nueva reserva/cancelación aún no comprobada. No certifica Windows, Firefox, zoom ni cierre de seguridad. El pendiente de instalar la fuente exacta del paquete anterior queda superado en Linux para esta referencia; documentos posteriores mantienen su propio corte.

Paquete con fuente exacta conservada y resultados posteriores: output/entrega/eventify-dev33-linux-comprobado.zip, SHA256ccf79b4b74f78859ae3da5d1d94f31a1df88024777ad10b94cf1987cdcffd3f9. CRC y huellas internas comprobados; no versión estable. Paquete anterior intacto.


## Persistencia posterior dev.33: ensayo preparado

Helper privado storage/clean-20261009035638-598f411d/verify-browser-persistence.sh preparado para el único proyecto eventify-clean-20261009035638-598f411d y puerto8086. Valida éxito previo, commit y SHA del ZIP, configuración local/eventify_qa, aislamiento de montajes y propietario de ambos volúmenes antes de detener web. Obtiene snapshots mediante qa-transfer, recrea sin build ni borrado, compara diez tablas/archivos y restablece web ante fallo. Agrega lectura DNS del contenedor con cantidades/longitudes y arquitectura, sin registrar dominios o variables privadas.

Bash-n y comprobaciones del destino/no-build/no-borrado correctas. Docker info devuelve1; ejecución real pendiente en terminal del usuario. No se tocó la copia ni se generó evidencia ficticia de persistencia posterior. El helper es específico del ensayo, queda fuera del paquete del receptor y no cambia dev.33. Al finalizar, revisar status/snapshots/DNS y consultar en navegador el historialCANCELADA y ticket sin vigencia; no repetir la reserva.


## Persistencia posterior dev.33 comprobada

Registro storage/dev33-persistence-lBxRJbW3, inicio8oct22:17:00CST, exit0, proyecto eventify-clean-20261009035638-598f411d. Comparación completa before/after idéntica: diez tablas,cinco archivos,ocho reservas y ocho tickets. Contenedores recreados sin build ni borrado de volúmenes; tres rutas HTTP responden correctamente. El pendiente del helper preparado queda superado para esta copia exacta.

DNS leído dentro del contenedor: arquitectura x86_64, un dominio search/domain de longitud1, LOCALDOMAIN ausente. No se encontró la condición de dominio largo del aviso8674 en ese runtime. Solo cifras, sin exponer nombres/variables. No extrapolar a Render, Windows, otro contenedor ni futuros cambios DNS; kernel y bibliotecas nativas conservan sus pendientes.

Después de la recreación, navegador IAB8086: login correcto, historial de la reserva6a15f6dedaf22a5018d499ce09d07161 conserva CANCELADA y dos entradas; ticket85471e40c94c7ed6285d65b8caef043bc2fc03f2e59794dd737a38a0699d4446 conserva QR y mensaje sin vigencia. Captura /tmp/eventify-dev33-persistencia.png inspeccionada. Sesión cerrada, ninguna nueva reserva o cancelación; pestaña temporal cerrada. No hace falta repetir instalación/persistencia para esta referencia por cambios documentales.

Linux: instalación limpia,recorrido y persistencia posterior aprobados para f4d6c5a. Pendientes restantes: Windows completo,Firefox/zoom/calendario,revisión de seguridad aún abierta,documentación final y defensa humana. No se marca estable ni se cierran todos los criterios B07. ZIP dev33-linux-comprobado conserva su corte previo; este registro añade la evidencia posterior sin alterar su fuente o huellas.


## Continuación autónoma: documentos y avisos bajos

El usuario pide continuar sin solicitar mensajes de continuidad; intervenir solo ante acceso/datos necesarios. README,INSTALACION,CIERRE y TECNICO actualizados a la evidencia Linux dev.33 y despliegue dev.30, conservando límites de cada ejecución. PDF regenerado:15 páginas, nombres correctos,texto dentro de página y páginas3/13/14/15 renderizadas/revisadas. No cambios funcionales ni nuevas suites.

Avisos LOW PHP/Apache:882 filas =736 fuera de cabeceras +146 de linux-libc-dev.147 páginas Debian descargadas; AVISOS-BAJOS.csv reúne identificadores/condiciones/fuentes sin aprobarlos automáticamente. Doce avisos Apache contrastados con inventarios y configuración; PHP/prefork/negotiation activos, módulos necesarios de otros avisos ausentes. HostnameLookups Off en configuración local exportada. Range/consumo y riesgo de código local malicioso no descartados. Otros135 identificadores fuera de cabeceras siguen pendientes;69 de binutils se repiten en ocho paquetes. Registros privados storage/image-audit/low-review.

VERIFICACION-FIREFOX prepara un recorrido concreto con teclado/calendario,zoom200%,roles e historial existente, sin nueva reserva. Solicitud de disponibilidad Firefox/Windows presentada al usuario; sin respuesta no se presume equipo compatible. Esta sesión no controla aplicaciones nativas y no puede certificar estos recorridos por IAB o por una mera instalación de navegador. Windows requiere host/Docker compatibles según su guía. No pedir de nuevo los ensayos Linux ya aprobados.


## Alcance LOW ampliado

AVISOS-BAJOS.csv agrega notas para otros 66 identificadores: 78 con contraste de configuración/uso junto a los doce Apache; 69 binutils aún pendientes individualmente. Búsqueda de invocaciones en aplicación, herramientas, Dockerfile y guiones; notas Debian TEMP consultadas. La ausencia de ruta web no elimina uso nativo, exposición local ni avisos del informe. GnuTLS/CBC, glibc, XML, herramientas privilegiadas y configuración externa curl continúan abiertos. No se aplicó parche ni se ejecutaron nuevas suites para cambios documentales.


Binutils: los 69 identificadores también tienen delimitación inicial por tipo de entrada (símbolos, ELF/DWARF, enlace/BFD, XCOFF, DLX y plugin/LTO). Las 147 filas del CSV tienen fuente y nota de alcance; no equivale a descarte o parcheo. Backends/plugins, consumidores nativos y cadena de construcción siguen abiertos. Posible separación de herramientas de desarrollo requiere build/inventario/integración real antes de cambiar runtime; esta sesión carece de acceso Docker.


## Paquete documental y versionado comprobados

Commit 227461a59f429b755f7258de68c412c69209d07c publicado en codex/b07-avisos-restantes; git ls-remote confirma la referencia y main conserva 3da680b74b41fa3da4069879edc1f649de9b97f2. Sin etiqueta estable o despliegue.

Paquete output/entrega/eventify-dev33-cierre-linux.zip, SHA256 8a51918698fc34e8f73cef4ef01ecb18cec12457e16382943f7e7b697e9eead4: quince entradas, CRC y huellas internas correctos. Conserva fuente exacta f4d6c5a y su manifiesto de 194 archivos ya comprobados en Linux; añade PDF de quince páginas y documentación complementaria del commit 227461a. Guía explica ambos cortes. No modifica código ni requiere repetir la instalación Linux; no contiene bases o credenciales privadas. Paquetes anteriores intactos.

PDF regenerado y página de pruebas renderizada/revisada después de distinguir persistencia Linux dev.33 de la restauración QA previa. Pendientes que requieren acceso humano: Firefox real con zoom/calendario y Windows con Docker compatible. Seguridad conserva condiciones nativas/host y backends/plugins abiertos; no certificación estable. No pedir mensajes de continuidad ni repetir ensayos ya aprobados.


## Ensayo aislado de reducción de herramientas: preparado

9 de octubre de 2026. Rama codex/b07-runtime-aislado desde 58e3b66. C-09/SEG-09: Dockerfile.runtime-check y runtime-prune.sh derivan imágenes experimentales y retiran herramientas de desarrollo sin autoremove; plan simulado y bibliotecas/Apache/PDO protegidos. test-runtime-minimal-linux.sh construye variantes local/pública y ejecuta unidades/integración MySQL con proyecto exclusivo, red interna, tmpfs y claves nuevas; sin puertos, montajes ni volúmenes persistentes. No cambia Dockerfile de entrega, VERSION, dev.33, destinos actuales o publicación.

Bash-n/sh-n y diff --check correctos. Generador Python ejecutado y Compose normalizado sin daemon: aislamiento y ausencia de herencia .env/build comprobados. Patrón amd64 revisado, gcc-base y bibliotecas requeridas no seleccionadas. Construcción, integración y reducción real de avisos NO ejecutadas: Docker info informa permiso denegado al socket. ENSAYO-RUNTIME contiene comando exacto y criterios. Tras ejecución humana, revisar registros e imágenes y escanear; no repetir instalación Linux dev.33. Windows/Firefox y defensa conservan sus pendientes.


## Runtime reducido: ensayo aprobado y dev.34 preparada

9 de octubre de 2026, 05:21:40 CST (11:21:40 UTC). Registro runtime-check-doJmuSur, commit b14fee3, exit0. Variantes local y pública: construcción y comprobación Apache/PDO correctas; 266 unidades y 219 controles MySQL/HTTP por variante. Proyectos efímeros retirados; inventarios y exportaciones conservados. Se retiraron 32 paquetes y no cambió la versión de ninguno conservado.

Trivy sobre ambas exportaciones, misma base almacenada actualizada el 8 de octubre a las 19:05:21 UTC, sin actualización/exclusiones: 2.011 → 424 filas por variante. Restantes: 1 CRITICAL, 91 HIGH, 162 MEDIUM, 166 LOW, 4 UNKNOWN; ningún par nuevo de identificador/paquete/versión frente al informe conservado. JSON/log/comparison en storage/runtime-check-doJmuSur. La reducción de cabeceras no parchea el kernel del host; permanecen bibliotecas vulnerables y riesgos de construcción.

Dev.34 incorpora exactamente runtime-prune.sh ensayado al final de ambos Dockerfile y verifica ausencia de herramientas en test-image-linux.sh. Sin cambios PHP, MySQL, datos o destinos activos. verify-runtime-release-linux.sh agrupa construcción/exportación de imágenes finales, unidades, rechazos públicos y TLS positivo/negativo en destinos efímeros. Bash-n/sh-n y diff --check correctos; construcción final y arranque Apache público/TLS pendientes de ejecución Docker en terminal. No afirmar que el ensayo derivado ya certifica estas imágenes finales.

La instalación Linux dev.33 conserva su aprobación y paquete. Nueva versión aún de desarrollo; Windows, Firefox y aceptación de dev.34 pendientes. No desplegar o reemplazar paquetes antes de revisar este ensayo final.


## Dev.34: imágenes finales y TLS aprobados

9 de octubre de 2026, inicio 05:45:31 CST (11:45:31 UTC). runtime-release-pdzRD8Jx, commit 6d41df54a4df19c7c5895de6eac8120d263809d3, exit0. image-check-hGj1k1LD: ambas imágenes finales construidas; ausencia de herramientas retirada, sintaxis, 266 unidades y QR comprobados. public-start-noDIeuWq: tres rechazos de configuración pública correctos. public-tls-qSBzd2c1: arranque Apache/TLS, HTTP interno, cuentas/roles/hashes/rechazos y recreación aprobados. public-no-tls-bTdhnbR5: servidor sin TLS rechazado antes de servir/aplicar migraciones, base vacía comprobada; todos los registros exit0.

Trivy sobre local.tar y public.tar finales con la misma base almacenada y sin exclusiones: 424 filas en cada una, idénticos identificadores/paquetes/versiones/severidades al ensayo derivado. Restan 1 CRITICAL, 91 HIGH, 162 MEDIUM, 166 LOW y 4 UNKNOWN; sin corrección publicada en el campo FixedVersion de este informe para los avisos restantes. Esto describe la base de avisos usada, no asegura inexistencia de una actualización posterior. JSON/log/comparison en image-check-hGj1k1LD. La retirada de cabeceras no parchea el host.

El pendiente de construcción final/TLS queda superado para 6d41df5. Sin cambios funcionales posteriores, sin despliegue ni actualización de instalaciones existentes. Dev.33 conserva instalación limpia/persistencia; no atribuirlas a una instalación del ZIP dev.34. Pendientes: Windows real, Firefox/zoom/calendario, aceptación del paquete dev.34, riesgos residuales de seguridad y defensa humana. Solicitud de disponibilidad Windows presentada al usuario; no suponer Docker instalado por una captura del escritorio.


Paquete fuente dev.34: output/entrega/eventify-desarrollo-23553476bd86/eventify-source.zip, commit 23553476bd86f31dd9b552f3f1d422839dbcbd14, 203 archivos, SHA256 8ab1a21852436e08ffbe200350282f32910fa483fa75d061208ef06d89c5a8f3. CRC y todas las huellas del manifiesto comprobados. Diferencia respecto a 6d41df5 exclusivamente MD: mismo código e imágenes declaradas que los ensayos aprobados. Paquete preparado para aceptación en receptor; todavía no instalado desde este ZIP en Windows. PDF técnico anterior conserva su corte dev.33; actualización final pendiente tras aceptación, sin presentarlo como documento dev.34.


## Orden de cierre solicitado: Firefox y seguridad, Windows al final

9 de octubre de 2026. El usuario prioriza C-06/C-07 y C-09; Windows queda para el final, sin eliminar su requisito. Firefox existe en el host, pero el inventario CUA solo expone IAB/MCP Apps y ninguna aplicación nativa; no se afirma control de Firefox. localhost:8086/eventos responde HTTP200. Solicitada comprobación humana de catálogo/búsqueda/zoom200% y versión. Corregida una separación accidental en la ruta de credenciales de VERIFICACION-FIREFOX, sin leer o publicar sus valores.

Seguridad: AVISOS-XML contrasta nueve avisos altos antes sin mención individual con Debian; Trixie figura vulnerable. Búsqueda de parsers y revisión de WeatherClient/TicketQr limitan entrada XML directa, sin descartar consumidores nativos. Crítico6653 reconsultado; no parche compatible acreditado por esa fuente. CSV residual agrupa 424 filas PHP/Apache en156 identificadores con referencias documentales; cobertura no implica cierre ni aceptación del riesgo. No cambios funcionales, builds o suites repetidas.


## Firefox: evidencia humana parcial recibida

9 de octubre de 2026. Capturas del usuario: Firefox 157.0.1 (64 bits), Linux Mint, zoom nativo200%, localhost8086. Catálogo con q=Encuentro y un resultado; foco visible; calendario Desde abierto y contenido de formulario sin recorte esencial en lo visible. Captura previa de detalle muestra título/precio/botón legibles. Cuatro archivos y huellas conservados en storage/firefox-20261009. No acceso nativo ni ejecución automatizada de Firefox.

VERIFICACION-FIREFOX actualizada. Pendientes: limpieza/combinación, selección/cancelación y teclado del calendario, inicio/historial/ticket al200%, login/roles/logout y casos de error. Capturas no acreditan transporte AJAX o navegación solo con teclado. Destino dev.33 con interfaz sin cambios en dev.34; no certificación de instalación nueva. Solo documentación; diff --check correcto, sin nuevas suites. Windows sigue al final.


## Firefox: historial y ticket al 200%

9 de octubre de 2026. Cinco capturas adicionales aportadas por el usuario sobre localhost8086: navegación autenticada como Usuario de prueba, historial con Encuentro de tecnología CANCELADA y dos entradas; ticket con identificador, QR completo y aviso sin vigencia. Texto y controles visibles legibles al zoom nativo200%. Nueve capturas acumuladas y huellas en storage/firefox-20261009. No se escaneó QR ni se controló Firefox desde esta sesión.

VERIFICACION-FIREFOX actualizada. Próximo paso humano: comprobar rechazo de /admin como usuario; cerrar sesión desde Mi cuenta y volver a la URL del ticket para comprobar petición de acceso. Siguen pendientes panel administrador, limpieza/combinaciones de filtros, recorrido de teclado/selección/cancelación del calendario e inicio al200%. No repetir reserva, instalación Linux ni suites por este cambio documental. Dev.33 es el destino observado; seguridad residual y Windows siguen abiertos, Windows al final. Revisión documental y git diff --check correctos.


## B07: presentación del acceso restringido, dev.35

9 de octubre de 2026. El usuario confirma visualmente rechazo de /admin en Firefox200%, pero solicita mejorar su presentación. Captura original y huella privadas conservadas. Rama codex/b07-acceso-denegado: AdminController conserva comprobación de rol/HTTP403/return antes de datos administrativos; nueva vista access-denied.php utiliza estilos adaptables existentes, mensaje claro y enlaces a eventos, cuenta e historial. Sin cambios CSS, SQL, reservas o permisos. VERSION/CHANGELOG dev.35.

Vista HTML estática idéntica a la plantilla revisada en IAB, captura storage/access-denied-preview/desktop.png. Revisión de diferencias y diff --check correctos. No PHP instalado en host y Docker/sudo no interactivo sin acceso: sintaxis PHP, integración y respuesta nueva en ejecución pendientes. No repetir suites Linux anteriores para atribuirlas a este cambio. Actualización propuesta: construir únicamente web con fuente actual y .env de la copia8086, proyecto eventify-clean-20261009035638-598f411d, --no-deps, conservando base y volúmenes. Requiere terminal del usuario; no ejecutada aquí. Firefox/logout/calendario, seguridad residual y Windows continúan con sus límites; Windows al final.


## B07: revisión de respuestas sin estilo, dev.36

9 de octubre de 2026. Captura humana admin-denegado-dev35-110.png confirma nueva página /admin en Firefox8086 al110%, conservada con huella privada. No equivale a comprobar200% ni toda la integración de dev.35. Revisión de controladores/enrutador/excepciones encontró texto aislado en CSRF de autenticación, CSRF administrativo,405 y error500 HTML, además de una salida405 vacía interna. Dev.36 agrega ErrorPage/vista compartida con escape y CSS existente. Conserva códigos,Allow,CSRF,roles y formatos API/consola; no revela mensajes internos.404,errores de validación y estados vacíos ya usan vistas con estilo. Sin cambios de BD/datos.

PHP8.3.6 extraído del paquete oficial Ubuntu php8.3-cli8.3.6-0ubuntu0.24.04.11 bajo storage/php-check, sin instalación del sistema. Sintaxis de todos los PHP correcta; prueba error-page con7 comprobaciones de código/escape/estilos/enlaces aprobada y añadida a guiones Linux/Windows. Servidor PHP local efímero8098: seis rutas comprobadas (/salirGET405HTML conAllow,/registroPOSTsinCSRF403HTML,/eventos500HTML por ausencia deliberada de PDO en este intérprete aislado,/api/eventos500plano conservado,/api/reservasGET405plano conAllow,/no-existe404conCSS). Sin credenciales MySQL ni conexión a bases. Registros privados php-check/lint.log,http-results.json,error-405.png. Vista405 revisada en IAB. Bash-n y diff --check correctos; PowerShell no ejecutado. No atribuir estas pruebas PHP8.3 al contenedor PHP8.4/MySQL: integración real dev.36 pendiente.

No nuevas coincidencias de páginas HTML con texto aislado en el alcance revisado; API JSON, SVG y salud son respuestas técnicas deliberadas. Esto no certifica todos los tamaños o estados posibles. Instalación8086 siguedev.35 hasta actualización; no despliegue público ni estable. Próximo paso: ensayar integración aislada y actualizar solo web8086 desde terminal con acceso Docker; conservar MySQL,volúmenes y reservas. Firefox y seguridad residual siguen pendientes; Windows al final.


## Dev.36: actualización8086 y controles HTTP reales

9 de octubre de 2026. El usuario informa actualización ejecutada; GET /salir8086 responde405 con la nueva vista ErrorPage. Script privado Python ejecutado con urllib y cookies separadas, cuentas ficticias locales existentes:22 comprobaciones correctas. Ambos roles ingresan y salen; USUARIO obtiene403conCSS en/admin,/admin/reservas,/admin/eventos/nuevo; historialCANCELADA y ticket propio accesibles; tras logout el mismo ticket devuelve303a/ingresar. ADMINISTRADOR obtiene200conCSS en panel,categorías,eventos,reservas; POST categorías con CSRF inválido devuelve403con nueva vista sin ejecutar edición. Visitante405/404conCSS y registro sinCSRF403también correctos. Ambas sesiones propias cerradas; sesión Firefox del usuario intacta. Sin crear reservas, cambiar eventos/categorías o sembrar datos. Registro storage/dev36-http-check/resultados.json, sin contraseñas,cookies o tokens.

Pruebas sobre HTTP real del destino actualizado complementan sintaxis PHP8.3 y ensayo aislado previo; no sustituyen suite completa PHP8.4/MySQL ni inspección visual Firefox. Bloqueo de ticket traslogout queda acreditado por HTTP, pendiente presentación Firefox si se exige evidencia de navegador. Próximas comprobaciones humanas Firefox: inicio y rechazo al200%, selección/cancelación de fechas y Tab/Shift+Tab,limpiar filtros y panel administrador. Seguridad residual, aceptación de paquete dev.36,PDF final y Windows siguen abiertos. No repetir instalación/persistencia Linux ya aprobadas.


## Dev.36: recorrido en navegador integrado solicitado por el usuario

9 de octubre de 2026. El usuario solicita efectuar el recorrido en el navegador disponible. IAB8086: búsqueda Encuentro devuelve un resultado; Limpiar filtros recupera cuatro eventos y vacía búsqueda. Tab desde búsqueda lleva a categoría con contorno solid; Shift+Tab vuelve a búsqueda. Campo Desde acepta2026-10-15 y se borra; calendario nativo abierto mediante su botón y cerrado conEscape, fecha permanecevacía. No se certifica selección de día mediante celda ni recorrido completo de teclado. Ctrl+plus no modifica innerWidth1280/devicePixelRatio1: no se acredita zoom nativo200%, ni se sustituye por viewport.

Prueba de adaptación mediante viewport390,640,1280×900: inicio,catálogo,ingreso (redirección desde/admin como visitante) y error405. Doce mediciones sin ancho de documento superior al viewport, CSS presente. Con cuentaUSUARIO se revisó rechazo/admin y captura390×844 sin desbordamiento; con ADMINISTRADOR se revisaron panel,categorías,eventos,reservas a390, todas sin desbordamiento de página. Capturas completas de catálogo,rechazo y panel revisadas visualmente. El nombre admin-390.png del primer recorrido corresponde al ingreso tras redirección, no al rechazo; denegado-390.png sí muestra403.

Ambas sesiones de prueba cerradas desde botón; viewport restaurado y pestaña temporal cerrada. Sin creación/cancelación de reservas ni edición administrativa. Evidencia,mediciones y huellas en storage/dev36-browser-check. Solo documentación; sin nuevas suites ni incremento funcional. Firefox mantiene la evidencia humana previa y sus pendientes específicos; no sumar IAB como si fuera Firefox. Windows y seguridad residual siguen abiertos.


## Criterio de entrega: diseño adaptable, zoom opcional

9 de octubre de 2026. El usuario indica que solo requiere diseño responsive y descarta zoom200% como condición de cierre. Es una decisión de QA propia, no una exigencia del PDF; no se cambia ningún requisito académico. C-06 y guía Firefox actualizados; registros históricos de intentos y capturas se conservan. Se mantiene móvil/tableta/escritorio y compatibilidad Chrome/Firefox. IAB dev.36 acredita los tamaños y recorridos registrados; no convierte automáticamente pruebas IAB en Firefox.

Siguiente prioridad técnica: cerrar evaluación de seguridad residual con sus límites y verificar integración de los cambios dev.35/36 en el entorno de pruebas aislado, sin repetir instalación/persistencia Linux anteriores. Después elegir y preparar referencia/paquete/PDF/publicación coherentes. Windows permanece último ensayo de plataforma; defensa y comprensión humanas pendientes. No marcar estable ni aceptar riesgos en nombre del usuario. Solo cambios documentales; diff --check correcto.


## Dev.37: revisión de experiencia y perfil

9 de octubre de 2026. Usuario autoriza pulir toda la experiencia y continuar seguridad/entrega con Windows al final. Rama codex/b07-experiencia-final. Registro/ingreso con ojos independientes y accesibles; login alinicio; navegación compartida por estado/rol; mensaje provisional retirado; cuenta convertida en perfil con edición real del nombre,correo conservado,accesos a reservas y logout. POST/cuentalimitado por CSRF e identidad de sesión, UPDATE preparado y validación de nombre reutilizada del registro. No cambio de correo/clave/rol ni migraciones. Transiciones cortas,respeto reduced-motion y singular/plural corregidos.

PHPCLI8.3: sintaxis completa,23 controles registro/CSRF y7ErrorPage aprobados. Node --check ui.js correcto. Vistas renderizadas por PHP con fixtures ficticios y revisadas IAB a390/768/1280:12 mediciones sin desbordamiento. Dos ojos probados por separado,tipo yaria-pressed correctos; muestra de contraseña retirada antes de capturar. Evidencia storage/dev37-preview; perfil/escrituras/inicio autenticado son vistas previas,no integración. Pruebas HTTP/MySQL ampliadas y helper storage/dev37-review/verify-linux.sh preparado para integración efímera y actualización solo web8086. Docker sin acceso desde esta sesión; ejecución real y aceptación dev.37 pendientes. Instalación8086 conserva dev.36 hasta que termine el helper.

REVISION-EXPERIENCIA documenta alcance,decisiones,pruebas ypendientes. Estado no estable; no atribuir capturas dev.36 a nuevas vistas. Próximo paso necesario: terminal del usuario para helper; después validar autónomamente la instalaciónactualizada por HTTP/navegador,conservar reservas y revisar nombre guardado. Seguridad residual,publicación/paquete/PDF final yWindows permanecen abiertos.


## Dev.37: primer ensayo detenido por expectativa antigua

9 de octubre de 2026. Helper ejecutado por usuario sobre d8829a7,exit1. Integración alcanza159 comprobaciones correctas y falla en reservas administrativas filtradas porque esperaba Usuario HTTP después de que el propio caso de perfil guardó Usuario Actualizado. Expectativa corregida al nombre actualizado, sin relajar código403,validaciones o filtros. Comprobaciones previas incluyen persistencia del nuevo nombre y conservación de correo/rol. No se ejecutó actualización de web8086 debido a set-e; conserva dev.36 y sus datos. Primeros registros conservados íntegros en storage/dev37-review/primer-intento. Sintaxis de prueba corregida y diff --check correctos; reejecución MySQL pendiente. Sin cambio funcional ni nueva versión.


## Dev.37: integración y recorrido real aprobados

9 de octubre de 2026. Segundo helper sobre ab710a671d40024bf84cc46026af4c4cd54a948d,exit0:225 comprobaciones MySQL/HTTP,sintaxis PHP8.4,23registro/CSRF y7ErrorPage aprobados,salud correcta. Actualizada únicamente web8086; DB yvolúmenes conservados. Registro storage/dev37-review/verify.log ystatus.txt.

IAB sobre web actualizada: ojos independientes cambian tipo/estado; loginUSUARIO yADMINISTRADOR redirigen alinicio con navegación porrol. Perfil guarda nombre ficticio temporal; logout/nuevoingreso conservan cambio; nombre original Usuario de prueba restaurado yconfirmado. Historial conserva reservaCANCELADA de2entradas y corrige singular1entrada. Perfil a390/768/1280 sin desbordamiento. Administrador accede alpanel desde perfil. Ambas sesiones cerradas,viewport restaurado,pestaña temporal cerrada. No se crearon/cancelaron reservas ni editaron eventos. Capturas,medidas yresultado en storage/dev37-live.

Superados los pendientes de integración y guardado dev.37 del helper. No equivale a nueva instalación limpia/ensayoWindows,ni certificaFirefoxporIAB. Seguridad residual,publicaciónactualdev.30,paquete/PDF con nueva referencia ydefensa humana conservan sus pendientes. No versión estable.


## Dev.37: corte del documento técnico

9 de octubre de 2026. Documento técnico actualizado a dev.37: 16 páginas, portada con los nombres confirmados, experiencia de usuario y evidencia de 225 controles de integración. Generador y contenido sincronizados; PDF renderizado y revisado visualmente, texto dentro de página y referencias comprobadas. Archivo output/pdf/EVENTIFY_Documento_Tecnico_Borrador.pdf. Se conserva como borrador mientras continúen los pendientes de entrega; no incorpora credenciales. Sin cambios de aplicación desde ab710a6 ni repetición de pruebas Linux.


## Dev.37: paquete y respaldo GitHub

Referencia exportada 7c3a3e28435566349efcd4b2a66eace89de978d2, misma aplicación verificada en ab710a6 y documentos posteriores. Rama y etiqueta anotada v0.1.0-dev.37 confirmadas con ls-remote; etiqueta resuelta al mismo commit. ZIP de213 archivos en output/entrega/eventify-desarrollo-7c3a3e284355: CRC, inventario y SHA-256 por archivo comprobados. ZIP SHA-256 9aac254df152d293013daa6cf714225772c30fd043e9ed23e80fa6d0faadad89. PDF acompañado con SHA-256 db2ccad78b39bc4064949e48c59c64dea8db6109241aa0fbc3671ae91a2af316. Sin secretos, base de datos ni credenciales locales. No se afirma instalación nueva de este ZIP ni Windows; Linux anterior conserva su evidencia.

Reconsulta oficial dirigida: libxml26653 y Apache63292 siguen vulnerables en Trixie; sin parche compatible identificado en esas tablas. AVISOS-XML registra fuentes y límites. Sin nuevo escaneo, exclusiones ni aceptación automática del riesgo. Publicación conserva dev.30; Windows queda último y defensa humana pendiente. Esta actualización documental no mueve la etiqueta ni cambia el paquete ya exportado.


## Publicación dev.37 y comprobaciones posteriores

9 de octubre de 2026. Usuario autoriza actualizar la publicación y continuar seguridad. Aiven MySQL Free estaba Powered off; el servicio existente se reactivó y pasó por Rebuilding hasta Running, sin cambio de plan, credenciales, permisos o datos. El endpoint DNS no resolvía mientras estaba apagado. Antes del deploy se guardaron huellas privadas de diez tablas.

Render Free desplegó manualmente el commit exacto de la etiqueta dev.37 y del paquete exportado, 7c3a3e28435566349efcd4b2a66eace89de978d2: dep-db4n8cbncjis73c7m4t0, Live,34,8s. Sin diferencias en migraciones/configuración/entrypoint respecto a dev.30. Snapshot posterior, antes de logins y clima, idéntico en las diez tablas. No se sembró demostración ni se hicieron escrituras de negocio.

Seguridad pública:81 controles HTTPS de dos usuarios y31 del administrador aprobados. Login al inicio, cookie/sesión/CSRF, restricciones, tickets ajenos, páginas administrativas con estilos y POST inválidos de categoría/perfil comprobados. Sesiones propias cerradas. IAB público: inicio sin mensaje provisional, catálogo, registro y ojos independientes correctos. Capturas/registros/huellas en storage/public-dev37; pestaña pública conservada para el usuario.

Clima: consulta del detalle recibió HTTP429 del proveedor, acreditado por diagnóstico redactado de Render. Mostró aviso y mantuvo el acceso a reserva; no se repitieron consultas para eludir el límite. No se afirma pronóstico útil nuevo en esta versión: la respuesta útil anterior pertenece a dev.30. El contador API puede cambiar después del snapshot por esta consulta esperada.

Rendimiento dirigido: Eventos local HTTP7ms inicial/4ms siguiente; navegación IAB local TTFB3,2ms y carga101,9ms. IAB público activo: TTFB504,2ms, DOM1057,9ms y carga1060,2ms. Muestras únicas, no percentiles ni arranque en frío de Docker/Render ni medición de Firefox del usuario. No se reprodujo lentitud local. Plan gratuito puede dormir y Aiven puede apagarse por inactividad; revalidar ambos antes de evaluación.

Seguridad de aplicación comprobada en este alcance, dependencia XML aún abierta. AVISOS-XML registra digest oficial sin cambios, estado Debian y alcance del parche nativo. No existe evidencia de corrección de libxml2 en la imagen entregada; no se oculta ni se marca estable. Windows y defensa humana siguen pendientes. Rama documental codex/b08-publicacion-dev37; etiqueta y paquete dev.37 no se mueven.


## Documento y reconsulta pública: dev.37

9 de octubre de 2026. PDF técnico sincronizado con la publicación dev.37, sus 112 controles HTTPS, conservación de diez tablas y límites de los servicios gratuitos. Se mantienen 16 páginas: render completo revisado visualmente, nombres y cifras comprobados, caracteres dentro de los límites de página. Consulta Packagist de las dos bibliotecas fijadas: cero avisos; no equivale a corregir libxml2 ni toda la imagen.

Detalle público consultado cerca del vencimiento de la caché negativa y reintento posterior mediante el botón: ambos mostraron indisponibilidad, con ingreso para reservar accesible. No se repiten llamadas para eludir la cuota. El diagnóstico HTTP429 registrado anteriormente se conserva; el texto del último reintento por sí solo no acredita su código upstream. Evidencia privada storage/public-dev37/weather-retry.json y weather-retry.png.

Solo documentación desde 74f1029; no se repite Linux ni se cambia aplicación, base, credenciales o red. Preparar paquete desde el commit documental resultante y acompañarlo con este PDF; conservar el ZIP anterior y la etiqueta dev.37 intactos. Continúan abiertos libxml2 y los restantes límites de la revisión de imágenes, compatibilidad Firefox pendiente, instalación Windows y defensa humana. No versión estable.


Paquete documental actualizado: referencia 44bd3a9a936005db52bda8de65c90037803ae335, push confirmado en codex/b08-publicacion-dev37. Directorio output/entrega/eventify-desarrollo-44bd3a9a9360; ZIP de213 archivos, CRC e inventario/huellas individuales correctos, SHA256 d9494a9798e542ab3a47e3df9181ac7df01fbd8262a36b5df92b41055e138bf4. PDF de16 páginas acompañado, SHA256 a0189715ec1c8337738762718f628fc260ad8fb20021e57ea8a7522f4326b361. Código de aplicación,configuración,migraciones,dependencias e imágenes declaradas sin diferencias respecto a la publicación 7c3a3e2. Esto acredita correspondencia de fuente, no instalación nueva de este ZIP. El commit posterior solo registra estos resultados; no obliga a reexportar ni a mover etiqueta o despliegue.
