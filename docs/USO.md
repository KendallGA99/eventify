# Uso de Eventify

## Cuenta

En **Crear cuenta** se registra nombre, correo y contraseña. El botón del ojo permite mostrar u ocultar la contraseña. Después de ingresar, la aplicación abre el inicio. **Mi cuenta** permite editar el nombre y cerrar sesión; el correo y el rol no se modifican desde el perfil.

## Eventos y reservas

**Eventos** combina filtros de nombre, categoría, fechas, disponibilidad y estado. El detalle muestra fecha, ubicación, precio, capacidad y espacios disponibles. El clima es orientativo y su ausencia no impide reservar.

Para reservar se necesita una sesión de usuario. Al confirmar la cantidad, el servidor valida las reglas y calcula el total. Si se pierde la conexión después de confirmar, se debe revisar **Mis experiencias** antes de repetir la solicitud. No se procesan pagos reales.

## Tickets y cancelación

**Mis experiencias** muestra las reservas y permite consultar sus tickets QR. La cancelación exige que falten más de 24 horas para el evento. Conserva el historial y libera los cupos una sola vez. El ticket cancelado sigue visible como historial, pero deja de estar vigente.

## Administración

El administrador gestiona categorías, eventos y reservas; consulta indicadores y asigna permisos a usuarios. No puede quitarse su propio acceso desde el panel ni dejar el sistema sin administradores activos. Cancelar un evento conserva las reservas e impide nuevas confirmaciones.

Los indicadores económicos corresponden a reservas, sin representar pagos recibidos. Las acciones administrativas se validan en el servidor aunque se intente acceder directamente mediante una URL.
