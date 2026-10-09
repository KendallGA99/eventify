# Historial de versiones de Eventify

## 0.1.0-dev.37 — Inicio, perfil y experiencia de uso (B07/RF-01/02/03/RNF-01/02)

- Mostrar/ocultar cada contraseña con control accesible; ingreso vuelve al inicio y navegación distingue visitante, usuario y administrador.
- Perfil permite actualizar el nombre con validación/CSRF y propietario de sesión; correo y rol se conservan. Inicio sin avisos de desarrollo; accesos a reservas, navegación consistente y singular/plural corregidos.
- Efectos suaves y preferencia de movimiento reducido; pruebas de integración ampliadas para perfil y redirección. Estado real de ejecución en ESTADO.

## 0.1.0-dev.36 — Presentación de errores (B07/RNF-01/SEG-03)

- Da estilo a formularios vencidos, acciones no permitidas y fallos generales de páginas HTML. Conserva códigos de error, controles CSRF/roles y formatos técnicos de API/consola.
- Añade prueba de escape de título, mensaje y referencia; siete comprobaciones correctas. Sintaxis y seis respuestas HTTP verificadas con PHP CLI extraído de paquete oficial Ubuntu, sin instalarlo en el host. Integración MySQL de esta versión pendiente.
- Captura humana confirma la página de acceso restringido dev.35 en Firefox al110%; no certifica dev.36 o zoom200%.

## 0.1.0-dev.35 — Página de acceso restringido (B07/RF-03/RNF-01)

- Sustituye el texto aislado de rechazo administrativo por una página con la identidad visual de Eventify, explicación y enlaces a eventos, cuenta e historial.
- Conserva la validación de rol en servidor, el código HTTP403 y la salida antes de consultar datos administrativos. Sin migraciones ni cambios de reservas.
- Versión de desarrollo; las pruebas anteriores corresponden a sus referencias y la instalación existente conserva su versión hasta actualizarla.

## 0.1.0-dev.34 — Runtime sin herramientas de construcción (B07/SEG-09)

- Incorpora en ambos Dockerfile la limpieza ensayada: 32 paquetes retirados, versiones conservadas sin cambios. Las imágenes derivadas aprobaron 266 unidades y 219 comprobaciones MySQL cada una.
- Escaneo de ambas variantes: 2.011 → 424 registros (1 crítico, 91 altos, 162 medios, 166 bajos, 4 sin clasificación), sin pares nuevos ni exclusiones. Retirar cabeceras no corrige el kernel del host.
- Imágenes finales, unidades, rechazos de configuración y arranque público/TLS aprobados sobre 6d41df5; escaneos finales idénticos al ensayo. No desplegada. Dev.33 y sus paquetes mantienen su evidencia.

## Ensayo de reducción de herramientas (B07/SEG-09)

- Añade construcción experimental derivada de las imágenes local y pública: retira herramientas de desarrollo sin autoremove y comprueba que PHP/PDO, Apache y bibliotecas necesarias permanezcan.
- Prepara unidades e integración MySQL en un proyecto efímero, sin puertos ni volúmenes persistentes, con claves propias. No cambia imágenes de entrega, VERSION o instalaciones existentes; construcción real pendiente.

## 0.1.0-dev.33 — Revisión de imágenes (B07/SEG-09)

- Prepara una imagen derivada de MySQL con gawk 5.1.0-6.el9_8.1 de Oracle; conserva servidor, entrypoint y digest de base. Construcción, 219 controles y ensayos TLS aprobados; escaneo elimina tres avisos gawk sin añadir identificadores.
- Aplica esa construcción a Compose local y ensayos; añade prueba aislada de versión, integración y controles TLS, sin actualizar instalaciones existentes.
- Contrasta el binario gosu oficial con govulncheck: cero avisos a nivel de símbolos; conserva las coincidencias de módulo y sus límites.
- Amplía revisión de ACL, ncurses, montajes, XML y Python; añade inventario completo por paquete sin exclusiones.
- Omite la fecha de entrega por indicación del usuario en el documento técnico y portada.

