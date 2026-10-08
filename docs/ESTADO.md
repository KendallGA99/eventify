# EVENTIFY: estado y continuidad

Fecha de referencia: 2026-10-08, zona del usuario America/Costa_Rica.

## Resumen actual

- Usuario autorizó iniciar la construcción por bloques.
- B00: commit inicial creado: 44da548. Se conservó el snapshot documental que estaba en el índice.
- B01: stack Docker ejecutado por el usuario; salud PDO/MySQL 200 y nueve comprobaciones HTTP verificadas. Persistencia/reinicio todavía pendiente.
- Git local: rama activa `codex/b07-integration-fix` (base previa `codex/plan-eventify`); identidad reutilizada de Código Tico y verificada con cuenta GitHub USUARIO_DESTINO. Código B01–B04 guardado como 0.1.0-dev.1; B05 en 0.1.0-dev.3 y B06 parcial en 0.1.0-dev.5 (categorías/eventos, dashboard y consulta de reservas). Ninguna es estable. Repositorio privado creado: USUARIO_DESTINO/eventify. Push inicial confirmado en main y codex/plan-eventify, ambas a0e0102; etiquetas checkpoint-b00 → 44da548 y v0.1.0-dev.1 → a0e0102. Referencias verificadas mediante git ls-remote.
- Usuario autorizó versionado/push progresivo. Remoto: REPOSITORIO_DESTINO_PENDIENTE. No se modificó Código Tico ni se inventaron fechas de commits.
- Linux Mint 22.3: el usuario instaló Docker 29.1.3 y Compose 2.40.3. Codex no tiene acceso directo al socket; sudo requiere contraseña interactiva en la terminal del usuario. PHP/MySQL objetivo se ejecutarán dentro de contenedores.
- Herramientas temporales de verificación: PHP CLI 8.3.6 y Compose 2.40.3 extraídos de paquetes Ubuntu en /tmp, sin instalación global.
- Stack objetivo PHP 8.4/Apache + MySQL 8.4 LTS actualizado y ejecutado en Linux mediante Docker. Correcciones de Composer y prueba de rollback incluidas en dev.7; suite vigente aprobada el 2026-10-08, 13:40:03 UTC (exit_code=0).
- Aplicación local disponible en http://localhost:8080 con override de desarrollo; conserva volúmenes. Las vistas temporales con fixtures de turnos anteriores no son el servidor de entrega.
- Verificado en imagen objetivo: sintaxis PHP, 177 comprobaciones de reglas, QR SVG/identificadores y conexión PDO/MySQL. Suite aislada: 152 comprobaciones correctas de migraciones/siembra/integridad, autenticación/permisos HTTP, catálogo, reservas/tickets/cancelación, dashboard/filtros y gestión administrativa.
- Concurrencia de reservas comprobada con dos procesos/conexiones: último cupo y duplicado activo del mismo usuario. Carreras de administración contra reserva aún por ampliar; no confundirlas con esas dos pruebas aprobadas.
- B02 integración aprobada; B03–B06 integración pertinente aprobada, cierre completo pendiente de flujos navegador reales y cobertura restante. API Open-Meteo real comprobada independientemente; en tests se desactiva para mantener aislamiento.
- Windows: scripts preparados; instalación limpia en equipo compatible pendiente. Chrome/Firefox, tableta, persistencia/recreación, respaldo/restauración y URL pública pendientes.
- Dependencias QR locales instaladas en imagen objetivo desde composer.lock; QR privado y propiedad comprobados por HTTP. Código vigente etiquetado v0.1.0-dev.7 → 994ec41361e2cb45bdfe6c40207773e760c21467; sigue siendo versión de desarrollo.
- Publicación: InfinityFree es candidata de investigación; PHP/MySQL real, API, secretos y límites aún no verificados. No se creó cuenta ni URL.

## Próxima acción necesaria

