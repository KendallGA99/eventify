# EVENTIFY: estado actual y continuidad

Actualizado 2026-10-08, America/Costa_Rica. Referencia operativa resumida; [HISTORIAL.md](HISTORIAL.md) conserva los registros anteriores íntegros, incluidas incidencias y resultados superados. Consultar evidencias por bloque. No sumar pruebas de ejecuciones diferentes.

## Alcance y autorización

- Proyecto académico PHP/MySQL, HTML/CSS/JS/jQuery/AJAX; desarrollo Linux, entrega Docker Compose Linux/Windows. Sin gastos autorizados.
- Construcción por bloques y commits/push progresivos autorizados. Repositorio privado [USUARIO_DESTINO/eventify](https://github.com/USUARIO_DESTINO/eventify), remoto SSH. No modificar Código Tico.
- URL pública obligatoria y pendiente. Usuario autorizó Render Free + Aiven MySQL Free e integración Render limitada a USUARIO_DESTINO/eventify al responder «dale» el 2026-10-08; gasto máximo ₡0, sin tarjeta ni planes pagados. Sin servicios externos creados todavía. No certificar Windows, publicación ni defensa por inferencia.

## Versión e instancias

- Rama: `codex/b08-rechazo-sin-tls`. VERSION `0.1.0-dev.24`, commit `6baa754` y etiqueta `v0.1.0-dev.24` publicados y referencias remotas verificadas (ensayo sin TLS aprobado); dev.23 `3badc85`/`v0.1.0-dev.23` publicado, ensayo parcial y fallo del evaluador; cuentas verificadas en dev.22 (`4947823`, etiqueta `v0.1.0-dev.22`). Ninguna versión estable; CI manual preparado, no ejecutado.
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
| Arranque público aislado | dev.20: imagen PHP 8.4 construida, tres rechazos reales y 25 guardas simuladas. dev.21: MySQL 8.4 con TLS, CA/hostname incorrectos rechazados, tres migraciones, Apache/HTTP interno y recreación aprobados | No demuestra proveedor externo ni URL HTTPS; servidor sin TLS aprobado en dev.24 |
| Cuentas dev.22 | `public-tls-5NcIVnVj`, 19:39:57 UTC, exit 0: CLI creó tres cuentas ficticias con roles/hashes válidos; repetición, entorno local y plan inválido rechazados sin cambios; mismas cuentas válidas tras recrear web | No certifica login público ni concurrencia de aprovisionamiento |
| Documentos y paquete | PDF borrador dev.17 de 15 páginas revisado. ZIP desde `e03973238467`, 163 archivos, CRC/huellas correctos e incluye guías Windows/defensa | Actualizar PDF/ZIP a versión final; PDF se entrega separado; revisión y defensa humana pendientes |

## Bloques y próximo trabajo

- B01/B02: stack, esquema y migraciones comprobados. Portabilidad final permanece abierta.
- B03–B06: implementación e integración aprobadas; aceptación de navegador parcial. Consultar [MATRIZ.md](MATRIZ.md).
- B07 abierto: completar Firefox/Windows y casos restantes de UI/seguridad. No repetir la suite extensa sin cambios, fallos o dudas relevantes.
- B08 activo: GitHub privado y preparación pública operativos; TLS y cuentas comprobados en ensayo aislado. Ensayo sin TLS dev.24 aprobado. Propuesta PUBLICACION-SOLICITUD.md autorizada. Próximo paso: acceso a las cuentas, planes gratuitos reales y permisos GitHub restringidos; después proveedor/HTTPS/URL/flujos/persistencia externos.
- B09 abierto: actualizar documento técnico y paquete cuando la evidencia se consolide; entrega final exige instalación del receptor, entregables completos y evidencia humana real.

## Último resultado y límites

El agente leyó `storage/public-tls-5NcIVnVj/status.txt` y `test.log`: inicio `2026-10-08T19:39:57Z`, `exit_code=0`, proyecto `eventify-tls-ee40bb5ec1e6`. PHP 8.4/MySQL 8.4 con TLS válido, tres migraciones, rechazos de CA/hostname y cuentas verificados antes y después de recrear únicamente web. El CLI de aprovisionamiento fue ejecutado de forma explícita; no existe siembra automática pública. Los hashes se comprobaron mediante password_verify, sin revelar claves.

El ensayo no expuso puertos y retiró solo sus contenedores/red/base tmpfs efímeros previstos; certificados, plan y registros privados se conservan. No modificó bases existentes ni creó cuentas externas. HTTP interno no prueba HTTPS público; roles/hashes no prueban login de navegador. Sin repetir las 219 pruebas de negocio ni cambiar VERSION/etiqueta por documentar este resultado.

## Incremento dev.23 preparado: servidor sin TLS

`docker-compose.no-tls-test.yml`, `tests/integration/public-no-tls.php` y `scripts/test-public-no-tls-linux.sh`: servidor MySQL 8.4 con tls_version vacío, control TCP sin cifrado y base vacía, rechazo mediante Database production con CA válida, arranque público que debe salir con error antes de servir/migrar y control final. Proyecto aleatorio propio, red interna, tmpfs, sin puertos/volúmenes persistentes; retira solo recursos efímeros propios y conserva registros privados. No cambia las políticas de seguridad ni bases existentes.

Compose config real sin daemon verificó aislamiento/configuración; Bash y diff correctos. Manual oficial MySQL 8.4 contrastado antes de ejecutar (opciones antiguas skip-ssl/have_ssl eliminadas; ensayo usa tls_version). PHP nuevo y MySQL real pendientes: `docker info` denegado; `sudo -n docker info` requiere contraseña. Necesario en terminal: `cd "/home/usuario/Documentos/ChatGPT/Proyecto U" && bash scripts/test-public-no-tls-linux.sh`. Leer `storage/public-no-tls-ID/status.txt` y logs antes de afirmar aprobado. No repetir la suite 219 para este incremento aislado.

## Dev.23 ejecutado y corrección dev.24

`storage/public-no-tls-HVqp1VtN`, inicio `2026-10-08T19:50:39Z`, exit_code=1; proyecto `eventify-no-tls-34356d6fced7`. Build y sintaxis PHP 8.4 aprobados. Primer probe: MySQL 8.4 disponible sin TLS, Database production rechaza y base vacía. rejection.log: preflight válido, Apache Syntax OK y error de conexión previsto. Script abortó al exigir código 1 del wrapper Compose; no registró el valor observado ni ejecutó el probe posterior. No afirmar suite completa aprobada ni causa concreta del código. Proyecto/red efímeros retirados; bases existentes conservadas.

Dev.24 conserva el job de rechazo hasta inspeccionar su State real: detenido, ExitCode 1, sin OOM/error y log previsto, sin servir/migrar. Código de Compose queda registrado aparte. Limpieza limitada al nombre y etiqueta del proyecto generado. Ocho controles del evaluador (incluido rechazo de estados activo/exitoso/OOM/runtime/migración/Apache) y Bash/diff correctos. Nueva ejecución Docker pendiente; socket denegado y sudo no interactivo requieren terminal del usuario. Comando: `cd "/home/usuario/Documentos/ChatGPT/Proyecto U" && bash scripts/test-public-no-tls-linux.sh`.

## Dev.24: rechazo sin TLS aprobado

`storage/public-no-tls-QnTPzu56`, inicio `2026-10-08T19:55:27Z`, exit_code=0; proyecto `eventify-no-tls-bedcc85fc265`. PHP 8.4 sin errores sintácticos. Dos probes antes/después del arranque confirman MySQL 8.4 disponible sin TLS, rechazo por Database production y base vacía. State real del job: exited, Running=false, ExitCode=1, OOMKilled=false, Error vacío; terminó 19:55:41 UTC. Log confirma preflight/Apache válidos y error de conexión previsto, sin migraciones ni Apache sirviendo. Compose agotó la espera (código 124), registrado aparte; no confundir ese timeout con fallo del rechazo de la aplicación.

Retirados únicamente job, db y red efímeros propios; plan/certificados/registros privados conservados y bases existentes intactas. Control servidor sin TLS cerrado con este alcance; sin nueva suite 219 ni cambio de VERSION/etiqueta por documentar. Proveedor/HTTPS/URL pública, Windows/Firefox y entrega final pendientes. Propuesta revisable [PUBLICACION-SOLICITUD.md](PUBLICACION-SOLICITUD.md); no crear servicios antes de autorización concreta.

## B08: publicación autorizada; acceso pendiente

Usuario respondió «dale» a propuesta concreta Render Free/Aiven MySQL Free, integración GitHub solo repositorio privado EVENTIFY y publicación HTTPS con datos ficticios/sin tarjeta/pagos. Se abrieron pestañas del navegador integrado: Render→GitHub login y Aiven Console login. Sin sesión previa; usuario debe completar acceso/registro, términos y MFA/CAPTCHA si aparecen, sin compartir claves por chat. Si aparece pantalla de permisos Render, dejarla para inspección; no ampliar a otros repositorios. Pestañas marcadas para continuidad.

No se crearon cuentas/servicios ni se conectó GitHub todavía; cuota, disponibilidad de Free y versión de MySQL de la cuenta no inspeccionadas. Autorización vigente: no volver a preguntar por la misma propuesta. Continuar desde las pestañas existentes una vez autenticadas; no eludir login ni modificar Docker/socket. URL pública sigue pendiente.


## Continuidad del registro externo

2026-10-08, después del «listo» del usuario: pestaña Render en `/register/github`, correo precargado por GitHub (no registrado aquí), texto «By signing up you agree to our terms of service» y botón Create Account. GitHub avanzó al formulario final de registro, pero no hay panel Render ni cuenta finalizada verificados. Agente no pulsó Create Account: usuario debe revisar y aceptar términos personalmente conforme al procedimiento aprobado.

Aiven continúa sin sesión; se abrió el enlace visible Sign up. Ofrece registro GitHub/Google/Microsoft/email y menciona créditos incluidos; no se activó servicio trial ni plan pagado. Usuario debe completar acceso/registro, sin tarjeta ni creación de servicios. Luego revisar disponibilidad de MySQL Free real y permisos GitHub únicamente EVENTIFY. Pestañas preservadas para continuidad; no se crearon servicios ni se otorgó acceso nuevo al repositorio. Autorización de publicación sigue vigente, sin necesidad de repetirla.


## B08: Render accesible; integración restringida y Aiven pendientes

2026-10-08: tras el siguiente «listo», Render tenía abiertas páginas promocionales. Se volvió al Dashboard y se inició acceso con la sesión GitHub existente: panel My Workspace y formulario New Web Service visibles. No hay servicio creado ni plan/cuotas inspeccionados todavía. Botón Connect GitHub no abrió ventana utilizable; se usó enlace oficial de instalación documentado en [Render Git Provider](https://render.com/docs/git-provider): github.com/apps/render/installations/new.

Instalación de Render en USUARIO_DESTINO preparada con **Only select repositories** y **un único USUARIO_DESTINO/eventify** seleccionado. No se pulsó Install. La app solicita lectura de código/metadata/Dependabot y lectura-escritura de actions/checks/status/deployments/environments/issues/PRs/hooks/workflows; email se solicita individualmente. Usuario debe aceptar este alcance concreto antes de instalar. No se amplió a todos los repositorios ni a Código Tico. Pestaña conservada para revisión.

Aiven quedó en OAuth GitHub con Full access a datos personales y explicación «read and write all user data» (emails privados/perfil/followers). Agente canceló; no concedió permisos. Se preparó alternativa `/signup/email`, sin introducir nombre/email/contraseña ni enviar formulario. Usuario debe completar personalmente credencial/términos y llegar al panel; no activar servicios trial ni añadir tarjeta. Pestaña conservada.

Autorización general Render Free/Aiven MySQL Free vigente, ₡0; siguen pendientes instalación concreta, cuenta Aiven, plan/version/CA externos, publicación y aceptación. No certificar URL ni servicios por preparar formularios. Capturas privadas temporales /tmp/eventify-render-permisos.png y /tmp/eventify-aiven-correo.png; no se añaden a Git.
