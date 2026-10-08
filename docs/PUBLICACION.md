# B08: propuesta de publicación gratuita

Revisión documental: 2026-10-08. Requisitos afectados: RT-09, ENT-A/D, U-02, RNF-04. **Propuesta técnica pendiente de implementación, autorización y prueba externa.** No hay cuentas nuevas, servicios creados ni URL desplegada. GitHub privado ya funciona; publicar la aplicación es una acción separada.

## Decisión y evidencia

El enunciado exige PHP/MySQL y URL funcional. El usuario pide cero gasto y entrega local Linux/Windows. La propuesta propia es separar el contenedor PHP/Apache y la base MySQL administrada: **Render Free + Aiven for MySQL Free**. La combinación es una inferencia de viabilidad a partir de capacidades documentadas; todavía no demuestra conexión entre proveedores ni cumplimiento del entregable.

| Alternativa | Evidencia consultada | Decisión para EVENTIFY |
| --- | --- | --- |
| InfinityFree | Su administrador declaró MariaDB 11.4 y que no puede seleccionarse la versión del motor. | No seleccionar para MySQL obligatorio con la evidencia disponible. No cambiar el esquema ni el requisito para adaptarlo. |
| Render con MySQL dentro del servicio gratuito | Almacenamiento efímero, sin disco persistente gratuito. | No colocar allí la base. |
| Render PHP Docker + Aiven MySQL Free | Docker admite PHP; Aiven ofrece MySQL gratuito administrado. | Candidata prioritaria para preparar y validar, sin afirmar publicación terminada. |

