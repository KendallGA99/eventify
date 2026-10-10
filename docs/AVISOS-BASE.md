# Avisos de bibliotecas base: condiciones y límites

Referencia histórica: estos resultados corresponden a las imágenes anteriores. Dev.41 corrige el entorno y sus tres imágenes finales no reportan avisos en el escaneo del10oct. Consulte [RUNTIME-CORREGIDO.md](RUNTIME-CORREGIDO.md) y [ESTADO.md](ESTADO.md) para versiones aplicadas, pruebas y límites.


B07/SEG-09. Revisión dirigida de 24 identificadores del informe PHP/Apache dev.32, cuyo código servido también corresponde a dev.33. Complementa [AVISOS-IMAGENES.md](AVISOS-IMAGENES.md) y [INVENTARIO-IMAGENES.md](INVENTARIO-IMAGENES.md). Las páginas oficiales de Debian se consultaron nuevamente; sus copias y el cotejo con el JSON del escáner quedan en el registro privado storage/image-audit/remaining-review.

Las observaciones se basan en código/configuración e inventario de paquetes, no en ataques ni análisis completo de funciones nativas. «No encontrado» no significa que una dependencia nunca pueda usar la función. No se cambian severidades ni se eliminan avisos del informe.

## glibc: trece avisos medios

Se repiten en libc6, libc-bin, libc-dev-bin y libc6-dev: 52 filas para trece identificadores. Debian mantiene Trixie vulnerable en las trece páginas consultadas; no importar glibc de otra distribución. El proceso PHP y sus extensiones pueden llamar glibc indirectamente: buscar un nombre en código PHP no basta para demostrar ausencia en el ejecutable.

