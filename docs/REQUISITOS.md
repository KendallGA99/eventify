# EVENTIFY: especificación y criterios de aceptación

## 1. Propósito y fuentes

Referencia operativa para desarrollar, probar y documentar el MVP de la suficiencia SC-502. No es el PDF técnico final ni evidencia de que la aplicación ya funcione.

| Fuente | Edición revisada | Uso |
| --- | --- | --- |
| `SC-502_Suficiencia_Especial_Eventify.pdf` | 10 páginas; archivo proporcionado el 2026-10-07 | Requisitos específicos, entregables, defensa y rúbrica de 100 puntos. |
| `SC-502 Ambiente Web Cliente-Servidor.pdf` | 23 páginas; archivo proporcionado el 2026-10-07 | Contenidos y contexto del curso ordinario. |
| Acuerdos del chat | Hasta 2026-10-07 | Linux/Windows, gratuidad, portabilidad, metodología, pruebas y seguimiento. |

PDF específicos, secciones de referencia: 1–4 propósito/alcance; 5 RF; 6 tecnologías; 7 RNF; 8 Docker/GitHub/despliegue; 9–10 documentación/entregables; 11–13 defensa/IA/condiciones mínimas; 14 rúbrica.

Huellas SHA-256 de los originales revisados:

```text
Curso: b30eb2f189b944674a9f169ba14e76e7d746fde2f1b4db35bc3dd7b1e25cf791
Suficiencia: d4fbe3e2300acbe19c57e44f83475d8c3086a61f74ced81470db56a37f343a27
```

Los originales están actualmente en `/home/usuario/Descargas/Proyecto U/`. Esa ubicación es una referencia de origen, nunca una dependencia de ejecución. Si se recibe otra edición, comparar requisitos y actualizar huella, decisiones y pruebas; conservar trazabilidad del cambio.

**Etiquetas:** PDF = obligación expresa; USUARIO = condición acordada; DECISIÓN = mecanismo adoptado o propuesto para implementar; PENDIENTE = falta una selección o evidencia. Los RF/RNF siguientes proceden del PDF; los criterios describen cómo comprobarlos y pueden añadir casos derivados necesarios para asegurar la regla.

## 2. Producto, actores y límites

Plataforma cliente-servidor para descubrir experiencias, reservar entradas, generar tickets y administrar eventos. Reservas, cupos, estados, usuarios, roles y tickets deben funcionar con persistencia real.

| Actor | Acciones |
| --- | --- |
| Visitante | Ver catálogo/detalles, buscar/filtrar, registrarse e iniciar sesión. |
| Usuario | Reservar, ver sus experiencias/tickets y cancelar bajo la regla temporal. |
| Administrador | Gestionar categorías/eventos, consultar reservas y obtener indicadores. |

No se exige pasarela de pagos real, app móvil nativa o escaneo/control de entrada. Tampoco se exige expresamente recuperación de contraseña, envío de correo, ticket PDF descargable, asientos numerados o múltiples organizadores. No incorporarlos como requisitos obligatorios por inferencia. El precio y total son valores de reservación, no comprobantes de cobro.

## 3. Condiciones del usuario

| ID | Condición | Evidencia de cierre |
| --- | --- | --- |
| U-01 | Desarrollo en Linux; ejecución en Linux y Windows. | Instalaciones limpias verificadas en ambos; registrar versiones/arquitectura. |
| U-02 | Tecnologías gratuitas; objetivo cero gasto recurrente o obligatorio académico. | Inventario de licencias, cuotas y condiciones; sin cobros ocultos. |
| U-03 | Transferir por ZIP/repositorio y permitir arranque sencillo. | Persona receptora sigue el manual sin rutas ni servicios privados del desarrollador. |
| U-04 | Protocolo completo, seguridad y pruebas. | Evidencia cliente → servidor → MySQL → respuesta, más casos adversos pertinentes. |
| U-05 | Versionado progresivo con GitHub. | Historial real, repo accesible al evaluador, documentación actualizada. |
| U-06 | Guía consultable sin releer PDFs completos. | AGENTS breve, especificación por IDs, plan y estado sincronizados. |

