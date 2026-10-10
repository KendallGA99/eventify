# EVENTIFY
## Documento técnico - borrador de evaluación

Curso: SC-502 Ambiente Web Cliente-Servidor. Fecha de corte: 10 de octubre de 2026. Código de referencia: dev.39; publicación dev.39. Estudiante: Kendall Gamboa Arce. Docente: Rita Robles Loaiza.

Este documento explica cómo funciona el sistema y qué se ha comprobado. La web pública ejecuta dev.39: ingreso al inicio, perfil editable y controles de acceso revisados. El despliegue conservó las diez tablas; después se aprobaron 81 controles HTTPS de usuarios y 36 del administrador. La instalación limpia Linux y su persistencia se comprobaron en el ZIP dev.33; los cambios de aplicación dev.39 tienen 225 controles MySQL/HTTP y recorrido real del navegador. Windows, la matriz restante de Firefox y los riesgos de las imágenes siguen pendientes. El pronóstico tuvo respuestas útiles en dev.30; en la consulta posterior a publicar dev.37 el proveedor respondió HTTP 429 y la reserva permaneció disponible.

## 1. Descripción y resultado esperado

EVENTIFY es una aplicación web para descubrir eventos, reservar entradas y consultar tickets con QR. Un usuario se registra, inicia sesión, filtra eventos y confirma una cantidad de entradas. El administrador gestiona categorías, eventos, reservas y métricas. El pronóstico meteorológico ayuda a planificar eventos próximos; su fallo no debe bloquear reservas.

El resultado final requerido incluye aplicación local portable, URL pública PHP/MySQL, repositorio con historial, cuentas de evaluación, documentación técnica y defensa individual. Actualmente hay código, pruebas registradas, GitHub privado, web pública y un paquete de desarrollo. No incluye cobros reales, aplicación móvil nativa ni control de acceso mediante escaneo.

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
| Cuenta | GET/POST /cuenta: consultar perfil y editar nombre con sesión/CSRF; correo y rol conservados. POST /salir destruye sesión. Login redirige al inicio. |
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

Una categoría agrupa muchos eventos; un usuario realiza muchas reservas; un evento recibe muchas reservas; una reserva tiene como máximo un ticket. Las claves foráneas restringen eliminación y conservan historia. Las diez tablas incluyen seis entidades de negocio y cuatro técnicas. La migración 003 añade weather_budget; su aplicación real está comprobada en desarrollo, QA y Aiven.

### Diccionario y reglas
| Tabla | Campos y reglas relevantes |
| --- | --- |
| roles | `id` TINYINT; `code` ASCII único y nombre. Roles ADMINISTRADOR/USUARIO creados en migración. |
| users | `id` BIGINT; `role_id`; `full_name` 150; `email` 254 único sin distinguir mayúsculas; `password_hash` 255; `is_active`; fechas de creación/actualización. |
| categories | `id` BIGINT; nombre 120 y slug ASCII 120 únicos; indicador activo y fechas. |
| events | `id`, categoría; slug ASCII único 160; nombre 180; descripción TEXT; ruta de imagen 255; inicio DATETIME; ubicación 255; coordenadas DECIMAL(9,6) opcionales con límites; capacidad INT positiva; precio DECIMAL(12,2) no negativo; estado y fechas. |
| reservations | `id`, usuario/evento; código ASCII único de 32 caracteres; cantidad INT positiva; precio unitario histórico DECIMAL(12,2); total DECIMAL(22,2), igual a precio × cantidad; estado; fecha de cancelación y auditoría temporal. |
| tickets | `id`; reserva única; código ASCII único de 64 caracteres; creación. Un ticket representa la reserva completa y su cantidad; QR SVG generado localmente. |
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

Las contraseñas se guardan con password_hash y se comprueban con password_verify. Las sesiones usan modo estricto y cookies HttpOnly, SameSite=Lax y Secure en producción. Al ingresar cambia el identificador; al salir se destruye. Tras 30 minutos de inactividad se reinicia la identidad. Los controles HTTPS públicos verificaron estas cookies, la regeneración y el rechazo de una sesión destruida.

Las operaciones de escritura requieren CSRF. El servidor limita métodos HTTP, valida entradas, usa consultas preparadas y escapa el contenido de las vistas. Las consultas de reservas y tickets incluyen el propietario. Un usuario no decide su rol, precio, total ni cupos enviando campos desde el navegador. La web rechaza tokens de otra sesión, tickets y QR ajenos y rutas administrativas sin el rol necesario.

