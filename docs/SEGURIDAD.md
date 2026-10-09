# Revisión de seguridad y dependencias

Revisión dirigida B07/RNF-03/SEG-01–09, 2026-10-08. Aplicación pública dev.26 y copia limpia Linux dev.28; cada ensayo indica su versión y alcance. Este documento registra evidencia específica y límites; no certifica ausencia de vulnerabilidades ni sustituye las verificaciones finales del despliegue.

## Dependencias de aplicación

| Biblioteca | Versión fijada | Licencia | Resultado |
| --- | --- | --- | --- |
| bacon/bacon-qr-code | 3.1.1 | BSD-2-Clause | composer.lock fija procedencia y referencia; auditoría sin avisos |
| dasprid/enum | 1.0.7 | BSD-2-Clause | Dependencia transitiva de QR; auditoría sin avisos |
| jQuery | 4.0.0 completo local | MIT | Huella coincide con ARQUITECTURA.md; encabezado y licencia local conservados |

Se ejecutó Composer 2.10.3 `audit --locked --no-plugins --no-scripts --format=json`, usando PHP 8.3 temporal con phar/iconv fuera del proyecto. Resultado exit0: advisories=[], abandoned=[], filter=[]. La auditoría consulta avisos sobre las versiones del lock; no instaló ni actualizó dependencias, no ejecutó scripts ni cambió la plataforma objetivo PHP8.4. No equivale a escanear PHP/Apache/MySQL o todos los paquetes de la imagen.

