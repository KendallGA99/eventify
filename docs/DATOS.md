# Modelo de datos y reglas

```text
roles -> users -> reservations -> tickets
categories -> events -> reservations
```

Una categoría agrupa muchos eventos; un usuario realiza muchas reservas; un evento recibe muchas reservas; una reserva tiene como máximo un ticket. Las claves foráneas restringen eliminación y conservan historia. Las diez tablas incluyen seis entidades de negocio y cuatro técnicas. La migración 003 añade weather_budget para limitar las consultas a los proveedores del clima.

### Diccionario y reglas
| Tabla | Campos y reglas relevantes |
| --- | --- |
| roles | `id` TINYINT; `code` ASCII único y nombre. Roles ADMINISTRADOR/USUARIO creados en migración. |
| users | `id` BIGINT; `role_id`; `full_name` 150; `email` 254 único sin distinguir mayúsculas; `password_hash` 255; `is_active`; fechas de creación/actualización. |
| categories | `id` BIGINT; nombre 120 y slug ASCII 120 únicos; indicador activo y fechas. |
| events | `id`, categoría; slug ASCII único 160; nombre 180; descripción TEXT; ruta de imagen 255; inicio DATETIME; ubicación 255; coordenadas DECIMAL(9,6) opcionales con límites; capacidad INT positiva; precio DECIMAL(12,2) no negativo; estado y fechas. |
| reservations | `id`, usuario/evento; código ASCII único de 32 caracteres; cantidad INT positiva; precio unitario histórico DECIMAL(12,2); total DECIMAL(22,2), igual a precio × cantidad; estado; fecha de cancelación y auditoría temporal. |
| tickets | `id`; reserva única; código ASCII único de 64 caracteres; creación. Un ticket representa la reserva completa y su cantidad; QR SVG generado localmente. |
| schema_migrations | Versión, SHA-256, estado EN_PROCESO/APLICADA, número de sentencias ejecutadas, inicio/finalización. |
| auth_limits | Migración 002: clave HMAC de acción/IP, inicio de ventana y contador; protección persistente de intentos. |
| demo_seed_runs | Identificador de demostración aplicada y fecha; evita sembrar de nuevo. |
| weather_budget | Migración 003: scope global, ventanas hora/minuto y contadores con máximos 30/h y 10/min; persistencia/transacción PDO preparadas. |

El esquema exacto está en database/migrations/001_initial_schema.php, 002_auth_limits.php y 003_weather_budget.php. Fechas en UTC, presentación en America/Costa_Rica. Precio histórico unit_price y total se guardan en DECIMAL; el servidor calcula precio por cantidad. active_event_id es columna generada: el índice único impide dos reservas activas por usuario/evento y permite conservar cancelaciones.

No existe una fila de cupos disponibles: se deriva capacidad menos suma de entradas CONFIRMADAS. Reserva y evento tienen estados separados; un evento cancelado invalida el ticket sin borrar su reserva. Imágenes actuales pertenecen a una galería del código; no hay carga arbitraria de archivos.

Confirmar reserva inicia una transacción, bloquea la fila del evento FOR UPDATE y revalida estado, fecha, duplicidad, cantidad y disponibilidad. Inserta reserva y ticket antes de COMMIT. Un fallo revierte ambos. La base añade índice único y restricciones, pero el límite de aforo necesita ese bloqueo de servicio.

Cancelar verifica propietario y usa orden de bloqueo evento antes de reserva. Solo se permite a más de 86400 segundos del inicio; exactamente 24 horas se rechaza. Repetir una cancelación ya realizada no libera cupos dos veces. No se borra historia ni se altera el precio guardado.

Administración conserva slug y precios históricos. Cualquier historial fija la fecha y prohíbe regresar a BORRADOR. No reduce capacidad por debajo de entradas confirmadas. Cancelar un evento impide nuevas reservas, conserva existentes y no equivale a cancelar cada reserva. Finalizar explícitamente exige que haya iniciado. Categoría inactiva conserva referencias.

El dashboard cuenta todos los eventos para el total general. Para los indicadores activos utiliza eventos publicados con inicio futuro y sus reservas confirmadas. La ocupación es entradas reservadas divididas entre capacidad acumulada, multiplicada por 100; si la capacidad es cero muestra cero. Los rankings comparan entradas reservadas y resuelven empates por identificador. Los ingresos potenciales suman los totales históricos de esas reservas y no representan pagos. Las métricas tienen pruebas con datos conocidos, sin inventar ventas reales.