No pedir repetir la misma verificación sin un cambio o fallo que lo justifique: la ejecución vigente terminó correctamente. Continuar con B06/B07: carreras de edición/cancelación administrativa contra reservas, navegador real con datos ficticios, matriz de requisitos y persistencia/instalación limpia. El acceso Docker sigue requiriendo sudo interactivo del usuario; solicitarlo solo cuando haga falta ejecutar nuevas pruebas/reinicios. No modificar permisos para evitarlo.

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
| B02 | Implementado; cierre pendiente | [Modelo de datos](DATOS.md), [evidencia B02](evidencias/B02.md) | Suite MySQL aislada aprobada, 152 checks de integración total; ver B07 |
| B03 | Implementado; cierre pendiente | [Evidencia B03](evidencias/B03.md) | HTTP/login/roles/CSRF aprobados; navegador real pendiente |
| B04 | Implementación parcial | [Evidencia B04](evidencias/B04.md) | QR/HTTP/MySQL y dos carreras aprobados; navegador real pendiente |
| B05 | Implementación parcial | [Evidencia B05](evidencias/B05.md): catálogo/filtros/historial jQuery | API real independiente y catálogo MySQL/HTTP aprobados; navegador real completo pendiente |
| B06 | Implementación parcial | [Evidencia B06](evidencias/B06.md): categorías/eventos/estados | Dashboard/reservas/admin MySQL/HTTP aprobados; carreras admin y navegador pendientes |
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

Versionado del incremento: push confirmado y referencias verificadas con git ls-remote. Rama codex/b05-catalogo y etiqueta v0.1.0-dev.2 apuntan al commit de código bbae88b; main conserva 3da680b. Las 81 comprobaciones de reglas se repitieron correctamente en este turno. Commits documentales posteriores no mueven la etiqueta.

## API meteorológica B05 — 2026-10-08

RF-15/RF-07, RNF-04/05 y SEG-06: Open-Meteo integrado en endpoint propio GET /api/eventos/{slug}/clima, coordenadas solo de evento público MySQL y destino HTTPS fijo. Panel jQuery independiente del formulario; no sesión/token/datos personales enviados al proveedor. Pronóstico diario en Costa Rica, horizonte hoy a +15 días; cancelados/finalizados/sin coordenadas/fuera del alcance no consultan. El proveedor gratuito se usa solo para evaluación no comercial, con atribución CC BY 4.0; revisar antes de otro uso.

Caché privada 1 h; errores con espera 10 min; datos anteriores etiquetados y máximo 6 h; bloqueo no bloqueante y presupuesto compartido 30 llamadas/h y 10/min. HTTP con verificación TLS, sin redirecciones, timeout de lectura 3 s y respuesta limitada a 128 KiB. WEATHER_ENABLED=false permite desactivar; pruebas Docker aisladas fuerzan false. No hay nuevas migraciones ni cuentas/gastos.

Evidencia ejecutada: 34 reglas de clima en PHP temporal 8.3.6, sintaxis PHP/JS/Bash y revisión diff. Consulta REAL HTTPS del cliente PHP a San José/2026-10-10: datos recibidos y guardados en /tmp/eventify-weather-live-result.json, luego visibles en panel real renderizado con fixture de evento sin MySQL. Navegador in-app: pronóstico/atribución, fallo simulado con botón reserva habilitado y fecha lejana sin datos correctos. Móvil 390×844 sin desbordamiento, consola sin errores. Capturas /tmp/eventify-weather-desktop.png, failure.png y mobile.png. Esta evidencia no equivale a imagen PHP 8.4 ni evento leído de MySQL. Total de reglas: 115, repetidas correctamente en este turno. Compose normal/test/dev válidos con variables ficticias y --env-file /dev/null, sin leer secretos ni requerir daemon.

