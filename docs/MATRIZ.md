# EVENTIFY: requisitos, código y evidencia

Revisión vigente: 10 de octubre de 2026. IDs y criterios completos en [REQUISITOS.md](REQUISITOS.md); resultados vigentes en [ESTADO.md](ESTADO.md), detalle en [B07](evidencias/B07.md). Esta matriz es un índice para revisión, no una certificación de entrega. Los nombres de pruebas indican cobertura relevante; resultados aprobados corresponden a las ejecuciones registradas, no al simple hecho de existir archivos.

## Funciones

Rutas de código y pruebas relativas a la raíz del repositorio. Integración aislada vigente: 234 comprobaciones MySQL/HTTP sobre el runtime corregido dev.41, incluidas caducidad y rechazo de sesión inventada. Las 266 unidades/QR pertenecen a sus ejecuciones históricas indicadas en ESTADO. Recorrido IAB dev.39 terminado; Firefox conserva evidencia humana parcial y no está disponible en el control autorizado. No se atribuyen pruebas de una referencia a otra por compartir este índice.

| ID | Código principal | Prueba pertinente | Evidencia vigente / falta |
| --- | --- | --- | --- |
| RF-01 | app/Http/AuthController.php; app/Domain/RegistrationInput.php | tests/unit/registration.php; tests/integration/http-auth.php | Integración y C-01 IAB/dev.28: confirmación, alta válida, duplicado, ingreso y logout comprobados; teclado/foco en390px. No sustituye Firefox |
| RF-02 | app/Security/Auth.php; app/Security/Session.php | tests/integration/http-auth.php; tests/integration/http-session-expiry.php | HTTP y Chrome/IAB login/logout. Integración234 añade caducidad, renovación, CSRF anterior y reenvío de cookie rechazada; sin cambiar Session |
| RF-03 | app/Http/AdminController.php; app/Security/Auth.php | tests/integration/http-auth.php; tests/integration/admin.php | HTTP roles/CSRF, acceso directo y propiedad; IAB dev.39 comprueba usuario403 con estilo y navegación administrativa. Caducidad revoca permisos en integración aislada |
| RF-04 | app/Infrastructure/CatalogRepository.php; app/Views/catalog-results.php | tests/integration/catalog.php | Catálogo real y C-02: vacío, limpieza, agotados y error de red con recuperación comprobados en IAB; filtros dev.39 recorridos. Firefox pendiente |
| RF-05 | public/assets/js/catalog.js; app/Http/CatalogController.php | tests/integration/catalog.php; tests/unit/catalog-filter.php | Búsqueda real; C-02 comprueba cancelación de consulta anterior y resultados conservados. No se fuerza un callback antiguo ni se atribuye esa prueba a Firefox |
| RF-06 | app/Domain/CatalogFilter.php; public/assets/js/catalog.js | tests/unit/catalog-filter.php; tests/integration/catalog.php | Servidor y C-02 IAB: búsqueda/categoría/fechas/cupos combinados, rango inválido y limpieza. Recorrido dev.39 confirmado; Firefox restante pendiente |
| RF-07 | app/Views/experiences.php; app/Infrastructure/CatalogRepository.php | tests/integration/catalog.php; tests/integration/reservations.php | Detalle/cupos/cancelación reales; C-04 comprueba estados de fallo API mediante respuestas simuladas, separados de éxito/fallo reales del proveedor |
| RF-08 | app/Domain/ReservationService.php; public/assets/js/reservations.js | tests/unit/reservation-input.php; tests/integration/reservations.php; tests/client/reservation-feedback.cjs | Reserva real y navegación al ticket; C-03 IAB/dev.28 interrumpe entrega de201 después de guardar: resultado desconocido, historial/ticket únicos y sin reintento inmediato |
| RF-09 | app/Domain/ReservationService.php; app/Domain/AdminService.php | tests/integration/reservations.php; tests/integration/admin-races.php | Cupos/rollback/último cupo y aforo concurrente aprobados; no depender del botón deshabilitado |
| RF-10 | app/Domain/ReservationPolicy.php; app/Views/catalog-results.php | tests/unit/reservation-policy.php; tests/integration/catalog.php | Regla/umbral probado; tarjeta de últimos espacios observada en catálogo real |
| RF-11 | app/Domain/ReservationService.php; database/migrations/001_initial_schema.php | tests/integration/reservations.php | Duplicado secuencial/concurrente aprobado; IAB confirma rechazo de POST duplicado y conserva historial/ticket original |
| RF-12 | app/Http/ReservationController.php; app/Views/reservation-results.php | tests/integration/http-auth.php; tests/integration/reservations.php | Historial propio real; otra cuenta Chrome obtiene404 del ticket, IAB obtiene404 de ticket y QR ajenos |
| RF-13 | app/Domain/ReservationPolicy.php; public/assets/js/experiences.js | tests/unit/reservation-policy.php; tests/integration/reservations.php | Bordes de 24 h/idempotencia en tests; cancelación AJAX real conserva historial/cupos |
| RF-14 | app/Support/TicketQr.php; app/Views/experiences.php | tests/unit/ticket-qr.php; tests/integration/http-auth.php; tests/integration/reservations.php | QR real generado/decodificado previamente; Chrome ticket/SVG 200 y QR cargado; privacidad HTTP/UI |
| RF-15 | app/Domain/EventWeather.php; app/Infrastructure/WeatherClient.php; public/assets/js/weather.js | tests/unit/event-weather.php | Cliente HTTPS/panel sobre evento DB; reglas/cache/errores probados; HTTP429 público gestionado y posterior pronóstico real visible en IAB (8oct18:22CST) |
| RF-16 | app/Infrastructure/AdminReadRepository.php; app/Views/admin-dashboard.php | tests/unit/admin-read.php; tests/integration/admin-read.php | Dataset conocido/cero/empates/importe histórico aprobados; dashboard real visible |
| RF-17 | app/Domain/AdminService.php; app/Views/admin.php | tests/unit/admin-input.php; tests/integration/admin.php | Integración y Chrome creación/desactivación conservando evento; sin DELETE destructivo |
| RF-18 | app/Domain/AdminInput.php; app/Domain/AdminService.php; app/Views/admin.php | tests/unit/admin-input.php; tests/integration/admin.php | CRUD real y C-05: cambio de fecha/borrador con historial y finalización futura rechazados; valores conservados. IAB dev.39 revisa panel, eventos y reservas |
| RF-19 | app/Domain/AdminInput.php; app/Domain/AdminService.php | tests/unit/admin-input.php; tests/integration/admin.php; tests/integration/admin-races.php | Integración/carreras aprobadas; C-05 IAB comprueba rechazo de aforo, precio, fecha, estado y slug inválidos sin modificar datos de negocio |
| RF-20 | app/Domain/AdminReservationFilter.php; app/Infrastructure/AdminReadRepository.php; app/Views/admin-reservations.php | tests/unit/admin-read.php; tests/integration/admin-read.php | Filtros/paginación/integración; Chrome filtros combinados y detalle histórico observados |
| RF-21 | app/Domain/AdminService.php; app/Domain/ReservationService.php | tests/integration/admin.php; tests/integration/admin-races.php | Ambos órdenes cancelación-reserva conservan datos; Chrome evento nuevo cancelado sin reserva ni pronóstico |