[InfinityFree: declaración del administrador, 17-10-2025](https://forum.infinityfree.com/t/mysql/116138), [Render: Docker/PHP](https://render.com/docs/docker), [Aiven: MySQL gratuito](https://aiven.io/docs/products/mysql/concepts/mysql-free-tier).

## Condiciones gratuitas y límites

Render proporciona HTTPS y dominio onrender.com. El servicio gratuito duerme tras 15 minutos sin tráfico y despertar puede tomar alrededor de un minuto; los archivos escritos se pierden al dormir, reiniciar o desplegar. Hay 750 horas mensuales compartidas por espacio de trabajo y cuotas de tráfico/construcción. Sin método de pago, superar estas cuotas suspende servicios o nuevas construcciones. Tráfico saliente inusualmente alto, incluida una base externa, puede causar suspensión; el documento no fija ese umbral. No hay shell ni trabajos puntuales gratuitos. [Condiciones de Render](https://render.com/docs/free).

Aiven documenta MySQL gratuito sin tarjeta y sin plazo fijo: un nodo, 1 CPU, 1 GB RAM, 1 GB de disco, respaldos y hasta 76 conexiones. Puede apagar servicios inactivos, reactivables, y cambiar región/configuración; no tiene SLA. Elegir el plan **Free**, evitando confundirlo con créditos de prueba o planes pagados. [Condiciones de Aiven](https://aiven.io/docs/products/mysql/concepts/mysql-free-tier).

No se garantiza disponibilidad continua para evaluación por leer estas condiciones. Antes de crear servicios, revisar condiciones vigentes, región, cuotas compartidas y opciones del panel; si solicita tarjeta o impone cobro, detener ese paso. No activar mantenimiento artificial ni pagos para eludir límites. Verificar disponibilidad antes de la defensa y conservar la entrega local.

## Adaptaciones necesarias del código

Esta inspección identifica trabajo pendiente; no cambia todavía el comportamiento de la aplicación.

1. **MySQL externo con TLS verificado.** Dev.12 prepara DatabaseConfig/Database con TLS obligatorio en production, CA fuera de raíz web y verificación de servidor activa. Rechaza configuración incompleta antes de conectar y comprueba Ssl_cipher antes de usar la conexión. Pruebas de configuración correctas; negociación/certificado/identidad reales pendientes. Comprobar motor/versión real, collation utf8mb4_0900_as_ci, CHECK, columnas generadas y locks del esquema existente. La versión disponible en la cuenta aún no se conoce. [Certificados y rotación de Aiven](https://aiven.io/docs/platform/concepts/tls-ssl-certificates).
2. **Arranque público controlado.** Render ejecutaría el Dockerfile web, no el Compose local completo. Adaptar puerto/bind y permisos de storage, manteniendo public como única raíz web. Preparar migraciones idempotentes con control de lock y fallo antes de servir; no depender de SSH ni exponer un endpoint de instalación. Las claves se suministran al ejecutar, nunca durante build. [Puertos y servicios de Render](https://render.com/docs/web-services).
3. **Persistencia.** Usuarios, eventos, reservas, tickets y límites de autenticación residen en MySQL. La galería actual viene del código y debe conservarse en cada imagen. Decisión propia para una sola instancia académica: sesiones PHP en archivos efímeros; un reinicio/redistribución del servicio obliga a ingresar nuevamente. La sesión debe mantenerse entre peticiones durante la ejecución normal y cerrarse correctamente. No ofrecer inicio permanente entre reinicios; comprobar esta conducta públicamente antes de cerrar B08. No requiere cambiar el almacenamiento de los registros de negocio. No confundir galería del código con capacidad de persistir futuras subidas.
4. **API externa.** EventWeather guarda caché y presupuesto en storage/weather. En un disco efímero se reinician; dev.13 prepara presupuesto persistente MySQL mediante 003_weather_budget. Migración, límites secuenciales y dos carreras específicas MySQL ya aprobados en219 comprobaciones; verificar además desde el proveedor antes de habilitar la API pública. Verificar Open-Meteo desde el proveedor, atribución, timeout y degradación sin bloquear reservas. WEATHER_ENABLED=false sirve para diagnóstico, pero no certifica el requisito de integración útil funcionando.
5. **Acceso de evaluación.** DemoSeeder bloquea production correctamente. Preparar aprovisionamiento explícito de cuentas ficticias con claves únicas y entrega privada; no quitar esa guarda ni sembrar administradores en cada arranque. Mantener APP_ENV=production y cookies Secure/HttpOnly/SameSite; probar HTTPS real y cierre de sesión.

## Secuencia y aceptación

1. QA, restore y copia limpia Linux aprobados con el alcance de ESTADO. Completar adaptaciones públicas de arranque/TLS/aprovisionamiento y sus pruebas; conservar etiquetas de desarrollo, revisando compatibilidad de esquema antes de elegir rollback.
2. Preparar configuración y procedimiento de aprovisionamiento revisables, sin crear servicios. Revisar permisos mínimos para acceso al repositorio privado y exclusión de secretos.
3. Con autorización externa, crear exclusivamente los planes gratuitos, configurar TLS/secretos/cuentas ficticias y desplegar una referencia Git comprobada. No transferir datos personales ni publicar el repositorio.
4. Registrar URL real, fecha, motor/versión sin credenciales, plan/cuotas y build/SHA. Verificar desde fuera: catálogo, ambos roles, reservas/tickets/QR, cancelación, administración y API.
5. Probar reinicio/redeploy: conservar datos y galería, comprobar sesiones y presupuesto API. Ensayar recuperación compatible con migraciones y respaldo; un rollback de código no restaura MySQL.
6. Revalidar acceso y cuotas durante la evaluación. ENT-A/RT-09 siguen pendientes hasta que exista esa evidencia. Windows, transferencia final y defensa humana mantienen sus verificaciones separadas.

Consultar [ESTADO.md](ESTADO.md), [RESPALDO.md](RESPALDO.md) y [VERSIONADO.md](VERSIONADO.md). Esta propuesta no es un manual de despliegue ejecutable ni autoriza crear cuentas, aceptar términos o publicar.

## Configuración TLS preparada en dev.12

La aplicación lee DB_TLS_MODE=verify_identity y DB_TLS_CA como ruta de archivo CA montado al ejecutar. Production exige ese modo; local/test conservan disabled por defecto. Modos ambiguos (preferred/required sin verificación) se rechazan. No existe opción para apagar la verificación del servidor. Una CA configurada junto con disabled también se rechaza para no ignorar una configuración accidental.

DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME y DB_PASSWORD se suministran al contenedor web público mediante el panel del proveedor. El Compose local sigue apuntando a su servicio db y no administra una base externa. No copiar secretos ni certificados del proveedor al repositorio o al Dockerfile. Preparar CA como archivo de ejecución fuera de public y actualizarla ante rotación.

PDO usa MYSQL_ATTR_SSL_CA y MYSQL_ATTR_SSL_VERIFY_SERVER_CERT=true durante construcción, conforme al [manual PHP](https://www.php.net/manual/en/class.pdo-mysql.php). Además, SHOW SESSION STATUS LIKE 'Ssl_cipher' debe indicar cifrado o la aplicación rechaza la conexión. No usar parámetros PostgreSQL sslmode/sslrootcert en el DSN MySQL.

Antes de publicación comprobar en PHP 8.4/mysqlnd: certificado válido/hostname correcto aceptados, CA no confiable/hostname incorrecto rechazados y MySQL sin TLS rechazado; nunca resolver estos fallos desactivando verificación. Las 40 comprobaciones unitarias verifican configuración y guardas con un certificado efímero; no abren una conexión MySQL. Repetir integración local afectada por la nueva configuración antes de certificar que el stack local sigue funcionando.

## Presupuesto API preparado en dev.13

El controlador meteorológico utiliza WeatherBudget con la misma conexión PDO: contador global en MySQL, no en el disco efímero del servidor PHP. Cada intento permitido se confirma antes del HTTP externo; los fallos externos cuentan. Un error de presupuesto omite la consulta, conserva respuesta segura y no afecta al endpoint de reserva. Migración aditiva, límites 30/h y 10/min, reloj UTC del servidor y ventanas por hora/minuto. Política/flujo probados; integración219, carreras específicas minuto/hora y persistencia/restore locales aprobados. Reinicios y conexión/API desde proveedores públicos pendientes. La caché sigue en archivos y puede perderse al dormir; volver a consultar queda sujeto al contador persistente. El controlador nunca cae al presupuesto de archivos; el modo sin callback se conserva para las pruebas unitarias independientes.

## Revalidación documental y decisiones de despliegue

Fuentes oficiales Render free/web-services y Aiven mysql-free-tier abiertas nuevamente2026-10-08: planes y límites anteriores siguen documentados. Aiven permite solo un servicio de cada tipo por organización; comprobar si ya existe uno para evitar asumir una segunda base gratuita. Respaldo incluido no prueba exportación/recuperación de esta aplicación. Render puede suspender por tráfico saliente elevado a BD/API; no hay umbral público que permita garantizar disponibilidad. Son riesgos de evaluación, no un costo activado.

Inspección actual: Dockerfile expone80/ApacheListen80; storage se crea para www-data; Session usa archivos y cookieSecure obligatoria enproduction. MigrationRunner ya usa lock MySQL para coordinar migraciones. Faltan arranque público que falle antes de servir si configuración/migración falla, CA secreta montada en ejecución, cuentas de evaluación únicas y negociación TLS real. No basta elegir planes ni crear servicios para cerrar esos puntos. Ver [DESPLIEGUE-PUBLICO.md](DESPLIEGUE-PUBLICO.md).

## Ensayo real previo a proveedores

Dev.21,2026-10-08T19:31:50Z/public-tls-LeWppmpW exit0: TLSPHP8.4/MySQL8.4 válido, CAajena/hostnameincorrecto rechazados, tres migraciones/ApacheHTTPinterno y recreación pública aprobados en red aislada con certificados ficticios. Esto supera la preparación de arranque y negociación local; no sustituye CA/hostname/motor del proveedor ni HTTPS/URL externa. Aprovisionamiento único y caso servidor sinTLS siguen pendientes.


## Dev.24: ensayo sin TLS completo aprobado

`public-no-tls-QnTPzu56`, inicio `2026-10-08T19:55:27Z`, exit_code=0; proyecto `eventify-no-tls-bedcc85fc265`. PHP 8.4 lint correcto; dos probes TCP antes/después: MySQL 8.4 disponible sin TLS, Database production rechaza, base vacía. State real: exited/no Running/ExitCode 1/sin OOM/Error, terminado 19:55:41 UTC. Preflight y Apache Syntax OK; fallo de conexión impide migrar/servir. Código Compose 124 indica espera agotada del wrapper y se conserva por separado; aplicación terminó antes con rechazo esperado.

Job/db/red efímeros propios retirados; archivos privados conservados, sin cambios en bases existentes. Caso servidor sin TLS cerrado en este entorno, sin relajar controles ni repetir 219 pruebas. No certifica proveedor/CA externa, HTTPS/URL ni Windows/Firefox. VERSION/etiqueta dev.24 conservadas.