storage/verification-status.txt sigue ausente al comprobar; mantener pendientes de integración y no cerrar B04/B05. Próximo: ejecutar verify-dev-linux.sh, comprobar flujo real y continuar B06 administración. Push confirmado: codex/b05-clima y etiqueta v0.1.0-dev.3 → 9ad4df4; git ls-remote comprobado. main sigue 3da680b. Commits documentales posteriores no mueven la etiqueta.

## Administración B06 parcial — 2026-10-08

RF-17/18/19/21, RF-03 y RNF-01/03/04: creación/consulta/edición/desactivación de categorías, creación/consulta/edición de eventos y cancelación/finalización separadas. Control ADMINISTRADOR y CSRF en servidor en todas las acciones; rutas de estado solo POST. Precio decimal exacto/cero, capacidad positiva, fecha Costa Rica con segundos conservados, coordenadas opcionales validadas y galería local de imágenes con lista permitida. Listas paginadas de 50. Sin nuevos uploads ni servicios externos.

Decisiones de protección: no eliminación física; categoría desactivada conserva eventos y no se ofrece para nuevos; eventos cancelados/finalizados son históricos y no reactivables por edición. Slug de evento inmutable; fecha y retorno a borrador bloqueados cuando hay cualquier reserva histórica. Aforo comprobado contra cantidades CONFIRMADAS y estado coordinado con fila del evento FOR UPDATE, igual que reservar/cancelar. Precio histórico de reservas no se modifica. Creación/edición con fecha futura; finalizar solo tras el inicio. Son decisiones adicionales, no todas exigencias literales del PDF.

Verificado: 37 reglas de AdminInput correctas (total 152), sintaxis PHP completa, Bash y diff correctos. Vista real con fixtures sin MySQL: formularios/eventos/categorías, galería, escritorio 1280×900 (dos columnas) y móvil 390×844 (una), ancho 1265/375 sin exceder viewport, consola sin errores. Capturas temporales /tmp/eventify-admin-desktop.png, mobile.png y categories.png. No se guardaron eventos desde estas fixtures ni se verificó autorización real en ellas.

Pruebas MySQL/HTTP ampliadas para crear/editar, reducir aforo, preservar precio/fecha/historial, desactivar categoría, cancelar/finalizar e idempotencia, permiso directo, CSRF y escape. Preparadas sin ejecutar. Carreras admin contra reserva todavía requieren ampliación/verificación. Dashboard y consulta administrativa de reservas pendientes. No cerrar B06 ni declarar estable. storage/verification-status.txt sigue ausente; activación Docker necesita terminal del usuario con sudo. Sin migraciones ni borrado de datos.

Push confirmado y git ls-remote verificado: rama codex/b06-admin y etiqueta v0.1.0-dev.4 → 779d4e9. main conserva 3da680b. Las 152 comprobaciones de reglas se repitieron correctamente en este turno con PHP temporal 8.3.6. Commits documentales posteriores conservan la etiqueta.

## Dashboard y consulta de reservas B06 — 2026-10-08

RF-16/20, D-03 y RNF-01/03/04: cinco indicadores base, tres derivados (evento/categoría con más entradas e ingresos potenciales). Un SELECT MySQL con CTE reúne todos los indicadores en el mismo snapshot e instante UTC: activos PUBLICADO/futuros, reservas CONFIRMADAS de esos eventos, ocupación ponderada suma entradas/suma capacidad. Cero agregado produce 0,00 %. Total eventos incluye todos los conservados. Rankings por entradas, empates señalados y menor ID determinista; categorías desactivadas con actividad histórica siguen contando. Potencial suma el total histórico de reservas activas y se etiqueta expresamente como distinto de pagos recibidos.

/admin/reservas permite filtros combinados de nombre de evento, nombre/correo de usuario y estado; LIKE con comodines literales y consultas preparadas. 50 por página y orden fecha/ID descendentes; detalle /admin/reservas/{id} conserva precio, total, estado, fecha y código ticket. Consultas solo GET/HEAD, ADMINISTRADOR recién verificado en servidor, no-store. No se añade edición ni se amplía acceso a QR privado de otros usuarios. Sin migraciones ni cambios de datos existentes.