- Registra instalación limpia Linux de la fuente f4d6c5a y persistencia posterior, con diez tablas y cinco archivos conservados. Windows y Firefox/zoom siguen pendientes.
- Actualiza manual, lista de cierre y PDF técnico; añade inventario LOW con límites explícitos, sin exclusiones ni certificación estable.

## 0.1.0-dev.32 — Portada y cierre documental (B09)

- Incorpora los nombres de estudiante y docente confirmados por el usuario; fecha de entrega por confirmar.
- Corrige la guía generada del paquete: la API pública ya tiene evidencia; Windows, Firefox y criterios finales siguen pendientes.
- Permite elegir puerto en el ensayo de copia limpia para conservar las instalaciones anteriores. Extracción y validación revisadas; nueva ejecución Docker pendiente.
- Registra despliegue público dev.30, persistencia de diez tablas y81 controles HTTPS posteriores correctos.
- Registra revisión de teclado y redistribución en IAB. Sin cambio de aplicación respecto a dev.30 ni nuevas migraciones. No es una versión estable.

## 0.1.0-dev.31 — 2026-10-08

B09: revisión editorial de las guías, requisitos y registros. Redacción centrada en el proyecto, instrucciones desde su carpeta raíz y conservación de commits, huellas, pruebas y limitaciones. Documento técnico actualizado a esta referencia. No cambia la aplicación, los contenedores activos ni los datos.

## 0.1.0-dev.30 — 2026-10-08

B07: corrige los nombres de imagen del ensayo aislado. mktemp generó mayúsculas y Docker rechazó el tag antes de construir; ahora se normaliza solo el nombre de imagen a minúsculas, conservando el directorio privado original. Bash y tres casos de formato comprobados, incluido el identificador que falló. Parche xz/liblzma de dev.29 intacto; construcción y escaneo posteriores aún pendientes.

## 0.1.0-dev.29 — 2026-10-08

B07/SEG-09: fija la corrección Debian de liblzma5/xz-utils en ambas imágenes PHP. El escaneo de la imagen real dev.28 confirmó que todavía usa la versión anterior; dependencias Composer sin avisos en ese informe. Nuevo ensayo construye ambas variantes, verifica versiones y ejecuta sintaxis/unidades sin red, bases, puertos ni montajes; exporta imágenes para escaneo posterior. Construcción y corrección efectiva todavía pendientes de ejecución Docker. No modifica MySQL, datos ni publicación; persisten otros avisos por evaluar.

## 0.1.0-dev.28 — 2026-10-08

B09: documento técnico actualizado con publicación, TLS, permisos, persistencia y controles HTTPS realmente comprobados. Mantiene pendientes de API pública, Windows, Firefox y defensa. Generador PDF usa VERSION en portada/cabecera para evitar referencias antiguas. Paquete conserva exportación por commit e instrucciones de entrega local independiente. Sin cambios funcionales ni de base de datos.

## 0.1.0-dev.27 — 2026-10-08

B07/B08: incorpora una verificación HTTPS reproducible con cuentas ficticias privadas. Comprueba cookies, CSRF, sesiones, permisos y propiedad de tickets sin crear datos de negocio. Ejecución contra la web dev.26: 81 controles correctos. Revisados 505 blobs del historial: no contienen los secretos privados conocidos ni rutas privadas prohibidas. No cambia la aplicación desplegada ni certifica una revisión completa de vulnerabilidades.

## 0.1.0-dev.26 — 2026-10-08

B08/RF-15/RNF-04/B09: documentación de entrega actualizada y comentarios orientados a explicar decisiones. Cliente meteorológico más legible y diagnóstico limitado a motivo/código HTTP, sin URL, coordenadas o contenido de la respuesta. Mantiene TLS, destino fijo, timeout, límite y fallback. Imagen pública comprueba sintaxis y pruebas existentes de clima/presupuesto durante la construcción. Render publicó cabd6f3 y pasó sintaxis más 39 comprobaciones de clima y 44 de presupuesto. El diagnóstico registra HTTP 429 del proveedor; el pronóstico público sigue pendiente y las reservas continúan disponibles.

## 0.1.0-dev.25 — 2026-10-08

