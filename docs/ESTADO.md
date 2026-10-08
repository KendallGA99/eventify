# EVENTIFY: estado actual y continuidad

Actualizado 2026-10-08, America/Costa_Rica. Referencia operativa resumida; [HISTORIAL.md](HISTORIAL.md) conserva los registros anteriores íntegros, incluidas incidencias y resultados superados. Consultar evidencias por bloque. No sumar pruebas de ejecuciones diferentes.

## Alcance y autorización

- Proyecto académico PHP/MySQL, HTML/CSS/JS/jQuery/AJAX; desarrollo Linux, entrega Docker Compose Linux/Windows. Sin gastos autorizados.
- Construcción por bloques y commits/push progresivos autorizados. Repositorio privado [USUARIO_DESTINO/eventify](https://github.com/USUARIO_DESTINO/eventify), remoto SSH. No modificar Código Tico.
- URL pública obligatoria y pendiente. Render + Aiven MySQL son candidatos documentados, sin cuentas/servicios externos creados ni autorización concreta para crearlos. No certificar Windows, publicación ni defensa por inferencia.

## Versión e instancias

- Rama: `codex/b08-rechazo-sin-tls`. VERSION `0.1.0-dev.23`, commit `3badc85` y etiqueta `v0.1.0-dev.23` publicados y referencias remotas verificadas (prueba real pendiente); última evidencia ejecutada dev.22 (`4947823`, etiqueta `v0.1.0-dev.22`). Ninguna versión estable; CI manual preparado, no ejecutado.
- `main`: `3da680b74b41fa3da4069879edc1f649de9b97f2`. No reescribir historial ni hacer push forzado. Rollback de código no restaura MySQL.
- Destinos existentes, según última verificación: normal `localhost:8080`; QA `localhost:8081` / `eventify-qa`; copia limpia `localhost:8083` / `eventify-clean-20261008160801-e96614c4`; restauración `localhost:8082` / `eventify-restore-20261008154302-2b8eb996`. No afirmar disponibilidad actual sin consultar; conservar sus volúmenes y datos.
- Docker 29.1.3 / Compose 2.40.3. El agente no tiene acceso al socket y sudo no interactivo exige contraseña; las operaciones Docker necesarias se ejecutan en terminal del usuario. No modificar grupos/socket/sudoers para eludirlo.
- Credenciales, planes, certificados, registros y snapshots privados en `storage/` y respaldos en `backups/`, ignorados por Git y fuera de la raíz pública. No imprimir claves ni adjuntar estos archivos al repositorio.

## Evidencia vigente

| Área | Resultado confirmado | Alcance pendiente |
| --- | --- | --- |
| Desarrollo | PHP 8.4, sintaxis, QR, conexión y 266 comprobaciones unitarias; última ejecución registrada 15:42:00 UTC, exit 0 | No incluye por inferencia todos los archivos incorporados después |
| Integración | 16:31:25 UTC, exit 0: 219 comprobaciones MySQL/HTTP, tres migraciones; carreras de reserva, administración y presupuesto meteorológico | No sumar las pruebas TLS a las 219; no certifica Windows ni publicación |
| Persistencia y restauración | QA conserva diez tablas y archivos; restauración aislada exacta. `existing-iAQixJNV`, 16:41:44 UTC, exit 0: copia limpia conserva diez tablas, ocho reservas/ocho tickets tras recrear; restauración coincide antes del navegador | Conservar destinos; pruebas específicas no equivalen a todos los escenarios |
| Copia limpia Linux | ZIP de código dev.17 en ruta con espacios: arranque, reserva/cancelación, ticket/QR y posterior persistencia aprobados | Copia limpia de la entrega final y Windows pendientes |
| Navegador | Chrome: login/logout de ambos roles, reserva/ticket/QR, cancelación, dashboard y filtros; restauración autenticada. IAB: permisos administrativos, reserva duplicada y ticket/QR ajenos rechazados | Registro completo, teclado/zoom, fallo de red real y casos administrativos restantes; Firefox pendiente |
| Diseño adaptable y API | Catálogo, cuenta, registro, ticket/QR y dashboard observados en tamaños indicados en B07; orientación móvil sin desbordamiento. Open-Meteo real visible en QA | Matriz final Chrome/Firefox y caída externa real en UI pendientes |
| Windows | PowerShell 5.1 real: seis scripts sin errores de parser; VM Windows 11 LTSC Evaluation de 4 GiB identificada | Docker/WSL y ejecución completa sin verificar; recursos/soporte limitan el ensayo |
| Seguridad | Composer audit sin avisos, procedencia/licencias/huella jQuery y 15 controles HTTP dirigidos aprobados | Revisión completa de imágenes/historial y cookies HTTPS públicas pendientes |
| Arranque público aislado | dev.20: imagen PHP 8.4 construida, tres rechazos reales y 25 guardas simuladas. dev.21: MySQL 8.4 con TLS, CA/hostname incorrectos rechazados, tres migraciones, Apache/HTTP interno y recreación aprobados | No demuestra proveedor externo, servidor sin TLS ni URL HTTPS |
| Cuentas dev.22 | `public-tls-5NcIVnVj`, 19:39:57 UTC, exit 0: CLI creó tres cuentas ficticias con roles/hashes válidos; repetición, entorno local y plan inválido rechazados sin cambios; mismas cuentas válidas tras recrear web | No certifica login público ni concurrencia de aprovisionamiento |
| Documentos y paquete | PDF borrador dev.17 de 15 páginas revisado. ZIP desde `e03973238467`, 163 archivos, CRC/huellas correctos e incluye guías Windows/defensa | Actualizar PDF/ZIP a versión final; PDF se entrega separado; revisión y defensa humana pendientes |

## Bloques y próximo trabajo

- B01/B02: stack, esquema y migraciones comprobados. Portabilidad final permanece abierta.
- B03–B06: implementación e integración aprobadas; aceptación de navegador parcial. Consultar [MATRIZ.md](MATRIZ.md).
- B07 abierto: completar Firefox/Windows y casos restantes de UI/seguridad. No repetir la suite extensa sin cambios, fallos o dudas relevantes.
- B08 activo: GitHub privado y preparación pública operativos; TLS y cuentas comprobados en ensayo aislado. Próximo control técnico: ejecutar ensayo de rechazo de servidor sin TLS preparado en dev.23; después preparar autorización concreta de servicios gratuitos y comprobar proveedor/HTTPS/URL/flujos/persistencia externos. No crear servicios todavía.
- B09 abierto: actualizar documento técnico y paquete cuando la evidencia se consolide; entrega final exige instalación del receptor, entregables completos y evidencia humana real.

## Último resultado y límites

El agente leyó `storage/public-tls-5NcIVnVj/status.txt` y `test.log`: inicio `2026-10-08T19:39:57Z`, `exit_code=0`, proyecto `eventify-tls-ee40bb5ec1e6`. PHP 8.4/MySQL 8.4 con TLS válido, tres migraciones, rechazos de CA/hostname y cuentas verificados antes y después de recrear únicamente web. El CLI de aprovisionamiento fue ejecutado de forma explícita; no existe siembra automática pública. Los hashes se comprobaron mediante password_verify, sin revelar claves.

El ensayo no expuso puertos y retiró solo sus contenedores/red/base tmpfs efímeros previstos; certificados, plan y registros privados se conservan. No modificó bases existentes ni creó cuentas externas. HTTP interno no prueba HTTPS público; roles/hashes no prueban login de navegador. Sin repetir las 219 pruebas de negocio ni cambiar VERSION/etiqueta por documentar este resultado.

## Incremento dev.23 preparado: servidor sin TLS

`docker-compose.no-tls-test.yml`, `tests/integration/public-no-tls.php` y `scripts/test-public-no-tls-linux.sh`: servidor MySQL 8.4 con tls_version vacío, control TCP sin cifrado y base vacía, rechazo mediante Database production con CA válida, arranque público que debe salir con error antes de servir/migrar y control final. Proyecto aleatorio propio, red interna, tmpfs, sin puertos/volúmenes persistentes; retira solo recursos efímeros propios y conserva registros privados. No cambia las políticas de seguridad ni bases existentes.

Compose config real sin daemon verificó aislamiento/configuración; Bash y diff correctos. Manual oficial MySQL 8.4 contrastado antes de ejecutar (opciones antiguas skip-ssl/have_ssl eliminadas; ensayo usa tls_version). PHP nuevo y MySQL real pendientes: `docker info` denegado; `sudo -n docker info` requiere contraseña. Necesario en terminal: `cd "/home/usuario/Documentos/ChatGPT/Proyecto U" && bash scripts/test-public-no-tls-linux.sh`. Leer `storage/public-no-tls-ID/status.txt` y logs antes de afirmar aprobado. No repetir la suite 219 para este incremento aislado.
