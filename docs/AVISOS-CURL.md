# curl: avisos medios y rutas utilizadas

B07/SEG-09. Diez identificadores, veinte filas MEDIUM en curl/libcurl4t64 del informe PHP/Apache dev.32. Código de aplicación y Dockerfile PHP iguales en dev.33. Se consultaron las páginas originales de curl y el estado Debian de cada identificador; copias y cobertura en storage/image-audit/curl-medium-review. Las diferencias de severidad se registran sin cambiar el informe.

## Uso en EVENTIFY

WeatherClient utiliza fopen/streams PHP con destino HTTPS fijo, validación de certificado/nombre y redirecciones deshabilitadas. No se encontraron curl_init, curl_exec, curl_multi ni opciones CURLOPT en app/public/bin. Esto separa el cliente meteorológico de los avisos libcurl, pero no demuestra ausencia de todas las llamadas de dependencias nativas.

Ambos Dockerfile sí ejecutan curl -fsSL para descargar Composer2.10.3 desde getcomposer.org; después verifican una huella SHA-256 fija antes de ejecutarlo. Los guiones test-public-tls-linux.sh y verify-existing-linux.sh ejecutan curl contra HTTP local para consultar códigos de respuesta. No incluyen opciones de cookies, netrc, Negotiate, Digest, certificado cliente o early data. La autenticación y sesiones de EVENTIFY se prueban por otros clientes; no son autenticación Digest de curl.

## Condiciones por identificador

| Aviso original | Condición | Resultado limitado |
| --- | --- | --- |
| [CVE-2026-10536](https://curl.se/docs/CVE-2026-10536.html) | Árbol HTTP/2 STREAM_DEPENDS, reset y cleanup del handle; proveedor Low. | Proveedor excluye CLI; la aplicación no configura esos handles. No es un fallo de todo HTTP/2. |
| [CVE-2026-11856](https://curl.se/docs/CVE-2026-11856.html) | Reutilizar handle Digest entre dos orígenes; proveedor Medium. | Proveedor excluye CLI; no hay ese cliente autenticado en la aplicación. |
| [CVE-2026-19931](https://curl.se/docs/CVE-2026-19931.html) | Negotiate, credenciales vacías y cambio del usuario ambiental; proveedor Medium. | También afecta CLI, pero los comandos del proyecto no activan Negotiate. No extrapolar a otros comandos del host. |
| [CVE-2026-80229](https://curl.se/docs/CVE-2026-80229.html) | Pool TLS multi y vida del contexto de proveedores OpenSSL3; proveedor Low. | También afecta CLI. Hay descarga HTTPS real: no se descarta. No se comprobó toda configuración efectiva de proveedores OpenSSL ni la vida de conexiones durante la construcción. Mantener abierto hasta parche compatible o verificación específica. |
| [CVE-2026-8924](https://curl.se/docs/CVE-2026-8924.html) | Motor de cookies y dominio con punto final que evade Public Suffix List; proveedor Low. | Destino Composer sin punto final y comandos sin motor de cookies explícito. Cookies PHP del navegador son una implementación distinta. Configuración externa del cliente no auditada por esta búsqueda. |
| [CVE-2026-8926](https://curl.se/docs/CVE-2026-8926.html) | netrc habilitado, usuario en URL y contraseña tomada de otro usuario; proveedor Low. | URL de descarga sin usuario y sin opción netrc en los comandos. No se inspeccionaron archivos privados netrc del host. |
| [CVE-2026-8932](https://curl.se/docs/CVE-2026-8932.html) | Reuso del pool después de cambiar opciones de clave/certificado mTLS; proveedor Low. | Proveedor excluye CLI; no hay handles mTLS libcurl del proyecto. La CA de MySQL mediante PDO no es esa configuración. |
| [CVE-2026-9079](https://curl.se/docs/CVE-2026-9079.html) | Limpiar credenciales de proxy sin eliminar realmente su estado anterior; proveedor Medium. | Proveedor excluye CLI; no hay esa secuencia de handles en el código. No afirmar ausencia de proxies del entorno. |
| [CVE-2026-9080](https://curl.se/docs/CVE-2026-9080.html) | curl_easy_pause dentro de CURLMOPT_SOCKETFUNCTION; proveedor Low. | Proveedor excluye CLI; callback no implementado por la aplicación. |
| [CVE-2026-9545](https://curl.se/docs/CVE-2026-9545.html) | HTTP/3 ngtcp2/nghttp3, sesión TLS en caché y early data habilitado; proveedor Low. | Comandos revisados sin HTTP/3 ni early data explícitos. HTTPS por sí solo no satisface estas condiciones; configuración externa y compilación exacta no quedan certificadas. |

## Distribución y límites

Las diez páginas Debian consultadas mantienen Trixie8.14.1-2+deb13u5 vulnerable. Las correcciones originales de curl y el paquete distribuido deben comprobarse por separado: no importar un binario de sid ni cambiar la base sin probarlo. En particular, conservar [el estado Debian de80229](https://security-tracker.debian.org/tracker/CVE-2026-80229) junto con su recomendación original de actualización o restricción del reuso.

Los comandos actuales no comienzan con -q: curl puede leer configuración predeterminada del usuario; Docker también puede recibir variables de proxy durante una construcción. Esta revisión de opciones del proyecto no certifica ese estado externo. La huella de Composer comprueba el archivo antes de ejecutarlo, pero no corrige un fallo de memoria ocurrido durante su descarga. No se ejecutó una explotación ni un análisis completo de dependencias nativas.

No hay parche nuevo aplicado, ni se eliminan curl/libcurl para bajar el contador. Sin cambios de código o imagen, no corresponde repetir builds o integración por este registro. B07 permanece abierto y dev.33 continúa como desarrollo.
