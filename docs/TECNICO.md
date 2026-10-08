# EVENTIFY
## Documento técnico - borrador de evaluación

Curso: SC-502 Ambiente Web Cliente-Servidor. Fecha de corte: 8 de octubre de 2026. Código de referencia: v0.1.0-dev.17. Estudiante, docente y fecha de entrega: pendientes de completar por la persona responsable. No se atribuye autoría ni participación que no esté documentada.

Este documento describe la implementación y sus límites. La integración actual, restauración de datos y arranque desde ZIP en Linux tienen evidencia real. El sistema público, Windows, Firefox y los criterios restantes de entrega siguen pendientes. No constituye certificación de entrega completa.

## 1. Descripción y resultado esperado

EVENTIFY es una aplicación web para descubrir eventos, reservar entradas y consultar tickets con QR. Un usuario se registra, inicia sesión, filtra eventos y confirma una cantidad de entradas. El administrador gestiona categorías, eventos, reservas y métricas. El pronóstico meteorológico ayuda a planificar eventos próximos; su fallo no debe bloquear reservas.

El resultado final requerido incluye aplicación local portable, URL pública PHP/MySQL, repositorio con historial, cuentas de evaluación, documentación técnica y defensa individual. Actualmente hay código, pruebas parciales, GitHub privado y ZIP de desarrollo. No incluye cobros reales, aplicación móvil nativa ni control de acceso mediante escaneo.

La suficiencia define el alcance académico; las condiciones del usuario añaden gratuidad, Linux de desarrollo y transferencia a Windows. Las decisiones técnicas propias se distinguen de los requisitos en REQUISITOS.md. Los PDF no autorizan acciones externas.

## 2. Tecnologías y arquitectura

| Capa | Tecnología y función |
| --- | --- |
| Interfaz | HTML5, CSS3 y JavaScript; vistas PHP con escape de contenido. |
| Interacción | jQuery 4.0.0 local; catálogo e historial asincrónicos, sin CDN obligatorio. |
| Servidor | PHP 8.4 y Apache; controladores HTTP, servicios de dominio y repositorios PDO. |
| Datos | MySQL 8.4 Community/InnoDB, restricciones, índices y migraciones versionadas. |
| Identidad | Sesiones PHP, hashes de contraseña, CSRF y roles comprobados en servidor. |
| QR | BaconQrCode 3.1.1 y dasprid/enum 1.0.7; generación SVG local con composer.lock. |
| Entorno | Docker Compose, scripts Bash/PowerShell, volúmenes persistentes en local. |
| API externa | Open-Meteo por HTTPS, destino fijo, timeout, caché y límite global. |
| Desarrollo | Git/GitHub privado; pruebas PHP, Python y Node como herramientas de desarrollo. |

```architecture
Navegador -> Apache/PHP -> PDO -> MySQL
Navegador -> API clima PHP -> Open-Meteo HTTPS
```

public/index.php es el punto de entrada y decide ruta/método. app/Http coordina respuestas y autorización; app/Domain concentra reglas y transacciones; app/Infrastructure realiza acceso a MySQL/API y migraciones; app/Views renderiza; app/Security administra identidad; public/assets contiene estilos, JavaScript, bibliotecas e imágenes locales. La raíz pública es public, no el repositorio completo.

PHP/MySQL son obligatorios. Docker reproduce el entorno local; el receptor no instala PHP/MySQL en su host. Python del empaquetador, herramientas PDF y Node de pruebas no son dependencias para ejecutar la aplicación extraída.

## 3. Navegación y roles

| Área | Rutas principales y acceso |
| --- | --- |
| Pública | /, /eventos, /eventos/{slug}, /registro, /ingresar. Los borradores no son públicos. |
| Cuenta | /cuenta y POST /salir; sesión válida y logout protegido. |
| Usuario | /experiencias, /tickets/{code}, /tickets/{code}/qr.svg; propiedad verificada en MySQL. |
| Administrador | /admin, /admin/categorias, /admin/eventos, /admin/reservas y detalles/formularios. |
| AJAX | /api/eventos, /api/experiencias, /api/reservas, cancelación y clima. |
| Disponibilidad | /salud informa ok/unavailable; no expone claves ni consultas. |

