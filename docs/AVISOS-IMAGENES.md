# Avisos de imágenes: revisión de alcance

B07/SEG-09, 8 de octubre de 2026. Complementa SEGURIDAD.md; no elimina avisos del escáner ni certifica ausencia de vulnerabilidades. Se revisaron las imágenes local/pública dev.30 exportadas en image-check-APnvo9xh, el código que EVENTIFY llama y fuentes oficiales. No se ejecutaron ataques ni se cambiaron módulos, paquetes o permisos.

## Apache

Los seis avisos altos del informe se repiten en apache2, apache2-bin, apache2-data y apache2-utils. Son 24 filas para seis identificadores, no 24 fallos independientes.

| Aviso | Módulo necesario | Resultado de la configuración de ambas imágenes |
| --- | --- | --- |
| CVE-2026-46729 | heartmonitor | No habilitado |
| CVE-2026-56153 | charset_lite | No habilitado |
| CVE-2026-63292 | vhost_alias | No habilitado |
| CVE-2026-63686 | xml2enc | No habilitado |
| CVE-2026-63718 | proxy_uwsgi | No habilitado |
| CVE-2026-73636 | auth_digest | No habilitado |

Se reconstruyó el inventario de mods-enabled leyendo las capas OCI, incluidas las eliminaciones whiteout. Resultado privado modules-review.json. Configuración incluye rewrite/headers/php/mpm_prefork; los módulos anteriores no aparecen. Es evidencia de la imagen, no una ejecución nueva de apache2 -M ni una certificación del proceso desplegado en Render. Ningún entrypoint revisado habilita esos módulos después; el VirtualHost del proyecto tampoco usa sus directivas. Mantenerlos deshabilitados y revaluar si cambia Apache.

[Apache publica las correcciones en 2.4.69](https://httpd.apache.org/security/vulnerabilities_24.html). [Debian todavía registra Trixie vulnerable para vhost_alias](https://security-tracker.debian.org/tracker/CVE-2026-63292) y [auth_digest](https://security-tracker.debian.org/tracker/CVE-2026-73636); la versión corregida aparece en sid/forky. No mezclar repositorios de distribuciones ni sustituir Apache manualmente para reducir un contador. La ausencia de versión corregida en el informe corresponde a la distribución auditada, no a la inexistencia de una corrección del proveedor original.

## XML y QR

El aviso crítico [CVE-2026-6653](https://security-tracker.debian.org/tracker/CVE-2026-6653) afecta la lectura de XML manipulado en libxml2. No se encontró uso de DOMDocument, simplexml, xml_parse o loadXML en app/public/bin. La administración elige imágenes de una galería cerrada; no acepta XML/SVG subido por el visitante.

La biblioteca BaconQrCode incluye SvgImageBackEnd con XMLWriter, pero TicketQr no utiliza esa clase: obtiene la matriz mediante Encoder y forma un SVG con rectángulos y números. payload solo admite 64 caracteres hexadecimales y agrega un prefijo fijo. Por tanto, esa ruta no introduce XML arbitrario de usuario en un parser. Esto es revisión estática de esa ruta, no prueba de que libxml2 nunca se use desde PHP ni descarte de sus otros avisos. Los ocho avisos altos y el crítico de libxml2 continúan en inventario.

## Kernel y herramientas auxiliares

- Los 55 avisos altos y uno crítico asociados a linux-libc-dev corresponden al paquete de cabeceras del kernel. No son evidencia de un kernel de esa versión ejecutándose dentro del contenedor. El [aviso crítico ksmbd](https://security-tracker.debian.org/tracker/CVE-2026-43185) exige ese servicio del kernel; revisar el host/proveedor por separado. No purgar cabeceras sin comprobar la construcción de extensiones.
- EVENTIFY obtiene el clima mediante streams PHP, HTTPS y JSON. No se encontraron llamadas curl_* en app/public/bin; curl de Dockerfile descarga Composer durante la construcción desde un destino fijo. Esta distinción no elimina los avisos del paquete curl/libcurl ni cubre posibles usos indirectos.
- Las coincidencias exec encontradas son PDO::exec para SQL; no son ejecución de comandos del sistema. No se encontraron shell_exec, system o proc_open en las rutas de aplicación revisadas.
- El crítico Go de la imagen MySQL está en gosu, no demostrado en el servidor MySQL. [El aviso Go requiere reanudación TLS y cambio de autoridades](https://pkg.go.dev/vuln/GO-2026-4337). Falta un análisis del binario/funciones alcanzables; no basta leer una versión del código fuente para certificar el ejecutable instalado. Los avisos de Python/gosu y sus posibles actualizaciones se mantienen pendientes. Este análisis no audita el servidor administrado Aiven.

## Decisión y pendientes

El parche xz/liblzma sí tiene construcción, unidades y escaneo comprobados. Para los avisos anteriores se registran condiciones e incertidumbre; no se añadió .trivyignore ni se ocultaron avisos sin corrección. La revisión no cubre todavía los 2011 registros restantes de PHP/Apache uno por uno ni toda la imagen MySQL. Antes de la entrega final: revisar los grupos no tratados, confirmar módulos del runtime, contrastar nuevos parches de las distribuciones y conservar evidencia de regresión. No marcar B07 ni la seguridad completa como terminados.