B08/RNF-03/04: Render construyó dev.24 y migró con usuario limitado, pero health HTTP503. Arranque público prepara una copia privada de la CA para www-data fuera del DocumentRoot (directorio700/archivo600); conserva verificación de CA/hostname y montaje original. Comprueba preflight y conexión real con la identidad del trabajador antes de servir; falla de forma segura si ese usuario no puede conectar. Sintaxis Bash y diff comprobados; despliegue real y verificación HTTP pendientes. No modifica datos ni aplica nuevas migraciones.

## 0.1.0-dev.24 — 2026-10-08

B08: corrige la evaluación del rechazo de arranque sin TLS. El ensayo dev.23 construyó la imagen, pasó sintaxis PHP 8.4 y confirmó rechazo de Database/base vacía, pero terminó exit 1 en la expectativa del código de Docker Compose. Log de arranque muestra preflight/Apache válidos y fallo de conexión antes de migrar; código exacto del wrapper no quedó registrado, ni hubo probe final.

El job se conserva hasta inspeccionar State de Docker: exige exited/no Running/ExitCode 1/sin OOM ni Error y mensajes previstos, sin migraciones ni Apache sirviendo. Registra por separado el código de Compose; limpieza exclusiva por nombre/etiqueta del proyecto propio. Ocho controles del evaluador y Bash/diff correctos; nuevo ensayo Docker pendiente. No cambia ni debilita TLS de la aplicación.

## 0.1.0-dev.23 — 2026-10-08

B08/RNF-03/04: prueba específica de MySQL 8.4 sin TLS, proyecto/red internos propios, base tmpfs vacía y sin puertos ni volúmenes persistentes. Control PDO por TCP confirma servidor disponible sin cifrado; Database production debe rechazarlo. Arranque público debe salir con error de migración antes de servir; nueva conexión de control confirma base vacía. Sin cambios de controles, reglas ni esquema.

Compose config real sin daemon y sintaxis Bash/diff correctos; opciones MySQL 8.4 contrastadas con manual oficial (tls_version vacío, no opciones eliminadas skip-ssl/have_ssl). PHP 8.4/MySQL y arranque negativos aún pendientes de ejecución: entorno de verificación sin acceso Docker/sudo no interactivo. Script test-public-no-tls-linux.sh registra resultado privado y retira únicamente recursos efímeros del ensayo. No certifica proveedor/HTTPS/URL pública ni entrega estable.

## 0.1.0-dev.22 — 2026-10-08

B08/ENT-D/RNF-03: plan privado con tres cuentas ficticias/claves independientes, CLI explícita production/TLS y tablausersvacía, transacciónSERIALIZABLE/GET_LOCK, hashesPASSWORD_DEFAULT. Rechaza repetición/usuariosprevios/planesinválidos; no UPDATE, seederauto, endpoint ni logs de claves. DemoSeeder sigue bloqueado enproduction. Plan montado solo en jobCLI, no servicio web.

Generador real probado: claves únicas, roles, permisos700/600 desde creación y no sobrescritura; Bash/diff correctos. Ensayo TLS aislado ampliado a CLI/roles/hashes/rechazos y conservación tras recrearweb; ejecuciónPHP8.4/MySQL pendiente. No crea cuentas externas por implementar ni entrega claves públicamente.

## 0.1.0-dev.21 — 2026-10-08

B08/RNF-03/RT-09: ensayo público TLS aislado con PHP8.4/MySQL8.4, certificados ficticios de2 días generados por ejecución, red interna/sin puertos y DBtmpfs. Verifica arranqueproduction/migraciones/ApacheHTTPinterno, CA ajena/hostname incorrecto rechazados por PDO, recreación web. Solo recursos del ensayo efímero; sin servicios externos ni bases existentes.

OpenSSL real confirmó CA/nombre válidos y dos negativos; permisos privados de claves/directorio correctos; Compose config efectivo (sin daemon) y Bash/diff correctos. Ejecución PHP/MySQL/TLS real pendiente; no certifica conexión externa, HTTPS público o entrega. Comando test-public-tls-linux.sh requiere Docker en terminal.

## 0.1.0-dev.20 — 2026-10-08

B08/RT-09/RNF-03/04: Dockerfile.public separado del Compose local; puerto runtime validado (10000 por defecto), preflight exigeproduction/configuración explícita/secreto mínimo32 caracteres/TLS verificado. Apache configtest y migraciones coordinadas preceden a servir; cualquier fallo detiene arranque. Sin seeder, import/reset o endpoint instalador. Misma imagen PHP/dependencias fijadas que Dockerfile local; revisar ambas al actualizar.

