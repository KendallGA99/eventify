# Historial de versiones de Eventify

## 0.1.0-dev.7 — 2026-10-08

Corrección de integración: prueba de rollback de reserva ante fallo de ticket mediante indisponibilidad temporal de tabla, preservada y restaurada en finally solo en eventify_test. Evita depender de privilegios globales para CREATE TRIGGER. Diagnóstico de fallos con fase, SQLSTATE, código MySQL y cantidad de comprobaciones previas; sin mensajes SQL, parámetros o credenciales.

Construcción dev.6, 177 reglas/QR/conexión aprobadas en ejecución del usuario. Integración avanzó hasta preparación de fallo de ticket y terminó con PDOException; hipótesis de privilegios del trigger, código exacto no registrado por diagnóstico anterior. Nueve comprobaciones HTTP del stack real correctas después de la corrección. Repetición de integración pendiente.

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
