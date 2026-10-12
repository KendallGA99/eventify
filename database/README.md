# Base de datos

MySQL 8.4 utiliza InnoDB, claves foráneas, índices únicos y restricciones para proteger la coherencia de los datos.

## Modelo

Las tablas de negocio son `roles`, `users`, `categories`, `events`, `reservations` y `tickets`. Las tablas técnicas `schema_migrations`, `auth_limits`, `demo_seed_runs` y `weather_budget` registran migraciones, límites de intentos, inicialización y consultas externas.

## Migraciones

- `001_initial_schema.php`: esquema de usuarios, eventos, reservas y tickets.
- `002_auth_limits.php`: límites persistentes de autenticación.
- `003_weather_budget.php`: presupuesto global de consultas meteorológicas.

`MigrationRunner` registra la firma y el progreso de cada migración. El arranque omite las migraciones ya aplicadas. Una sentencia fallida conserva el estado de ejecución y exige revisar el esquema antes de continuar; MySQL no permite suponer un rollback completo de operaciones DDL.

## Reglas de datos

La reserva guarda cantidad, precio unitario histórico, total y estado. Un índice único impide más de una reserva activa por usuario y evento. `ReservationService` bloquea el evento dentro de una transacción y vuelve a comprobar su estado, fecha y ocupación antes de guardar.

Las cancelaciones conservan las filas y los tickets. Las fechas se almacenan en UTC y se muestran en la zona America/Costa_Rica. Los importes se calculan con decimales.

## Inicialización y persistencia

`ExampleInstaller` prepara ocho eventos, cinco categorías y cuentas con claves únicas cuando la base local está vacía. Un marcador, un bloqueo y una transacción evitan duplicados. El arranque posterior conserva cuentas, eventos y reservas existentes. El servidor público no crea estas cuentas automáticamente.

MySQL y los archivos de la aplicación utilizan volúmenes persistentes separados. Recuperar una versión de código no recupera los datos: un respaldo debe contemplar MySQL y los archivos guardados.