SHA-256 real de jQuery local: 39a546ea9ad97f8bfaf5d3e0e8f8556adb415e470e59007ada9759dce472adaa. Los [avisos oficiales de jQuery](https://github.com/jquery/jquery/security/advisories) GHSA-jpcq-cgw6-v4j6 y GHSA-gxr4-xjj5-5px2 afectan versiones anteriores a3.5.0, según sus rangos publicados; no corresponden a4.0.0. Esto no asegura ausencia de futuros avisos ni permite insertar HTML no confiable. [Lanzamiento oficial4.0.0](https://blog.jquery.com/2026/01/17/jquery-4-0-0/). [Avisos BaconQrCode](https://github.com/Bacon/BaconQrCode/security/advisories) y [Enum](https://github.com/DASPRiD/Enum/security/advisories) consultados como fuentes complementarias; el resultado principal automatizado es Composer.

## Protecciones inspeccionadas

- Auth consulta identidad/rol activo en servidor, registra usuarios con rol USUARIO autoritativo, hash PASSWORD_DEFAULT y rehash al autenticar. La limitación de intentos usa MySQL con lock y no confía en cabeceras de proxy del cliente.
- Session regenera al ingresar, destruye al salir y caduca por inactividad de30min. Cookies HttpOnly/SameSite=Lax y Secure en producción/HTTPS; el entorno HTTP local permite iniciar sesión.
- ReservationController exige CSRF y usuario de servidor en escritura; las reglas de precio, propiedad, cupo y transacción están respaldadas por la integración registrada en B07. Esta lectura dirigida no repitió esas211 comprobaciones.
- Catálogo e historial insertan HTML renderizado por servidor en AJAX. Las vistas inspeccionadas escapan datos con htmlspecialchars ENT_QUOTES/ENT_SUBSTITUTE UTF-8; mantener ese contrato al agregar campos. `.html()` no vuelve segura una cadena arbitraria.
- WeatherClient fija host/ruta HTTPS de Open-Meteo, no sigue redirecciones, verifica certificado/hostname, limita espera a3s y respuesta a128KiB; no recibe URL arbitraria del visitante. WeatherBudget conserva cuota en MySQL.
- Git actual solo contiene storage/.gitkeep; no .env personal, dumps, output, backups ni vendor versionados. .gitignore/.dockerignore excluyen esos datos. Comprobación por nombres actuales, no auditoría exhaustiva de todo el historial o contenido de imágenes.

## HTTP real de lectura: quince comprobaciones

En copia limpia dev.17, sin autenticar ni mutar datos: GET /ingresar200; X-Content-Type-Options nosniff, X-Frame-Options DENY, Referrer-Policy strict-origin-when-cross-origin, Cache-Control no-store y CSP frame-ancestors none. Cookie HttpOnly/SameSite=Lax, sin Secure en HTTP local.

GET /.env, /storage/demo-credentials.json, /composer.lock y /app/bootstrap.php devolvieron404. GET /admin303 con Location /ingresar. GET /api/reservas405, PUT /ingresar405 y GET /salud200. No se imprimieron cookies ni contenido privado. El primer intento de comprobación esperaba302 para /admin y falló; al inspeccionar el comportamiento previsto, se verificó el303 real y su destino, sin cambiar servidor ni relajar autorización.

## Pendientes de cierre

- Auditoría de imagen base/PHP/Apache/MySQL y revisión completa de secretos/historial según riesgo de entrega; esta revisión cubre dependencias de aplicación y controles indicados.
- Cookies HTTPS y conexión externa TLS ya tienen evidencia. Las sesiones de Render son efímeras: el usuario debe ingresar de nuevo tras recrear la web; MySQL conserva sus reservas. No exigir ni afirmar persistencia de sesión no implementada.
- Casos restantes de interfaz, permisos y errores; Firefox/Windows. La copia Linux dev.28 ya conserva la reserva nueva, su cancelación y ticket después de recrear web/MySQL. El recorrido HTTP autenticado del destino restaurado anterior ya tiene evidencia.
- Revisar avisos otra vez antes de entregar si cambia una dependencia o transcurre tiempo relevante. Mantener las licencias BSD de dependencias dentro del vendor instalado por Composer y los avisos MIT de jQuery al redistribuir.


## Verificación de la web pública

En dev.26 se ejecutaron 81 controles HTTPS con `tests/public_security.py`: cookies y sesión, CSRF, accesos administrativos, historial y propiedad de ticket/QR. El script exige un origen HTTPS y cuentas ficticias privadas; no sigue redirecciones ni muestra credenciales. Los POST de negocio se rechazan por CSRF inválido y no crean reservas. Véase ESTADO para el alcance y el comando. Las sesiones propias de la prueba se cerraron.

La revisión de 505 blobs únicos del historial no encontró los secretos reales conocidos de configuración/cuentas ni rutas de datos privados. Esta revisión dirigida complementa la exclusión de archivos; no sustituye el escaneo de vulnerabilidades de las imágenes, que sigue pendiente.


## Imágenes base: escaneo del 8 de octubre

Trivy 0.75.0 oficial, archivo Linux amd64 contrastado con SHA-256 del release; herramienta y licencia fuera de Git. Escaneo remoto de las referencias exactas de Dockerfile y Compose, sin servicio Docker ni enviar fuente/configuración privada. Base de avisos actualizada 2026-10-08T19:05:21Z; descargada 2026-10-09T00:36:55Z. Plataforma linux/amd64, scanner vuln; informes privados storage/image-audit/php-base.json y mysql-base.json. Ambos procesos terminaron con exit0: significa que el análisis terminó, no ausencia de avisos. No se usó ignore-unfixed ni exclusiones de avisos. MySQL se ejecutó con telemetría desactivada; el primer ensayo PHP usó los valores predeterminados del escáner.

| Base | Filas de avisos | Identificadores distintos | Críticos | Altos | Filas con versión corregida |
| --- | ---: | ---: | ---: | ---: | ---: |
| PHP/Apache, Debian 13.7 | 2015 | 1227 | 2 | 145 | 4 |
| MySQL, Oracle Linux 9.8 y auxiliares | 69 | 69 | 1 | 29 | 68 |

Las filas PHP repiten avisos entre paquetes del mismo origen; no equivalen a 2015 vulnerabilidades distintas explotables por la web. El scanner no encontró archivos de dependencias de lenguaje en esa base: no certifica por separado el PHP compilado desde fuente ni la imagen construida de EVENTIFY. Composer mantiene su auditoría independiente.

Revisión inicial de los críticos:

- CVE-2026-6653 corresponde a libxml2 y XML manipulado. El [registro Debian](https://security-tracker.debian.org/tracker/CVE-2026-6653) mantiene vulnerable la versión Trixie y lo clasifica como problema menor sin DSA. Búsqueda en app/bin/public no encontró DOMDocument, simplexml, xml_parse ni loadXML; esto reduce la ruta evidente de entrada, pero no demuestra ausencia de uso transitivo. No marcar resuelto.
- CVE-2026-43185 corresponde al kernel/ksmbd y aparece asociado a linux-libc-dev. El [registro Debian](https://security-tracker.debian.org/tracker/CVE-2026-43185) describe el fallo del servicio SMB del kernel. Un paquete de cabeceras no demuestra que ese servicio esté ejecutándose en el contenedor. Evaluación del kernel del host/proveedor queda fuera de este escaneo.
- CVE-2025-68121 aparece en Go incorporado a usr/local/bin/gosu de la base MySQL. El [aviso Go](https://pkg.go.dev/vuln/GO-2026-4337) requiere reanudación TLS y modificación de configuración de autoridades. No equivale a un fallo demostrado del TLS del servidor MySQL o PDO; falta revisar alcanzabilidad en ese ejecutable. No excluir el aviso sin evidencia.

Cuatro filas corregibles PHP corresponden a xz-utils/liblzma5, dos identificadores para dos paquetes: versión base 5.8.1-1+deb13u1, corrección 5.8.1-1+deb13u2 según [Debian](https://security-tracker.debian.org/tracker/TEMP-1147318-639065). La construcción instala paquetes mediante apt y puede actualizar dependencias: comprobar la imagen final antes de proponer el parche. MySQL incluye avisos de gawk, Python y gosu; la base Aiven administrada no es esta imagen local y no se certifica con este informe.

Consulta directa del registro Docker confirmó que las etiquetas oficiales php:8.4-apache y mysql:8.4 todavía apuntan a los mismos digests fijados. No hay actualización de esas etiquetas que elimine los avisos en este momento. Sin cambios de imágenes, paquetes, MySQL, datos o despliegue. Siguiente paso: escanear la imagen exacta construida de la copia dev.28 y revisar correcciones/alcanzabilidad. Escaneo completado de bases; aceptación de seguridad permanece abierta. [Método oficial de Trivy](https://www.trivy.dev/docs/latest/guide/target/container_image/).


## Imagen construida dev.28 y parche dev.29

Exportación real del usuario: storage/image-audit/eventify-dev28.tar (172 MiB), ID de índice OCI ee251f7ebd2d846f00b89aa1058f7c3c8c5571b40d47350eb5498d554eeeeb78. Verificada cadena índice→manifest amd64→config y coincidencia con ImageID del informe:976feb32a34564fa225658f7fe5167f65a1a61e7a3f1eef64fd75772f28031f2. La primera comparación config/índice dio distinto por ser identificadores de objetos OCI diferentes; la comparación correcta de la cadena completa sí coincide. No confundir con una imagen equivocada.

Ejecutado Trivy image --input con telemetría desactivada, scanner vuln y base ya descargada, exit0. Informe privado eventify-dev28.json:186 paquetes Debian, mismos2015avisos/doscríticos/145altos; vendor/composer/installed.json detectado, cero avisos de las bibliotecas instaladas. liblzma5 y xz-utils permanecen en5.8.1-1+deb13u1; la instalación de unzip no los actualizó. No se escanearon secretos en esta ejecución ni se certifica toda la seguridad del runtime.

Dev.29 fija ambos paquetes en5.8.1-1+deb13u2, tanto Dockerfile como Dockerfile.public. No cambia aplicación, esquema ni imagen MySQL. scripts/test-image-linux.sh construye variantes con nombres propios, comprueba ambas versiones, sintaxis y todas las unidades existentes, y guarda tar/IDs/registros privados para otro escaneo. Procesos sin red, puertos, montajes ni MySQL; no altera contenedores activos. Bash-n/diff aprobados; construcción, unidades en estas imágenes y escaneo posterior aún pendientes. No desplegar ni afirmar parche efectivo hasta comprobarlos. Los demás avisos siguen abiertos y se revisan por separado.