Ejecución real: 25 unidades nuevas, total 177 de reglas correctas con PHP temporal 8.3.6; sintaxis PHP completa/Bash y git diff --check correctos. Navegador Chromium in-app con fixture fuera del repositorio y datos ficticios: dashboard, envío/preservación de filtros y detalle, escritorio 1280×900 y móvil 390×844; anchos observados 1265/375 sin desbordamiento, consola sin errores. Capturas /tmp/eventify-dashboard-desktop.png y mobile.png, admin-reservations-mobile.png y admin-reservation-detail.png. La fixture verifica vistas, no filtros SQL, permisos ni persistencia.

Prueba aislada preparada antes de siembra: dashboard vacío, seis eventos en estados/fechas diferentes, 21 entradas/202 espacios = 10,40 %, tres reservas activas y ₡2.200,00 histórico con precio actual diferente; empates, filtros literales/correo, detalle, 53 canceladas paginadas 50+3 y rollback de fixtures. HTTP ampliado para rechazar rol usuario, filtrar/consultar como administrador, filtro inválido, inexistente y POST prohibido. Estas pruebas MySQL/HTTP NO ejecutadas. sudo -n docker info volvió a requerir contraseña; storage/verification-status.txt ausente. Mantener B06 abierto, Windows/URL pública y concurrencia pendientes. Próximo: integración Docker actualizada y carreras administrativas; comprobar luego instalación/persistencia.

Push confirmado y referencias verificadas con git ls-remote: codex/b06-dashboard y v0.1.0-dev.5 → b5bc7846bb5580b0c6c09621c34b340687e9e3e8. main conserva 3da680b74b41fa3da4069879edc1f649de9b97f2. Etiqueta de desarrollo, sin ejecutar CI ni desplegar. Commits documentales posteriores no mueven la etiqueta.

## Corrección de construcción — 2026-10-08, dev.6

El usuario ejecutó verify-dev-linux.sh; resultado REAL storage/verification-status.txt: inicio 2026-10-08T13:31:03Z, exit_code=1. Log: Composer 2.10.3 descargado con checksum correcto; instalación detenida al extraer dasprid/enum porque la imagen carece de ext-zip y unzip/7z. Falló durante build, antes del arranque/migraciones/pruebas de esta ejecución; no equivale al fallo HTTP anterior ni a prueba aprobada. El aviso buildx/Bake no fue la causa fatal.

Corrección Dockerfile: apt-get install --no-install-recommends unzip solo dentro de la capa de construcción, Composer conserva lock/checksum/plugins/scripts deshabilitados/plataforma, purge de unzip y limpieza de índices apt al terminar. No instalar paquetes en host ni ampliar permisos Docker; no modificar base/volúmenes. Objetivo RT-06/U-01: desbloquear reconstrucción reproducible del stack con dependencias QR.

Verificado después de editar: Compose normal/dev y aislado válidos con variables ficticias y --env-file /dev/null; Bash -n verify-dev-linux.sh y git diff --check correctos. No repetir unidades sin cambios PHP ni afirmar build corregido hasta ejecutarlo. sudo -n todavía requiere contraseña; se pidió repetir el script en terminal del usuario. B02–B06, imagen objetivo y B07 permanecen abiertos.

Push de corrección confirmado y git ls-remote verificado: codex/b07-build-fix y v0.1.0-dev.6 → 5fe196b6fa0bf398f68504a61019c1e334b91f4b. main conserva 3da680b. No se ejecutó CI ni se desplegó; etiqueta conserva el código y este registro documental no la mueve.

## Integración Docker real y corrección de prueba — 2026-10-08, dev.7

