# Revisión de seguridad y dependencias

Revisión dirigida B07/RNF-03/SEG-01–09, 2026-10-08. Código dev.17; copia limpia aislada en 8083. Este documento registra evidencia específica y límites; no certifica ausencia de vulnerabilidades ni sustituye las verificaciones finales del despliegue.

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
- HTTPS público/cookies Secure, TLS MySQL externo real y persistencia de sesiones en proveedor.
- Casos restantes de UI, permisos y errores; Firefox/Windows, copia tras nueva reserva/recreación y HTTP restaurado.
- Revisar avisos otra vez antes de entregar si cambia una dependencia o transcurre tiempo relevante. Mantener las licencias BSD de dependencias dentro del vendor instalado por Composer y los avisos MIT de jQuery al redistribuir.
