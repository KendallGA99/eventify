# EVENTIFY: estado actual y continuidad

Actualizado 2026-10-08, America/Costa_Rica. Referencia operativa resumida; [HISTORIAL.md](HISTORIAL.md) conserva los registros anteriores íntegros, incluidas incidencias y resultados superados. Consultar evidencias por bloque. No sumar pruebas de ejecuciones diferentes.

## Alcance y autorización

- Proyecto académico PHP/MySQL, HTML/CSS/JS/jQuery/AJAX; desarrollo Linux, entrega Docker Compose Linux/Windows. Sin gastos autorizados.
- Construcción por bloques y commits/push progresivos autorizados. Repositorio privado [USUARIO_DESTINO/eventify](https://github.com/USUARIO_DESTINO/eventify), remoto SSH. No modificar Código Tico.
- URL pública publicada: https://eventify-academico.onrender.com; aceptación final todavía parcial. Usuario autorizó Render Free + Aiven MySQL Free e integración Render limitada a USUARIO_DESTINO/eventify al responder «dale» el 2026-10-08; gasto máximo ₡0, sin tarjeta ni planes pagados. MySQL Free y Render Free creados; dev.25 Live verificada. No certificar Windows, publicación ni defensa por inferencia.

## Versión e instancias

- Rama: `codex/b08-rechazo-sin-tls`. VERSION `0.1.0-dev.25`, commit `fb461ad` (Render Live); dev.24 `6baa754` y etiqueta `v0.1.0-dev.24` publicados y referencias remotas verificadas (ensayo sin TLS aprobado); dev.23 `3badc85`/`v0.1.0-dev.23` publicado, ensayo parcial y fallo del evaluador; cuentas verificadas en dev.22 (`4947823`, etiqueta `v0.1.0-dev.22`). Ninguna versión estable; CI manual preparado, no ejecutado.
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
- B08 activo: GitHub privado y preparación pública operativos; TLS y cuentas comprobados en ensayo aislado. Ensayo sin TLS dev.24 aprobado. Propuesta PUBLICACION-SOLICITUD.md autorizada. Render conectado solo a EVENTIFY y formulario Free preparado sin desplegar. MySQL/Render Free, HTTPS y flujos centrales externos verificados en dev.25; pendientes clima exitoso desde Render, cookies/controles externos restantes y revisión final.
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


## B08: integración Render confirmada y formulario Free preparado

2026-10-08: al retomar, formulario antiguo Install seguía abierto; pulsarlo con selección única mostró «GitHub App has already been installed». Se inspeccionó instalación existente Render 169376729: tenía All repositories seleccionado y Render listaba Código Tico y otros repositorios. Se corrigió según alcance autorizado: Only select repositories → USUARIO_DESTINO/eventify → Save. Nueva navegación a configuración confirmó All=false, Only selected=true y único USUARIO_DESTINO/eventify. Render recargado mostró solo EVENTIFY; no se modificó código de otros proyectos ni otras integraciones.

Formulario New Web Service preparado, sin pulsar Deploy: nombre eventify-academico, Docker, rama codex/b08-rechazo-sin-tls, Dockerfile.public, health /salud, Auto-Deploy Off, contexto/raíz predeterminados. Plan inicial estaba en $7/mes; agente seleccionó Free y UI confirmó $0/mes, 0.1 CPU/512MB. No se creó servicio ni se añadió tarjeta. No tomar formulario no enviado como configuración persistida o URL desplegada; verificar campos de nuevo al retomar. Región provisional Ohio, ajustar si la oferta MySQL requiere otra decisión.

Aiven aún muestra formulario signup/email vacío y Register with email deshabilitado. Usuario debe completar nombre/correo/contraseña y términos, verificar correo si procede y llegar al panel, sin tarjeta ni crear servicios. No se vuelve a pedir autorización general. Después inspeccionar MySQL Free, versión/CA y preparar base nueva antes de desplegar. Publicación real/aceptación externa continúan pendientes.

Captura privada de alcance guardado /tmp/eventify-render-acceso-restringido.png; formulario /tmp/eventify-render-preparado-free.png. Pestañas marcadas para continuidad, sin almacenar claves ni capturas en Git.


## B08: MySQL Free creado en Aiven

2026-10-08: registro por correo completado por el usuario; consola autenticada. Se omitió el asistente inicial con PostgreSQL Developer de prueba. Create service → MySQL tenía Professional seleccionado; se cambió explícitamente a Free: precio $0/Free, free-1-1gb, un CPU/1 GB RAM/1 GB almacenamiento, North America, apagado por inactividad. Se creó eventify-mysql dentro del alcance autorizado; Overview confirma MySQL 8.4, Free y asignación DigitalOcean sfo. No se añadió tarjeta ni se creó servicio Developer/Professional. La cuenta muestra créditos de prueba incluidos en registro; el servicio creado es Free independiente de esos créditos.

Estado observado Building: aún no confirma conexión disponible. CA real copiada a storage/aiven-evaluacion/mysql-ca.pem con permisos 600, directorio 700 ignorado por Git; PEM y fechas válidas mediante openssl. Primer Copy password devolvió vacío durante construcción, descartado como credencial no disponible; no se imprimen secretos. Plan de tres cuentas ficticias preparado con generador existente (JSON600), sin crear cuentas todavía. Job privado preparar.sh/probe.php preparado: guardas MySQL8.4/TLS/identidad/collation/base nueva vacía, migraciones y CLI explícito, sin borrar ni restaurar bases. Bash -n aprobado, PHP/job externo pendiente; todavía no hay production.env válido ni base eventify_evaluacion creada.

Render conserva formulario Free, repo/branch/Dockerfile.public/health originales; región cambiada a Oregon para aproximarla al sfo asignado por Aiven. No se pulsó Deploy ni se cargaron secretos. Docker del agente sigue denegado; sudo no interactivo no disponible. Verificar servidor Running, crear base nueva y guardar credenciales reales antes de solicitar un único comando necesario al usuario. No publicar antes de TLS/migraciones/cuentas externos.


## B08: servidor disponible y base externa nueva confirmada

Aiven pasó a Running/MySQL8.4.11; Create database → eventify_evaluacion mostró mensaje de creación y fila real en Databases, conservando defaultdb. Credencial disponible copiada posteriormente y configuration production.env600 generada fuera de Git: CA real /etc/secrets/mysql-ca.pem, verify_identity, APP_SECRET único. No imprimir ni versionar valores. Captura /tmp/eventify-aiven-base-creada.png muestra base y Running sin claves.

Job privado storage/aiven-evaluacion/preparar.sh listo, Bash-n aprobado; registra preparacion.log/status.txt privados. Requiere Docker en terminal del usuario por socket denegado/sudo interactivo; comando con ruta: cd "/home/usuario/Documentos/ChatGPT/Proyecto U" && bash storage/aiven-evaluacion/preparar.sh. Primero verifica base vacía/TLS/versión/collation y después migra/aprovisiona tres cuentas/repite migración idempotente. No repetir una vez aprovisionado sin inspeccionar: guarda base vacía rechaza repeticiones. PHP y conexión externa aún no ejecutados. Usa credencial administrativa del proveedor solo para preparación; revisar usuario/privilegios de aplicación antes de Render. Render sin Deploy ni URL, aceptación pública pendiente.


## B08: preparación MySQL externa aprobada y entrega local independiente

Leídos status.txt/preparacion.log de storage/aiven-evaluacion: inicio 2026-10-08T20:29:33Z, exit_code=0. Imagen pública dev24 construida; probe PHP confirma MySQL8.4, TLS con identidad/CA, collation compatible y base nueva vacía. Migraciones001–003 aplicadas, tres cuentas ficticias creadas por CLI explícito y segunda migración sin repetición. No demuestra aún URL HTTPS, login público, permisos mínimos del usuario de aplicación o persistencia externa tras redeploy. No repetir job de preparación sobre base poblada. Render sigue sin desplegar.

Usuario reafirma entrega sencilla al compañero por paquete, sin asociaciones/cuentas externas. Confirmado en Compose DB_HOST=db y servicio MySQL local; start-windows genera configuración/inicia/migra sin Render/Aiven/GitHub. INSTALACION documenta separación: ZIP local independiente, Docker/virtualización e Internet inicial requeridos, cuentas EVENTIFY internas, URL pública adicional. No transferir cuentas personales ni secretos públicos en el paquete. Windows completo y paquete final limpio siguen pendientes. Documentación revisada con diff --check; sin cambios de código ni repetición de suite extensa.


## B08: usuario de aplicación limitado preparado

Consola Users confirma solo avnadmin; documentación oficial Aiven identifica cuenta administrativa y privilegios iniciales amplios de nuevos service users. Antes de Render se preparó job privado separado permisos.sh: CREATE USER eventify_app nuevo con REQUIRE SSL (sin reset/rotación/revocación ajena), GRANT SELECT/INSERT/UPDATE/DELETE/CREATE únicamente sobre la base de evaluación; guion escapa el guion bajo del nombre para evitar patrones de base. CREATE permite el CREATE TABLE IF NOT EXISTS del runner; no concede DROP/ALTER/GRANT OPTION/globales. Conserva snapshot de tres cuentas internas; segunda conexión application.env verifica TLS/grants y migraciones ya aplicadas. Archivos/password aleatorio 600 ignorados por Git, no incluidos en imagen/paquete.

Bash-n y exclusión Git aprobados; PHP/job y concesión real pendientes. Docker directo/sudo-n siguen denegados, necesita ejecución en terminal: cd "/home/usuario/Documentos/ChatGPT/Proyecto U" && bash storage/aiven-evaluacion/permisos.sh. Resultado permisos-status.txt/permisos.log privado. No repetir ciegamente si hay fallo o usuario existente; inspeccionar sin borrar. No cargar credencial administrativa en Render; formulario/publicación siguen pendientes hasta confirmar usuario limitado. Fuente: https://aiven.io/docs/products/mysql/howto/manage-service-users y manual MySQL8.4 creación de cuentas.


## B08: fallo de permisos diagnosticado y corregido; Render iniciado

permisos-status exit1, inicio20:35:36UTC: sintaxis PHP válida y CREATE USER/GRANT ejecutados, snapshot de tres cuentas conservado; probe posterior rechazado. No repetir CREATE USER. Diagnóstico independiente PyMySQL1.1.2 (herramienta temporal privada, no dependencia de la aplicación) con CA/hostname verificadas: @@partial_revokes=1 y SQLmode ANSI_QUOTES; escape añadido al guion bajo produjo permiso sobre nombre literal incorrecto. Se concedieron cinco permisos sobre eventify_evaluacion literal y retiraron únicamente los permisos erróneos del usuario eventify_app creado para este ensayo; no modificar avnadmin ni usuarios ajenos. Primer REVOKE tuvo1141 por escape del identificador; nueva consulta information_schema confirmó nombre real y segunda operación exacta aprobada. Tres cuentas/hashes conservados.

Conexión independiente como eventify_app confirma Ssl_cipher, hostname/CA, USAGE global solamente y SELECT/INSERT/UPDATE/DELETE/CREATE en una sola base, tres cuentas y tres migraciones. Evidencia privada permisos-python.json. No afirmar probe PHP aprobado: validación en arranque Render pendiente. permisos.sh bloqueado preventivamente para no repetir creación.

Render preparado con application.env (usuario limitado, nunca avnadmin), SecretFile mysql-ca.pem real, Free$0, Oregon, Docker/Dockerfile.public, rama codex/b08-rechazo-sin-tls, /salud, AutoDeployOff. Deploy web service pulsado dentro de publicación previamente autorizada; esperar resultado visible e inspeccionar versión/logs/HTTPS antes de cerrar. No nueva VERSION por cambios de documentación/configuración privada.


## B08: dev.25 Live, flujos públicos y persistencia real

Servicio Render srv-db3vutm7bikc73ajj6og, URL https://eventify-academico.onrender.com; Free, Docker, Oregon. Primer deploy dep-db3vutu7bikc73ajj920/69c9d4e cancelado: construcción/migración PHP8.4 correctas, /salud HTTP503 bajo Apache. Se preparó dev.25/fb461ade36531e3c61710321372e3b307eba6977: copia CA de ejecución para www-data (700/600 fuera de public), preflight y health como trabajador antes de servir. No rebajar TLS ni cambiar SQL/datos. Bash/diff correctos; ensayo TLS aislado de esta modificación aún pendiente.

Despliegue manual dep-db400dui0phs73eg6dng: Deploy succeeded|Live, 2026-10-08 14:42:31CST, duración32.1s; logs confirman preflight root/trabajador, configtest, esquema actualizado sin repetir migraciones y Apache operativo. Código fb461ad coincide con referencia desplegada. No versión estable ni suite219 repetida.

Navegador IAB HTTPS real: portada/catálogo; login administrador, dashboard, crear categoría y evento ficticio publicado para12oct18h, capacidad30/precio2500; logout; login usuario, reservaAJAX de2 entradas/total5000, ticket/QR visible, historial; cancelaciónAJAX conserva registro y catálogo vuelve a30espacios. Clima externo mostró fallo controlado y reserva continuó: acredita manejo de error, no pronóstico exitoso desde Render. Usuario rechazado al navegar directamente a /admin. Datos ficticios explícitos, sin pagos reales.

Restart service real confirmado en Events:8oct14:46CST. Snapshot privado antes/después con conexión TLS independiente coincide en filas/sha256 de diez tablas; reserva cancelada/ticket/evento/usuarios conservados. Sesión expiró por almacenamiento efímero de Render; nuevo login funciona e historial conserva cancelación. No prometer sesión persistente ni equiparar restart a todas las variantes de redeploy/restauración. Archivos snapshot-before/after-restart.json privados. Screenshot /tmp/eventify-publicado.png y ticket privado /tmp/eventify-public-ticket.png; no subir capturas/secretos a Git. Sesión final cerrada; pestaña catálogo entregable.

Pendientes para aceptación final: pronóstico exitoso/proveedor y diagnóstico de fallo; cookies HTTPS/CSRF/propiedad externos completos; nueva copia limpia y Windows/Firefox reales; actualización PDF/ZIP final, revisión seguridad/imágenes/historial y evidencia humana. No solicitar intervención para Docker si diagnóstico independiente seguro permite avanzar; herramienta PyMySQL temporal no sustituye PHP/MySQL ni se entrega como dependencia.


## B08/B09: redacción natural y diagnóstico del clima dev.26

Usuario pide documentos/comentarios claros y naturales para facilitar exposición. AGENTS incorpora pauta permanente: explicar decisiones y reglas, evitar comentarios obvios/frases genéricas, mantener legibilidad y registro real de aportes/pruebas. README actualizado a dev.25/publicación y entrega local independiente; DEFENSA más directa, sin atribuir preparación no realizada. Comentarios WeatherBudget/EventWeather explican caché, concurrencia, cuota y reinicios. Comprobación de líneas sin comentarios confirmó instrucciones ejecutables idénticas en esos dos archivos; diff-check aprobado. Revisión editorial completa del paquete aún pendiente.

Clima: una nueva consulta IAB pública falla controladamente; llamada independiente desde Linux HTTP200 con timezone/unidades correctas y fechas8–23oct. MySQL weather_budget muestra intentos consumidos(2/hora,1/minuto), sin prueba de cuota agotada. No atribuir aún causa a Render. Dev.26/cabd6f3: WeatherClient expandido para lectura y logs solo motivos constantes/códigoHTTP(numérico), nunca URL/coordenadas/respuesta/trace; TLS/timeout3/límite128KiB conservados. Dockerfile.public agrega lint y tests unitarios existentes de clima/presupuesto en build, sin usar MySQL ni proveedor real. Despliegue manual solicitado; resultados de construcción, HTTP y diagnóstico pendientes. No declarar suite ni corrección aprobada antes de evidencia.


## B08: verificación de dev.26 y límite del proveedor

Render confirma Deploy succeeded | Live para cabd6f335d8952332ce6d0a71fcc515bb6f5b176, despliegue dep-db4076rbc2fs73c5vrg0, 2026-10-08 14:56:59 CST, duración 43,4 s. Construcción PHP con lint aprobado y salida real: Clima 39 comprobaciones correctas; Presupuesto meteorológico 44. Arranque conserva esquema sin repetir migraciones y /salud HTTP200. No equivale a repetir toda la suite de integración.

Nueva visita IAB al evento ficticio devuelve fallo controlado. Logs de aplicación a 14:59:40 CST: eventify_weather reason=http_status status=429. El proveedor responde, pero rechaza por límite; no hay evidencia de fallo TLS o de agotamiento del presupuesto propio. No se conoce qué límite exacto del proveedor se alcanzó. IP compartida es una hipótesis, no una causa comprobada. La documentación oficial describe límites gratuitos y posibles cuotas compartidas por IP: https://open-meteo.com/en/pricing y https://github.com/open-meteo/open-meteo/discussions/115. No cambiar destino, aumentar reintentos ni contratar un servicio para eludirlo.

Evidencia visual /tmp/eventify-clima-diagnostico-dev26.png, fuera de Git. RF-15 en publicación continúa pendiente de una respuesta útil real. Próxima acción: evaluar una integración gratuita compatible si persiste el límite, manteniendo el manejo de errores, presupuesto cero y entrega local independiente. Revisión editorial inicial concluida; revisión completa de documento técnico/paquete sigue pendiente. Dev.26 es una versión de desarrollo, no una entrega estable.
