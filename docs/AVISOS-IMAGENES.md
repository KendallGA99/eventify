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


## Ampliación de revisión: dev.32

image-check-ERw4lEwZ, exit0: apache2ctl -M ejecutado realmente sobre ambas imágenes, sin red/montajes/puertos. Los seis módulos de la tabla Apache no están cargados; esto amplía la evidencia estática anterior. No es inventario del proceso de Render. Ambos escaneos nuevos conservan2011 filas/145HIGH/2CRITICAL; xz/liblzma no tiene avisos. DB Trivy actualizada8oct19:05UTC, reutilizada sin actualizar. No agrupar filas repetidas como fallos independientes.

- libsystemd0/libudev1: el inventario incluye bibliotecas, no los paquetes systemd/systemd-homed. [Debian describe CVE-2026-16742 como escalada local mediante systemd-homed](https://security-tracker.debian.org/tracker/CVE-2026-16742). Compose no declara servicio homed y los entrypoints inician Apache/MySQL. Inferencia de alcance: no se encontró esa ruta en el proyecto; no es prueba universal de explotabilidad ni auditoría del host.
- Perl: [CVE-2026-9538 exige lectura de un TAR manipulado mediante Archive::Tar](https://security-tracker.debian.org/tracker/CVE-2026-9538). No hay llamadas a Perl/Archive::Tar ni subida de TAR en las rutas app/public/bin revisadas. Composer descarga dist desde dependencias fijadas durante build. Mantener el aviso; revisar por separado la cadena de construcción.
- util-linux: [CVE-2026-78408 exige nsenter --join-cgroup usado por un operador privilegiado](https://security-tracker.debian.org/tracker/CVE-2026-78408). No se encontró nsenter/mount en el código ni se monta el socket Docker/espacios del host en Compose. Otros avisos de util-linux, ACL y ncurses no se consideran resueltos por esta observación.
- Expat: [CVE-2026-76957 implica callbacks de codificación de XML](https://security-tracker.debian.org/tracker/CVE-2026-76957). No se encontró parser XML de usuario en app/public/bin; módulos DAV no figuran en apache2ctl -M. Esto no demuestra que toda biblioteca deje de usar Expat ni cubre sus cinco avisos altos.

Los cuatro avisos contrastados siguen señalados como vulnerables en Trixie en el tracker consultado; no incorporar sid/forky para conseguir reporte vacío. Continúan pendientes el análisis del binario gosu/MySQL, ACL/ncurses y variantes de montajes/parsers, además de los avisos medios/bajos. El trabajo anterior sobre XML/kernel/curl conserva sus límites.

## Revisión restante y parche preparado: dev.33

La imagen MySQL fijada tiene 69 filas: 1 crítica, 29 altas, 32 medias, 6 bajas y 1 desconocida. Su desglose es 3 avisos del sistema Oracle, 19 de Python incluido en mysqlsh y 47 de gosu. [INVENTARIO-IMAGENES.md](INVENTARIO-IMAGENES.md) agrupa todos los paquetes de ambas familias de imágenes. No se mezclan estos conteos con las 2011 filas de PHP/Apache.

### Binario gosu

Se descargaron las capas del índice fijado de MySQL desde el registro oficial, verificando SHA-256 del índice, manifiesto amd64, configuración y dos capas. No se ejecutó gosu en el host. La capa contiene el binario con SHA-256 `52c8749d0142edd234e9d6bd5237dff2d81e71f43537e2f4f66f75dd4b243dd0`, idéntico al [archivo oficial gosu 1.19 amd64](https://github.com/tianon/gosu/releases/tag/1.19). Buildinfo confirma Go1.24.6 y gosu v1.19.0. El entrypoint exacto lo usa para cambiar de root a mysql y volver a ejecutar el mismo script; no lo usa como cliente TLS.

Se ejecutó govulncheck v1.8.0, sin wrapper ni exclusiones, en modo binary sobre ese archivo. Base Go actualizada 8oct22:31UTC. Informe JSON: 57 identificadores, 65 observaciones de módulo/paquete y ninguna con función vulnerable. Salida textual: cero vulnerabilidades a nivel de símbolos, tres de paquetes y54 de módulos. El aviso GO-2026-4337 aparece únicamente a nivel de módulo; nm no encuentra símbolos crypto/tls. Tampoco encuentra crypto/x509, net/http, net/url, net/mail, encoding/xml, encoding/asn1 ni archive/tar. Los tres avisos de paquetes corresponden a os.Root/internal.syscall.unix, sin función afectada detectada.

La [documentación de govulncheck](https://pkg.go.dev/golang.org/x/vuln/cmd/govulncheck) distingue análisis de símbolos de versiones y advierte que el modo binary no produce un grafo de llamadas. El exit0 del JSON por sí solo no demuestra ausencia de hallazgos: ese formato sale0 incluso si los hay. Se revisaron además las observaciones y la salida textual. Evidencia privada: storage/image-audit/gosu-review, con buildinfo, símbolos, manifiestos y ambos informes. Resultado limitado al binario linux/amd64 extraído; no certifica arm64, cambios futuros, MySQL completo o Aiven. Se conservan las 47 filas Trivy y no se añade ninguna exclusión. La [política del proveedor](https://raw.githubusercontent.com/tianon/gosu/1.19/SECURITY.md) recomienda contrastar funciones, no reemplazar el ejecutable solo por la versión del compilador.

### ACL, terminal y montajes

- [CVE-2026-54369](https://security-tracker.debian.org/tracker/CVE-2026-54369): exige un llamador privilegiado de funciones ACL basadas en rutas y un atacante que pueda sustituir sus componentes por enlaces. La aplicación no ejecuta setfacl/getfacl ni ofrece operaciones ACL; no admite cargas de archivos o rutas arbitrarias. No se descartan todos los usos indirectos de libacl por herramientas de construcción/administración. Trixie sigue vulnerable; la corrección agrega ABI y no se importa desde sid.
- [CVE-2025-69720](https://security-tracker.debian.org/tracker/CVE-2025-69720): Debian concreta el fallo en analyze_string de la herramienta infocmp, no en cualquier uso de una terminal. EVENTIFY no ejecuta infocmp ni procesa terminfo suministrado por visitantes. Trixie permanece vulnerable. Las cinco filas asociadas al paquete fuente ncurses siguen en el informe.
- [CVE-2026-76642](https://security-tracker.debian.org/tracker/CVE-2026-76642) necesita hooks privilegiados después de un helper de montaje fallido; [CVE-2026-78409](https://security-tracker.debian.org/tracker/CVE-2026-78409) necesita X-mount.subdir autorizado en fstab y enlaces intermedios; [CVE-2026-78410](https://security-tracker.debian.org/tracker/CVE-2026-78410) depende de bind mounts restringidos y cambios privilegiados de permisos/propiedad. El código no invoca mount/nsenter ni configura esas opciones. Compose no da privilegios ni expone el socket Docker. Los volúmenes gestionados por Docker no equivalen a llamar mount desde PHP. No se audita por ello el host ni se elimina el aviso de los paquetes instalados.

### Variantes XML y Python

[CVE-2026-74860](https://security-tracker.debian.org/tracker/CVE-2026-74860) afecta bindings SAX de libxml2 para Python al leer DTD; [CVE-2026-86144](https://security-tracker.debian.org/tracker/CVE-2026-86144) afecta propagación de flags en XInclude. El código servido por EVENTIFY no usa esos bindings ni XInclude. El generador PDF es una herramienta local separada: usa escape de XML para texto, no un endpoint de lectura de documentos externos. Las demás variantes de libxml2/Expat y sus usos indirectos siguen abiertos; no extender esta inferencia a cualquier parser instalado.

Los 19 avisos Python de MySQL están bajo usr/lib/mysqlsh/lib/python3.13/site-packages, no en dependencias PHP. El entrypoint y EVENTIFY no llaman mysqlsh; el cliente de salud es mysql. Los ocho altos son cryptography (3), pyOpenSSL (1) y urllib3 (4). Sus condiciones incluyen operaciones criptográficas específicas, callbacks DTLS, redirects/proxies y respuestas comprimidas/chunked. Esa separación de rutas reduce la exposición directa encontrada, pero no demuestra que los componentes auxiliares sean inocuos en otros usos.

No se ejecuta pip upgrade sobre mysqlsh: el [changelog de pyOpenSSL](https://raw.githubusercontent.com/pyca/pyopenssl/358cbf29c4e364c59930e53a270116249581eaa3/CHANGELOG.rst) restringe25.3.0 a cryptography46.x, mientras varios avisos requieren versiones48–50. La sustitución aislada puede romper compatibilidad. [El aviso de wheels cryptography](https://github.com/pyca/cryptography/security/advisories/GHSA-537c-gmf6-5ccf) exige distinguir OpenSSL incluido en wheels de una compilación desde fuentes; el METADATA por sí solo no lo resuelve. [urllib3 2.8.0](https://github.com/urllib3/urllib3/releases/tag/2.8.0) publica correcciones, pero no certifica la integración con mysqlsh. Mantener seguimiento de una imagen compatible del proveedor; no retirar mysqlsh sin comprobar RPM, entrypoint y herramientas de respaldo.

### Corrección compatible preparada

[Oracle ELSA-2026-73512](https://linux.oracle.com/errata/ELSA-2026-73512.html) corrige CVE-2026-40467/40468/40553 mediante gawk5.1.0-6.el9_8.1. Dockerfile.mysql deriva de la misma imagen oficial y fija únicamente ese paquete desde los repositorios Oracle. No cambia servidor MySQL, entrypoint, usuarios, esquema o volúmenes. Los cuatro Compose usan la nueva construcción. El build falla si no obtiene la versión exacta; no acepta silenciosamente otra.

scripts/test-mysql-image-linux.sh prepara construcción, consulta RPM sin red, exportación para Trivy e integración/controles TLS en bases efímeras. **Construcción, integración y escaneo del parche todavía pendientes**: la sesión no tiene acceso al socket Docker. No iniciar ni recrear instalaciones existentes hasta comprobar el incremento. No publicar este cambio en Render; allí MySQL es administrado por Aiven.

Los avisos medios/bajos/desconocidos quedan inventariados por completo, pero pendientes de análisis individual de condiciones. B07 permanece abierto. Próximo paso concreto: ejecutar el ensayo aislado, escanear su mysql.tar y confirmar eliminación de los tres avisos gawk sin regresiones; después revisar los grupos restantes según exposición.