La navegación visual no otorga permisos. Una URL de administración exige ADMINISTRADOR; una URL de ticket exige su propietario. Un ticket ajeno responde 404 sin revelar contenido. El registro público asigna USUARIO aunque el navegador envíe otro rol.

## 4. Datos y relaciones

```er
roles -> users -> reservations -> tickets
categories -> events -> reservations
```

Una categoría agrupa muchos eventos; un usuario realiza muchas reservas; un evento recibe muchas reservas; una reserva tiene como máximo un ticket. Las claves foráneas restringen eliminación y conservan historia. Las diez tablas incluyen seis entidades de negocio y cuatro técnicas. La migración 003 añade weather_budget; su aplicación real está comprobada en desarrollo y QA.

### Diccionario y reglas
| Tabla | Campos y reglas relevantes |
| --- | --- |
| roles | `id` TINYINT; `code` ASCII único y nombre. Roles ADMINISTRADOR/USUARIO creados en migración. |
| users | `id` BIGINT; `role_id`; `full_name` 150; `email` 254 único sin distinguir mayúsculas; `password_hash` 255; `is_active`; fechas de creación/actualización. |
| categories | `id` BIGINT; nombre 120 y slug ASCII 120 únicos; indicador activo y fechas. |
| events | `id`, categoría; slug ASCII único 160; nombre 180; descripción TEXT; ruta de imagen 255; inicio DATETIME; ubicación 255; coordenadas DECIMAL(9,6) opcionales con límites; capacidad INT positiva; precio DECIMAL(12,2) no negativo; estado y fechas. |
| reservations | `id`, usuario/evento; código ASCII único de 32 caracteres; cantidad INT positiva; precio unitario histórico DECIMAL(12,2); total DECIMAL(22,2), igual a precio × cantidad; estado; fecha de cancelación y auditoría temporal. |
| tickets | `id`; reserva única; código ASCII único de 64 caracteres; creación. Un ticket representa la reserva y su cantidad; QR SVG implementado; representa la reserva completa y su cantidad. |
| schema_migrations | Versión, SHA-256, estado EN_PROCESO/APLICADA, número de sentencias ejecutadas, inicio/finalización. |
| auth_limits | Migración 002: clave HMAC de acción/IP, inicio de ventana y contador; protección persistente de intentos. |
| demo_seed_runs | Identificador de demostración aplicada y fecha; evita sembrar de nuevo. |
| weather_budget | Migración 003: scope global, ventanas hora/minuto y contadores con máximos 30/h y 10/min; persistencia/transacción PDO preparadas. |

El esquema exacto está en database/migrations/001_initial_schema.php, 002_auth_limits.php y 003_weather_budget.php. Fechas en UTC, presentación en America/Costa_Rica. Precio histórico unit_price y total se guardan en DECIMAL; el servidor calcula precio por cantidad. active_event_id es columna generada: el índice único impide dos reservas activas por usuario/evento y permite conservar cancelaciones.

No existe una fila de cupos disponibles: se deriva capacidad menos suma de entradas CONFIRMADAS. Reserva y evento tienen estados separados; un evento cancelado invalida el ticket sin borrar su reserva. Imágenes actuales pertenecen a una galería del código; no hay carga arbitraria de archivos.

## 5. Reglas transaccionales y decisiones

Confirmar reserva inicia una transacción, bloquea la fila del evento FOR UPDATE y revalida estado, fecha, duplicidad, cantidad y disponibilidad. Inserta reserva y ticket antes de COMMIT. Un fallo revierte ambos. La base añade índice único y restricciones, pero el límite de aforo necesita ese bloqueo de servicio.