.env y los datos privados de storage quedan fuera de Git y de las imágenes. Los errores no muestran consultas, claves ni trazas al visitante. La revisión dirigida de 505 blobs históricos no encontró los secretos privados conocidos; no sustituye un detector completo de secretos ni el escaneo de las imágenes.

En producción MySQL exige TLS con CA e identidad del servidor verificadas. La aplicación comprueba Ssl_cipher antes de entregar PDO. Ensayos MySQL 8.4 rechazaron CA y hostname incorrectos y un servidor sin cifrado. En Render se prepara una copia de la CA para el trabajador www-data, en un directorio privado con permisos 700 y archivo 600, fuera de public. La configuración y conexión se verifican como ese trabajador antes de servir.

Las cuentas públicas de evaluación se crean por una operación CLI explícita, con claves únicas y datos ficticios; nunca por siembra automática al arrancar. Render utiliza eventify_app, limitado a SELECT, INSERT, UPDATE, DELETE y CREATE en una sola base. CREATE permite las comprobaciones de tablas del gestor de migraciones; no se conceden DROP, ALTER ni permisos globales de administración.

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

La aplicación mantiene una cuota global MySQL de 30 intentos/h y 10/min, consumida antes del HTTP externo bajo lock de fila. Los errores externos cuentan; falla de presupuesto omite la llamada. La caché puede perderse en alojamiento efímero, pero la cuota MySQL debe conservarse. Reglas/callback, migración, límites en MySQL y persistencia/restauración comprobados. Dos carreras reales del presupuesto fueron comprobadas en la integración MySQL de 219 controles.

## 9. Despliegue, instalación y transferencia

Linux usa scripts/start-linux.sh; Windows usa la guía PowerShell de INSTALACION.md con Docker Desktop y contenedores Linux. El inicio genera claves si no existe .env, espera disponibilidad MySQL y ejecuta migraciones. No borra volúmenes ni siembra destructivamente. Demo es operación local separada. Windows aún no fue ejecutado en un equipo compatible.

Volúmenes locales mysql_data y app_storage conservan datos y archivos al recrear contenedores. Migraciones mantienen firma SHA-256 y progreso DDL; una incompleta bloquea reintento inseguro. No alterar firmas ni borrar historial para arrancar. Git conserva código, no los datos de la base.

El paquete se exporta desde un commit concreto, con manifiesto y SHA-256 del ZIP y de cada archivo. Contiene código, scripts, migraciones, licencias y .env.example; no contiene claves, .git, volúmenes ni datos de MySQL. El receptor no necesita Python, Git ni cuentas en Render, Aiven o GitHub para ejecutar la copia local. Sí necesita Docker/Compose y conexión a Internet para descargar las imágenes y dependencias inicialmente.

El ZIP dev.33 del commit f4d6c5a arrancó en Linux desde una ruta con espacios en el puerto8086. Se comprobaron ambos roles, reserva, ticket/QR, cancelación e historial. La recreación posterior conservó las huellas de diez tablas y cinco archivos, ocho reservas y ocho tickets; el navegador volvió a mostrar el ticket cancelado y su QR. El ZIP fuente coincide con la huella del paquete probado. Windows tiene evidencia de sintaxis PowerShell, pero no de ejecución completa del stack.

La restauración QA usó una base y volúmenes nuevos, un dump consistente y comparación de filas y archivos. No importó encima de una base existente. El navegador también comprobó el destino restaurado autenticado. Para transferir reservas ya creadas hay que exportar los datos por separado, siguiendo RESPALDO.md; el ZIP de código no los incluye.

La web de evaluación está en https://eventify-academico.onrender.com, con PHP/Apache en Render Free y MySQL 8.4.11 en Aiven Free. Se publicó solo EVENTIFY desde el repositorio privado. No se usó tarjeta ni un plan pagado. Los límites e inactividad de los planes gratuitos deben revisarse para la fecha de evaluación; una URL publicada no implica disponibilidad permanente.

