# Avisos bajos: alcance y registro de revisión

B07/SEG-09. LOW no significa que un aviso sea inocuo. La imagen PHP/Apache conservada tiene 882 filas LOW: 736 fuera de linux-libc-dev y 146 en cabeceras. Las 736 filas se agrupan en 147 identificadores; 69 de binutils se repiten en ocho paquetes y representan 552 filas. Los totales no son fallos independientes ni evidencia de ejecución del kernel en el contenedor.

AVISOS-BAJOS.csv permite buscar cada identificador, paquetes afectados, condición y fuente. Las 147 páginas Debian se descargaron y conservaron en storage/image-audit/low-review. Reunir una descripción no constituye una evaluación:Se contrastaron doce avisos Apache y se delimitó el uso de otros 66 identificadores. Los 69 de binutils tienen una delimitación inicial por tipo de entrada; quedan pendientes backends, plugins, consumidores y parcheo. Delimitar una ruta del proyecto no certifica que una biblioteca nativa sea inocua. Los identificadores TEMP son registros de Debian, no se convierten en CVE inventados.

## Apache: doce identificadores, 48 filas

Inventario cargado local/público dev.32 comprobado anteriormente con apache 2ctl -M: PHP, prefork y negotiation presentes; usertrack, suexec, cgi, cgid, session_cookie, auth_digest y proxy_html ausentes. La configuración exportada local registra HostnameLookups Off. Archivo de configuración del proyecto compartido por ambos Dockerfile; directorio público sin MultiViews declarado y sin .htaccess permitido. No se certifica configuración adicional del proveedor.

