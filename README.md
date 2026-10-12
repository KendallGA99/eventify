# EVENTIFY

Aplicación web para explorar eventos, reservar entradas y consultar tickets digitales con código QR. El panel administrativo permite gestionar eventos, categorías, reservas y usuarios, además de consultar indicadores de ocupación.

[Ver la aplicación](https://eventify-academico.onrender.com) · [Documento técnico](docs/Documento-Tecnico-Eventify.pdf)

## Funcionalidades

- Registro, inicio y cierre de sesión; perfil con edición del nombre.
- Catálogo con búsqueda por nombre y filtros combinables de categoría, fechas, disponibilidad y estado.
- Reservas con validación de cupos, cantidad y precio en el servidor.
- Historial personal, tickets QR y cancelación cuando faltan más de 24 horas para el evento.
- Administración de categorías, eventos, reservas y permisos de usuarios.
- Indicadores de reservas y ocupación. Los importes representan reservas, sin procesamiento de pagos.
- Pronóstico del tiempo con caché y proveedor de respaldo. Si falla la consulta, las reservas siguen disponibles.

## Tecnologías

HTML5, CSS3, JavaScript, jQuery, AJAX, PHP 8.4, sesiones PHP y MySQL 8.4. Apache sirve la aplicación; Docker Compose define el entorno local. Las bibliotecas de jQuery, fuentes y generación QR se sirven localmente. Open-Meteo y MET Norway proporcionan información meteorológica por HTTPS.

## Organización del código

| Carpeta | Responsabilidad |
| --- | --- |
| `app/Http` | Rutas, controladores y respuestas HTTP. |
| `app/Domain` | Reglas de negocio, reservas y administración. |
| `app/Infrastructure` | Repositorios PDO, migraciones y clientes de clima. |
| `app/Security` | Autenticación, sesiones y protección CSRF. |
| `app/Views` | Vistas HTML y fragmentos de respuesta. |
| `public` | Punto de entrada y recursos estáticos. |
| `database` | Migraciones y catálogo inicial de ocho eventos. |
| `tests` | Pruebas unitarias, integración y comprobaciones de interfaz. |

## Documentación técnica

- [Descripción, requisitos y diseño](docs/TECNICO.md): documento técnico completo.
- [Configuración](config/README.md): variables del entorno y conexión a datos.
- [Base de datos](database/README.md): migraciones, restricciones e inicialización.
- [Uso de la aplicación](docs/USO.md): recorridos de usuario y administrador.
- [Validación](docs/VALIDACION.md): resultados registrados y alcance de las pruebas.
- [Versiones](CHANGELOG.md): cambios recientes del proyecto; las ramas y etiquetas conservan el historial.

## Entorno y datos

La referencia del código local es **0.1.0-dev.52**. La publicación web utiliza **0.1.0-dev.46**; los cambios posteriores corresponden al instalador de Windows y a la documentación.

El entorno local ejecuta PHP/Apache y MySQL en contenedores, con volúmenes persistentes. La configuración se define en variables de entorno; `.env.example` contiene los nombres y valores de referencia, sin secretos reales. Las cuentas iniciales usan contraseñas únicas y sus credenciales no se incluyen en el repositorio.

La web pública y cada instalación local mantienen sus propios datos. Las cuentas, eventos y reservas no se sincronizan entre ellas. El alojamiento gratuito puede tardar en responder después de un período de inactividad.

## Decisiones principales

Las reservas se confirman dentro de una transacción que bloquea el evento y vuelve a comprobar los cupos. El precio histórico se guarda con la reserva. Las cancelaciones conservan el historial y liberan espacios una sola vez. Las rutas administrativas y los tickets verifican roles y propiedad en el servidor.

La interfaz se adapta a teléfonos, tabletas y escritorio. Las comprobaciones de navegadores y tamaños se describen con su versión y alcance en el documento de validación. El proyecto no incluye pagos reales, aplicación móvil nativa ni control de entrada mediante escaneo.