La demostración pública comprobó login de ambos roles, creación de categoría y evento ficticio, reserva de dos entradas por un total calculado de 5000 colones, ticket/QR y cancelación con historial. Un reinicio real conservó filas y huellas de diez tablas. Los archivos de sesión están en el almacenamiento efímero de Render: tras recrear la web puede requerirse otro login, sin perder reservas de MySQL. El proveedor respondió HTTP 429 en una visita y el sistema mantuvo disponible la reserva. El 8 de octubre a las 18:22 CST, la API pública devolvió HTTP 200 con un pronóstico útil visible para el evento ficticio: RF-15 tiene evidencia de éxito y fallo controlado. No se garantiza que un servicio gratuito nunca limite las consultas.

## 10. Pruebas y límites de evidencia

| Evidencia ejecutada | Resultado | Qué no demuestra |
| --- | --- | --- |
| Desarrollo Linux | 266 comprobaciones unitarias, sintaxis PHP8.4 y QR en cada imagen local/pública dev.32; módulos Apache revisados ausentes en ejecución. | No cubre por inferencia cambios posteriores ni Windows. |
| Integración MySQL/HTTP | 219 comprobaciones, tres migraciones y carreras de reserva, administración y presupuesto; 16:31:25 UTC. | No equivale a pruebas completas de la web pública. |
| Persistencia Linux | ZIP dev.33, ruta con espacios: diez tablas y cinco archivos idénticos tras recrear después de reservar/cancelar; ocho reservas y tickets. | Comprobado el ZIP fuente dev.33 exacto; no certifica Windows u otro paquete. |
| Chrome y diseño adaptable | Flujos de ambos roles, reserva, QR, cancelación y vistas en móvil/tableta/escritorio. | Registro móvil con foco/teclado y validación vacía comprobados; Firefox con capturas humanas parciales; matriz restante pendiente. Zoom200% opcional por indicación del usuario. |
| TLS real | CA/hostname incorrectos y servidor sin TLS rechazados; conexión externa válida en Aiven/Render. | No es una auditoría universal de la infraestructura. |
| Publicación dev.30 | Render Live (390b1ef); parche xz/liblzma, sintaxis y 39 controles de clima más 44 de presupuesto en construcción; diez tablas conservadas y 81 controles HTTPS posteriores. | Pronóstico público útil observado tras actualizar; disponibilidad permanente y todas las variantes administrativas no garantizadas. |
| Flujos públicos dev.25/26 | Login, administración, reserva/ticket/cancelación y conservación de diez tablas tras reiniciar. | Disponibilidad permanente o todas las variantes de redeploy. |
| Seguridad pública dev.27 | 81 controles HTTPS de cookies, sesión, CSRF, permisos y ticket/QR ajenos aprobados. | Todas las variantes de ataque o revisión de imágenes. |
| Dependencias e historial | Composer sin avisos; jQuery verificado; 505 blobs sin secretos conocidos. | Ausencia de futuras vulnerabilidades o secretos desconocidos. |
| Imágenes dev.30 | Construcción, unidades y escaneo confirman corrección xz/liblzma, cuatro filas de avisos eliminadas. | Persisten avisos y revisión de alcance; no equivale a seguridad completa. Despliegue documentado en su fila propia. |
| Windows | Seis scripts analizados por PowerShell 5.1 real. | Instalación y ejecución con Docker/WSL aún pendientes. |

Cada fila corresponde a su ejecución y alcance. No se suman como una suite única ni se atribuyen pruebas antiguas a código posterior. ESTADO.md y las evidencias por bloque conservan comandos, resultados e incidencias. Los ensayos previos con fallos no se consideran aprobados por una corrección posterior.

## 11. Preparación de defensa y conclusiones

La persona responsable debe explicar ruta navegador-PHP-PDO-MySQL, sesión/rol/CSRF, total del servidor, bloqueo de cupos, estados e historia, jQuery/AJAX, API externa y limitaciones de despliegue. Ensayar demo con datos ficticios y una modificación menor real. Registrar participación y resultados humanos cuando ocurran; este documento no acredita comprensión ni defensa.

Ejercicios sugeridos: explicar por qué un ticket ajeno devuelve 404; demostrar rechazo a exactamente 24 h; localizar el cálculo del precio y contrastarlo con el total visual; recorrer una migración y explicar qué pasa si se interrumpe; mostrar que el clima falla sin impedir reservar. Son propuestas de preparación, no evidencias ejecutadas.

La base funcional y la web pública tienen evidencia real de sus flujos principales. Para cerrar quedan Windows, Firefox y casos de interfaz restantes, revisión de los avisos de imágenes, instalación limpia del paquete final y revisión del documento por el estudiante. La preparación y defensa individual necesitan participación humana real. Este documento mantiene visibles esas limitaciones.

