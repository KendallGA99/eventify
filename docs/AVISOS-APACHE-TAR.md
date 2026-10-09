# Apache y restauración: avisos medios

B07/SEG-09. Se contrastaron doce identificadores con fuentes oficiales y con el informe PHP/Apache dev.32: 39 filas MEDIUM, nueve identificadores repetidos en cuatro paquetes Apache y tres en tar. El código y configuración de esas imágenes no cambian en dev.33. Copias de fuentes y cotejo en el registro privado storage/image-audit/apache-tar-review. No se cambian severidades ni se excluyen avisos.

## Apache

El inventario real apache2ctl -M de ambas imágenes, conservado en image-check-ERw4lEwZ, no carga dav, dav_fs, http2, ssl, proxy_ftp, auth_digest ni userdir. rewrite sí está cargado. Ambos Dockerfile copian docker/apache.conf; su regla envía al controlador index.php cuando el archivo solicitado no existe y AllowOverride None impide que un .htaccess altere esa configuración. No se encontraron expresiones LA-U en docker ni en los entrypoints del proyecto.

| Aviso y fuente | Condición específica | Resultado limitado |
| --- | --- | --- |
| [CVE-2026-42528](https://security-tracker.debian.org/tracker/CVE-2026-42528) | Locks WebDAV y cálculo de memoria en mod_dav. | Módulo ausente del inventario cargado. |
| [CVE-2026-56154](https://security-tracker.debian.org/tracker/CVE-2026-56154) | mod_rewrite usando lookahead LA-U:HTTP. | Módulo presente; expresión necesaria ausente de las reglas del proyecto. No confundir esto con rewrite deshabilitado. |
| [CVE-2026-57941](https://security-tracker.debian.org/tracker/CVE-2026-57941) | Reentrada de un buffer compartido en mod_http2. | Módulo ausente. El HTTPS externo de Render no prueba que Apache use ese módulo. |
| [CVE-2026-58415](https://security-tracker.debian.org/tracker/CVE-2026-58415) | Lectura del directorio de propiedades .DAV en mod_dav_fs. | Módulo ausente; no se ofrece WebDAV. |
| [CVE-2026-59797](https://security-tracker.debian.org/tracker/CVE-2026-59797) | mod_ssl, SSLRequire y expresiones relacionadas con archivos. | Módulo ausente. TLS de la conexión PHP–MySQL es un control distinto. |
| [CVE-2026-63045](https://security-tracker.debian.org/tracker/CVE-2026-63045) | Proxy FTP directo que procesa respuesta PASV manipulada. | proxy_ftp ausente; el proyecto no configura ese proxy. |
| [CVE-2026-73637](https://security-tracker.debian.org/tracker/CVE-2026-73637) | Digest concurrente con AuthDigestNcCheck o vida de nonce igual a cero. | auth_digest ausente. Login mediante sesiones PHP no es autenticación Digest de Apache. |
| [CVE-2026-79768](https://security-tracker.debian.org/tracker/CVE-2026-79768) | UserDir absoluto sin comodín y equivalencia de rutas con /./. | userdir ausente; DocumentRoot no activa UserDir. |
| [CVE-2026-93546](https://security-tracker.debian.org/tracker/CVE-2026-93546) | PROPPATCH con muchos namespaces, usuario WebDAV con escritura. | dav_fs ausente; no hay ese servicio de escritura. |

Las condiciones anteriores no se encontraron en las imágenes ensayadas. Los paquetes siguen señalados en el escaneo: esta evaluación no sustituye un parche ni se extrapola a otra configuración o arquitectura. Debian mantiene Trixie vulnerable en las páginas consultadas y registra la corrección Apache2.4.69 en sid/forky; no importar paquetes de esas distribuciones. El rango original del aviso42528 llega a2.4.67 mientras el estado Debian también señala2.4.68: conservar esa diferencia, sin descartarlo solo por comparar versiones.

## tar y el ensayo de restauración

La aplicación no acepta archivos tar del visitante, pero tar sí se utiliza en scripts/test-restore-qa-linux.sh. El ensayo crea un archivo desde dos nombres fijos del almacenamiento QA y lo extrae en un destino nuevo; la base se transfiere por SQL y las imágenes provienen de la galería. El directorio del registro se crea con modo700 y umask077. No hay respaldo incremental ni opción --one-top-level en ese guion.

| Aviso y fuente | Condición y revisión |
| --- | --- |
| [CVE-2026-18477](https://security-tracker.debian.org/tracker/CVE-2026-18477) | Carrera en restauración incremental, registros dumpdir X y atacante local con escritura. El ensayo usa tar -cf, no respaldo incremental. Un destino nuevo por sí solo no mitiga el aviso: la separación aquí depende del formato y de quién pueda modificar origen/destino. No certifica otros respaldos del host. |
| [CVE-2026-18508](https://security-tracker.debian.org/tracker/CVE-2026-18508) | --one-top-level, hardlinks y enlace simbólico previo. El guion no utiliza esa opción. No convertir esa observación en permiso para extraer archivos desconocidos. |
| [CVE-2026-5704](https://security-tracker.debian.org/tracker/CVE-2026-5704) | Archivo manipulado que introduce contenido no visible en la inspección previa. El guion consume el archivo que acaba de generar en su registro privado; no recibe una ruta de archivo externo. Eso limita la exposición a entrada remota, pero no valida un archivo reemplazado por un usuario con acceso al registro. La comparación posterior de snapshots comprueba contenido esperado, no constituye un aislamiento contra archivos maliciosos. |

Debian mantiene los tres avisos de tar sin corrección de paquete en Trixie. No se elimina tar, pues rompería el ensayo actual, ni se presenta su extracción como mecanismo seguro para material ajeno. Si se añade importación de respaldos proporcionados por terceros, debe diseñarse y probarse esa entrada antes de habilitarla; este ensayo QA no la implementa.

## Alcance del avance

Solo se revisaron fuentes, configuración, inventarios y guiones. No se ejecutaron ataques, restauraciones o builds nuevos, ni se cambiaron servicios, permisos, datos o paquetes. Los ensayos funcionales anteriores siguen vinculados a sus referencias originales. B07 continúa abierto por los grupos restantes, DNS efectivo y kernel del entorno, Firefox/zoom y Windows. dev.33 conserva su etiqueta de desarrollo.