Cancelar verifica propietario y usa orden de bloqueo evento antes de reserva. Solo se permite a más de 86400 segundos del inicio; exactamente 24 horas se rechaza. Repetir una cancelación ya realizada no libera cupos dos veces. No se borra historia ni se altera el precio guardado.

Administración conserva slug y precios históricos. Cualquier historial fija la fecha y prohíbe regresar a BORRADOR. No reduce capacidad por debajo de entradas confirmadas. Cancelar un evento impide nuevas reservas, conserva existentes y no equivale a cancelar cada reserva. Finalizar explícitamente exige que haya iniciado. Categoría inactiva conserva referencias.

El dashboard distingue número de reservas de número de entradas e ingresos potenciales de pagos. Usa agregados y ocupación ponderada sobre el conjunto documentado; evita división por cero. Las métricas tienen pruebas con datos conocidos, sin inventar ventas reales.

## 6. Requisitos e historias de aceptación

Las siguientes obligaciones y criterios proceden de la referencia operativa REQUISITOS.md, derivada del enunciado. Son criterios de aceptación, no afirmaciones de que todos ya pasaron. MATRIZ.md relaciona cada RF con código, prueba y limitación. Los actores principales son visitante, usuario y administrador.

### RF-01 — Registro de usuarios

- Solicitar nombre completo, correo, contraseña y confirmación; validar cliente y servidor; correo único; contraseñas sin texto plano.
- Aceptación: alta válida persistida; campos inválidos y confirmación distinta rechazados; correo duplicado rechazado también por petición directa; hash en MySQL. La regla de normalización del correo se documenta, no se inventa un formato que excluya direcciones válidas.
- Evidencia prevista: integración de registro y verificación del navegador/esquema.

### RF-02 — Autenticación y cierre

- Ingresar con correo/contraseña y mantener sesión PHP; mensajes comprensibles sin información sensible; destruir sesión al salir.
- Aceptación: acceso válido/incorrecto, persistencia entre peticiones y acceso denegado tras logout. Verificar renovación de identificador de sesión como medida de implementación.
- Evidencia: pruebas de autenticación, sesión y navegador.

### RF-03 — Roles y autorización

- Roles ADMINISTRADOR y USUARIO; autorización en servidor; rechazar URL directa no autorizada. Ocultar botones no es protección.
- Aceptación: visitante/usuario rechazados en endpoints administrativos; administrador accede; registro público no puede elevar su rol enviando campos adicionales.
- Evidencia: matriz de permisos UI y HTTP, con respuestas correctas según HTML/API.

### RF-04 — Catálogo

- Tarjetas u otra vista apropiada con imagen, nombre, categoría, fecha/hora, ubicación, precio, disponibilidad y enlace; distinguir cancelados/finalizados.
- Aceptación: información real de MySQL, estados visibles y caso sin resultados. Borradores accesibles solo al administrador según D-02.

### RF-05 — Búsqueda dinámica

- Buscar por nombre sin recarga completa mediante comunicación asincrónica.
- Aceptación: resultados pertinentes, vacío, sin coincidencias y fallo controlado; las respuestas tardías no reemplazan la consulta más reciente.

### RF-06 — Filtros combinables

- Categoría, fecha o rango temporal y disponibilidad; combinables; al menos un filtro mediante AJAX.
- Aceptación: combinación de los tres criterios, limpiar filtros, rango inválido y consulta sin resultados; validar parámetros en servidor.

### RF-07 — Detalle y disponibilidad

- Imagen, nombre, descripción, categoría, fecha/hora, ubicación, precio, capacidad, espacios disponibles; información API cuando corresponda.
- Disponibilidad calculada con reservas vigentes persistidas.
- Aceptación: cantidad de cupos coincide con MySQL; evento inexistente gestionado; disponibilidad actualizada tras reservar/cancelar; fallo API no bloquea el detalle.

### RF-08 — Crear reservación

