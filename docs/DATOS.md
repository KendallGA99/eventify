# Modelo y operación de datos

El modelo y los flujos de autenticación, reservas, tickets y administración están implementados. La imagen MySQL dev.38 aprobó 225 comprobaciones MySQL/HTTP; la persistencia y restauración conservan sus referencias propias en [ESTADO.md](ESTADO.md). Requisitos afectados: RF-01–RF-13, RF-17–RF-21 y RNF-03/RNF-04. Esta guía describe la operación local; no certifica Windows ni la entrega final.

## Relaciones

```mermaid
erDiagram
    roles ||--o{ users : define
    categories ||--o{ events : clasifica
    users ||--o{ reservations : realiza
    events ||--o{ reservations : recibe
    reservations ||--o| tickets : genera
```

Las seis tablas de negocio usan InnoDB, claves primarias autoincrementales y claves foráneas con eliminación restringida. `schema_migrations`, `auth_limits`, `demo_seed_runs` y `weather_budget` registran operaciones técnicas; son diez tablas en total. No hay eliminaciones en cascada del historial.

## Diccionario

| Tabla | Campos y reglas relevantes |
| --- | --- |
| roles | `id` TINYINT; `code` ASCII único y nombre. Roles ADMINISTRADOR/USUARIO creados en migración. |
| users | `id` BIGINT; `role_id`; `full_name` 150; `email` 254 único sin distinguir mayúsculas; `password_hash` 255; `is_active`; fechas de creación/actualización. |
| categories | `id` BIGINT; nombre 120 y slug ASCII 120 únicos; indicador activo y fechas. |
| events | `id`, categoría; slug ASCII único 160; nombre 180; descripción TEXT; ruta de imagen 255; inicio DATETIME; ubicación 255; coordenadas DECIMAL(9,6) opcionales con límites; capacidad INT positiva; precio DECIMAL(12,2) no negativo; estado y fechas. |
| reservations | `id`, usuario/evento; código ASCII único de 32 caracteres; cantidad INT positiva; precio unitario histórico DECIMAL(12,2); total DECIMAL(22,2), igual a precio × cantidad; estado; fecha de cancelación y auditoría temporal. |
| tickets | `id`; reserva única; código ASCII único de 64 caracteres; creación. Un ticket representa la reserva y su cantidad; la vista y el QR se generan al consultar el ticket, sin almacenar una copia visual redundante. |
| schema_migrations | Versión, SHA-256, estado EN_PROCESO/APLICADA, número de sentencias ejecutadas, inicio/finalización. |
| auth_limits | Migración 002: clave HMAC de acción/IP, inicio de ventana y contador; protección persistente de intentos. |
| demo_seed_runs | Identificador de demostración aplicada y fecha; evita sembrar de nuevo. |
| weather_budget | Migración003: ventanas de minuto/hora y contadores compartidos para limitar consultas al proveedor meteorológico. |

El esquema de negocio está en `database/migrations/001_initial_schema.php`;002_auth_limits.php y003_weather_budget.php incorporan límites de autenticación y clima. Los campos son obligatorios salvo coordenadas, fecha de cancelación y fecha de finalización técnica. Fechas DATETIME en UTC; presentación en America/Costa_Rica. Los importes se guardan como decimales, sin números flotantes para cálculos monetarios de negocio.

## Decisiones y límites

- Evento: BORRADOR, PUBLICADO, CANCELADO o FINALIZADO. Reserva: CONFIRMADA o CANCELADA. Ticket válido se deriva de su reserva y evento; no duplicar un estado que pueda desincronizarse.
- Una reserva confirmada por usuario/evento. La columna generada `active_event_id` y el índice único permiten conservar varias cancelaciones históricas y volver a reservar después de cancelar.
- Cupos disponibles = capacidad menos cantidades confirmadas. Canceladas no ocupan. Últimos espacios cuando disponibles ≤ 20% de capacidad; con cero se presenta agotado.
- Cancelar exige más de 86.400 segundos hasta el inicio; exactamente 24 horas no basta. La política usa instantes, independientemente de la zona horaria.
- La base impide cantidades/precios/totales incoherentes, referencias inexistentes y duplicidad activa. ReservationService confirma dentro de una transacción y bloquea la fila del evento antes de revalidar aforo, fecha y estado. Las restricciones del esquema complementan ese bloqueo; no sustituyen la comprobación del total ocupado.
- Índices de catálogo por estado/fecha y categoría/fecha; ocupación por evento/estado; historial por usuario/fecha. No guardar contadores de disponibilidad redundantes.

## Migraciones y arranque

`start-linux.sh` y `start-windows.ps1` esperan MySQL y ejecutan `php bin/migrate.php`. Repetir el arranque conserva los datos y omite migraciones ya aplicadas con la misma firma. Los archivos aplicados son inmutables; toda modificación futura usa una nueva versión.

MySQL confirma DDL implícitamente. El ejecutor registra progreso y utiliza un bloqueo de aplicación para evitar dos migraciones simultáneas. Si falla una sentencia, conserva el estado EN_PROCESO y bloquea el reintento automático. No se garantiza un rollback global de todas las tablas.

