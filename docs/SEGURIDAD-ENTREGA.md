# Seguridad de la entrega

Corte: 10 de octubre de 2026. Resumen operativo de las comprobaciones y los riesgos que permanecen; no reemplaza los informes detallados ni constituye aceptación automática del riesgo.

## Controles comprobados

- Publicación dev.39: 81 controles HTTPS de usuarios y 36 de administración; roles, sesiones, propiedad, CSRF y protección de archivos privados. Diez tablas conservaron su contenido durante el despliegue. Evidencia en ESTADO.md.
- Copia local dev.39 inspeccionada nuevamente: PHP 8.4.26, errores fuera de la respuesta, exposición de versión PHP desactivada y sesiones estrictas. Contenedor sin modo privilegiado; puerto 8086 enlazado a 127.0.0.1.
- Apache cargado en esa copia: vhost_alias, xml2enc, proxy y CGI no aparecen. Esto confirma la configuración local, no el inventario vivo del proveedor público.
- Código de WeatherClient: destino HTTPS fijo, comprobación de certificado y nombre, sin seguimiento de redirecciones, respuesta limitada a 128 KiB y análisis JSON. TicketQr produce SVG con una matriz y coordenadas numéricas; no analiza un documento XML recibido del visitante.

La inspección dev.39 es de configuración y rutas de código. Sus resultados no se presentan como un escaneo nuevo.

## Protección del contenedor dev.40

La web de `docker-compose.yml` y el servicio de integración de `docker-compose.test.yml` activan `security_opt: no-new-privileges:true`. El kernel impide que un proceso obtenga privilegios adicionales al ejecutar archivos setuid o con capacidades. Apache puede iniciar como root y reducir el usuario de sus trabajadores a www-data; la opción no convierte por sí sola todo el contenedor en un proceso sin root ni bloquea cualquier ataque.

Criterios de aceptación: Apache inicia, sirve CSS, mantiene trabajadores www-data con `NoNewPrivs=1` y la integración MySQL/HTTP conserva sus controles. Se prueba primero en aislamiento, sin publicar puertos ni conectar volúmenes de la instalación. El resultado y la aplicación local se registran en ESTADO.md.

Esta configuración pertenece a Docker Compose. No acredita la misma protección en Render, que utiliza su propio runtime, ni una instalación Windows todavía no verificada. No parchea libxml2 o Apache y no elimina los riesgos siguientes.

## Decisiones pendientes

### Caducidad y modo estricto: comprobación adicional

La integración aislada del 10 de octubre amplía 225 a 234 comprobaciones, sin cambios del código de aplicación. Verifica el rechazo de un identificador de sesión inventado y, tras envejecer únicamente last_activity de una sesión temporal, la pérdida de identidad AJAX y permisos administrativos, renovación del identificador, eliminación del archivo de sesión anterior y rechazo del CSRF antiguo. Reenviar la cookie eliminada tampoco recupera acceso.

El caso está en tests/integration/http-session-expiry.php, incluido desde http-auth.php. Solo acepta APP_ENV=test y la base eventify_test. No cambia el reloj del equipo ni las sesiones de las instalaciones locales o públicas. El primer intento tuvo un fallo de orden en la prueba de cookie inicial, conservado en ESTADO; la ejecución corregida terminó con 234 controles y exit0. Esto acredita comportamiento de sesiones PHP, no remedia los avisos nativos siguientes.

| Riesgo | Evidencia y límite | Qué permite avanzar |
| --- | --- | --- |
| libxml2, CVE-2026-6653 y otros XML | PHP local carga libxml2 2.9.14 y las extensiones DOM, SimpleXML, XML, XMLReader y XMLWriter. No es correcto declarar ausente la biblioteca. No se encontró una entrada XML del visitante en las rutas revisadas; esto no demuestra ausencia de todo consumidor nativo. | Corrección compatible y probada del runtime, o aceptación explícita del riesgo con alcance de evaluación y responsable. |
| Apache, CVE-2026-63292 | El módulo vhost_alias requerido por el aviso no está cargado en la copia inspeccionada. No equivale a parchear el paquete ni cubre las demás vulnerabilidades de Apache. | Conservar esta condición y revisar al cambiar módulos/configuración; incorporar corrección compatible cuando esté disponible y verificarla. |
| Otros avisos de las imágenes | Los conteos y análisis pertenecen a sus imágenes y base del escáner originales. Las reducciones anteriores retiraron herramientas de construcción y MySQL Shell, con pruebas propias. | Seguir AVISOS-RESIDUALES.csv y los informes de MySQL; no reutilizar un conteo antiguo como si fuera un escaneo de hoy. |
| Host y proveedor | Las comprobaciones del contenedor no certifican kernel, plataforma administrada o Windows. | Comprobar cada entorno dentro del alcance y permisos disponibles. |

Debian mantiene Trixie vulnerable para [libxml2/CVE-2026-6653](https://security-tracker.debian.org/tracker/CVE-2026-6653). Apache publica correcciones en [2.4.69](https://httpd.apache.org/security/vulnerabilities_24.html), pero una corrección del proyecto original no acredita su integración en la imagen Debian usada. La revisión no mezcla distribuciones ni introduce una biblioteca compilada a mano sin pruebas nativas y de compatibilidad.

## Límites de entrega

El material sirve para evaluación académica con datos ficticios. No se afirma seguridad completa ni se amplía a producción con información sensible. El responsable todavía no ha aceptado los riesgos residuales. C-09 permanece abierto; no se elimina para declarar que solo falta Windows.

Ante una corrección candidata: conservar las imágenes anteriores, construir en aislamiento, comparar inventarios y avisos, ejecutar reglas/integración/TLS según el componente afectado y actualizar con respaldo y comprobación de persistencia. Un escaneo vacío por exclusiones no es el criterio de éxito.

Evidencia privada de esta inspección: storage/security-defense-20261010, con módulos Apache, versión/extensiones PHP, límites del contenedor y ejecución del ejemplo de reglas de reserva. No incluye contraseñas ni volcados de MySQL.


## Actualizaciones compatibles consultadas

El10oct se ejecutó apt-get update y apt-cache policy en un contenedor temporal de la imagen web local, sin puertos, montajes, secretos o acceso a los volúmenes de Eventify. Se consultaron exclusivamente los repositorios Trixie configurados; no se instalaron paquetes ni se construyó una imagen nueva.

| Paquete | Versión instalada y candidata, idénticas |
| --- | --- |
| apache2 | 2.4.68-1~deb13u1 |
| curl | 8.14.1-2+deb13u5 |
| libcurl4t64 | 8.14.1-2+deb13u5 |
| libxml2 | 2.12.7+dfsg+really2.9.14-2.1+deb13u3 |
| libexpat1 | 2.8.3-1~deb13u1 |
| libc6 | 2.41-12+deb13u4 |
| libssl3t64 | 3.5.7-1~deb13u3 |

No apareció una actualización para esos siete paquetes. Esto acredita disponibilidad de versiones en ese momento, no ausencia de vulnerabilidades ni cobertura de todos los paquetes. Los trackers consultados conservan Trixie vulnerable para [libxml2/6653](https://security-tracker.debian.org/tracker/CVE-2026-6653), [Apache/63292](https://security-tracker.debian.org/tracker/CVE-2026-63292), [Expat/66046](https://security-tracker.debian.org/tracker/CVE-2026-66046) y [curl/80229](https://security-tracker.debian.org/tracker/CVE-2026-80229). No se importan paquetes de otras distribuciones por aparecer allí como corregidos. Evidencia privada en storage/security-candidates-20261010.
