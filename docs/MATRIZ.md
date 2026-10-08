# EVENTIFY: requisitos, código y evidencia

Revisión 2026-10-08. IDs y criterios completos en [REQUISITOS.md](REQUISITOS.md); resultados vigentes en [ESTADO.md](ESTADO.md), detalle en [B07](evidencias/B07.md). Esta matriz es un índice para revisión, no una certificación de entrega. Los nombres de pruebas indican cobertura relevante; resultados aprobados corresponden a las ejecuciones registradas, no al simple hecho de existir archivos.

## Funciones

Rutas de código y pruebas relativas a la raíz del repositorio. Integración vigente: 211 comprobaciones en MySQL/HTTP; 266 unidades y QR. Navegador Chrome parcial sobre QA actual; cambio JS dev.10 activado y reserva con navegación automática comprobada.

| ID | Código principal | Prueba pertinente | Evidencia vigente / falta |
| --- | --- | --- | --- |
| RF-01 | app/Http/AuthController.php; app/Domain/RegistrationInput.php | tests/unit/registration.php; tests/integration/http-auth.php | Integración aprobada; flujo completo de registro en navegador pendiente |
| RF-02 | app/Security/Auth.php; app/Security/Session.php | tests/integration/http-auth.php | HTTP y Chrome login/logout; tras logout admin conduce a login |
| RF-03 | app/Http/AdminController.php; app/Security/Auth.php | tests/integration/http-auth.php; tests/integration/admin.php | HTTP roles/CSRF; Chrome USUARIO rechazado en /admin; rutas restantes UI pendientes |
| RF-04 | app/Infrastructure/CatalogRepository.php; app/Views/catalog-results.php | tests/integration/catalog.php | Catálogo real Chrome/DB y estados; matriz completa vacío/errores UI pendiente |
| RF-05 | public/assets/js/catalog.js; app/Http/CatalogController.php | tests/integration/catalog.php; tests/unit/catalog-filter.php | Búsqueda AJAX SQL/evento nuevo real; respuestas tardías en navegador real pendientes |
| RF-06 | app/Domain/CatalogFilter.php; public/assets/js/catalog.js | tests/unit/catalog-filter.php; tests/integration/catalog.php | Filtros servidor aprobados; combinación completa y errores Chrome/Firefox pendientes |
| RF-07 | app/Views/experiences.php; app/Infrastructure/CatalogRepository.php | tests/integration/catalog.php; tests/integration/reservations.php | Detalle real/cupos tras cancelación y evento cancelado visibles; fallo API UI real pendiente |
| RF-08 | app/Domain/ReservationService.php; public/assets/js/reservations.js | tests/unit/reservation-input.php; tests/integration/reservations.php; tests/client/reservation-feedback.cjs | Reserva actual de dos entradas → ticket automático/QR aprobada en Chrome; dev.10 activado. Error de transporte real pendiente |
| RF-09 | app/Domain/ReservationService.php; app/Domain/AdminService.php | tests/integration/reservations.php; tests/integration/admin-races.php | Cupos/rollback/último cupo y aforo concurrente aprobados; no depender del botón deshabilitado |
| RF-10 | app/Domain/ReservationPolicy.php; app/Views/catalog-results.php | tests/unit/reservation-policy.php; tests/integration/catalog.php | Regla/umbral probado; tarjeta de últimos espacios observada en catálogo real |
| RF-11 | app/Domain/ReservationService.php; database/migrations/001_initial_schema.php | tests/integration/reservations.php | Duplicado secuencial/concurrente aprobado; recorrido UI duplicado pendiente |
| RF-12 | app/Http/ReservationController.php; app/Views/reservation-results.php | tests/integration/http-auth.php; tests/integration/reservations.php | Historial propio real; otra cuenta Chrome obtiene 404 del ticket |
| RF-13 | app/Domain/ReservationPolicy.php; public/assets/js/experiences.js | tests/unit/reservation-policy.php; tests/integration/reservations.php | Bordes de 24 h/idempotencia en tests; cancelación AJAX real conserva historial/cupos |
| RF-14 | app/Support/TicketQr.php; app/Views/experiences.php | tests/unit/ticket-qr.php; tests/integration/http-auth.php; tests/integration/reservations.php | QR real generado/decodificado previamente; Chrome ticket/SVG 200 y QR cargado; privacidad HTTP/UI |
| RF-15 | app/Domain/EventWeather.php; app/Infrastructure/WeatherClient.php; public/assets/js/weather.js | tests/unit/event-weather.php | Cliente HTTPS y panel real sobre evento DB; reglas/cache/errores controlados; caída externa UI real pendiente |
| RF-16 | app/Infrastructure/AdminReadRepository.php; app/Views/admin-dashboard.php | tests/unit/admin-read.php; tests/integration/admin-read.php | Dataset conocido/cero/empates/importe histórico aprobados; dashboard real visible |
| RF-17 | app/Domain/AdminService.php; app/Views/admin.php | tests/unit/admin-input.php; tests/integration/admin.php | Integración y Chrome creación/desactivación conservando evento; sin DELETE destructivo |
| RF-18 | app/Domain/AdminInput.php; app/Domain/AdminService.php; app/Views/admin.php | tests/unit/admin-input.php; tests/integration/admin.php | Chrome borrador/edición/publicación/cancelación nuevo; finalización/edición con reservas UI pendientes |
| RF-19 | app/Domain/AdminInput.php; app/Domain/AdminService.php | tests/unit/admin-input.php; tests/integration/admin.php; tests/integration/admin-races.php | Límites e integridad/histórico/carrera de aforo aprobados; casos adversos UI restantes pendientes |
| RF-20 | app/Domain/AdminReservationFilter.php; app/Infrastructure/AdminReadRepository.php; app/Views/admin-reservations.php | tests/unit/admin-read.php; tests/integration/admin-read.php | Filtros/paginación/integración; Chrome filtros combinados y detalle histórico observados |
| RF-21 | app/Domain/AdminService.php; app/Domain/ReservationService.php | tests/integration/admin.php; tests/integration/admin-races.php | Ambos órdenes cancelación-reserva conservan datos; Chrome evento nuevo cancelado sin reserva ni pronóstico |