- Usuario autenticado selecciona una o más entradas; total dinámico precio × cantidad; persistir en MySQL.
- Aceptación: reserva válida tiene usuario/evento/cantidad/valor; no autenticado rechazado; cantidades cero/negativas/fraccionarias/inválidas rechazadas. Manipular precio/total en la petición no cambia el cálculo autoritativo del servidor (DECISIÓN de seguridad).

### RF-09 — Control de cupos

- Comprobar disponibilidad en servidor inmediatamente antes de confirmar; no superar disponible; nunca disponibilidad negativa.
- Aceptación: reservar exactamente los cupos restantes funciona; pedir uno de más falla; dos peticiones simultáneas al último cupo no pueden confirmar ambas. Un fallo a mitad de operación no deja reserva/ticket parcial ni consume cupos.
- Implementación prevista: transacción, protección de concurrencia y lectura autoritativa de MySQL; mecanismo concreto documentado en B02/B04.

### RF-10 — Últimos espacios

- Mostrar automáticamente «¡Últimos espacios disponibles!» cuando disponible ≤ 20 % de capacidad.
- Aceptación: verificar debajo, exactamente en el umbral y por encima; capacidades pequeñas sin errores de redondeo. Mantener la condición matemática del PDF y distinguir agotado en la interfaz.

### RF-11 — Reserva activa duplicada

- No mantener dos reservaciones activas independientes del mismo usuario/evento; informar y evitar duplicado.
- Aceptación: segunda petición secuencial y dos peticiones simultáneas no crean dos activas. Nueva reserva tras cancelación permitida solo si el evento admite reservar y existen cupos (DECISIÓN).

### RF-12 — Mis experiencias

- Mostrar evento, fecha, cantidad, total, estado y acceso al ticket de las reservas propias.
- Aceptación: propietario accede; otra cuenta no puede leer reserva/ticket ni por HTML ni por endpoint/URL manipulada; estados e historial correctos.

### RF-13 — Cancelar reservación

- Solo si faltan más de 24 horas; cambiar estado sin borrar fila; liberar cupos; con 24 horas o menos impedir e informar motivo.
- Aceptación: 24 h + 1 s permitido, exactamente 24 h y 24 h − 1 s rechazados; usar reloj controlable para pruebas. Repetir cancelación no libera dos veces; solo propietario o acción administrativa expresamente definida.

### RF-14 — Ticket digital

- Cada reservación confirmada genera ticket con identificador único, evento, usuario, fecha/hora, cantidad, código de reservación y QR generado con biblioteca apropiada.
- Aceptación: registro persistido, código único, QR real renderizable, datos coinciden con reserva; rechazo de acceso ajeno. Un ticket por reserva puede representar varias entradas; no exigir un ticket por entrada.
- No se requiere escáner. Contenido del QR y comportamiento al cancelar definidos en D-04; no exponer datos privados sin autorización por incorporar una URL al QR.

### RF-15 — API externa útil

- Mínimo una API; datos integrados de forma útil; manejar indisponibilidad/error. Clima es ejemplo, no obligación.
- Aceptación: respuesta real visible en contexto del evento, error/timeout/cuota/formato inesperado manejados; no inventar datos como si fueran respuesta real.
- Elegir API con condiciones gratuitas compatibles, atribución/licencia documentada, caché razonable y límites temporales/geográficos respetados. Si clima no cubre la fecha, comunicarlo.

### RF-16 — Dashboard

- Datos reales: total eventos, eventos activos, reservas activas, entradas reservadas y porcentaje global de ocupación.
- Al menos dos derivados: evento más reservado, categoría más popular, mayor ocupación o ingresos potenciales.
- Aceptación: comparar con conjunto de datos conocido; cantidades de entradas no confundidas con cantidad de reservas; cálculo ponderado y conjunto coherente según D-03; sin división por cero. Ingresos potenciales claramente identificados, nunca pagos reales.

### RF-17 — Categorías