## Calidad y entrega

| IDs | Código/documentación | Evidencia y pendiente |
| --- | --- | --- |
| RNF-01/02 | public/assets/css/app.css; app/Views | C-06 y ESTADO: vistas/teclado comprobados en los tamaños registrados; perfil/panel dev.39 a390/768/1280 sin desbordamiento. Firefox restante pendiente; zoom200% opcional por instrucción del usuario |
| RNF-03 | app/Security; controladores; migraciones | HTTP propiedad/roles/CSRF y sesiones; integración234. Publicación dev.41:81 controles HTTPS de usuarios/36 administrativos. NoNewPrivs y cinco trabajadores UID33 comprobados. Tres imágenes finales sin avisos, base Trivy10oct12:33UTC; C-09 cerrado para ese corte. Informes antiguos conservados como historia |
| RNF-04 | bootstrap/controladores/clientes AJAX | C-03 resultado desconocido real tras201; C-04 simula timeout/error/cuota y comprueba recuperación. Pruebas de clientes y errores HTML con estilo tienen referencias propias en ESTADO |
| RNF-05 | CatalogRepository/AdminReadRepository; EventWeather | Paginación/índices/snapshot de dashboard/caché y presupuesto API implementados; no afirmar benchmark no medido |
| RNF-06 | Chrome QA | Chrome/IAB y capturas humanas Firefox157.0.1 con alcance parcial. Inventario autorizado expone Chrome/IAB, sin Firefox; usuario descartó geckodriver. C-07 bloqueado |
| RNF-07 | app/Domain, Http, Infrastructure, Security, Views | Responsabilidades separadas; revisión final pendiente |
| RT-01/02/03 | reservations.js; catalog.js; experiences.js; weather.js | Dos funcionalidades jQuery y más de tres operaciones AJAX; C-02/C-03/C-04 y recorrido IAB dev.39 con evidencia. No sustituye compatibilidad Firefox |
| RT-04/05 | PHP/MySQL; docs/DATOS.md; database/migrations | Migraciones/relaciones/CRUD e integración aprobados; no sustituir tecnologías |
| RT-06 / U-01 | Dockerfile; docker-compose.yml; scripts Linux/Windows | ZIP Linux dev.33 exacto instalado y persistencia posterior comprobada; Web/MySQL dev.41 actualizados en8086, diez tablas y ocho reservas/tickets conservados. Windows: seis scripts con parser PowerShell5.1 real; instalación Docker pendiente |
| RT-09 / U-02 | docs/ARQUITECTURA.md; docs/PUBLICACION.md | Render PHP + Aiven MySQL Free publicados; TLS, URL HTTPS, flujos centrales, persistencia y pronóstico útil reales comprobados. No garantiza disponibilidad permanente; revisión final pendiente |
| RT-07 / U-05 | GitHub privado; docs/VERSIONADO.md; CHANGELOG.md | Historial/push/etiquetas reales; acceso de evaluador, CI si procede y entrega final pendientes |
| RT-08 | README.md; docs/INSTALACION.md | Manual, tecnologías y enlace público disponibles; paquete final y revisión del receptor pendientes |
| U-03 | docs/INSTALACION.md; docs/RESPALDO.md; docs/PAQUETE.md; scripts/package-source.py; scripts/test-restore-qa-linux.sh; bin/qa-transfer.php | ZIP dev.14 real con 153 huellas correctas; 13 checks de empaquetado. 40 checks de orquestación simulada y dump/import MySQL real aprobados; comparación exacta de diez tablas/archivos. ZIP Linux dev.17 con flujo Chrome aprobado; HTTP restaurado autenticado y persistencia posterior al flujo Linux aprobados (existing-iAQixJNV); transferencia al receptor y Windows pendientes |
| U-04/06 | AGENTS.md; PLAN/ESTADO/MATRIZ/evidencias | Protocolo aplicado; estado breve e historial conservado. No certificar pruebas pendientes |
| ENT / defensa | docs/REQUISITOS.md | PDF dev.41 de18 páginas y paquete229 archivos revisados/verificados; cuentas ficticias gestionadas fuera de Git. Guías preparadas. Revisión del receptor, entrega de accesos y ensayo/defensa humanos pendientes |

