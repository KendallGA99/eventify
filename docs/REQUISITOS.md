# Requisitos e historias de usuario

Los siguientes requisitos corresponden al enunciado de la suficiencia especial. Los criterios indican cómo revisar el comportamiento esperado. Los actores son visitante, usuario y administrador.

### RF-01 — Registro de usuarios

- Solicitar nombre completo, correo, contraseña y confirmación; validar cliente y servidor; correo único; contraseñas sin texto plano.
- Aceptación: alta válida persistida; campos inválidos y confirmación distinta rechazados; correo duplicado rechazado también por petición directa; hash en MySQL. El correo se normaliza antes de comprobar duplicados.

### RF-02 — Autenticación y cierre

- Ingresar con correo/contraseña y mantener sesión PHP; mensajes comprensibles sin información sensible; destruir sesión al salir.
- Aceptación: acceso válido/incorrecto, persistencia entre peticiones y acceso denegado tras logout. Verificar renovación de identificador de sesión como medida de implementación.

### RF-03 — Roles y autorización

- Roles ADMINISTRADOR y USUARIO; autorización en servidor; rechazar URL directa no autorizada. Ocultar botones no es protección.
- Aceptación: visitante/usuario rechazados en endpoints administrativos; administrador accede; registro público no puede elevar su rol enviando campos adicionales.

### RF-04 — Catálogo

- Tarjetas u otra vista apropiada con imagen, nombre, categoría, fecha/hora, ubicación, precio, disponibilidad y enlace; distinguir cancelados/finalizados.
- Aceptación: información real de MySQL, estados visibles y caso sin resultados. Borradores accesibles solo al administrador según su estado.

### RF-05 — Búsqueda dinámica

- Buscar por nombre sin recarga completa mediante comunicación asincrónica.
- Aceptación: resultados pertinentes, vacío, sin coincidencias y fallo controlado; las respuestas tardías no reemplazan la consulta más reciente.

### RF-06 — Filtros combinables

- Nombre y categoría visibles; fecha, disponibilidad y estado en un desplegable accesible. Los criterios se combinan mediante AJAX y también funcionan por GET.
- Aceptación: combinación de los tres criterios, limpiar filtros, rango inválido y consulta sin resultados; validar parámetros en servidor.

### RF-07 — Detalle y disponibilidad

- Imagen, nombre, descripción, categoría, fecha/hora, ubicación, precio, capacidad, espacios disponibles; información API cuando corresponda.
- Disponibilidad calculada con reservas vigentes persistidas.
- Aceptación: cantidad de cupos coincide con MySQL; evento inexistente gestionado; disponibilidad actualizada tras reservar/cancelar; fallo API no bloquea el detalle.

### RF-08 — Crear reservación

- Usuario autenticado selecciona una o más entradas; total dinámico precio × cantidad; persistir en MySQL.
- Aceptación: reserva válida tiene usuario/evento/cantidad/valor; no autenticado rechazado; cantidades cero/negativas/fraccionarias/inválidas rechazadas. Manipular precio/total en la petición no cambia el cálculo autoritativo del servidor.

### RF-09 — Control de cupos

- Comprobar disponibilidad en servidor inmediatamente antes de confirmar; no superar disponible; nunca disponibilidad negativa.
- Aceptación: reservar exactamente los cupos restantes funciona; pedir uno de más falla; dos peticiones simultáneas al último cupo no pueden confirmar ambas. Un fallo a mitad de operación no deja reserva/ticket parcial ni consume cupos.
- Implementación: transacción, bloqueo de la fila del evento y lectura autoritativa de MySQL antes de guardar.

### RF-10 — Últimos espacios

- Mostrar automáticamente «¡Últimos espacios disponibles!» cuando disponible ≤ 20 % de capacidad.
- Aceptación: verificar debajo, exactamente en el umbral y por encima; capacidades pequeñas sin errores de redondeo. Mantener la condición matemática del PDF y distinguir agotado en la interfaz.

### RF-11 — Reserva activa duplicada