- Crear, consultar, modificar y eliminar o desactivar; proteger integridad referencial.
- Aceptación: CRUD funcional con validación; categoría utilizada no rompe eventos ni historial al desactivar/eliminar. Decisión inicial: desactivación cuando esté referenciada; no borrar en cascada eventos.

### RF-18 — Administración de eventos

- CRUD completo: identificador, nombre, descripción, categoría, imagen, fecha, hora, ubicación, capacidad, precio, estado.
- Estados BORRADOR, PUBLICADO, CANCELADO, FINALIZADO.
- Aceptación: creación, lectura, edición y eliminación segura/desactivación conforme al historial. No borrar eventos reservados para simular cancelación; exponer eliminación real solo donde no destruya historial y esté autorizada.

### RF-19 — Validaciones de eventos

- Capacidad > 0; precio ≥ 0; categoría y obligatorios presentes; fechas válidas; no bajar capacidad por debajo de entradas ya reservadas.
- Aceptación: probar cada límite y petición directa; precio cero permitido; rechazo de capacidad insuficiente también frente a una reserva concurrente.
- Reglas adicionales sobre fechas futuras, imagen y cambios en eventos reservados se documentan como decisiones, no como citas textuales del PDF.

### RF-20 — Consulta administrativa de reservas

- Administrador consulta todas, ve detalle y filtra por evento, usuario y estado.
- Aceptación: permisos, filtros combinados y estados históricos; consultas consistentes con MySQL, vacío gestionado. No añadir edición arbitraria de reservas por inferencia.

### RF-21 — Cancelar eventos

- Cambiar a CANCELADO, bloquear nuevas reservas, conservar existentes y comunicar claramente cancelación.
- Aceptación: evento/reservas históricas permanecen; reservar falla incluso si la pantalla estaba abierta antes; ticket y sección personal muestran cancelación; dashboard usa conjunto coherente.
- Resolver carrera entre cancelación y confirmación de reserva bajo la misma política transaccional.


### Historias para validar con la persona responsable

| Historia | Actor y objetivo | Criterio de aceptación concreto |
| --- | --- | --- |
| HU-01 / RF-01/02 | Visitante quiere crear una cuenta e ingresar. | Alta válida guarda hash; correo duplicado y contraseña incorrecta fallan; login regenera sesión. |
| HU-02 / RF-04/05/06 | Usuario quiere encontrar un evento disponible. | Filtros AJAX coherentes; borrador privado; precio cero válido y estado agotado visible. |
| HU-03 / RF-08/09/11 | Usuario quiere reservar entradas sin sobreventa. | Cantidad positiva, total del servidor, último cupo y duplicado concurrente protegidos. |
| HU-04 / RF-12/14 | Usuario quiere consultar su ticket y QR. | Ticket propio visible; ajeno 404; QR local sin información personal en payload. |
| HU-05 / RF-13 | Usuario quiere cancelar a tiempo. | Más de 24 h permitido; límite exacto y después rechazados; historial conservado. |
| HU-06 / RF-17/18/19/21 | Administrador quiere gestionar eventos con seguridad. | CSRF/rol, aforo suficiente, estados válidos y cancelación conservan reservas. |
| HU-07 / RF-15/16/20 | Administrador quiere revisar reservas y métricas. | Filtros combinados y agregados verificables con conjunto conocido; datos de otro rol protegidos. |

### Requisitos no funcionales