| Aviso oficial | Condición revisada y límite |
| --- | --- |
| [CVE-2001-1534](https://security-tracker.debian.org/tracker/CVE-2001-1534) | usertrack no aparece cargado; sesiones PHP distintas. |
| [CVE-2003-1307](https://security-tracker.debian.org/tracker/CVE-2003-1307) | php sí cargado; requiere ejecutar/modificar PHP malicioso. Sin carga de código del visitante; no descartado frente a compromiso local. |
| [CVE-2003-1580](https://security-tracker.debian.org/tracker/CVE-2003-1580) | HostnameLookups Off en apache 2.conf exportado; no hay cambio del proyecto. Runtime del proveedor no auditado. |
| [CVE-2003-1581](https://security-tracker.debian.org/tracker/CVE-2003-1581) | Misma condición de DNS inverso deshabilitado; logs no se presentan como HTML a visitantes. |
| [CVE-2007-0086](https://security-tracker.debian.org/tracker/CVE-2007-0086) | Range y consumo de ancho de banda. No descartado: no se ensayó condición TCP ni protección frente a agotamiento. |
| [CVE-2007-1743](https://security-tracker.debian.org/tracker/CVE-2007-1743) | suexec no aparece cargado; no se configura ejecución CGI del proyecto. |
| [CVE-2007-3303](https://security-tracker.debian.org/tracker/CVE-2007-3303) | mpm_prefork sí cargado; requiere código local en worker. Sin ejecución de código arbitrario del visitante; riesgo de compromiso local no descartado. |
| [CVE-2008-0456](https://security-tracker.debian.org/tracker/CVE-2008-0456) | negotiation sí cargado; no hay carga de archivos con nombres arbitrarios ni MultiViews en directorio de la aplicación. No basta ausencia de Indexes. |
| [CVE-2026-42356](https://security-tracker.debian.org/tracker/CVE-2026-42356) | cgi/cgid no cargados; proyecto no habilita directorio CGI. |
| [CVE-2026-47360](https://security-tracker.debian.org/tracker/CVE-2026-47360) | session_cookie no cargado; cookie PHP no es mod_session_cookie. |
| [CVE-2026-48005](https://security-tracker.debian.org/tracker/CVE-2026-48005) | auth_digest no cargado; AuthDigestNcCheck no configurado. |
| [CVE-2026-56449](https://security-tracker.debian.org/tracker/CVE-2026-56449) | proxy_html no cargado; no se configura proxy que reescriba HTML. |

Los avisos antiguos sobre ejecución de código local permanecen relevantes como límites del modelo de alojamiento. No alojar PHP ajeno ni convertir EVENTIFY en un servicio para ejecutar archivos subidos. Deshabilitar índices no corrige por sí solo otros módulos. El aviso Range no se descarta y su antigüedad no es prueba de corrección. Se conserva el estado Debian y la severidad del informe; no hay parche Apache aplicado en esta revisión.

## Binutils: construcción y análisis de objetos

Binutils está en la cadena de construcción de la extensión PDO; no se ofrece al visitante análisis de binarios o símbolos. Se contrastaron sus descripciones por entrada: símbolos para demangling; ELF/DWARF en readelf/objdump; objetos para enlace/BFD; XCOFF; DLX con backend habilitado; plugin/LTO. No se ejecutaron archivos manipulados. La aplicación no ofrece ese análisis al visitante, pero la construcción PDO sí emplea la cadena de herramientas. Backends y plugins efectivos aún no comprobados; no se certifica que el código vulnerable esté ausente. No purgar compiladores o bibliotecas sin construir y comprobar el resultado.

La revisión no modifica paquetes, servicios ni datos. Kernel efectivo del host, Windows, Firefox/zoom y límites nativos pendientes se siguen por separado. No se crea .trivyignore ni se reducen contadores por exclusión.

## Otros 66 identificadores: alcance de uso

El CSV conserva cada condición y fuente Debian, con una nota de alcance por identificador. La búsqueda abarcó app, public, bin, docker, Dockerfile y scripts; se separaron las llamadas SQL de PDO de ejecución de comandos. No se añadió una exclusión al escáner.

- curl: once avisos sobre SSH/SFTP/SCP, LDAP/LDAPS, HTTP/2 push, certificados y cookies. La API de clima usa streams PHP; Composer y ensayos usan CLI. No se configura esa funcionalidad en los comandos revisados; curl puede leer configuración del usuario y heredar proxies. Esos consumidores siguen abiertos.
- glibc: patrones glob fijos; preg_* usa PCRE y no certifica regexec POSIX. Los avisos de mitigaciones, memoria y consumidores nativos siguen abiertos. POWER8 no es la arquitectura del contenedor local comprobado; no extrapolar a otro destino.
- APT, bash, util-linux y shadow: no hay una ruta web que invoque apt-key, chfn/chsh o su, pero existen herramientas privilegiadas. INPUTRC, entornos, permisos de btmp, rangos subuid/NFS y acceso local no quedan descartados. Los registros TEMP se mantienen como tales; se leyeron sus notas Debian.
- Kerberos y LDAP: no se configura KDC, slapd, autenticación LDAP o importación LMDB en EVENTIFY. Bibliotecas GSSAPI/LDAP instaladas pueden tener consumidores ajenos a PHP; siguen pendientes.
- systemd: el contenedor ejecuta Apache y registra por stdout/stderr. Las bibliotecas instaladas no acreditan uso de journald ni certifican el host. La integridad de diarios del host queda fuera de este contraste.
- SQLite, XML, GnuTLS, ncurses y Perl: MySQL es la base del proyecto; zipfile de Python no es la extensión SQLite. No se configura xmlcatalog --shell ni entrada termcap del visitante. XML nativo, TLS/CBC y temporales Perl requieren evaluación de sus consumidores; avisos abiertos.
- coreutils, dash, diffutils, m 4, patch y re 2c: no se encontró entrada del visitante enviada a esas herramientas. m 4 y re 2c sí forman parte de la construcción; no se trata de paquetes ausentes. chown de storage no usa -R/-L. No se aplica parche ni se certifica la cadena de suministro completa.
- tar: interviene en restauración QA. No ofrecer tar/rmt como importación arbitraria; un respaldo propio no demuestra seguridad ante un archivo manipulado.

Los 147 identificadores con contraste inicial de configuración o uso siguen contándose en el informe original. Ninguno se elimina por esta revisión. Los backends/plugins y consumidores binutils, las 146 filas de cabeceras, los avisos nativos señalados y las condiciones del host/proveedor quedan pendientes; no se declara SEG-09 cerrado ni versión estable.

Próxima decisión de seguridad: comprobar si la construcción de la imagen permite retirar herramientas de desarrollo del runtime sin eliminar bibliotecas necesarias. Exige construcción real, inventario posterior e integración; no se prescribe una purga a ciegas ni se altera una imagen verificada solo para reducir el contador.