25 comprobaciones de orquestación simulada correctas; sintaxis Bash/diff correctos. scripts/test-public-linux.sh prepara build/sintaxis PHP8.4 y tres rechazos reales sin red/volúmenes/puertos ni claves reales; ejecución Docker pendiente. PHP temporal anterior ya no está disponible y no se afirma lintPHP nuevo. Arranque válido/TLS externo/aprovisionamiento/URL pública pendientes.

## 0.1.0-dev.19 — 2026-10-08

B07/U-01/U-03: verify-existing-linux.sh comprueba destinos de ensayo existentes, proyecto/base/puerto/montajes y propiedad de volúmenes antes de mutar. Compara snapshots de copia limpia alrededor de una recreación posterior al flujo de navegador y arranca la restaurada sin reimportar/sembrar/migrar/reconstruir. Conserva volúmenes y deja ambos destinos para navegador, con registros/credenciales privados. Bash/diff y ocho guardas simuladas correctos; ejecución Docker nueva pendiente. Dev.18 ya aprobó integración real: 219 comprobaciones, incluidas dos carreras meteorológicas.

## 0.1.0-dev.18 — 2026-10-08

B07/RF-15/RNF-04: agrega dos carreras de presupuesto meteorológico en MySQL aislado. Dos procesos PHP con conexiones independientes compiten por el último permiso del minuto y de la hora; la prueba retiene la fila hasta que ambos intentan consumir, comprueba un permiso/una denegación, contadores persistidos y denegación posterior. Restaura la fixture y no realiza llamadas externas. Sin cambios en reglas, esquema o datos de aplicación.

Sintaxis de los tres archivos afectados, diff y 44 reglas unitarias correctos con PHP8.3 temporal. Ejecución concurrente en MySQL8.4/PHP8.4 pendiente: Docker deniega acceso al entorno de verificación y sudo no interactivo exige contraseña. No sustituye la evidencia anterior de 211 checks por resultados aún no ejecutados.

## 0.1.0-dev.17 — 2026-10-08

B07: corrige permisos de fuente extraída bajo umask077 antes de copiarla a Docker. Código644/directorios755 y ejecutables755; padre privado700 y .env600 conservados. Dev.16 aisló y pasó migraciones/persistencia, pero HTTP500 por fuente ilegible para Apache. Siete verificaciones reales de extracción y nueve de proyecto simulado correctas; arranque HTTP corregido pendiente.

## 0.1.0-dev.16 — 2026-10-08

Corrige aislamiento del ensayo B07: sudo eliminaba COMPOSE_PROJECT_NAME y el arranque dev.15 usaba eventify. start-linux.sh admite --project y pasa -p explícitamente; el ensayo comprueba proyecto/volúmenes/montajes/puerto antes de iniciar y rechaza copias antiguas. Dev.15 no debe usarse para este ensayo. Nueve checks con sudo/Docker simulados aprobados; recuperación normal y nueva ejecución real pendientes. Sin borrado de datos ni cambio de esquema.

## 0.1.0-dev.15 — 2026-10-08

B07/U-01/U-03: ensayo de arranque desde un ZIP Git exacto en ruta con espacios, claves nuevas y proyecto/volúmenes separados. Ejecuta el inicio del manual, siembra explícita y recreación con comparación de persistencia; conserva destino para navegador. Preparado, no ejecutado contra Docker ni Windows. Sin cambios de reglas/esquema.

## Verificación real B07 — 2026-10-08

Dev.14 actualizado y comprobado: 266 unidades y 211 checks MySQL, persistencia/restauración real de diez tablas y archivos, confirmación de reserva/ticket/QR en Chrome y formularios adaptables. Corrige texto histórico de éxito sin cambiar reglas, comparaciones ni esquema. VERSION/etiquetas conservadas; instalación limpia, Windows/Firefox, TLS externo y URL pública pendientes.

## Documentación técnica B09 — 2026-10-08