## Calidad y entrega

| IDs | Código/documentación | Evidencia y pendiente |
| --- | --- | --- |
| RNF-01/02 | public/assets/css/app.css; app/Views | Ticket móvil/tableta/escritorio y dashboard tableta; catálogo/cuenta/registro 360 y registro 768 sin desbordamiento; matriz de páginas/estados, teclado/foco, zoom y orientación pendientes |
| RNF-03 | app/Security; controladores; migraciones | HTTP propiedad/roles/CSRF, SQL parametrizado/escape; auditoría Composer/huella jQuery/15 checks HTTP aprobados en SEGURIDAD.md; revisión completa de imágenes/historial y cookies HTTPS pendientes |
| RNF-04 | bootstrap/controladores/clientes AJAX | Errores de negocio y API controlados; dev.10 espera acotada probada en cliente controlado, flujo exitoso actual Chrome aprobado; timeout real pendiente |
| RNF-05 | CatalogRepository/AdminReadRepository; EventWeather | Paginación/índices/snapshot de dashboard/caché y presupuesto API implementados; no afirmar benchmark no medido |
| RNF-06 | Chrome QA | Chrome 154.0.8037.97 parcial; Firefox real pendiente |
| RNF-07 | app/Domain, Http, Infrastructure, Security, Views | Responsabilidades separadas; revisión final pendiente |
| RT-01/02/03 | reservations.js; catalog.js; experiences.js; weather.js | Dos funcionalidades jQuery (catálogo e historial) y operaciones AJAX significativas observadas; pruebas de estados restantes pendientes |
| RT-04/05 | PHP/MySQL; docs/DATOS.md; database/migrations | Migraciones/relaciones/CRUD e integración aprobados; no sustituir tecnologías |
| RT-06 / U-01 | Dockerfile; docker-compose.yml; scripts Linux/Windows | Stack Linux/persistencia QA y arranque desde ZIP Linux dev.17 con reserva/ticket/cancelación aprobados; recreación tras reserva nueva y Windows pendientes |
| RT-09 / U-02 | docs/ARQUITECTURA.md; docs/PUBLICACION.md | Render PHP + Aiven MySQL: candidata; TLS PDO preparado (40 guardas), presupuesto API MySQL aprobado en integración y persistencia dev.13; TLS externo/concurrencia específica y URL real pendientes. InfinityFree no seleccionada por MariaDB; localhost no cumple |
| RT-07 / U-05 | GitHub privado; docs/VERSIONADO.md; CHANGELOG.md | Historial/push/etiquetas reales; acceso de evaluador, CI si procede y entrega final pendientes |
| RT-08 | README.md; docs/INSTALACION.md | Manual/tecnologías/comandos preparados; enlace público y paquete final pendientes |
| U-03 | docs/INSTALACION.md; docs/RESPALDO.md; docs/PAQUETE.md; scripts/package-source.py; scripts/test-restore-qa-linux.sh; bin/qa-transfer.php | ZIP dev.14 real con 153 huellas correctas; 13 checks de empaquetado. 40 checks de orquestación simulada y dump/import MySQL real aprobados; comparación exacta de diez tablas/archivos. ZIP Linux dev.17 con flujo Chrome aprobado; HTTP restaurado, transferencia al receptor y Windows pendientes |
| U-04/06 | AGENTS.md; PLAN/ESTADO/MATRIZ/evidencias | Protocolo aplicado; estado breve e historial conservado. No certificar pruebas pendientes |
| ENT / defensa | docs/REQUISITOS.md | PDF técnico/paquete/cuentas/evidencia humana de comprensión y defensa pendientes |

## Siguiente revisión dirigida

1. Activar dev.10 y verificar confirmación real, sin repetir POST de resultado desconocido.
2. Completar permisos/rutas/casos adversos de navegador y responsive/Firefox.
3. Ensayar respaldo/restauración sobre destino aislado y copia limpia Linux/Windows.
4. Resolver URL pública gratuita y documentación/entrega/defensa; no cerrar B07 ni marcar estable mientras falten criterios.

| ENT-C / ENT-E/F | docs/TECNICO.md; scripts/build-technical-pdf.py | PDF borrador de 14 páginas generado/revisado visualmente; 21 RF/7 RNF presentes. Documento final y revisión/dominio/defensa humana pendientes |