| ID | Obligación PDF | Criterio verificable |
| --- | --- | --- |
| RNF-01 | Usabilidad consistente e intuitiva; retroalimentación. | Formularios etiquetados, mensajes de éxito/error/carga, vacío y confirmaciones pertinentes; navegación por teclado y foco visible como medidas de calidad. |
| RNF-02 | Móvil, tableta y computadora; no solo reducir proporcionalmente. | Verificar 360/390, 768 y 1280/1440 px, zoom y orientación; menú, formularios, tarjetas, tablas y QR utilizables; sin desbordamiento de la página. Estos tamaños son decisiones de QA. |
| RNF-03 | Hashing, sesiones, autorización/validación servidor, consultas parametrizadas, escape y secretos por entorno. | Pruebas de permisos/propiedad, inyección SQL/XSS, sesión/logout y exclusión de secretos; controles extra en SEG. |
| RNF-04 | Manejar errores auth/validación/BD/API/cupos/permisos sin revelar datos internos. | Mensajes seguros y respuestas HTTP coherentes; logs redactados; probar fallos y servicio externo caído. |
| RNF-05 | Evitar consultas y recursos innecesarios; optimizar imágenes; evitar recargas injustificadas. | Revisar consultas repetidas, índices/filtros, tamaño de imágenes, caché API y operaciones AJAX. No inventar objetivo de rendimiento no medido. |
| RNF-06 | Chrome y Firefox actuales. | Flujo real en ambos navegadores; registrar versiones. Herramientas automatizadas no sustituyen una revisión de los navegadores requeridos. |
| RNF-07 | Separar responsabilidades, nombres claros y reutilización; no un único PHP. | Revisión de estructura, funciones compartidas, manejo central de conexión/errores y separación de vistas/reglas/acceso a datos. |

## 7. Seguridad y errores

Hashing con password_hash/password_verify; sesiones con modo estricto, cookies HttpOnly y SameSite=Lax, Secure en producción/HTTPS. Login regenera identificador; logout destruye sesión; inactividad superior a 30 minutos reinicia identidad. Archivos de sesión locales son privados. Su persistencia en alojamiento efímero sigue por resolver; no se certifica sesión pública estable.

CSRF en escrituras; métodos HTTP permitidos explícitamente; validación servidor; SQL preparado; escape de contenido en vistas; CSP y cabeceras de protección. Consultas de reservas/tickets incluyen propietario, no solo identificador. El usuario no puede fijar rol, precio, total ni disponibilidad.

.env y storage no se versionan. Los logs de errores usan referencia/clase sin mensajes SQL, claves ni trazas. Cuentas ficticias locales con claves aleatorias; DemoSeeder bloquea production. El alta de cuentas de evaluación públicas requiere aprovisionamiento separado.

Dev.12 prepara TLS verificado para MySQL en producción: CA de archivo fuera de public, verificación activa y comprobación Ssl_cipher antes de entregar PDO. Pruebas de configuración aprobadas; negociación real, CA/nombre incorrectos y servidor sin TLS pendientes.

## 8. AJAX y API externa

| Operación | Entrada | Respuesta y protección |
| --- | --- | --- |
| GET /api/eventos | q, category, from, to, availability, state, page validados. | JSON html/page/message; solo eventos públicos, fragmento escapado. |
| POST /api/reservas | csrf, event_id, quantity. | 201 con message/id/ticket_url/available; sesión, CSRF y transacción. |
| POST /api/reservas/{id}/cancelar | csrf. | JSON message; propietario y regla de 24 h; errores seguros. |
| GET /api/experiencias | Cookie de sesión. | JSON html/message del usuario; sin sesión 401. |
| GET /api/eventos/{slug}/clima | Slug de evento público. | JSON status/message y forecast cuando válido; desconocido 404. |

Errores de negocio usan códigos HTTP acordes (401, 403, 404, 409, 422); métodos no admitidos 405. Un error inesperado puede devolver 500 con texto seguro desde el manejador global, por lo que el cliente debe tolerar respuesta no JSON. No es una API de pagos.

jQuery aporta dos funciones significativas: catálogo con debounce/aborto/control de secuencia e historial actualizado tras cancelar. AJAX tiene más de tres operaciones. El total visual orienta al usuario; el precio definitivo lo calcula PHP/MySQL.

La confirmación cliente tiene espera máxima de 15 s incluyendo JSON. Bloquea doble envío; ante timeout/red/5xx o respuesta inválida dirige a consultar experiencias y evita reintento inmediato desde el formulario. Abortar la espera no cancela una reserva ya confirmada en MySQL. Su activación en QA y navegación automática al ticket fueron comprobadas en Chrome.