Fuente editable y generador PDF del documento técnico, referido a código dev.14. Borrador de 14 páginas con arquitectura/ER, navegación/diccionario, 21 RF/7 RNF, historias/aceptación, seguridad/API, despliegue, evidencia y defensa. Render y revisión visual completos; cobertura textual comprobada. Sin cambios de aplicación/VERSION/etiquetas. Autoría, pruebas nuevas, URL, Windows, revisión humana y entrega final permanecen pendientes.

## 0.1.0-dev.14 — 2026-10-08

B09/U-03/ENT-B: exportación limpia desde referencia Git a ZIP de desarrollo con manifiesto/huellas SHA-256 e instrucciones. Rechaza secretos/datos/logs versionados por nombre, enlaces/submódulos, cambios sin commit, referencias incompletas y sobrescritura. No incluye MySQL ni credenciales personales. Receptor no requiere Python/Git; usa Docker según manual.

13 checks de empaquetado correctos en repositorios ficticios; exportación real se registra posteriormente. Sin cambios de aplicación/esquema respecto de dev.13. No certifica copia limpia Linux/Windows, integración, restore ni entrega final.

## 0.1.0-dev.13 — 2026-10-08

B08/RNF-04/RT-09: presupuesto meteorológico global en MySQL, máximo 30 intentos/hora y 10/minuto, consumido dentro de transacción con lock de fila antes de contactar al proveedor. Migración aditiva 003_weather_budget; no modifica migraciones previas ni datos existentes. El controlador usa este presupuesto en todos los entornos; falla cerrado sin llamadas externas ni detalles privados. Caché local conserva su función; reiniciarla no reinicia el presupuesto MySQL.

44 reglas de presupuesto, 39 checks del flujo de clima y 40 de orquestación Docker simulada correctos; sintaxis PHP/Bash/diff correctos. Integración MySQL preparada para conexiones nuevas, límites, reloj atrasado, transacción ajena y almacenamiento caído: no ejecutada. Persistencia/restore QA incluyen la nueva tabla. Migración real, concurrencia del presupuesto, TLS, Docker y Windows pendientes. Código antiguo sin migración 003 no es rollback compatible automáticamente.

## 0.1.0-dev.12 — 2026-10-08

B08/RNF-04/RT-09: conexión PDO con configuración TLS verificada obligatoria en production, CA de archivo fuera de public y comprobación de cifrado negociado antes de usar MySQL. Valida entorno/modo/DSN/CA y rechaza degradación o CA ignorada; local/test conservan configuración interna por defecto. Sin cambios de esquema/datos ni servicios creados.

40 checks de configuración y guardas con PHP 8.3/PDO mysqlnd temporal correctos, sintaxis/diff correctos; incluidos en scripts de pruebas Linux/Windows. PHP 8.4 Docker, regresión de conexión local y negociación TLS con MySQL real pendientes. No marca estable ni certifica despliegue público.

## Preparación documental B08 — 2026-10-08

Propuesta Render PHP Docker + Aiven MySQL Free con fuentes oficiales, límites gratuitos y criterios de publicación. InfinityFree no seleccionada con la evidencia actual de MariaDB. Identificadas adaptaciones pendientes de TLS PDO, sesiones, presupuesto API y aprovisionamiento público; sin cambiar código, VERSION ni etiquetas dev.11. No se crearon servicios ni se publicó la aplicación. ESTADO/ARQUITECTURA/MATRIZ actualizados; revisión documental y diff correctos.

## 0.1.0-dev.11 — 2026-10-08

B07/U-03: ensayo de respaldo/restauración exclusivo de QA. Exporta SQL con mysqldump y dos archivos persistentes del alcance actual; compara recuentos/hashes de nueve tablas y archivos/galería contra destino nuevo y vacío, con claves/proyecto/volúmenes propios. Detiene temporalmente solo web QA para consistencia y la reinicia también ante fallos. No transfiere sesiones/cache/secretos del entorno, no borra datos/volúmenes. No es restauración general sobre instalaciones existentes ni certificación Windows.

verify-qa-linux.sh reúne actualización/persistencia y ensayo en un comando con registro privado. Sintaxis PHP/Bash/Compose y 40 checks de orquestación con Docker simulado aprobados; tres guardas PHP rechazaron entornos/destinos no permitidos. MySQL/Docker real del ensayo y activación dev.10/dev.11 pendientes.

