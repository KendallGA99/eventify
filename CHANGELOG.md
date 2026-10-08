# Historial de versiones de Eventify

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
