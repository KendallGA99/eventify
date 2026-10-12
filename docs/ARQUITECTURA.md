# Arquitectura y navegación

| Capa | Tecnología y función |
| --- | --- |
| Interfaz | HTML5, CSS3 y JavaScript; vistas PHP con escape de contenido. |
| Interacción | jQuery 4.0.0 local; catálogo e historial asincrónicos, sin CDN obligatorio. |
| Servidor | PHP 8.4 y Apache; controladores HTTP, servicios de dominio y repositorios PDO. |
| Datos | MySQL 8.4 Community/InnoDB, restricciones, índices y migraciones versionadas. |
| Identidad | Sesiones PHP, hashes de contraseña, CSRF y roles comprobados en servidor. |
| QR | BaconQrCode 3.1.1 y dasprid/enum 1.0.7; generación SVG local con composer.lock. |
| Entorno | Docker Desktop y Docker Compose; asistente CMD/PowerShell y volúmenes persistentes. |
| API externa | Open-Meteo y respaldo MET Norway por HTTPS, destinos fijos, timeout, caché y límite global. |
| Desarrollo | Git y GitHub para conservar el historial; pruebas automatizadas de reglas e integración. |

```text
Navegador -> Apache/PHP -> PDO -> MySQL
PHP -> Open-Meteo / MET Norway (HTTPS)
```

public/index.php es el punto de entrada y decide ruta/método. app/Http coordina respuestas y autorización; app/Domain concentra reglas y transacciones; app/Infrastructure realiza acceso a MySQL/API y migraciones; app/Views renderiza; app/Security administra identidad; public/assets contiene estilos, JavaScript, bibliotecas e imágenes locales. La raíz pública es public, no el repositorio completo.

PHP y MySQL se ejecutan dentro de contenedores. Docker reproduce el entorno local sin instalar esos componentes directamente en Windows. Las herramientas de pruebas y generación de documentos son independientes de la ejecución de la aplicación.

| Área | Rutas principales y acceso |
| --- | --- |
| Pública | /, /eventos, /eventos/{slug}, /registro, /ingresar. Los borradores no son públicos. |
| Cuenta | GET/POST /cuenta: consultar perfil y editar nombre con sesión/CSRF; correo y rol conservados. POST /salir destruye sesión. Login redirige al inicio. |
| Usuario | /experiencias, /tickets/{code}, /tickets/{code}/qr.svg; propiedad verificada en MySQL. |
| Administrador | /admin, /admin/categorias, /admin/eventos, /admin/reservas, /admin/usuarios y detalles/formularios. |
| AJAX | /api/eventos, /api/experiencias, /api/reservas, cancelación y clima. |
| Disponibilidad | /salud informa ok/unavailable; no expone claves ni consultas. |

La navegación visual no otorga permisos. Una URL de administración exige ADMINISTRADOR; una URL de ticket exige su propietario. Un ticket ajeno responde 404 sin revelar contenido. El registro público asigna USUARIO aunque el navegador envíe otro rol.