## 0.1.0-dev.10 — 2026-10-08

B07/RF-08/RNF-04: confirmación AJAX con espera máxima de 15 s, incluyendo lectura del JSON. Evita doble submit pendiente; ante timeout, red/JSON inválido o HTTP 5xx indica consultar experiencias y bloquea reenvío desde ese formulario. Interrumpir la espera no cancela una reserva confirmada en MySQL. Errores de negocio permiten corregir datos; éxito conserva navegación al ticket. Sin cambios de backend/esquema.

14 comprobaciones de comportamiento del cliente correctas en Node de desarrollo, sintaxis JS y diff correctos. Activación/verificación en imagen QA pendiente. Administración Chrome real en dev.9: crear categoría/borrador, publicar y encontrar por AJAX, cancelar evento conservando detalle y desactivar categoría conservando evento correctos. Sin modificar datos de la instancia 8080 ni eventos demo anteriores.

## 0.1.0-dev.9 — 2026-10-08

B07: entorno QA separado mediante proyecto Compose eventify-qa, puerto local 8081, base/volúmenes/claves propios y datos ficticios. Script inicializa/migra/siembra explícitamente, compara snapshot y recrea contenedores conservando volúmenes, valida persistencia y deja servidor listo para navegador. Nueve tablas con recuentos y hashes, marcador privado, credenciales e imágenes comparados; no imprime registros/claves.

Sintaxis PHP/Bash, Compose estático y diff correctos. Ejecución real/persistencia QA aprobadas posteriormente (14:02:55 UTC, exit_code=0). Chrome real comprobó búsqueda, login, reserva/ticket/QR, cancelación, dashboard/filtros y ticket responsive; primera respuesta visual de reserva pendiente de diagnóstico. Firefox/Windows/restauración y navegador completo pendientes. La suite anterior mantiene evidencia de 177 reglas/QR y 170 checks de integración. Sin cambiar negocio, base local existente ni ampliar permisos.

## 0.1.0-dev.8 — 2026-10-08

B06/B07: cuatro carreras administrativas preparadas con conexiones/procesos independientes y ambos órdenes de bloqueo del evento: reducción de aforo frente a reserva y cancelación frente a reserva. Instrumentación PDO exclusiva de prueba mantiene el lock real del servicio mientras la otra conexión intenta adquirirlo. Verificar resultados, aforo/estado, tickets e historial previo. No modifica código de aplicación, esquema ni datos locales.

Nuevo verify-integration-linux.sh ejecuta solo integración aislada y guarda resultado/log privados. Sintaxis PHP/Bash y diff correctos; nuevas 18 comprobaciones MySQL aprobadas posteriormente a las 13:52:00 UTC: integración total 170, exit_code=0. La suite dev.7 previa aprobó 177 reglas/QR y 152 checks MySQL/HTTP; las carreras dev.8 tienen su propio registro de integración aprobado.

## 0.1.0-dev.7 — 2026-10-08

Corrección de integración: prueba de rollback de reserva ante fallo de ticket mediante indisponibilidad temporal de tabla, preservada y restaurada en finally solo en eventify_test. Evita depender de privilegios globales para CREATE TRIGGER. Diagnóstico de fallos con fase, SQLSTATE, código MySQL y cantidad de comprobaciones previas; sin mensajes SQL, parámetros o credenciales.

Construcción dev.6, 177 reglas/QR/conexión aprobadas en ejecución del usuario. Integración avanzó hasta preparación de fallo de ticket y terminó con PDOException; hipótesis de privilegios del trigger, código exacto no registrado por diagnóstico anterior. Nueve comprobaciones HTTP del stack real correctas después de la corrección. Repetición aprobada posteriormente a las 13:40:03 UTC: exit_code=0, 177 reglas + QR y 152 comprobaciones MySQL/HTTP. Persistencia/Windows/navegador completo/carreras administrativas siguen pendientes.

## 0.1.0-dev.6 — 2026-10-08

Corrección de construcción: instalar unzip dentro de la imagen mientras Composer extrae dependencias dist y retirarlo al finalizar. Mantener lockfile, checksum de Composer, validación de plataforma y controles de plugins/scripts. No requiere instalar PHP/unzip en el host ni cambia datos/esquema.