## Siguiente revisión dirigida

Los casos concretos y sus criterios están en [CIERRE.md](CIERRE.md). La corrección nativa dev.41 y la preparación del paquete no cierran Firefox, Windows o participación humana. Conservar las evidencias históricas siguientes, que describen etapas anteriores. No cerrar B07 ni marcar estable mientras falten criterios.

| ENT-C / ENT-E/F | docs/TECNICO.md; scripts/build-technical-pdf.py | PDF borrador actualizado dev.17 de 15 páginas generado/revisado visualmente; 21 RF/7 RNF presentes. Documento final y revisión/dominio/defensa humana pendientes |

## Preparación pública dev.20

Dockerfile.public/entrypoint/preflight:25 guardas simuladas y buildPHP8.4/tres rechazos reales aprobados (public-start-vxriCKan,19:23:27UTC/exit0). Sin proveedor/URL; arranque positivo, TLS real y aprovisionamiento aún pendientes. Esta evidencia no cierraRT-09/ENT-A ni sustituye Windows/Firefox.

## TLS y arranque público aislado dev.21

public-tls-LeWppmpW,19:31:50UTC/exit0: PHP8.4/MySQL8.4 conTLSválido y rechazoCAajena/hostnameincorrecto, tres migraciones/ApacheSyntaxOK/HTTPinterno, recreaciónwebsinmigracionesrepetidas. Cumple el ensayo local de estos controles; RT-09/ENT-A siguen pendientes por proveedor/URLHTTPS real y aprovisionamiento. No certifica caso servidor sinTLS.