## 12. Referencias y trazabilidad

Fuentes de requisitos: SC-502_Suficiencia_Especial_Eventify.pdf y SC-502 Ambiente Web Cliente-Servidor.pdf, analizados previamente en REQUISITOS.md. Fuente de implementación: public/index.php, app/, database/migrations/, composer.lock y scripts/ del código dev.39 (inicio, navegación, perfil y controles de contraseña actualizados; imagen MySQL corregida). Fuente de evidencia: ESTADO.md, MATRIZ.md y docs/evidencias por bloque.

Documentos operativos: INSTALACION.md, ARQUITECTURA.md, DATOS.md, PUBLICACION.md, RESPALDO.md, VERSIONADO.md, PAQUETE.md, COPIA-LIMPIA.md, SEGURIDAD.md, AVISOS-IMAGENES.md y CIERRE.md. Los documentos históricos pueden describir etapas anteriores: las migraciones/código y el estado vigente prevalecen al comprobar una afirmación técnica.

Repositorio privado: https://github.com/USUARIO_DESTINO/eventify. La visibilidad privada no garantiza acceso del evaluador; gestionarlo cuando se acuerde. No se incluyen claves ni datos personales de cuentas de prueba en este documento.

## Ampliación de seguridad: dev.33

Se analizó el binario oficial gosu de la imagen MySQL linux/amd64 con govulncheck, sin exclusiones: no se detectaron funciones vulnerables presentes; siguen registradas coincidencias por versión de módulo/paquete. Esto no certifica toda la imagen ni el servidor administrado. ACL, ncurses y montajes requieren rutas privilegiadas o herramientas que la aplicación no llama; XML y Python conservan límites de revisión. El detalle y fuentes están en docs/AVISOS-IMAGENES.md y el conteo completo por paquete en docs/INVENTARIO-IMAGENES.md.

La imagen derivada conserva MySQL 8.4.11 y corrige tres avisos de gawk con el paquete Oracle 5.1.0-6.el9_8.1. Dev.33 aprobó construcción, 219 comprobaciones de integración, TLS, cuentas y rechazo del arranque sin TLS. El escaneo confirma 69→66 avisos, sin nuevos ni exclusiones. Las instalaciones anteriores conservan su versión; la copia limpia dev.33 aprobó recorrido y persistencia posterior.


## Experiencia de uso y aceptación local: dev.37

El ingreso válido redirige al inicio. La navegación distingue visitante, usuario y administrador; ocultar enlaces no reemplaza la autorización en servidor. Registro e ingreso permiten mostrar u ocultar cada contraseña con un botón accesible. Sin JavaScript, los campos siguen protegidos y utilizables. No se conserva la contraseña en almacenamiento del navegador al alternar su visibilidad.

Mi cuenta permite editar el nombre completo con POST, token CSRF, validación de servidor y consulta preparada sobre el identificador de sesión. El correo y el rol no son editables desde ese formulario. Las reservas y tickets siguen vinculados al identificador del usuario. El perfil ofrece acceso a experiencias, administración cuando corresponde y cierre de sesión. Se retiró el aviso provisional del inicio y se incorporaron transiciones breves, desactivadas cuando el sistema solicita reducir movimiento.

Dev.37 aprobó 225 comprobaciones de integración MySQL/HTTP, sintaxis PHP 8.4, 23 controles de registro/CSRF y 7 de páginas de error. En la instalación 8086 se comprobó ingreso al inicio de ambos roles, edición y persistencia del nombre tras una nueva sesión, navegación al panel y conservación del historial. El nombre ficticio original se restauró; no se crearon reservas durante esa revisión. Perfil adaptado a 390/768/1280px sin desbordamiento en los tamaños observados. No certifica instalación Windows, toda la matriz Firefox o un ZIP nuevo por inferencia.

## Reducción del runtime y riesgos conservados: dev.34–37

Dev.34 retiró 32 paquetes de construcción de ambas imágenes PHP y mantuvo versiones de los paquetes conservados. Imágenes finales, unidades y arranque público con TLS positivo/negativo tienen evidencia propia sobre 6d41df5. Con la misma base Trivy almacenada, el informe pasó de 2011 a 424 filas por imagen, sin identificadores/paquetes/versiones nuevos ni exclusiones: 1 crítico, 91 altos, 162 medios, 166 bajos y 4 sin clasificación. Esta reducción no corrige el kernel del host ni demuestra que las bibliotecas restantes estén libres de vulnerabilidades.