Open-Meteo se consulta desde PHP por HTTPS, coordenadas validadas y destino fijo. Lectura acotada a 3 s y 128 KiB, sin seguir redirects; unidades/rangos/fecha revisados. Pronóstico diario solo para evento público futuro dentro de 15 días, con atribución. Caché positiva 1 h, negativa 10 min y resultado anterior hasta 6 h. El fallo permite continuar con la reserva.

Dev.13 prepara cuota global MySQL de 30 intentos/h y 10/min, consumida antes del HTTP externo bajo lock de fila. Los errores externos cuentan; falla de presupuesto omite la llamada. La caché puede perderse en alojamiento efímero, pero la cuota MySQL debe conservarse. Reglas/callback, migración, límites en MySQL y persistencia/restauración comprobados. Carrera simultánea específica del presupuesto pendiente.

## 9. Despliegue, instalación y transferencia

Linux usa scripts/start-linux.sh; Windows usa la guía PowerShell de INSTALACION.md con Docker Desktop y contenedores Linux. El inicio genera claves si no existe .env, espera disponibilidad MySQL y ejecuta migraciones. No borra volúmenes ni siembra destructivamente. Demo es operación local separada. Windows aún no fue ejecutado en un equipo compatible.

Volúmenes locales mysql_data y app_storage conservan datos y archivos al recrear contenedores. Migraciones mantienen firma SHA-256 y progreso DDL; una incompleta bloquea reintento inseguro. No alterar firmas ni borrar historial para arrancar. Git conserva código, no los datos de la base.

El ZIP dev.17 exporta el commit b604f0e, 160 archivos con manifiesto y SHA-256 comprobados. No incluye secretos, .git ni MySQL. El receptor no necesita Python/Git para ejecutar. El primer build necesita Internet y descarga dependencias desde composer.lock. Ese ZIP arrancó en Linux desde ruta con espacios, proyecto y volúmenes propios; Chrome comprobó login de ambos roles, reserva/ticket/QR y cancelación con historial. No restaura reservas existentes ni certifica Windows.

Restore QA se ejecutó: nueva base/proyecto/volúmenes, dump consistente, copia privada de credenciales ficticias/marcador y comparación de filas por PK. No importa sobre una base existente y conserva volúmenes. La versión actual compara diez tablas. Exportación/importación MySQL real y comparación exacta de diez tablas/archivos aprobaron. El flujo HTTP del destino restaurado sigue pendiente; las pruebas simuladas se registran por separado.

La candidata pública es PHP Docker en Render y MySQL gratuito en Aiven, según PUBLICACION.md. No hay cuentas nuevas, URL ni conexión entre proveedores verificadas. TLS real, sesiones, arranque/migraciones públicas, cuentas de evaluación y cuotas necesitan validarse antes de publicar. El objetivo cero gasto no elimina límites/inactividad de los planes gratuitos.

## 10. Pruebas y límites de evidencia

| Evidencia ejecutada | Resultado | Qué no demuestra |
| --- | --- | --- |
| Desarrollo actual, 8 octubre | 266 unidades, sintaxis PHP 8.4, QR y 211 checks MySQL/HTTP aprobados. | Windows, URL pública ni ausencia de toda vulnerabilidad. |
| Concurrencia MySQL | Dos carreras de reserva y cuatro admin-reserva aprobadas con procesos/locks reales. | Carrera específica de cuota meteorológica. |
| QA actual y restore | Migración 003, recreación y dump/import reales; diez tablas/archivos idénticos. | Flujo HTTP del destino restaurado. |
| Chrome 154.0.8037.97 | Reserva actual → ticket automático/QR; cancelación/historial, admin y permisos parciales. | Firefox ni matriz completa de casos adversos. |
| Responsive real | Ticket 360/390/768/1280/1440; catálogo/cuenta/registro 360 y registro768 sin desbordamiento. | Toda la matriz, zoom, orientación y foco. |
| TLS configuración | 40 guardas incluidas en PHP8.4 de la suite actual. | Negociación externa TLS válida/CA/hostname incorrectos. |
| Clima y presupuesto | 44 reglas, 39 checks clima y 41 checks MySQL del presupuesto incluidos en las suites actuales. | Carrera simultánea específica y caída externa en UI. |
| ZIP Linux dev.17 | 160 archivos, referencia exacta, arranque y recreación/persistencia inicial; reserva/ticket/cancelación Chrome. | Reserva nueva tras otra recreación, Windows o receptor real. |
| Auditoría dirigida | Composer sin avisos/abandonados, huella jQuery y 15 checks HTTP correctos. | Escaneo de imagen, historial completo, HTTPS público. |