Nueva ejecución REAL del usuario: storage/verification-status.txt inicio 2026-10-08T13:34:51Z, exit_code=1. Esta vez build dev.6 completó, Composer/QR instalados, arranque/migraciones completados, 177 comprobaciones de reglas y generación/validación QR correctas en imagen objetivo; sintaxis/configuración/conexión MySQL correctas. La suite MySQL aislada llegó hasta reservations.php, después de los datos conocidos de admin-read.php, migraciones/demo/integridad/auth, catalog.php y http-auth.php. Por el orden de ejecución y ausencia de excepciones anteriores, esas comprobaciones completaron, pero la suite completa NO está aprobada.

Error registrado: «Evento no reservable: cocina-agotado (PDOException)». La etiqueta era la última aserción exitosa, no necesariamente la operación fallida. Siguiente instrucción: CREATE TRIGGER fail_ticket. Hipótesis fundada: privilegios globales exigidos por binlog activo (MySQL 8.4); log anterior no guardó SQLSTATE/código, por lo que no afirmar diagnóstico exacto. Referencia oficial https://dev.mysql.com/doc/refman/8.4/en/replication-options-binary-log.html. Avisos de inicialización temporal MySQL no son por sí mismos el fallo.

Corrección de la prueba: solo APP_ENV=test/DB_DATABASE=eventify_test, renombrar temporalmente tickets a isolated_ticket_storage conservando tabla/datos, provocar fallo 1146 al crear ticket y restaurar en finally. Verificar rollback, transacción cerrada y recuentos de reservas/tickets intactos. Sin nuevas facultades globales ni cambios del servicio de reservas, base local o volúmenes. Diagnóstico seguro con etiqueta de fase, SQLSTATE validado, código numérico y cantidad de checks anteriores; sin imprimir mensajes/consultas/trazas/credenciales.

Verificado ahora: sintaxis de ambos PHP modificados y diff correctos; nueve comprobaciones HTTP de tests/smoke_http.py contra stack REAL http://127.0.0.1:8080 correctas (salud 200, rutas privadas de archivos 404, POST salud 405). Repetición de integración/carreras/servicio administrativo pendiente; no cerrar B02–B07. Docker sigue requiriendo sudo interactivo; no alterar permisos. Próximo: repetir verify-dev-linux.sh, examinar códigos reales si falla y continuar revisión navegador/persistencia.

Push confirmado y git ls-remote verificado: codex/b07-integration-fix y v0.1.0-dev.7 → 994ec41361e2cb45bdfe6c40207773e760c21467; main conserva 3da680b. Commits documentales posteriores no mueven etiqueta; sin CI ni despliegue.

## Suite vigente aprobada — 2026-10-08

Evidencia leída directamente: storage/verification-status.txt inicio 2026-10-08T13:40:03Z, exit_code=0; verification.log termina «Actualización y verificaciones de desarrollo terminadas correctamente». 177 reglas correctas, QR generación/identificadores y 152 checks MySQL/HTTP correctos. Build, arranque/migraciones y salud completaron. Código sin modificaciones locales al recibir resultado: dev.7, etiqueta 994ec41, HEAD documental d696a66.

Los fallos anteriores se conservan como historial, pero sus pendientes de reconstrucción/integración quedan supersedidos por esta ejecución. CREATE TRIGGER fue sustituido por fallo de tabla aislada/restauración; rollback conservó reservas/tickets y pasaron ambos procesos de carrera de reservas. AdminReadRepository probó el dataset conocido/empates/filtros/paginación y AdminService CRUD/aforo/precio/historial/estados. No se publicó log privado ni credenciales.

Revisión documentada en evidencias/B07.md con límites reales. No marcar versión estable ni entrega completa: persisten carreras administrativas, flujos navegador reales Chrome/Firefox/tableta, instalación limpia Linux/Windows, persistencia/exportación/restauración, URL pública/PDF/defensa. No es necesario ejecutar nada nuevo del usuario por este registro documental.