## Actualización dev.22: ENT-D / RNF-03 / SEG-08

CLI explícito y ensayo aislado `public-tls-5NcIVnVj`, 19:39:57 UTC / exit 0: tres cuentas ficticias con roles/hashes correctos; repetición, entorno local y plan inválido rechazados sin cambios. Cuentas conservadas tras recrear web. TLS válido y rechazos de CA/hostname nuevamente aprobados. Actualiza los pendientes históricos de aprovisionamiento anteriores: ensayo local aprobado; ENT-D sigue pendiente de cuentas funcionales en entorno público y entrega privada del acceso. ENT-A/RT-09, servidor sin TLS y login público permanecen pendientes.


Dev.23 prepara el caso servidor sin TLS mediante ensayo separado y controles TCP de disponibilidad/base vacía. Configuración Compose/Bash verificadas; ejecución PHP 8.4/MySQL y arranque negativo pendiente. RNF-03/04/RT-09 no se elevan a completos por preparar la prueba.


Actualización dev.23/24: public-no-tls-HVqp1VtN exit 1. Sintaxis PHP 8.4/control TCP/rechazo Database/base vacía aprobados; arranque muestra error previsto, pero el evaluador del código Compose abortó antes del control final. Dev.24 cambia a inspección del estado real del contenedor; ocho controles del evaluador correctos, repetición Docker pendiente. Caso completo servidor sin TLS sigue abierto.


Resultado vigente dev.24: public-no-tls-QnTPzu56, 19:55:27 UTC / exit 0. Rechazo de MySQL sin TLS y arranque detenido con exit 1 reales; dos controles TCP/base vacía correctos. Supera el fallo parcial dev.23. RNF-03/04: este control aprobado; RT-09/ENT-A siguen pendientes por proveedor/HTTPS/URL.

## Incremento dev.42 — RF-15

Respaldo MET Norway integrado en app/Infrastructure/MetWeatherClient.php y WeatherFallbackClient.php; muestras revisadas en app/Domain/MetForecast.php. Clima:58 comprobaciones correctas; integración aislada234 y seguridad pública81. Render sirve bd7e3e5; detalle público del12oct muestra17,9-26,4°C, fuente y fecha de consulta. Móvil con ancho efectivo375px sin desbordamiento. La actualización local conservó diez tablas y la pública nueve, con cambio únicamente en weather_budget. No se atribuye un nuevo escaneo de imágenes ni certificación Windows/Firefox a este incremento.