El informe histórico MySQL dev.33 conserva 66 filas. La reducción sin Shell dev.38 elimina 19 filas de bibliotecas Python sin uso; los 47 avisos conservados mantienen sus límites documentados. No se acepta el riesgo residual automáticamente ni se presenta la versión como estable. La web pública ya ejecuta dev.37, con el resultado del despliegue y sus controles registrados a continuación. El paquete dev.37 requiere aceptación propia; Windows se ensaya al final.


### Publicación y límites del alojamiento: corte dev.37

Render Free publicó el commit 7c3a3e28435566349efcd4b2a66eace89de978d2 el 9 de octubre a las 17:09:37 CST. Diez tablas conservaron filas y huellas antes y después del despliegue. Las comprobaciones HTTPS posteriores aprobaron 81 controles de usuarios y 31 del administrador, sin escrituras de negocio.

Aiven MySQL Free estaba apagado por inactividad; se reactivó el servicio existente. Render también puede dormir: conviene comprobar ambos antes de evaluar. Una muestra de Eventos cargó en 101,9 ms localmente y 1060,2 ms públicamente con el servicio activo; no mide arranque en frío ni garantiza latencia.

El pronóstico recibió HTTP 429 del proveedor. Se mostró el aviso de indisponibilidad y se mantuvo el acceso a reservar. Un reintento posterior mantuvo la indisponibilidad. La respuesta útil anterior conserva su propia evidencia; no se inventan valores ni se eluden límites.

Packagist no devolvió avisos para las dos bibliotecas de Composer. Libxml2 sigue abierto en la imagen base; no se instaló un parche nativo sin pruebas. El documento sigue siendo borrador hasta completar la aceptación.


### Reducción de MySQL: dev.38

La imagen final retira mysql-shell mediante RPM y comprueba que servidor, clientes SQL, gosu y entrypoint conserven sus huellas. RPM puede dejar cachés Python; solo se elimina su directorio después de comprobar que ningún resto pertenece a otro paquete. La candidata aprobó 225 controles MySQL/HTTP, respaldo/restauración ficticios y TLS positivo/negativo.

La imagen final de 9321f68 aprobó construcción,225 controles, TLS positivo/negativo y escaneo propios en mysql-image-check-YmuG4fAM. El escaneo con la misma base Trivy del 8 de octubre confirma 47 filas idénticas a la candidata, sin avisos nuevos o exclusiones: 1 crítico, 21 altos, 22 medios, 2 bajos y 1 sin clasificación. Retirar componentes no corrige vulnerabilidades de gosu o del host, ni los avisos PHP/libxml2.

No hay cambios de aplicación o esquema. La web pública permanece en dev.37 y Aiven no cambia; la receta MySQL afecta futuras construcciones locales. Las instalaciones existentes conservan datos e imágenes. La instalación Windows, las comprobaciones restantes de Firefox y la aceptación humana continúan pendientes.


### Presentación y publicación: dev.39

El historial y su actualización AJAX muestran FINALIZADO cuando un evento publicado ya pasó, conservando el estado almacenado. Las reservas administrativas usan «1 entrada» en singular. No cambian cupos, precios, permisos ni esquema. La integración aprobó 225 controles MySQL/HTTP; IAB comprobó filtros, perfil persistente y restaurado, logout, tickets, roles y administración. El panel se revisó a 390, 768 y 1280 píxeles sin desbordamiento. Estas pruebas no certifican Firefox.

Render Free desplegó el commit 0e3aae247da50deaa0150be4e235728cbe3db12a el 10 de octubre. El despliegue dep-db52frajnfac7390qf80 quedó Live y tardó 32,7 segundos. Las diez tablas mantuvieron filas y huellas; después se aprobaron 81 controles HTTPS de usuarios y 36 administrativos. IAB confirmó panel, reserva cancelada existente y catálogo; las sesiones se cerraron. No se crearon reservas o cuentas públicas. Dev.37 se conserva como referencia anterior para rollback compatible.

El paquete actualizado requiere su comprobación Windows; la instalación Linux original dev.33 conserva evidencia propia. Libxml2 y los demás riesgos residuales mantienen sus límites. Este documento sigue siendo borrador de evaluación; la defensa y comprensión requieren participación real del estudiante.