Internet para descarga inicial y API; no prometer funcionamiento totalmente desconectado. Docker del host tiene requisitos reales: confirmar equipo/Windows antes de certificar compatibilidad.

## 4. Requisitos funcionales y aceptación

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

## 5. Requisitos no funcionales

| ID | Obligación PDF | Criterio verificable |
| --- | --- | --- |
| RNF-01 | Usabilidad consistente e intuitiva; retroalimentación. | Formularios etiquetados, mensajes de éxito/error/carga, vacío y confirmaciones pertinentes; navegación por teclado y foco visible como medidas de calidad. |
| RNF-02 | Móvil, tableta y computadora; no solo reducir proporcionalmente. | Verificar 360/390, 768 y 1280/1440 px, zoom y orientación; menú, formularios, tarjetas, tablas y QR utilizables; sin desbordamiento de la página. Estos tamaños son decisiones de QA. |
| RNF-03 | Hashing, sesiones, autorización/validación servidor, consultas parametrizadas, escape y secretos por entorno. | Pruebas de permisos/propiedad, inyección SQL/XSS, sesión/logout y exclusión de secretos; controles extra en SEG. |
| RNF-04 | Manejar errores auth/validación/BD/API/cupos/permisos sin revelar datos internos. | Mensajes seguros y respuestas HTTP coherentes; logs redactados; probar fallos y servicio externo caído. |
| RNF-05 | Evitar consultas y recursos innecesarios; optimizar imágenes; evitar recargas injustificadas. | Revisar consultas repetidas, índices/filtros, tamaño de imágenes, caché API y operaciones AJAX. No inventar objetivo de rendimiento no medido. |
| RNF-06 | Chrome y Firefox actuales. | Flujo real en ambos navegadores; registrar versiones. Herramientas automatizadas no sustituyen una revisión de los navegadores requeridos. |
| RNF-07 | Separar responsabilidades, nombres claros y reutilización; no un único PHP. | Revisión de estructura, funciones compartidas, manejo central de conexión/errores y separación de vistas/reglas/acceso a datos. |

## 6. Tecnología, repositorio y entrega

| ID | Obligación y aceptación |
| --- | --- |
| RT-01 | JavaScript: funciones, objetos, estructuras de control, eventos, DOM, validaciones y cálculo dinámico aplicados a funciones reales; señalar archivos y flujos. |
| RT-02 | jQuery en dos o más funciones significativas con selectores, eventos y manipulación dinámica; importar o hacer una instrucción trivial no basta. |
| RT-03 | Tres operaciones AJAX: búsqueda/filtrado, una actualización relacionada con reservas/disponibilidad y tercera a elección; estados y errores gestionados. |
| RT-04 | PHP y MySQL, formularios, sesiones, funciones, errores, consultas parametrizadas, relaciones y CRUD. |
| RT-05 | Modelo normalizado con entidades equivalentes a usuarios, roles, categorías, eventos, reservas y tickets; ajustes justificados. |
| RT-06 | Dockerfile, docker-compose.yml y .env.example según arquitectura; instrucciones de arranque local verificadas. |
| RT-07 | GitHub: README, .env.example, .gitignore, Dockerfile, docker-compose.yml, /database y /docs o estructura equivalente; historial razonable de desarrollo. |
| RT-08 | README: descripción, tecnologías, requisitos, instalación, configuración, ejecución, cuentas de prueba y enlace al sistema público. |
| RT-09 | URL pública accesible, con frontend, backend, MySQL, autenticación y operaciones principales funcionales al evaluar. Render/Vercel son ejemplos; elegir plataforma compatible. |
| ENT-A | Aplicación publicada: URL verificable, no solo contenedor local. |
| ENT-B | Fuente completo e historial GitHub; sin secretos reales ni credenciales sensibles. |
| ENT-C | PDF técnico final coherente con implementación: portada/descripción, RF/RNF, historias y aceptación, sitemap, arquitectura, ER, diccionario, API, decisiones, seguridad, despliegue y conclusiones. |
| ENT-D | Administrador y usuario de prueba funcionales; entregar acceso seguro, datos ficticios y evitar reutilizar secretos reales. |
| ENT-E | Defensa individual: demo, arquitectura/datos, frontend/backend, sesiones/auth, AJAX/API, explicación de cualquier fragmento y modificación técnica menor posible. |
| ENT-F | IA permitida como apoyo; dominio y responsabilidad del estudiante. No simular defensa ni comprensión. |