Ante fallo: detener cambios, conservar registros, respaldar la base y revisar las sentencias aplicadas frente al esquema real. Preparar una reparación específica y revisar antes de actualizar el registro técnico. No borrar tablas/volúmenes ni marcar APLICADA sin comprobar el resultado. No existe un reset automático.

## Demostración opcional

Después del arranque, Linux: `bash scripts/demo-linux.sh`. Windows: `powershell -ExecutionPolicy Bypass -File scripts/demo-windows.ps1`.

Inserta tres cuentas ficticias, cuatro categorías, ocho eventos y siete reservas con tickets. Incluye evento gratuito, últimos espacios, agotado, borrador, cancelado, finalizado y próximo a iniciar. Fechas relativas a la primera ejecución; repetir no las mueve. Es una transacción y no modifica datos existentes; un conflicto aborta la operación.

Contraseñas aleatorias: hashes en MySQL y credenciales de evaluación en `/var/www/html/storage/demo-credentials.json`, dentro del volumen privado, fuera de la raíz pública y de Git. El comando no imprime claves. Se permite solo APP_ENV local/test. No es un mecanismo de creación de administradores públicos. Las cuentas se consultan por el procedimiento privado de la siguiente sección.

Si existe un archivo de credenciales sin marca en la base, el sembrador se detiene para revisión. No lo sobrescribe. Si se pierde el archivo tras una siembra exitosa, las cuentas no se recrean ni se cambian sus claves automáticamente.

## Acceso a las cuentas de demostración

El arranque normal no crea estas cuentas. Ejecute primero la demostración explícita de la sección anterior. Cada instalación genera contraseñas distintas; no existe una clave universal para admin@eventify.test o usuario@eventify.test. Una cuenta registrada desde la web tiene rol de usuario y no sustituye al administrador de prueba.

Desde la raíz de su instalación Linux, puede copiar las credenciales a un archivo privado del propio proyecto:

```bash
umask 077
docker compose exec -T web cat storage/demo-credentials.json > storage/demo-credentials.json
```

Si Docker exige sudo, anteponga sudo al comando docker en su terminal; la redirección debe quedar fuera de sudo para crear el archivo con su usuario. Abra storage/demo-credentials.json con un editor local y use la entrada del rol necesario. No pegue su contenido en chats, logs o documentación. El archivo está excluido de Git y del paquete fuente.

En Windows PowerShell, con Docker Desktop abierto:

```powershell
docker compose cp web:/var/www/html/storage/demo-credentials.json .\storage\demo-credentials.json
```

Conserve la copia en una carpeta privada de su perfil y ábrala localmente. El comando corresponde a la instalación Compose de la carpeta actual; no apunta al entorno público ni a otra instalación. Si inició un proyecto con nombre explícito, utilice ese mismo nombre con docker compose -p NOMBRE en ambos sistemas. Los ensayos QA y copia limpia guardan sus propias credenciales en la ruta que informa cada script.

Si la demostración aún no se ejecutó, el archivo no existirá. Un error de copia no autoriza a volver a crear usuarios o cambiar contraseñas. La recuperación y la transferencia de datos se tratan por separado en [RESPALDO.md](RESPALDO.md).

## Pruebas aisladas

Linux: `bash scripts/test-database-linux.sh`. Windows: `powershell -ExecutionPolicy Bypass -File scripts/test-database-windows.ps1`.

Usan `docker-compose.test.yml`, proyecto `eventify-tests`, base `eventify_test` en memoria temporal, sin puertos ni volúmenes de la aplicación. Al finalizar se retiran únicamente sus contenedores y red; los datos de prueba son efímeros. No ejecutarlas contra una base de negocio.

La suite verifica migraciones repetibles, siembra no destructiva, hashes, cupos derivados, restricciones, historial cancelado, bloqueo de semillas en producción y detección de migraciones alteradas/incompletas. La suite también ejecuta comprobaciones HTTP de permisos, propiedad y transacciones/carreras; cada resultado corresponde a la referencia de código registrada. No sustituye navegador, persistencia de entrega o Windows.

## Edición administrativa

No cambia esquema. Evento actualizado bajo bloqueo FOR UPDATE: capacidad >= entradas confirmadas; slug se conserva. Cualquier historial de reservas fija fecha y prohíbe volver a BORRADOR. Cancelados/finalizados conservan datos y no se reactivan. Cancelar evento no cancela individualmente sus reservas: tickets muestran invalidez por estado del evento, con historial/precio conservados. Finalización explícita solo después del inicio; el catálogo ya trata PUBLICADO pasado como finalizado sin modificar fila automáticamente. Categoría desactivada conserva referencias; no se asigna a un nuevo evento. Precio nuevo no actualiza unit_price/total de reservas. La integración comprueba reglas y carreras administrativas; consulte sus referencias en ESTADO y evidencias/B04.md. La aceptación de navegador conserva sus límites propios.