| Aviso y fuente oficial | Condición | Revisión del proyecto |
| --- | --- | --- |
| [CVE-2026-18374](https://security-tracker.debian.org/tracker/CVE-2026-18374) | Modo fopen con extensión ccs vacía controlada por el atacante. | WeatherClient abre HTTPS en modo fijo rb; locks de EventWeather usan c. El visitante no decide el modo. No descarta otras llamadas nativas. |
| [CVE-2026-19499](https://security-tracker.debian.org/tracker/CVE-2026-19499) | strfmon/strfmon_l, padding y buffer insuficiente. | No se usan formatos strfmon ni money_format en la aplicación; presentación de importes mediante funciones PHP. Uso indirecto pendiente. |
| [CVE-2026-19542](https://security-tracker.debian.org/tracker/CVE-2026-19542) | tdelete con árbol de profundidad específica y gran cantidad de nodos. | No se expone una API de esos árboles; no se ha demostrado ausencia en todas las bibliotecas nativas. |
| [CVE-2026-5435](https://security-tracker.debian.org/tracker/CVE-2026-5435) | Impresión de registros TSIG mediante funciones DNS obsoletas. | No se ofrecen funciones de diagnóstico/impresión DNS. No equivale a un fallo de toda resolución de nombres. |
| [CVE-2026-6238](https://security-tracker.debian.org/tracker/CVE-2026-6238) | Funciones DNS de depuración ns_printrrf/ns_printrr/fp_nquery con respuesta manipulada. | Debian distingue expresamente estas funciones del camino normal del resolver. El proyecto no las llama directamente. |
| [CVE-2026-6368](https://security-tracker.debian.org/tracker/CVE-2026-6368) | wordexp con WRDE_APPEND y liberación posterior. | No hay expansión de comandos del visitante; sin shell_exec/system/proc_open en app/public/bin. Uso indirecto pendiente. |
| [CVE-2026-6791](https://security-tracker.debian.org/tracker/CVE-2026-6791) | wordexp con usuario muy largo después de una tilde. | No se envían nombres/rutas del visitante a wordexp ni al shell. No aplicar esta observación al host completo. |
| [CVE-2026-77117](https://security-tracker.debian.org/tracker/CVE-2026-77117) | Conversión SHIFT_JISX0213 con estado y buffer de salida reducido. | Aplicación UTF-8/utf8mb4; sin iconv ni selección de esa codificación en app/public/bin. No audita conversiones internas de PHP. |
| [CVE-2026-80489](https://security-tracker.debian.org/tracker/CVE-2026-80489) | Variante EUC_JISX0213 de la conversión anterior. | Misma limitación: no ruta directa encontrada, no ausencia universal certificada. |
| [CVE-2026-8674](https://security-tracker.debian.org/tracker/CVE-2026-8674) | Dominio de búsqueda DNS muy largo en resolv.conf o LOCALDOMAIN. | **Pendiente del entorno.** WeatherClient sí necesita resolver el proveedor. El código no define search/LOCALDOMAIN, pero Docker/host/proveedor pueden hacerlo. No descartado. |
| [CVE-2026-86805](https://security-tracker.debian.org/tracker/CVE-2026-86805) | Binario setuid/setgid, RPATH con ORIGIN, enlaces y carrera; depende también de hardlinks del host. | El proyecto no instala binarios setuid. No se auditaron todos los permisos/RPATH de la base ni los sysctl del host; conservar como condición pendiente. |
| [CVE-2026-89092](https://security-tracker.debian.org/tracker/CVE-2026-89092) | Servicio nscd activo con servidor DNS malicioso. | El inventario de paquetes del JSON no contiene nscd; los entrypoints del proyecto inician Apache/MySQL, no ese servicio. No certifica ausencia en el host. |
| [CVE-2026-95818](https://security-tracker.debian.org/tracker/CVE-2026-95818) | Loader en programas setuid/setgid con RPATH/RUNPATH que comienza con ORIGIN. | No confundirlo con cualquier carga de PHP; falta revisar todos los binarios privilegiados de la imagen/host. No descartado por la búsqueda de código. |

## Compresión, SQLite y parsers

| Aviso y fuente oficial | Condición y resultado limitado |
| --- | --- |
| [CVE-2026-27171](https://security-tracker.debian.org/tracker/CVE-2026-27171) | Funciones CRC combine con ciertos valores producen bucle. No hay llamadas explícitas a estas API en la aplicación; Apache carga deflate, por lo que no declarar que zlib está sin uso. Trixie vulnerable. |
| [CVE-2026-85091](https://security-tracker.debian.org/tracker/CVE-2026-85091) | Escritura gz no bloqueante, buffer externo y gzprintf/gzvprintf. No se encontró esa ruta directa. Hay discrepancia a aclarar: descripción refiere 1.3.1.2–1.3.2, pero Debian marca vulnerable su paquete basado en 1.3.1 e indica discusión del rango. No clasificar como falso positivo sin resolverla. |
| [CVE-2026-50812](https://security-tracker.debian.org/tracker/CVE-2026-50812) | Session Extension de SQLite aplicando un changeset manipulado. EVENTIFY conecta exclusivamente a MySQL mediante PDO, no importa changesets SQLite. Biblioteca instalada no implica base SQLite usada por la aplicación. |
| [CVE-2026-50813](https://security-tracker.debian.org/tracker/CVE-2026-50813) | SQLite, concatenación/merge de changesets. Misma separación de ruta; no se purga la biblioteca por esta inferencia. |
| [CVE-2026-76781](https://security-tracker.debian.org/tracker/CVE-2026-76781) | Catálogo XML con nextCatalog sin atributo catalog. No se admite catálogo/XML del visitante ni se ejecuta un parser de esos documentos en app/public/bin. Uso indirecto pendiente. |
| [CVE-2026-86137](https://security-tracker.debian.org/tracker/CVE-2026-86137) | Parser de grupos de caracteres xmlregexp en libxml2. La validación del proyecto usa expresiones PHP, no xmlregexp; falta demostrar todos los usos nativos. |
| [CVE-2025-66382](https://security-tracker.debian.org/tracker/CVE-2025-66382) | Expat procesando archivo manipulado y alto tiempo de CPU. Descripción y estado de paquete tienen rangos distintos; no descartar solo por comparación textual de versión. No hay endpoint XML, pero post_max_size=6M no impediría por sí solo recibir un archivo de 2 MiB. La ausencia de parser directo es una observación distinta. |
| [CVE-2026-102633](https://security-tracker.debian.org/tracker/CVE-2026-102633) | Overflow de tamaño Expat en plataformas de 32 bits. Imagen exportada/ensayada es amd64; no es evidencia de una ejecución de 32 bits. No se certifican otras arquitecturas ni usos indirectos por esto. |

Los ocho avisos de esta tabla siguen registrados en Trixie como vulnerables. Las correcciones originales y su integración en la distribución se siguen por separado; no hay nueva corrección compatible aplicada en esta revisión.

## Severidad desconocida

UNKNOWN indica falta de clasificación en el informe, no un fallo inocuo. Las seis filas corresponden a tres identificadores:

- [CVE-2026-82560](https://security-tracker.debian.org/tracker/CVE-2026-82560), cuatro paquetes Perl: agotamiento al formatear documentos POD mediante Pod::Text. La aplicación no recibe POD ni llama ese formateador. No cubre herramientas que alguien utilice para documentación en el host.
- [CVE-2026-98373](https://security-tracker.debian.org/tracker/CVE-2026-98373): kernel, movimiento de tablas hugetlb/mremap; no es una función ejecutada por el paquete de cabeceras linux-libc-dev instalado en la imagen.
- [CVE-2026-98374](https://security-tracker.debian.org/tracker/CVE-2026-98374): kernel, estado de retransmisión TCP. El kernel efectivo depende del host/proveedor; no se descarta su aplicabilidad al host por tratarse de un contenedor. Falta revisar versión/parches de ese kernel por separado.

## Próxima comprobación

Priorizar DNS real del entorno (resolv.conf/LOCALDOMAIN), inventario setuid/RPATH y kernel del host antes de dar por evaluadas esas condiciones. Mantener las discrepancias de versiones de zlib/Expat abiertas. Continuar los otros grupos medios/bajos del inventario sin usar esta tabla como aprobación de todos los paquetes.

No se modificó código, configuración, imágenes, datos o permisos. No se repiten integración/TLS ni builds por este registro documental. Este avance no cierra B07 ni convierte dev.33 en estable.

## Comprobaciones adicionales de entorno e imagen

Lecturas realizadas sin modificar configuración:

- Host Linux actual: kernel 7.0.0-38-generic y fs.protected_hardlinks=1. Esto reduce una condición necesaria del aviso86805 en este host; no prueba parches del kernel ni condiciones de Render/Windows. Las dos páginas Ubuntu consultadas para los avisos de kernel no devolvieron información verificable; el estado de esos parches sigue pendiente.
- resolv.conf del host tiene un dominio search/domain de longitud 1; no se imprime su contenido. LOCALDOMAIN no está definido en el proceso de inspección. No se encontró la condición de dominio largo en ese archivo, pero falta revisar el DNS efectivo dentro del contenedor y el proveedor público.
- Se reconstruyeron los archivos setuid/setgid de las capas exportadas de la imagen local dev.32, incluidas las eliminaciones whiteout. Se encontraron 11 ELF: chage, chfn, chsh, expiry, gpasswd, mount, newgrp, passwd, su, umount y unix_chkpwd. readelf -dW no encontró RPATH/RUNPATH en ninguno. No se ejecutaron esos binarios en el host. La configuración de la imagen no contiene LOCALDOMAIN.

Esta revisión elimina la duda de inventario RPATH de esos 11 ELF para la imagen local exacta: no se encontró el requisito RPATH/RUNPATH de los avisos86805/95818. No se extrapola al proceso desplegado en Render, otra arquitectura, programas añadidos, bibliotecas privilegiadas de terceros o el host. mount/umount siguen instalados con setuid; no se cambian sus permisos por esta inspección. Datos en setid-rpath.json del registro privado.

Cobertura cotejada automáticamente con el JSON original: 24 identificadores corresponden a 66 filas,60 MEDIUM y 6 UNKNOWN. Es cobertura de condiciones consultadas; las observaciones con límites y discrepancias permanecen abiertas. Los restantes avisos del inventario no se consideran aprobados por esta revisión.


## DNS efectivo de la copia dev.33

La lectura posterior desde el contenedor web de eventify-clean-20261009035638-598f411d registra x86_64,un dominio search/domain de longitud1 y LOCALDOMAIN ausente. Evidencia dns-runtime.json en storage/dev33-persistence-lBxRJbW3,ensayo exit0. No se encontró la condición de dominio largo de CVE-2026-8674 en esta copia. El pendiente anterior de DNS queda superado únicamente para este runtime; proveedor público y otras plataformas siguen sin comprobarse. No cambia el estado vulnerable del paquete ni certifica otros avisos del resolver.