ENT-E/F son identificadores de seguimiento propios para obligaciones humanas; los cuatro entregables A–D sí corresponden al listado del PDF. No se exige formato IEEE expresamente en suficiencia. El calendario grupal del programa no fija la fecha de esta entrega individual.

## 7. Decisiones iniciales de arquitectura y negocio

Estado de todas: propuestas para comenzar; revisables con justificación antes de implementar. Una vez adoptadas, actualizar pruebas y documentación.

| ID | Decisión propuesta | Motivo / límite |
| --- | --- | --- |
| D-01 | Aplicación PHP organizada por controladores, servicios/reglas, acceso PDO a MySQL y vistas; HTML/CSS/JS/jQuery locales. Base sin framework, PHP 8.4/Apache y MySQL 8.4 fijados por digest en B01. | Evidenciar aprendizaje y mantener separación; ejecución del stack todavía pendiente. |
| D-02 | Borradores privados; publicados futuros reservables; cancelados/finalizados distinguibles; eventos pasados no reservables aunque su estado no se haya actualizado. | Evitar publicación accidental y reservas de experiencias terminadas; definir transición FINALIZADO. |
| D-03 | Activo = PUBLICADO y futuro; reservas activas = confirmadas de esos eventos para indicadores operativos. Ocupación = suma entradas de ese conjunto / suma capacidades × 100. Total de eventos = registros conservados, separado del conjunto activo. | Conjuntos coherentes; especificar derivados y empates; cero capacidad agregada produce 0 %, no error. |
| D-04 | Reserva CONFIRMADA/CANCELADA; ticket persistido por reserva, invalidado visual/operativamente por cancelación de reserva o evento, conservado en historial. QR sin datos privados expuestos. | El PDF no fija nombres de estados de reserva ni contenido QR. Cancelar evento no borra ni cambia retroactivamente el precio de las reservas. |
| D-05 | Fechas almacenadas en UTC y mostradas en America/Costa_Rica; entrada convertida explícitamente; reloj del servidor autoritativo. | Comparar cancelación estricta >24 h; misma política en PHP/MySQL/Docker. |
| D-06 | Precio unitario y total históricos en reserva; DECIMAL y cálculo monetario preciso; servidor calcula importe. | Edición posterior de evento no altera valor reservado; no representa pago real. |
| D-07 | Transacciones y protección de fila de evento para coordinar reservar, cancelar y modificar cupos; impedir duplicidad activa concurrente mediante regla verificada en BD/servicio. | Escoger mecanismo compatible con versión de MySQL; probar carreras, no basarse en botones deshabilitados. |
| D-08 | Docker Compose con servicios web/PHP y MySQL, healthchecks y almacenamiento persistente; scripts Linux/Windows separados; migraciones y seeds no destructivos. | Inicialización nueva y actualización existente son operaciones distintas; no exigir PHP/MySQL en el host. |
| D-09 | JavaScript/jQuery: catálogo dinámico y actualización de Mis experiencias; AJAX: catálogo, crear reserva/actualizar cupos, cancelar/actualizar lista. API/QR/Bootstrap opcional por seleccionar. | Cobertura real verificable, sin añadir frameworks innecesarios. |

