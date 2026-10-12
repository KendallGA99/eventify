# Entorno de ejecución y persistencia

El despliegue local utiliza Docker Desktop y Docker Compose en Windows. El asistente CMD/PowerShell comprueba las dependencias, configura la aplicación, espera la disponibilidad de MySQL y aplica las migraciones. Los accesos de inicio y detención controlan los servicios sin eliminar los datos persistentes.

Una instalación vacía recibe ocho eventos de ejemplo, cinco categorías y cuentas de administrador y usuario con contraseñas únicas. Los accesos se guardan en storage/example-credentials.json de la carpeta instalada. Si ya existen datos, el arranque los conserva y no reemplaza cuentas ni eventos. Los ejemplos no representan cobros ni actividades contratadas.

Los volúmenes mysql_data y app_storage conservan datos al detener o recrear contenedores. La copia del código no contiene las reservas de una instalación existente: para trasladarlas hay que realizar un respaldo de MySQL y de los archivos. No se deben borrar los volúmenes como parte del uso normal.

La aplicación pública está en https://eventify-academico.onrender.com. Usa PHP/Apache en Render y MySQL en Aiven. La copia local es independiente: no comparte automáticamente cuentas, reservas ni contraseñas con la web pública y no necesita cuentas en esos proveedores. Los planes gratuitos pueden suspender servicios por inactividad, lo que afecta su disponibilidad y el tiempo de la primera respuesta.

Git conserva el código y las versiones, pero no reemplaza un respaldo de la base de datos. Para volver a una versión anterior hay que revisar primero su compatibilidad con las migraciones y los datos existentes.
