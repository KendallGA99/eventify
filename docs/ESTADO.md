# EVENTIFY: estado actual y continuidad

Actualizado 2026-10-08, America/Costa_Rica. Referencia operativa resumida; [HISTORIAL.md](HISTORIAL.md) conserva los registros anteriores íntegros, incluidas incidencias y resultados superados. Consultar evidencias por bloque. No sumar pruebas de ejecuciones diferentes.

## Alcance y autorización

- Proyecto académico PHP/MySQL, HTML/CSS/JS/jQuery/AJAX; desarrollo Linux, entrega Docker Compose Linux/Windows. Sin gastos autorizados.
- Construcción por bloques y commits/push progresivos autorizados. Repositorio privado [USUARIO_DESTINO/eventify](https://github.com/USUARIO_DESTINO/eventify), remoto SSH. No modificar Código Tico.
- URL pública publicada: https://eventify-academico.onrender.com; aceptación final todavía parcial. Usuario autorizó Render Free + Aiven MySQL Free e integración Render limitada a USUARIO_DESTINO/eventify al responder «dale» el 2026-10-08; gasto máximo ₡0, sin tarjeta ni planes pagados. MySQL Free y Render Free creados; dev.26 Live verificada. No certificar Windows, publicación ni defensa por inferencia.

## Versión e instancias

- VERSION `0.1.0-dev.31` (revisión editorial; aplicación igual a dev.30, otros avisos pendientes); aplicación Render dev.26 `cabd6f3` Live; dev.24 `6baa754` y etiqueta `v0.1.0-dev.24` publicados y referencias remotas verificadas (ensayo sin TLS aprobado); dev.23 `3badc85`/`v0.1.0-dev.23` publicado, ensayo parcial y fallo del evaluador; cuentas verificadas en dev.22 (`4947823`, etiqueta `v0.1.0-dev.22`). Ninguna versión estable; CI manual preparado, no ejecutado.
- `main`: `3da680b74b41fa3da4069879edc1f649de9b97f2`. No reescribir historial ni hacer push forzado. Rollback de código no restaura MySQL.
- Destinos existentes, según última verificación: normal `localhost:8080`; QA `localhost:8081` / `eventify-qa`; copia limpia `localhost:8083` / `eventify-clean-20261008160801-e96614c4`; restauración `localhost:8082` / `eventify-restore-20261008154302-2b8eb996`. No afirmar disponibilidad actual sin consultar; conservar sus volúmenes y datos.
- Docker 29.1.3 / Compose 2.40.3. El entorno de verificación no tiene acceso al socket y sudo no interactivo exige contraseña; las operaciones Docker necesarias se ejecutan en terminal del usuario. No modificar grupos/socket/sudoers para eludirlo.
- Credenciales, planes, certificados, registros y snapshots privados en `storage/` y respaldos en `backups/`, ignorados por Git y fuera de la raíz pública. No imprimir claves ni adjuntar estos archivos al repositorio.

## Evidencia vigente

| Área | Resultado confirmado | Alcance pendiente |
| --- | --- | --- |
| Desarrollo | PHP 8.4, sintaxis, QR, conexión y 266 comprobaciones unitarias; última ejecución registrada 15:42:00 UTC, exit 0 | No incluye por inferencia todos los archivos incorporados después |
| Integración | 16:31:25 UTC, exit 0: 219 comprobaciones MySQL/HTTP, tres migraciones; carreras de reserva, administración y presupuesto meteorológico | No sumar las pruebas TLS a las 219; no certifica Windows ni publicación |
| Persistencia y restauración | QA conserva diez tablas y archivos; restauración aislada exacta. `existing-iAQixJNV`, 16:41:44 UTC, exit 0: copia limpia conserva diez tablas, ocho reservas/ocho tickets tras recrear; restauración coincide antes del navegador | Conservar destinos; pruebas específicas no equivalen a todos los escenarios |
| Copia limpia Linux | ZIP dev.28 en ruta con espacios: arranque, roles, reserva/ticket/QR, cancelación/historial; persistencia posterior a reserva/cancelación: diez tablas y archivos idénticos, historial/ticket/QR visibles | Windows y aceptación completa pendientes |
| Navegador | Chrome: login/logout de ambos roles, reserva/ticket/QR, cancelación, dashboard y filtros; restauración autenticada. IAB: permisos administrativos, reserva duplicada y ticket/QR ajenos rechazados | Teclado/zoom, fallo de red real y casos administrativos restantes; Firefox pendiente |
| Diseño adaptable y API | Catálogo, cuenta, registro, ticket/QR y dashboard observados en tamaños indicados en B07; orientación móvil sin desbordamiento. Open-Meteo real visible en QA | Matriz final Chrome/Firefox y caída externa real en UI pendientes |
| Windows | PowerShell 5.1 real: seis scripts sin errores de parser; VM Windows 11 LTSC Evaluation de 4 GiB identificada | Docker/WSL y ejecución completa sin verificar; recursos/soporte limitan el ensayo |
| Seguridad | Composer audit sin avisos, jQuery verificado, 81 controles HTTPS públicos y 505 blobs sin secretos conocidos | Bases PHP/MySQL escaneadas con avisos; imagen construida, correcciones/alcanzabilidad y revisión final pendientes |
| Arranque público aislado | dev.20: imagen PHP 8.4 construida, tres rechazos reales y 25 guardas simuladas. dev.21: MySQL 8.4 con TLS, CA/hostname incorrectos rechazados, tres migraciones, Apache/HTTP interno y recreación aprobados | No demuestra proveedor externo ni URL HTTPS; servidor sin TLS aprobado en dev.24 |
| Cuentas dev.22 | `public-tls-5NcIVnVj`, 19:39:57 UTC, exit 0: CLI creó tres cuentas ficticias con roles/hashes válidos; repetición, entorno local y plan inválido rechazados sin cambios; mismas cuentas válidas tras recrear web | No certifica login público ni concurrencia de aprovisionamiento |
| Documentos y paquete | PDF borrador dev.31: 15 páginas renderizadas y revisadas; ZIP dev.31 exportado y ZIP dev.28 probado preservado | ZIP dev.28 con integridad e instalación limpia Linux comprobadas; paquete final, Windows y revisión/defensa humana pendientes |

## Bloques y próximo trabajo

- B01/B02: stack, esquema y migraciones comprobados. Portabilidad final permanece abierta.
- B03–B06: implementación e integración aprobadas; aceptación de navegador parcial. Consultar [MATRIZ.md](MATRIZ.md).
- B07 abierto: completar Firefox/Windows y casos restantes de UI/seguridad. No repetir la suite extensa sin cambios, fallos o dudas relevantes.
- B08 activo: GitHub privado y preparación pública operativos; TLS y cuentas comprobados en ensayo aislado. Ensayo sin TLS dev.24 aprobado. Propuesta PUBLICACION-SOLICITUD.md autorizada. Render conectado solo a EVENTIFY y servicio Free publicado. MySQL/Render Free, HTTPS y flujos centrales externos verificados en dev.25; Pronóstico público útil y 81 controles HTTPS aprobados; revisión final pendiente.
- B09 abierto: actualizar documento técnico y paquete cuando la evidencia se consolide; entrega final exige instalación del receptor, entregables completos y evidencia humana real.

## Último resultado y límites

Se revisaron `storage/public-tls-5NcIVnVj/status.txt` y `test.log`: inicio `2026-10-08T19:39:57Z`, `exit_code=0`, proyecto `eventify-tls-ee40bb5ec1e6`. PHP 8.4/MySQL 8.4 con TLS válido, tres migraciones, rechazos de CA/hostname y cuentas verificados antes y después de recrear únicamente web. El CLI de aprovisionamiento fue ejecutado de forma explícita; no existe siembra automática pública. Los hashes se comprobaron mediante password_verify, sin revelar claves.

El ensayo no expuso puertos y retiró solo sus contenedores/red/base tmpfs efímeros previstos; certificados, plan y registros privados se conservan. No modificó bases existentes ni creó cuentas externas. HTTP interno no prueba HTTPS público; roles/hashes no prueban login de navegador. Sin repetir las 219 pruebas de negocio ni cambiar VERSION/etiqueta por documentar este resultado.

## Incremento dev.23 preparado: servidor sin TLS

`docker-compose.no-tls-test.yml`, `tests/integration/public-no-tls.php` y `scripts/test-public-no-tls-linux.sh`: servidor MySQL 8.4 con tls_version vacío, control TCP sin cifrado y base vacía, rechazo mediante Database production con CA válida, arranque público que debe salir con error antes de servir/migrar y control final. Proyecto aleatorio propio, red interna, tmpfs, sin puertos/volúmenes persistentes; retira solo recursos efímeros propios y conserva registros privados. No cambia las políticas de seguridad ni bases existentes.

Compose config real sin daemon verificó aislamiento/configuración; Bash y diff correctos. Manual oficial MySQL 8.4 contrastado antes de ejecutar (opciones antiguas skip-ssl/have_ssl eliminadas; ensayo usa tls_version). PHP nuevo y MySQL real pendientes: `docker info` denegado; `sudo -n docker info` requiere contraseña. Necesario en terminal: `bash scripts/test-public-no-tls-linux.sh`. Leer `storage/public-no-tls-ID/status.txt` y logs antes de afirmar aprobado. No repetir la suite 219 para este incremento aislado.

## Dev.23 ejecutado y corrección dev.24

`storage/public-no-tls-HVqp1VtN`, inicio `2026-10-08T19:50:39Z`, exit_code=1; proyecto `eventify-no-tls-34356d6fced7`. Build y sintaxis PHP 8.4 aprobados. Primer probe: MySQL 8.4 disponible sin TLS, Database production rechaza y base vacía. rejection.log: preflight válido, Apache Syntax OK y error de conexión previsto. Script abortó al exigir código 1 del wrapper Compose; no registró el valor observado ni ejecutó el probe posterior. No afirmar suite completa aprobada ni causa concreta del código. Proyecto/red efímeros retirados; bases existentes conservadas.

Dev.24 conserva el job de rechazo hasta inspeccionar su State real: detenido, ExitCode 1, sin OOM/error y log previsto, sin servir/migrar. Código de Compose queda registrado aparte. Limpieza limitada al nombre y etiqueta del proyecto generado. Ocho controles del evaluador (incluido rechazo de estados activo/exitoso/OOM/runtime/migración/Apache) y Bash/diff correctos. Nueva ejecución Docker pendiente; socket denegado y sudo no interactivo requieren terminal del usuario. Comando: `bash scripts/test-public-no-tls-linux.sh`.

## Dev.24: rechazo sin TLS aprobado

`storage/public-no-tls-QnTPzu56`, inicio `2026-10-08T19:55:27Z`, exit_code=0; proyecto `eventify-no-tls-bedcc85fc265`. PHP 8.4 sin errores sintácticos. Dos probes antes/después del arranque confirman MySQL 8.4 disponible sin TLS, rechazo por Database production y base vacía. State real del job: exited, Running=false, ExitCode=1, OOMKilled=false, Error vacío; terminó 19:55:41 UTC. Log confirma preflight/Apache válidos y error de conexión previsto, sin migraciones ni Apache sirviendo. Compose agotó la espera (código 124), registrado aparte; no confundir ese timeout con fallo del rechazo de la aplicación.

Retirados únicamente job, db y red efímeros propios; plan/certificados/registros privados conservados y bases existentes intactas. Control servidor sin TLS cerrado con este alcance; sin nueva suite 219 ni cambio de VERSION/etiqueta por documentar. Proveedor/HTTPS/URL pública, Windows/Firefox y entrega final pendientes. Propuesta revisable [PUBLICACION-SOLICITUD.md](PUBLICACION-SOLICITUD.md); no crear servicios antes de autorización concreta.

## B08: publicación autorizada; acceso pendiente

Usuario respondió «dale» a propuesta concreta Render Free/Aiven MySQL Free, integración GitHub solo repositorio privado EVENTIFY y publicación HTTPS con datos ficticios/sin tarjeta/pagos. Se abrieron pestañas del navegador integrado: Render→GitHub login y Aiven Console login. Sin sesión previa; usuario debe completar acceso/registro, términos y MFA/CAPTCHA si aparecen, sin compartir claves por chat. Si aparece pantalla de permisos Render, dejarla para inspección; no ampliar a otros repositorios. Pestañas marcadas para continuidad.

No se crearon cuentas/servicios ni se conectó GitHub todavía; cuota, disponibilidad de Free y versión de MySQL de la cuenta no inspeccionadas. Autorización vigente: no volver a preguntar por la misma propuesta. Continuar desde las pestañas existentes una vez autenticadas; no eludir login ni modificar Docker/socket. URL pública sigue pendiente.


## Continuidad del registro externo

2026-10-08, después del «listo» del usuario: pestaña Render en `/register/github`, correo precargado por GitHub (no registrado aquí), texto «By signing up you agree to our terms of service» y botón Create Account. GitHub avanzó al formulario final de registro, pero no hay panel Render ni cuenta finalizada verificados. No se pulsó Create Account: usuario debe revisar y aceptar términos personalmente conforme al procedimiento aprobado.

Aiven continúa sin sesión; se abrió el enlace visible Sign up. Ofrece registro GitHub/Google/Microsoft/email y menciona créditos incluidos; no se activó servicio trial ni plan pagado. Usuario debe completar acceso/registro, sin tarjeta ni creación de servicios. Luego revisar disponibilidad de MySQL Free real y permisos GitHub únicamente EVENTIFY. Pestañas preservadas para continuidad; no se crearon servicios ni se otorgó acceso nuevo al repositorio. Autorización de publicación sigue vigente, sin necesidad de repetirla.


## B08: Render accesible; integración restringida y Aiven pendientes

2026-10-08: tras el siguiente «listo», Render tenía abiertas páginas promocionales. Se volvió al Dashboard y se inició acceso con la sesión GitHub existente: panel My Workspace y formulario New Web Service visibles. No hay servicio creado ni plan/cuotas inspeccionados todavía. Botón Connect GitHub no abrió ventana utilizable; se usó enlace oficial de instalación documentado en [Render Git Provider](https://render.com/docs/git-provider): github.com/apps/render/installations/new.

Instalación de Render en USUARIO_DESTINO preparada con **Only select repositories** y **un único USUARIO_DESTINO/eventify** seleccionado. No se pulsó Install. La app solicita lectura de código/metadata/Dependabot y lectura-escritura de actions/checks/status/deployments/environments/issues/PRs/hooks/workflows; email se solicita individualmente. Usuario debe aceptar este alcance concreto antes de instalar. No se amplió a todos los repositorios ni a Código Tico. Pestaña conservada para revisión.

Aiven quedó en OAuth GitHub con Full access a datos personales y explicación «read and write all user data» (emails privados/perfil/followers). Se canceló la solicitud; no se concedieron permisos. Se preparó alternativa `/signup/email`, sin introducir nombre/email/contraseña ni enviar formulario. Usuario debe completar personalmente credencial/términos y llegar al panel; no activar servicios trial ni añadir tarjeta. Pestaña conservada.

Autorización general Render Free/Aiven MySQL Free vigente, ₡0; siguen pendientes instalación concreta, cuenta Aiven, plan/version/CA externos, publicación y aceptación. No certificar URL ni servicios por preparar formularios. Capturas privadas temporales /tmp/eventify-render-permisos.png y /tmp/eventify-aiven-correo.png; no se añaden a Git.


## B08: integración Render confirmada y formulario Free preparado

2026-10-08: al retomar, formulario antiguo Install seguía abierto; pulsarlo con selección única mostró «GitHub App has already been installed». Se inspeccionó instalación existente Render 169376729: tenía All repositories seleccionado y Render listaba Código Tico y otros repositorios. Se corrigió según alcance autorizado: Only select repositories → USUARIO_DESTINO/eventify → Save. Nueva navegación a configuración confirmó All=false, Only selected=true y único USUARIO_DESTINO/eventify. Render recargado mostró solo EVENTIFY; no se modificó código de otros proyectos ni otras integraciones.

Formulario New Web Service preparado, sin pulsar Deploy: nombre eventify-academico, Docker, la rama de trabajo, Dockerfile.public, health /salud, Auto-Deploy Off, contexto/raíz predeterminados. Plan inicial estaba en $7/mes; se seleccionó Free y UI confirmó $0/mes, 0.1 CPU/512MB. No se creó servicio ni se añadió tarjeta. No tomar formulario no enviado como configuración persistida o URL desplegada; verificar campos de nuevo al retomar. Región provisional Ohio, ajustar si la oferta MySQL requiere otra decisión.

Aiven aún muestra formulario signup/email vacío y Register with email deshabilitado. Usuario debe completar nombre/correo/contraseña y términos, verificar correo si procede y llegar al panel, sin tarjeta ni crear servicios. No se vuelve a pedir autorización general. Después inspeccionar MySQL Free, versión/CA y preparar base nueva antes de desplegar. Publicación real/aceptación externa continúan pendientes.

Captura privada de alcance guardado /tmp/eventify-render-acceso-restringido.png; formulario /tmp/eventify-render-preparado-free.png. Pestañas marcadas para continuidad, sin almacenar claves ni capturas en Git.


## B08: MySQL Free creado en Aiven

2026-10-08: registro por correo completado por el usuario; consola autenticada. Se omitió la configuración inicial con PostgreSQL Developer de prueba. Create service → MySQL tenía Professional seleccionado; se cambió explícitamente a Free: precio $0/Free, free-1-1gb, un CPU/1 GB RAM/1 GB almacenamiento, North America, apagado por inactividad. Se creó eventify-mysql dentro del alcance autorizado; Overview confirma MySQL 8.4, Free y asignación DigitalOcean sfo. No se añadió tarjeta ni se creó servicio Developer/Professional. La cuenta muestra créditos de prueba incluidos en registro; el servicio creado es Free independiente de esos créditos.

Estado observado Building: aún no confirma conexión disponible. CA real copiada a storage/aiven-evaluacion/mysql-ca.pem con permisos 600, directorio 700 ignorado por Git; PEM y fechas válidas mediante openssl. Primer Copy password devolvió vacío durante construcción, descartado como credencial no disponible; no se imprimen secretos. Plan de tres cuentas ficticias preparado con generador existente (JSON600), sin crear cuentas todavía. Job privado preparar.sh/probe.php preparado: guardas MySQL8.4/TLS/identidad/collation/base nueva vacía, migraciones y CLI explícito, sin borrar ni restaurar bases. Bash -n aprobado, PHP/job externo pendiente; todavía no hay production.env válido ni base eventify_evaluacion creada.

Render conserva formulario Free, repo/branch/Dockerfile.public/health originales; región cambiada a Oregon para aproximarla al sfo asignado por Aiven. No se pulsó Deploy ni se cargaron secretos. El acceso Docker del entorno de verificación sigue denegado; sudo no interactivo no disponible. Verificar servidor Running, crear base nueva y guardar credenciales reales antes de solicitar un único comando necesario al usuario. No publicar antes de TLS/migraciones/cuentas externos.


## B08: servidor disponible y base externa nueva confirmada

Aiven pasó a Running/MySQL8.4.11; Create database → eventify_evaluacion mostró mensaje de creación y fila real en Databases, conservando defaultdb. Credencial disponible copiada posteriormente y configuration production.env600 generada fuera de Git: CA real /etc/secrets/mysql-ca.pem, verify_identity, APP_SECRET único. No imprimir ni versionar valores. Captura /tmp/eventify-aiven-base-creada.png muestra base y Running sin claves.

Job privado storage/aiven-evaluacion/preparar.sh listo, Bash-n aprobado; registra preparacion.log/status.txt privados. Requiere Docker en terminal del usuario por socket denegado/sudo interactivo; comando con ruta: bash storage/aiven-evaluacion/preparar.sh. Primero verifica base vacía/TLS/versión/collation y después migra/aprovisiona tres cuentas/repite migración idempotente. No repetir una vez aprovisionado sin inspeccionar: guarda base vacía rechaza repeticiones. PHP y conexión externa aún no ejecutados. Usa credencial administrativa del proveedor solo para preparación; revisar usuario/privilegios de aplicación antes de Render. Render sin Deploy ni URL, aceptación pública pendiente.


## B08: preparación MySQL externa aprobada y entrega local independiente

Leídos status.txt/preparacion.log de storage/aiven-evaluacion: inicio 2026-10-08T20:29:33Z, exit_code=0. Imagen pública dev24 construida; probe PHP confirma MySQL8.4, TLS con identidad/CA, collation compatible y base nueva vacía. Migraciones001–003 aplicadas, tres cuentas ficticias creadas por CLI explícito y segunda migración sin repetición. No demuestra aún URL HTTPS, login público, permisos mínimos del usuario de aplicación o persistencia externa tras redeploy. No repetir job de preparación sobre base poblada. Render sigue sin desplegar.

Usuario reafirma entrega sencilla al compañero por paquete, sin asociaciones/cuentas externas. Confirmado en Compose DB_HOST=db y servicio MySQL local; start-windows genera configuración/inicia/migra sin Render/Aiven/GitHub. INSTALACION documenta separación: ZIP local independiente, Docker/virtualización e Internet inicial requeridos, cuentas EVENTIFY internas, URL pública adicional. No transferir cuentas personales ni secretos públicos en el paquete. Windows completo y paquete final limpio siguen pendientes. Documentación revisada con diff --check; sin cambios de código ni repetición de suite extensa.


## B08: usuario de aplicación limitado preparado

Consola Users confirma solo avnadmin; documentación oficial Aiven identifica cuenta administrativa y privilegios iniciales amplios de nuevos service users. Antes de Render se preparó job privado separado permisos.sh: CREATE USER eventify_app nuevo con REQUIRE SSL (sin reset/rotación/revocación ajena), GRANT SELECT/INSERT/UPDATE/DELETE/CREATE únicamente sobre la base de evaluación; guion escapa el guion bajo del nombre para evitar patrones de base. CREATE permite el CREATE TABLE IF NOT EXISTS del runner; no concede DROP/ALTER/GRANT OPTION/globales. Conserva snapshot de tres cuentas internas; segunda conexión application.env verifica TLS/grants y migraciones ya aplicadas. Archivos/password aleatorio 600 ignorados por Git, no incluidos en imagen/paquete.

Bash-n y exclusión Git aprobados; PHP/job y concesión real pendientes. Docker directo/sudo-n siguen denegados, necesita ejecución en terminal: bash storage/aiven-evaluacion/permisos.sh. Resultado permisos-status.txt/permisos.log privado. No repetir ciegamente si hay fallo o usuario existente; inspeccionar sin borrar. No cargar credencial administrativa en Render; formulario/publicación siguen pendientes hasta confirmar usuario limitado. Fuente: https://aiven.io/docs/products/mysql/howto/manage-service-users y manual MySQL8.4 creación de cuentas.


## B08: fallo de permisos diagnosticado y corregido; Render iniciado

permisos-status exit1, inicio20:35:36UTC: sintaxis PHP válida y CREATE USER/GRANT ejecutados, snapshot de tres cuentas conservado; probe posterior rechazado. No repetir CREATE USER. Diagnóstico independiente PyMySQL1.1.2 (herramienta temporal privada, no dependencia de la aplicación) con CA/hostname verificadas: @@partial_revokes=1 y SQLmode ANSI_QUOTES; escape añadido al guion bajo produjo permiso sobre nombre literal incorrecto. Se concedieron cinco permisos sobre eventify_evaluacion literal y retiraron únicamente los permisos erróneos del usuario eventify_app creado para este ensayo; no modificar avnadmin ni usuarios ajenos. Primer REVOKE tuvo1141 por escape del identificador; nueva consulta information_schema confirmó nombre real y segunda operación exacta aprobada. Tres cuentas/hashes conservados.

Conexión independiente como eventify_app confirma Ssl_cipher, hostname/CA, USAGE global solamente y SELECT/INSERT/UPDATE/DELETE/CREATE en una sola base, tres cuentas y tres migraciones. Evidencia privada permisos-python.json. No afirmar probe PHP aprobado: validación en arranque Render pendiente. permisos.sh bloqueado preventivamente para no repetir creación.

Render preparado con application.env (usuario limitado, nunca avnadmin), SecretFile mysql-ca.pem real, Free$0, Oregon, Docker/Dockerfile.public, la rama de trabajo, /salud, AutoDeployOff. Deploy web service pulsado dentro de publicación previamente autorizada; esperar resultado visible e inspeccionar versión/logs/HTTPS antes de cerrar. No nueva VERSION por cambios de documentación/configuración privada.


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


## B07/B08: controles HTTPS e historial, dev.27

Objetivo: verificar RNF-03/04, RF-03/12/14 y SEG-01/03 en la web publicada, sin crear datos de negocio ni cambiar permisos. Revisados Session, AuthController, ReservationController, rutas y consultas de propiedad antes del ensayo.

Ejecutado: `python3 tests/public_security.py --base https://eventify-academico.onrender.com --accounts storage/aiven-evaluacion/cuentas/accounts.json`. Resultado: 81 comprobaciones correctas contra dev.26. Cuenta ficticia propietaria y segunda cuenta: login con sesión regenerada, cookies Secure/HttpOnly/SameSite=Lax/path, CSP/nosniff/frame DENY, cuenta no-store, CSRF ausente y token de otra sesión rechazados, API privada sin login401, GET de escritura405, archivos privados404, ticket/QR ajenos404, historial ajeno sin ticket, administración403, logout y reutilización de sesión destruida rechazados. Todos los POST de negocio se enviaron con CSRF inválido; no se enviaron reservas ni cancelaciones válidas. Login/logout generan actividad de autenticación y sesiones. El ensayo no demuestra todos los escenarios de ataque ni la matriz de navegadores.

Sintaxis Python y diff-check aprobados. Script usa biblioteca estándar, HTTPS validado, no sigue redirecciones ni imprime claves/cookies/respuestas. Las cuentas son entrada privada; no forman parte de Git ni del paquete. El receptor no necesita Python para ejecutar la aplicación, únicamente para este ensayo opcional.

Revisión de historial de todas las referencias locales: 505 blobs únicos comparados con secretos reales conocidos de los dos entornos privados y las tres cuentas de evaluación; cero coincidencias. Sin .env privados, storage privado o backups en objetos del historial. Es una comprobación dirigida, no un detector universal de secretos ni escáner de vulnerabilidades de imágenes.

Alternativa meteorológica revisada: MET Norway Locationforecast. Requiere identificación real en User-Agent, atribución CC BY4.0, caché respetando Expires/Last-Modified, compresión y gestión de redirecciones. No es compatible directamente con el adaptador diario actual: series horarias y horizonte distinto. No se activó, no se enviaron coordenadas ni se cambió proveedor. Fuentes: https://docs.api.met.no/doc/TermsOfService y https://docs.api.met.no/doc/ForecastJSON.html. Mantener RF-15 público pendiente; preparar adaptación y pruebas antes de reemplazar la integración.

Dev.27 añade pruebas/documentación; aplicación pública permanece en dev.26, sin nuevo despliegue necesario para este incremento. Próximos pendientes: respuesta útil real de API, imagen/dependencias, Firefox/Windows y actualización PDF/paquete. No marcar B07/B08 completos.


## B09: documento técnico actualizado, dev.28

Objetivo ENT-C/U-03: explicar la versión actual, la entrega local independiente y la evidencia sin atribuir al estudiante una defensa no realizada. Revisados requisitos de PDF, documento técnico, instalación, empaquetador, estado y scripts. El documento conserva RF-01–21, RNF-01–07, historias/aceptación, navegación, arquitectura, ER, diccionario, reglas, seguridad, API, despliegue y conclusiones.

TECNICO actualizado: MySQL/Aiven y Render reales, TLS/usuario limitado, sesiones efímeras, persistencia y restauración comprobadas, 219 controles de integración, 81 HTTPS, límite HTTP429 y pendientes Windows/Firefox/API/defensa. Quitadas afirmaciones antiguas de que no existía publicación ni pruebas de TLS. Portada y cabecera PDF toman VERSION; no se cambió lógica de aplicación ni esquema.

Ejecutado generador con Python del runtime y ReportLab. PDF output/pdf/EVENTIFY_Documento_Tecnico_Borrador.pdf: 15 páginas A4, sin contraseña, índice y secciones presentes, caracteres dentro del área de página. Poppler renderizó las 15 páginas; inspección visual de tres hojas de contacto confirmó tablas, diagramas y paginación sin recortes/solapamientos. Texto mantiene estado de borrador y los datos de estudiante/docente pendientes.

Empaquetador actualizado solo en instrucciones de pendientes; 13 comprobaciones con repositorios ficticios aprobadas. PDF separado del ZIP; código se exportará por commit, no desde la carpeta con secretos. Exportación e integridad dev.28 todavía pendientes en esta entrada. No repetir suite MySQL por cambios de documentos. Instalación limpia de este incremento y ejecución Windows siguen abiertas.


### Paquete dev.28 exportado y comprobado

Exportado b92ed44968ac9116b35bf003a41486930f7e0d71 por scripts/package-source.py --ref b92ed44 --output-dir output/entrega. Salida output/entrega/eventify-desarrollo-b92ed44968ac/eventify-source.zip: 185 archivos, versión dev.28, CRC aprobado, manifiesto/huellas SHA-256 coincidentes. SHA-256 ZIP: 798941953798e19e758804cae02289be11ec3508ecd2d6d3e9a640f6a13330f5. Comparación de contenido del ZIP contra secretos privados conocidos sin coincidencias. No contiene Git, .env reales, cuentas de evaluación, volúmenes o datos MySQL. PDF dev.28 se entrega separado. No se ha arrancado este ZIP ni ejecutado en Windows; preservado el paquete anterior. No confundir integridad con instalación aprobada.


## B07/B08: ensayo limpio preparado y pronóstico público confirmado

Docker directo devuelve permiso denegado; sudo -n devuelve restricción no new privileges del entorno de verificación. No cambiar socket, grupos ni protecciones. Puerto8083 ocupado por instancia anterior;8084 sin conexión activa. Preparado wrapper privado storage/verificacion-zip-dev28/verificar.sh: etiqueta dev.28 fija, comprueba huella del ZIP entregado, usa puerto8084/proyecto y volúmenes propios; ejecuta arranque del ZIP, sintaxis PHP, 39 checks clima/44 presupuesto, demostración explícita y recreación/persistencia. Conserva destinos. Bash-n y siete comprobaciones de extracción aprobadas; ejecución Docker solicitada en terminal, todavía sin resultado. Comando: bash storage/verificacion-zip-dev28/verificar.sh v0.1.0-dev.28. No marcar instalación aprobada hasta leer status/log y verificar navegador.

En paralelo, GET HTTPS real a /api/eventos/taller-tecnologia-demo/clima devuelve HTTP200/status available. IAB muestra pronóstico diario para12oct:17,3–24,1°C y probabilidad máxima de precipitación100%, consulta8oct18:22CST, atribución Open-Meteo/CC BY4.0 y aviso orientativo. Captura /tmp/eventify-pronostico-publico.png revisada visualmente. RF-15 cuenta ahora con respuesta útil real en la publicación, además de fallo429 previamente gestionado. Sin cambios de proveedor, código, cuotas, TLS o planes. No atribuir causa exacta a la recuperación ni prometer ausencia de futuros límites.

Este resultado posterior supera el pendiente de pronóstico de los registros anteriores. PDF/ZIPdev.28 conservan el corte previo que describe el429; actualización de evidencia final necesaria antes de entregar como definitivo. Windows, Firefox, imagen y ensayo nuevo siguen abiertos. Solo documentación de evidencia; sin VERSION nueva ni despliegue.


## B07/B09: copia limpia dev.28 ejecutada y revisada

Usuario confirmó ejecución. Registros storage/clean-20261009002340-886e6d4b: inicio2026-10-09T00:23:40Z (8oct18:23:40CST), exit_code0, proyecto eventify-clean-20261009002340-886e6d4b, puerto8084. reference.json coincide con b92ed44968ac9116b35bf003a41486930f7e0d71 y ZIP SHA798941953798e19e758804cae02289be11ec3508ecd2d6d3e9a640f6a13330f5,185archivos. Arranque desde fuente limpia en ruta con espacios, migraciones, sintaxis,39checks clima/44presupuesto y demostración explícita aprobados. Recreación inicial de web/db conserva tablas, archivos, marcador, credenciales e imágenes. Sin tocar destinos anteriores.

IAB http://localhost:8084: login usuario, historial inicial, evento Encuentro de tecnología40espacios, reservaAJAX de2entradas/precio0, navegación automática al ticket/QR, cancelaciónAJAX y registro CANCELADA en historial; detalle vuelve a40espacios. Ticket1fbd7f58b20c3bbc55257af9ac2911226b024635e9361a224c09757026065ed6, reserva a64611159370e489f1a78bf119e07867. Login administrador/dashboard y consulta reservas confirma nueva reserva8 cancelada; logout de ambos roles. Datos ficticios, sin pagos. Capturas temporales /tmp/eventify-dev28-ticket.png y /tmp/eventify-dev28-cancelacion.png, fuera de Git. QR revisado visualmente.

Alcance: instalación limpia y flujo central Linux dev.28 aprobados con estos casos. El clima está desactivado en este ensayo, su éxito público tiene evidencia separada. La recreación ocurrió antes de esta nueva reserva: no afirmar persistencia de la reserva8 después de otra recreación. Windows/Firefox, imagen y aceptación completa siguen pendientes. Copia y volúmenes conservados; sesión final cerrada. Solo evidencia/documentación, sin versión nueva ni suite extensa repetida.


## B07: persistencia posterior a reserva preparada

Preparado storage/verificacion-zip-dev28/persistencia.sh, limitado al proyecto existente eventify-clean-20261009002340-886e6d4b/puerto8084. Valida configuración, entorno local, base eventify_qa y propiedad de ambos volúmenes antes de detener web. Toma snapshot consistente, recrea web/db sin borrar volúmenes, detiene web para otro snapshot, compara exactamente, reinicia web y comprueba tres rutas HTTP. Trap intenta restaurar web si el ensayo falla. No importación, migración ni siembra; no toca destino restaurado ni instancias anteriores. Bash-n aprobado; ejecución solicitada al usuario por restricción Docker/sudo, todavía sin resultado. No repetir el ensayo limpio completo ni crear otro ZIP para este control.

SEGURIDAD y VERIFICACION-WINDOWS aclaran resultados vigentes: cookies HTTPS/TLS externos ya comprobados, sesiones Render efímeras y PowerShell real no equivalente a instalación Windows. Sin cambios de código ni versión. Pendientes concretos: snapshot posterior de reserva8, Firefox, Windows compatible, escaneo de imágenes y sincronización final PDF/paquete/evidencia humana.


## B07: persistencia dev.28 posterior a reserva aprobada

RNF-01/02, RF-12/14 y U-03: registro privado storage/existing-wq5XDtXK, inicio 2026-10-09T00:28:53Z (8 de octubre, 18:28:53 CST), exit_code=0. El ensayo actuó únicamente sobre eventify-clean-20261009002340-886e6d4b, puerto 8084. Los snapshots antes/después de recrear web y MySQL son idénticos: diez tablas, ocho reservas, ocho tickets y archivos duraderos. Sin nueva siembra, importación ni eliminación de volúmenes. Tres rutas HTTP respondieron correctamente; destinos anteriores conservados.

Después de la recreación, IAB confirmó login del usuario ficticio, historial con la reserva de dos entradas CANCELADA y acceso a su mismo ticket. QR completo visible y aviso de ticket sin vigencia; captura revisada /tmp/eventify-dev28-persistencia.png, fuera de Git. Logout comprobado al finalizar. Esto completa el ensayo de persistencia posterior al flujo central de la copia limpia Linux dev.28, pendiente en la entrada anterior. No certifica Windows, Firefox ni todos los escenarios de aceptación.

Solo documentación de evidencia; VERSION permanece dev.28 y no se cambia la aplicación pública. Restan matriz de navegador, ejecución Windows compatible, escaneo de imágenes y sincronización final de PDF/paquete, además de preparación y defensa humanas.


## B07: imágenes base analizadas; avisos pendientes de revisión

SEG-09/RNF-03: Trivy 0.75.0 oficial con checksum comprobado. Dos análisis remotos linux/amd64 terminaron exit0; base de avisos del 8 de octubre. PHP/Apache: 2015 filas/1227 identificadores, dos críticos y145altos; MySQL y auxiliares:69filas/69identificadores, un crítico y29altos. No interpretar exit0 como aprobación ni conteos como ataques demostrados. Detalle, fuentes primarias y límites en SEGURIDAD.md. Digests de etiquetas oficiales actuales coinciden con los fijados; no se cambió stack ni despliegue.

Revisión inicial distingue libxml2, kernel/cabeceras y Go de gosu; no se descartan sin comprobar uso. Imagen final de EVENTIFY aún sin analizar. Preparado wrapper privado storage/image-audit/exportar-imagen.sh: valida el proyecto de la copia limpia8084 y exporta únicamente su imagen por ID, sin volúmenes ni configuración del contenedor, sin detener servicios ni sobrescribir exportación existente. Bash-n aprobado. Se necesita terminal del usuario solo por acceso Docker/sudo restringido; después se puede escanear el tar.

Sin cambios de aplicación ni VERSION nueva; evidencia registrada, seguridad/B07 siguen abiertos.


## B07/SEG-09: imagen dev.28 escaneada y dev.29 preparado

Usuario exportó la imagen real de la copia limpia8084, sin datos/volúmenes. Cadena de digests OCI confirmada con el config auditado. `storage/image-audit/tool/trivy image --disable-telemetry --skip-db-update --scanners vuln --cache-dir storage/image-audit/cache --timeout 5m --format json --output storage/image-audit/eventify-dev28.json --input storage/image-audit/eventify-dev28.tar` terminó exit0. Mismos2015avisos Debian de base; Composer vendor detectado sin avisos. Detalle de digests y límites en SEGURIDAD.md.

Ambos paquetes xz/liblzma siguen en versión anterior: dev.29 fija corrección Debian5.8.1-1+deb13u2 en Dockerfile local/público. Imagen PHP/MySQL base y datos intactos; Render conserva dev.26 y copia limpia conserva dev.28. Nuevo scripts/test-image-linux.sh construye imágenes aisladas, verifica paquetes, sintaxis/unidades y exporta para escaneo posterior. Bash-n y diff correctos. Construcción/pruebas/escaneo de dev.29 pendientes del comando Docker en terminal del usuario; no marcar estable ni publicar automáticamente.

Próxima acción: bash scripts/test-image-linux.sh. Leer storage/image-check-ID/status.txt/test.log y escanear ambos tar antes de dar el parche por comprobado. Sin reinstalar o detener el stack existente.


## B07: corrección del nombre de imagen, dev.30

Ensayo image-check-sFMEIgyi: exit_code=125. Docker rechazó eventify-image-check-sFMEIgyi-local por mayúsculas en el repositorio antes de construir; no llegó a pruebas, exportación ni actualización de servicios. Advertencia legacy builder separada, no causa del rechazo.

Script normaliza el identificador de imagen con expansión Bash a minúsculas y conserva el directorio/log original. Bash-n y tres casos de formato, incluido sFMEIgyi, correctos. No equivalen a construir ni ejecutar PHP. VERSION dev.30 conserva el parche xz anterior; repetir scripts/test-image-linux.sh en terminal del usuario por restricción del servicio Docker. Registros fallidos preservados.


## B07: construcción y escaneo dev.30 aprobados para el parche xz

Usuario ejecutó scripts/test-image-linux.sh. Registro storage/image-check-APnvo9xh:inicio2026-10-09T00:58:15Z/exit0, versióndev.30, commit390b1ef6dcbb9a9d76eaad8a910a80da364bb637 y árbol limpio. Local/pública construidas con paquetes corregidos comprobados por dpkg; cada una pasa sintaxis,266unidades y QR. Pruebas sin red/puertos/montajes/MySQL; no repetir ni sumar con integración219.

Se ejecutó el mismo comando Trivy --input utilizado para dev.28, con --disable-telemetry --skip-db-update --scanners vuln, cambiando entrada a cada tar del registro y salida a local-scan.json/public-scan.json. Ambos procesos exit0; cadena OCI de exportación/config verificada. Comparación:2015→2011avisos por imagen, cuatro filas xz/liblzma desaparecidas y cero añadidas; bibliotecas Composer sin avisos. Dos críticos y145altos de la base permanecen, no marcar seguridad completa. Detalles en SEGURIDAD.md.

Parche concreto de runtime comprobado; VERSION/etiqueta dev.30 conservados. Render sigue dev.26, copia limpia8084 sigue dev.28. No publicar/recrear destinos por inferencia. Próximos pendientes: revisión de alcanzabilidad/correcciones restantes, Firefox/Windows y casos finales de aceptación, sincronización PDF/paquete y evidencia humana de defensa.


## B07: alcance de avisos y teclado del registro

Dev.30: inventario de módulos en las dos imágenes exportadas, aplicando whiteouts de capas OCI, guardado privadamente en modules-review.json. Los seis avisos altos Apache requieren módulos no habilitados. Contraste oficial: corrección upstream2.4.69, Debian Trixie aún afectado en los avisos consultados. No eliminar avisos ni mezclar paquetes sid. TicketQr construye SVG directamente y no usa el SvgImageBackEnd/XMLWriter encontrado en vendor. Revisión estática de entradas XML, streams de clima y ejecución de comandos; alcance y pendientes en AVISOS-IMAGENES.md. No equivale a comprobar runtime ni resolver todos los avisos.

IAB/copia limpia8084 dev.28,390×844: navegación Tab desde marca/Inicio a nombre, correo, contraseña, confirmación y botón; foco visible3px y sin desbordamiento horizontal. Enter con formulario vacío muestra Completa este campo y devuelve foco a full_name; no datos ingresados ni cuenta creada. Captura /tmp/eventify-registro-teclado.png revisada; viewport restaurado y pestaña final inicio sin sesión. Verifica teclado/validación nativa de este formulario, no registro válido ni accesibilidad completa. CUA ofrece Chrome/IAB, sin Firefox en inventario; Firefox real permanece pendiente.

Solo documentación/evidencia; VERSION dev.30 conservada. Pendientes: seguridad restante, casos de UI/zoom, Firefox/Windows, sincronización de entregables y defensa humana.


## B09: documento actualizado y casos de cierre

README, PLAN, MATRIZ y TECNICO actualizados para distinguir código dev.30, publicación dev.26 y ZIP dev.28 probado. Quitadas referencias operativas antiguas de pronóstico público pendiente, recreación Linux pendiente y herramientas de pruebas no elegidas. Se conserva el historial de etapas anteriores. CIERRE.md organiza doce casos restantes con criterio/evidencia: interfaz/errores, teclado/zoom, Firefox, Windows, avisos, publicación de la referencia elegida, paquete definitivo y revisión/defensa humanas. No cuenta su preparación como ejecución.

Generador PDF ejecutado con Python/ReportLab del runtime. output/pdf/EVENTIFY_Documento_Tecnico_Borrador.pdf:15páginasA4, portada/cabecera dev.30, texto con RF-21/RNF-07 y evidencia actual. Poppler renderizó las15páginas; tres hojas de contacto revisadas visualmente, tablas/diagramas sin recortes ni solapamientos. Pdfplumber no encontró caracteres fuera de página. Borrador dev.28 anterior conservado fuera de Git. Datos de estudiante/docente y revisión humana pendientes.

Sin cambios de aplicación, versión nueva, despliegue ni ZIP nuevo; no repetir integración MySQL por documentos. PDF es borrador actualizado, no declaración de entrega final aprobada.


## B09: revisión editorial dev.31

Documentos y guías revisados con español natural, comandos desde la raíz del proyecto y explicaciones centradas en las reglas de EVENTIFY. Se conservan los identificadores de commits, huellas, resultados reales y pendientes de aceptación. No se atribuye al estudiante una revisión o defensa sin evidencia. La aplicación conserva el comportamiento de dev.30; este incremento modifica documentación y VERSION.

Verificación editorial: búsqueda en archivos versionados sin menciones a herramientas de redacción; identificadores históricos conservados y git diff --check correcto. PDF dev.31 regenerado:15 páginas A4, texto/requisitos y límites de página comprobados; todas las páginas renderizadas y revisadas visualmente. Borrador dev.30 conservado. No se repiten pruebas de aplicación porque no cambió su código.

Dev.31 publicada:4088cbe34aef3e9c7ec12ead72b9781fd61efac8; rama y etiqueta resuelta comprobadas en remoto. Paquete output/entrega/eventify-desarrollo-4088cbe34aef/eventify-source.zip:CRC correcto, VERSION dev.31 y documentación revisada sin menciones a herramientas de redacción. SHA-256:52a0028febe8263a52cf8be1238201a4effb38323bbbeec968fef6814383c36b. Guía adjunta actualizada para reflejar el pronóstico público comprobado. El ZIP incluye fuente y migraciones, sin base existente ni claves personales; no se ha instalado esta nueva referencia en Linux o Windows. PDF generado aparte y conservado como borrador.


## B07 / C-01: registro completo comprobado en navegador

RF-01/02/03 y RNF-03/04. IAB en la copia limpia Linux http://localhost:8084, dev.28: datos ficticios de nombre/correo example.invalid y contraseña aleatoria guardados únicamente en storage/registro-browser/cuenta.json, permisos600. Confirmación distinta devuelve «Las contraseñas no coinciden» y vacía los campos de contraseña; alta válida dirige a /ingresar?registro=ok con aviso de cuenta creada. Repetición del mismo correo devuelve «Ese correo ya está registrado». Login incorrecto muestra mensaje genérico; login correcto muestra identidad de la cuenta en /cuenta. Acceso directo a /admin rechazado por el servidor. Logout seguido de /cuenta exige nuevo ingreso. Cuenta de prueba conservada; no reservas nuevas, bases eliminadas ni cambios externos.

Revisados AuthController, Auth, RegistrationInput, Session, vistas y rutas; git diff entre b92ed44968ac9116b35bf003a41486930f7e0d71 y HEAD no muestra diferencias en estos archivos. Auth asigna USUARIO desde el servidor y utiliza consultas preparadas. Esta ejecución verifica el recorrido del navegador; hashes, renovación de sesión y peticiones manipuladas cuentan con pruebas de integración anteriores, no se presentan como nuevas consultas MySQL. Captura /tmp/eventify-registro-cuenta.png revisada visualmente, sin contraseñas. Sesión final cerrada.

C-01 de navegador completado con este alcance. No certifica Firefox, Windows ni la instalación del ZIP dev.31. Sin cambios funcionales ni VERSION nueva; no repetir suite extensa. Siguiente caso: C-02, filtros combinados y estados AJAX.


## B07 / C-02: catálogo y estados AJAX comprobados

RF-05/06, RT-02/03 y RNF-04. IAB, copia limpia Linux dev.28 en localhost:8084, sin sesión ni escritura de datos. Búsqueda SQL + Tecnología + fecha15oct2026 + Con espacios devuelve únicamente Taller de SQL,28espacios. Cambiar categoría a Cultura conserva los otros criterios y devuelve cero resultados con mensaje útil. Limpiar restablece campos/selectores y cinco eventos; Agotados devuelve únicamente Cocina. Fecha inicial18oct con final15oct rechazada con mensaje específico, sin sustituir resultados válidos.

Prueba de red controlada únicamente en esta pestaña: Network.emulateNetworkConditions con latencia1800ms. Durante la solicitud, aria-busy=true y «Buscando eventos…». En una misma secuencia se enviaron SQL y Encuentro: eventos CDP registran solicitud SQL663263.282, loadingFailed/canceled=true/ERR_ABORTED, y solicitud Encuentro663263.283 con loadingFinished. Resultado final y URL corresponden a Encuentro de tecnología; la consulta anterior no reaparece. Esto verifica cancelación de solicitud pendiente, no la entrega deliberada de un callback antiguo pese al aborto.

Modo offline temporal en la pestaña: una nueva búsqueda SQL falla y conserva Encuentro; aviso «No pudimos actualizar los eventos. Se conservan los resultados anteriores; vuelva a intentar». aria-busy vuelve a null. Restaurada la red sin latencia, reintento devuelve Taller de SQL. Captura /tmp/eventify-catalogo-error.png revisada visualmente. Al terminar se limpiaron filtros y desactivó Network; no queda emulación de red. No se desconectó el host ni se probó el fallo en Render.

Revisados catálogo JS, controlador, filtro y repositorio PDO: validación de parámetros/rango en servidor y consultas preparadas. git diff de estos archivos y catalog-results entre dev.28 y HEAD sin diferencias. C-02 comprobado con este alcance; no certifica Firefox, Windows ni otra instalación del ZIP. Sin cambio funcional/VERSION ni repetición de integración MySQL. Siguiente caso C-03: resultado desconocido al confirmar una reserva, en evaluación aislada.