La ejecución del usuario registrada a las 13:31:03 UTC terminó con exit_code=1 antes de iniciar los servicios/pruebas: faltaban ext-zip o unzip/7z. Compose normal/dev/test y sintaxis Bash comprobados estáticamente después de la corrección; reconstrucción objetivo pendiente de repetición. Versión de desarrollo.

## 0.1.0-dev.5 — 2026-10-08

B06: dashboard con cinco indicadores base, rankings de evento/categoría e ingresos potenciales históricos, conjunto activo coherente y consulta MySQL única. Consulta administrativa de reservas con filtros combinados por nombre de evento, nombre/correo del usuario y estado; paginación y detalle histórico de solo lectura. Autorización ADMINISTRADOR en servidor. Sin migraciones ni cambios en datos existentes.

Verificado: 25 reglas nuevas (177 total), sintaxis PHP/Bash y vistas reales con fixtures móvil/escritorio. Dataset MySQL y permisos HTTP ampliados y pendientes de ejecución; imagen objetivo, concurrencia administrativa y cierre B06 pendientes. Versión de desarrollo.

## 0.1.0-dev.4 — 2026-10-08

B06 parcial: categorías crear/consultar/editar/desactivar; eventos crear/consultar/editar/cancelar/finalizar; galería local, formularios adaptables y listas paginadas. Permisos/CSRF servidor, precios/fechas/cupos validados, protección de historial y bloqueos compartidos con reservas. No eliminación física ni nuevas migraciones.

Verificado: 37 reglas nuevas (152 total), sintaxis y formularios con fixtures móvil/escritorio. MySQL/HTTP/permisos/carreras preparados o pendientes; dashboard/consulta admin de reservas pendientes. No es versión estable.

## 0.1.0-dev.3 — 2026-10-08

B05: pronóstico diario Open-Meteo en detalle, API útil para evaluación no comercial con atribución. Panel AJAX independiente, datos validados, caché/backoff/límites, TLS/timeout y fallback; horizonte de 16 días respetado. WEATHER_ENABLED=false desactiva, pruebas aisladas sin red externa.

Verificado: 34 pruebas nuevas de clima (115 reglas), consulta PHP HTTPS real y datos visibles en vista con fixture, error y fecha lejana, móvil, sintaxis. MySQL/imagen PHP 8.4 y B04 pendientes; versión de desarrollo, sin cambios de esquema/datos.

## 0.1.0-dev.2 — 2026-10-08

B05 parcial: catálogo con búsqueda, categoría, rango de fechas Costa Rica, cupos, estados y páginas; jQuery 4.0.0 local; actualización autenticada de Mis experiencias tras cancelación. Borradores privados y eventos cancelados/finalizados visibles sin reserva. No cambia esquema ni datos.

Verificado: 23 unidades nuevas (81 de reglas), sintaxis y prueba de interfaz con fixtures aisladas móvil/escritorio. MySQL/HTTP objetivo y API externa pendientes. Versión de desarrollo; no estable.

## 0.1.0-dev.1 — 2026-10-08

Primera versión de desarrollo del código B01–B04: Docker PHP/MySQL, instalación Linux/Windows, migraciones, datos demo opcionales, autenticación/roles/CSRF, reservas/cancelación transaccionales, historial y ticket con QR local.

Verificado: 58 unidades con PHP temporal 8.3, sintaxis, configuración estática, QR decodificado y dependencias auditadas. La versión anterior ejecutó migraciones y unidades en Docker; la suite MySQL/HTTP falló en una comprobación de cookie. Correcciones y ampliaciones pendientes de ejecución en la imagen actualizada. No es una versión estable ni una entrega final.

Pendientes: validar integración/concurrencia/navegador, reinicio/persistencia, jQuery/filtros/API, administración completa, Windows, publicación y entregables académicos.

## Checkpoint B00 — 2026-10-08

Commit inicial `44da548`: documentación de requisitos y construcción por bloques. Se conserva el contenido que ya estaba preparado en el índice. Los commits se crean ahora; no se alteran sus fechas para simular un historial anterior.
