# Seguridad de la entrega dev.39

Corte: 10 de octubre de 2026. Resumen operativo de las comprobaciones y los riesgos que permanecen; no reemplaza los informes detallados ni constituye aceptación automática del riesgo.

## Controles comprobados

- Publicación dev.39: 81 controles HTTPS de usuarios y 36 de administración; roles, sesiones, propiedad, CSRF y protección de archivos privados. Diez tablas conservaron su contenido durante el despliegue. Evidencia en ESTADO.md.
- Copia local dev.39 inspeccionada nuevamente: PHP 8.4.26, errores fuera de la respuesta, exposición de versión PHP desactivada y sesiones estrictas. Contenedor sin modo privilegiado; puerto 8086 enlazado a 127.0.0.1.
- Apache cargado en esa copia: vhost_alias, xml2enc, proxy y CGI no aparecen. Esto confirma la configuración local, no el inventario vivo del proveedor público.
- Código de WeatherClient: destino HTTPS fijo, comprobación de certificado y nombre, sin seguimiento de redirecciones, respuesta limitada a 128 KiB y análisis JSON. TicketQr produce SVG con una matriz y coordenadas numéricas; no analiza un documento XML recibido del visitante.

La revisión es de configuración y rutas de código. No se enviaron ataques ni se cambiaron bibliotecas, permisos, datos o configuración del servicio.

## Decisiones pendientes

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