- No mantener dos reservaciones activas independientes del mismo usuario/evento; informar y evitar duplicado.
- Aceptación: segunda petición secuencial y dos peticiones simultáneas no crean dos activas. Nueva reserva tras cancelación permitida solo si el evento admite reservar y existen cupos.

### RF-12 — Mis experiencias

- Mostrar evento, fecha, cantidad, total, estado y acceso al ticket de las reservas propias.
- Aceptación: propietario accede; otra cuenta no puede leer reserva/ticket ni por HTML ni por endpoint/URL manipulada; estados e historial correctos.

### RF-13 — Cancelar reservación

- Solo si faltan más de 24 horas; cambiar estado sin borrar fila; liberar cupos; con 24 horas o menos impedir e informar motivo.
- Aceptación: 24 h + 1 s permitido, exactamente 24 h y 24 h − 1 s rechazados; comprobar el límite con un reloj controlado en las pruebas. Repetir cancelación no libera dos veces; solo propietario o acción administrativa expresamente definida.

### RF-14 — Ticket digital

- Cada reservación confirmada genera ticket con identificador único, evento, usuario, fecha/hora, cantidad, código de reservación y QR generado con biblioteca apropiada.
- Aceptación: registro persistido, código único, QR real renderizable, datos coinciden con reserva; rechazo de acceso ajeno. Un ticket por reserva puede representar varias entradas; no exigir un ticket por entrada.
- El QR contiene el identificador de la reserva y no datos personales. El ticket permanece como historial después de cancelar, pero deja de estar vigente. No se incluye un sistema de escaneo de acceso.

### RF-15 — API externa útil

- Mínimo una API; datos integrados de forma útil; manejar indisponibilidad/error. Clima es ejemplo, no obligación.
- Aceptación: respuesta real visible en contexto del evento, error/timeout/cuota/formato inesperado manejados; no inventar datos como si fueran respuesta real.
- Elegir API con condiciones gratuitas compatibles, atribución/licencia documentada, caché razonable y límites temporales/geográficos respetados. Si clima no cubre la fecha, comunicarlo.

### RF-16 — Dashboard

- Datos reales: total eventos, eventos activos, reservas activas, entradas reservadas y porcentaje global de ocupación.
- Al menos dos derivados: evento más reservado, categoría más popular, mayor ocupación o ingresos potenciales.
- Aceptación: comparar con conjunto de datos conocido; cantidades de entradas no confundidas con cantidad de reservas; cálculo ponderado y conjunto de eventos publicados cuyo inicio es futuro; sin división por cero. Ingresos potenciales claramente identificados, nunca pagos reales.

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


### Historias de usuario y criterios de aceptación

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
| RNF-02 | Móvil, tableta y computadora; no solo reducir proporcionalmente. | Verificar 360/390, 768 y 1280/1440 px, zoom y orientación; menú, formularios, tarjetas, tablas y QR utilizables; sin desbordamiento de la página. Estos tamaños cubren móviles, tabletas y escritorio. |
| RNF-03 | Hashing, sesiones, autorización/validación servidor, consultas parametrizadas, escape y secretos por entorno. | Pruebas de permisos/propiedad, inyección SQL/XSS, sesión/logout y exclusión de secretos; protecciones adicionales de sesión y CSRF. |
| RNF-04 | Manejar errores auth/validación/BD/API/cupos/permisos sin revelar datos internos. | Mensajes seguros y respuestas HTTP coherentes; logs redactados; probar fallos y servicio externo caído. |
| RNF-05 | Evitar consultas y recursos innecesarios; optimizar imágenes; evitar recargas injustificadas. | Revisar consultas repetidas, índices/filtros, tamaño de imágenes, caché API y operaciones AJAX. Las mediciones se registran antes de establecer metas de rendimiento. |
| RNF-06 | Chrome y Firefox actuales. | Flujo real en ambos navegadores; registrar versiones. Herramientas automatizadas no sustituyen una revisión de los navegadores requeridos. |
| RNF-07 | Separar responsabilidades, nombres claros y reutilización; no un único PHP. | Revisión de estructura, funciones compartidas, manejo central de conexión/errores y separación de vistas/reglas/acceso a datos. |
