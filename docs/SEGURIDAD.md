# Seguridad y manejo de errores

Las contraseñas se guardan con password_hash y se comprueban con password_verify. Las sesiones usan modo estricto y cookies HttpOnly, SameSite=Lax y Secure en producción. Al ingresar cambia el identificador; al salir se destruye. Tras 30 minutos de inactividad se reinicia la identidad. Los controles HTTPS públicos verificaron estas cookies, la regeneración y el rechazo de una sesión destruida.

Las operaciones de escritura requieren CSRF. El servidor limita métodos HTTP, valida entradas, usa consultas preparadas y escapa el contenido de las vistas. Las consultas de reservas y tickets incluyen el propietario. Un usuario no decide su rol, precio, total ni cupos enviando campos desde el navegador. La web rechaza tokens de otra sesión, tickets y QR ajenos y rutas administrativas sin el rol necesario.

La configuración privada se obtiene mediante variables de entorno y queda fuera del repositorio y de la raíz pública. Los errores no muestran consultas, claves ni trazas al visitante.

La conexión pública a MySQL usa TLS y valida el certificado y la identidad del servidor. Las cuentas públicas de evaluación se preparan expresamente con claves únicas; no se crean administradores con una contraseña universal al iniciar el servidor. La cuenta de la aplicación tiene permisos limitados a su base de datos.

Los contenedores locales usan no-new-privileges para impedir que los procesos adquieran privilegios adicionales al ejecutar archivos. Las dependencias están fijadas por versión. El escaneo de las tres imágenes corregidas del 10 de octubre de 2026 no reportó avisos con la base utilizada en esa fecha. Es un resultado de ese corte: requiere revisiones posteriores y no audita el equipo del usuario ni los proveedores.