La elección de imagen, archivo o URL externa, política de edición de fecha con reservas, contenido del QR y estados de transición debe concretarse antes del bloque dependiente. No afirmar que esta tabla ya describe un producto construido.

## 8. Seguridad adicional del protocolo (DECISIÓN/USUARIO)

- SEG-01: CSRF en escrituras; no usar GET para cancelar/crear/modificar.
- SEG-02: regenerar sesión al ingresar; cookies HttpOnly, SameSite y Secure bajo HTTPS; expiración documentada. No activar Secure en HTTP local de forma que impida autenticarse.
- SEG-03: limitar intentos de login razonablemente sin filtrar existencia de cuentas; validar identidad y rol en servidor.
- SEG-04: secretos solo por entorno, .gitignore/.dockerignore, sin claves en logs, commits, imágenes o documento técnico.
- SEG-05: validar tamaño/tipo/nombre y almacenamiento si se permiten uploads; impedir ejecutar archivos subidos y traversal. Si no hay uploads, documentar gestión segura de imágenes elegida.
- SEG-06: llamadas API con destino configurado/permitido, timeout, manejo de errores y límites; no permitir URLs arbitrarias del usuario hacia redes internas.
- SEG-07: precios/cupos/roles/propiedad no confiados al navegador; cantidades enteras, integridad referencial, rollback coherente y protección de repetición.
- SEG-08: datos ficticios, cuentas demo aisladas, no sembrar claves públicas conocidas automáticamente en producción; instrucción de configuración para evaluación.
- SEG-09: licencia/procedencia/versiones de dependencias, revisión de vulnerabilidades aplicables y parcheo sin romper evidencia.
- SEG-10: respaldo y restauración antes de cambios destructivos autorizados; nunca resetear volumen para arreglar un error sin explicar impacto y autorización pertinente.

## 9. Rúbrica de suficiencia

| Criterio | Puntos |
| --- | ---: |
| Análisis/modelado | 5 |
| HTML5/estructura | 5 |
| CSS3/responsive/UX | 7 |
| JavaScript | 8 |
| jQuery | 4 |
| PHP/backend | 12 |
| MySQL/modelo relacional | 10 |
| CRUD/reglas | 8 |
| Autenticación/seguridad | 8 |
| AJAX/integración | 7 |
| API externa | 4 |
| Tickets/reservas | 5 |
| Dashboard | 4 |
| Calidad/mantenibilidad | 4 |
| Docker/GitHub/despliegue | 5 |
| Documentación | 3 |
| Defensa | 5 |
| Total | 100 |

Condiciones mínimas independientes del puntaje: aplicación ejecutable, comunicación cliente-servidor, persistencia MySQL y autenticación funcionales; funciones reales, fuente entregado y defensa realizada con conocimiento suficiente. Ningún checklist de pantallas reemplaza estas condiciones.

## 10. Trazabilidad y control de cambios

- `docs/PLAN.md` asigna bloques y pruebas a los IDs; `docs/ESTADO.md` contiene el estado real y enlaces a evidencias. Evitar mantener estados duplicados contradictorios.
- Cada evidencia de bloque referencia requisitos, implementación, prueba y resultado. Hasta existir código y prueba, un requisito está pendiente.
- Al modificar reglas, actualizar decisión, aceptación, prueba y documentación técnica. Registrar origen: usuario, PDF nuevo o decisión propia.
- El PDF final se genera desde documentación actualizada y se revisa contra el código que se entrega; estos Markdown no lo sustituyen.
- El curso ordinario y la suficiencia no comparten automáticamente evaluación/calendario. El programa incluye prácticas/casos y proyecto grupal (50 %); suficiencia es individual (100 puntos). No sumar sus rúbricas ni inventar una nota mínima no indicada.