No sumar las filas como pruebas adicionales: los checks de TLS/clima/presupuesto forman parte de las suites indicadas. Evidencia privada detallada en ESTADO.md, B07 y SEGURIDAD.md. El agente no dispone de sudo no interactivo; las operaciones Docker que lo requieren se ejecutan en la terminal del usuario sin compartir claves.

El ensayo dev.15 perdió el nombre del proyecto bajo sudo y recreó contenedores normales conservando volúmenes; se recuperó el arranque normal. Dev.16 corrigió aislamiento, pero la extracción600/700 impedía lectura de Apache. Dev.17 corrigió permisos de fuente manteniendo secretos privados y aprobó el arranque/flujo. Se conservaron versiones y destinos; no eliminar evidencia de fallos ni considerarlos aprobación de versiones anteriores.

## 11. Preparación de defensa y conclusiones

La persona responsable debe explicar ruta navegador-PHP-PDO-MySQL, sesión/rol/CSRF, total del servidor, bloqueo de cupos, estados e historia, jQuery/AJAX, API externa y limitaciones de despliegue. Ensayar demo con datos ficticios y una modificación menor real. Registrar participación y resultados humanos cuando ocurran; este documento no acredita comprensión ni defensa.

Ejercicios sugeridos: explicar por qué un ticket ajeno devuelve 404; demostrar rechazo a exactamente 24 h; localizar el cálculo del precio y contrastarlo con el total visual; recorrer una migración y explicar qué pasa si se interrumpe; mostrar que el clima falla sin impedir reservar. Son propuestas de preparación, no evidencias ejecutadas.

La base funcional está implementada y tiene evidencia parcial real. Para cerrar faltan persistencia de la reserva nueva tras recreación, HTTP del destino restaurado, Windows, Firefox/casos responsive y seguridad restantes, TLS/sesiones públicas, URL externa, documento revisado por el estudiante y defensa. Este borrador se actualizará con hechos verificados, sin eliminar limitaciones para aparentar cumplimiento.

## 12. Referencias y trazabilidad

Fuentes de requisitos: SC-502_Suficiencia_Especial_Eventify.pdf y SC-502 Ambiente Web Cliente-Servidor.pdf, analizados previamente en REQUISITOS.md. Fuente de implementación: public/index.php, app/, database/migrations/, composer.lock y scripts/ del código dev.17. Fuente de evidencia: ESTADO.md, MATRIZ.md y docs/evidencias por bloque.

Documentos operativos: INSTALACION.md, ARQUITECTURA.md, DATOS.md, PUBLICACION.md, RESPALDO.md, VERSIONADO.md, PAQUETE.md, COPIA-LIMPIA.md y SEGURIDAD.md. Los documentos históricos pueden describir etapas anteriores: las migraciones/código y el estado vigente prevalecen al comprobar una afirmación técnica.

Repositorio privado: https://github.com/USUARIO_DESTINO/eventify. La visibilidad privada no garantiza acceso del evaluador; gestionarlo cuando se acuerde. No se incluyen claves ni datos personales de cuentas de prueba en este documento.
