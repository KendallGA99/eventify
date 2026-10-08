# EVENTIFY: plan por bloques

## Forma de trabajo

Construir incrementos verificables, primero infraestructura y datos, después un flujo completo y finalmente cobertura administrativa/presentación/entrega. Aplicar el protocolo de AGENTS en cada bloque y actualizar ESTADO con hechos. El plan no fija fechas sin conocer la entrega real.

Ningún bloque está implementado al redactar este archivo. B00 prepara documentación; los demás se inician cuando el usuario solicite desarrollo. Las elecciones pendientes pueden resolverse mediante trabajo independiente; una dependencia que requiere datos del receptor o autorización externa permanece explícita.

## B00 — Base documental (intervención actual)

- Crear AGENTS, requisitos, plan, estado y README.
- Verificar cobertura RF/RNF/tecnologías/entregables y enlaces internos.
- Registrar fuentes, diferencias entre curso/suficiencia y decisiones frente a obligaciones.
- Cierre: documentos coherentes, sin claims de aplicación probada; siguiente paso claro.

## B01 — Arquitectura, herramientas y viabilidad gratuita

Dependencia: solicitud de iniciar desarrollo. Cobertura: U-01/02/05, RT-04/06/07/09, RNF-07.

1. Verificar Docker/Compose, Git y herramientas disponibles; no instalar paquetes o servicios pagados por defecto.
2. Elegir versiones soportadas de PHP/MySQL y fijarlas; bibliotecas gratuitas con licencia/procedencia registrada. Framework PHP y Bootstrap son opcionales.
3. Verificar API candidata, QR y herramientas de pruebas; no contratar nada.
4. Evaluar publicación pública gratuita: soporte PHP, MySQL real, persistencia, cuotas, caducidad/suspensión, HTTPS y cuentas demo. Descartar alternativas que pierdan datos o requieran pago; documentar si falta una opción.
5. Identificar Windows/arquitectura del receptor. Documentar Docker Desktop/WSL2/virtualización según requisitos vigentes; Linux tendrá Docker Engine/Compose u opción compatible.
6. Preparar estructura propuesta (public, app, config, database, tests, docs, scripts); public es raíz servida, secretos fuera de ella.
7. Crear rama de trabajo codex/ cuando corresponda, reglas de exclusión y versiones/lockfiles; configurar GitHub cuando cuenta/nombre/visibilidad estén definidos y exista autorización para publicar.
8. Crear Dockerfile/Compose/.env.example con healthcheck, persistencia y arranque básico.

Cierre: arquitectura/versiones documentadas, stack local arranca, conexión MySQL comprobada y diagnóstico claro de pendientes externos. Esto no certifica Windows ni URL pública.

## B02 — Modelo, migraciones, datos y reglas

Dependencia: B01 local. Cobertura: RT-05, RF-07/09/11/13/14/17/18/19/21, RNF-03/07.

1. ER y diccionario: usuarios/roles/categorías/eventos/reservas/tickets; claves, restricciones, índices y campos monetarios/temporales.
2. Adoptar o ajustar D-02 a D-07: estados, publicación, hora, indicadores, precio histórico, QR, transacciones, capacidad y duplicados.
3. Migraciones versionadas, inicialización de una BD nueva y seeds de demo separados/idempotentes; no aplicar resets sobre datos existentes.
4. Datos ficticios: publicados con cupos, agotados, últimos espacios, borrador, cancelado, finalizado, reservas propias/ajenas/canceladas y dos roles.
5. Diseñar política de conservación y migración/exportación de datos.

Cierre: esquema normalizado e integridad verificada con MySQL; migraciones/seed repetidos sin pérdida de datos; decisiones y diagrama coherentes. Los datos con fechas relativas deben reproducir los casos y límites de cancelación.

## B03 — Autenticación, permisos e interfaz base

Dependencia: B02. Cobertura: RF-01/02/03, RNF-01/02/03/04/07, RT-01/04.

1. Registro, validaciones, hashing, sesión PHP y logout.
2. Guards de rol/propiedad, CSRF, cookies y política de sesión, errores seguros y protección de login.
3. Layout semántico, navegación pública/usuario/admin, mensajes, estados de carga y formularios accesibles/adaptables.
4. Pruebas de petición directa, campos manipulados y sesión; no confiar en UI.

Cierre: usuario/admin de prueba ingresan correctamente; roles rechazados cuando corresponde; login/registro/logout verificados desde navegador, endpoint y BD.

## B04 — Primer flujo completo: evento → reserva → ticket → cancelación

Dependencia: B03; datos de evento sembrados válidos de B02 permiten trabajar sin esperar todo el CRUD. Cobertura: RF-07/08/09/10/11/12/13/14, RT-01/03/04, RNF-03/04.

1. Detalle real y cantidad/total dinámico; validación de precio/cupos autoritativa en PHP.
2. Crear reserva con transacción y protección de concurrencia; generar ticket persistido con QR real.
3. Mis experiencias y ticket protegidos por propiedad.
4. Cancelación estricta >24 h, liberación única y conservación del historial.
5. AJAX de reservar y cancelar, con errores/feedback y actualización real de interfaz.
6. Casos adversos: último cupo simultáneo, duplicado simultáneo, total manipulado, reserva ajena, evento no reservable y fallo transaccional.

Cierre: flujo completo comprobado UI → PHP → MySQL → respuesta; cupos e historial coherentes bajo concurrencia. Guardar evidencia real de las carreras y del límite temporal.

## B05 — Catálogo, búsqueda, filtros y API externa

Dependencia: B04 y API gratuita seleccionada. Cobertura: RF-04/05/06/07/10/15, RT-01/02/03, RNF-01/02/04/05/06.

1. Catálogo/detalles con estados, disponibilidad, imágenes optimizadas y mensajes.
2. Búsqueda/filtros combinados AJAX; selectores/eventos/DOM de jQuery significativos y demostrables.
3. Completar el segundo uso significativo de jQuery en actualización de Mis experiencias u otra función real; inventariar exactamente dónde se cumplen los mínimos.
4. API útil en contexto; timeout, caché y fallback honesto; claves protegidas si existen, cuota/licencia/atribución documentadas.
5. Revisar responsividad y acceso por teclado en catálogo, detalle, reserva y ticket.

Cierre: ≥2 usos jQuery, ≥3 operaciones AJAX y ≥1 integración real API con evidencia; falla externa no bloquea las reservas.

## B06 — Administración y dashboard

Dependencia: B04; puede avanzar junto con partes independientes de B05 sin agentes adicionales. Cobertura: RF-16/17/18/19/20/21, RNF-01/02/03/05/07.

1. CRUD categorías y eventos, validaciones y política de integridad/historial.
2. Consulta/detalle/filtros administrativos de reservas.
3. Cancelar evento y transición finalizado con efectos documentados; proteger carreras con reserva/modificación de cupo.
4. Dashboard: cinco indicadores base y dos derivados; cálculos contrastados con datos conocidos.
5. Tablas y acciones adaptables al móvil, validaciones seguras de imágenes si hay uploads.

Cierre: administrador realiza operaciones y usuario no; estado/precio/capacidad/historial/indicadores correctos; ningún dato estático simula un indicador.

## B07 — Revisión integral y portabilidad

Dependencia: B05/B06. Cobertura: todos RF/RNF, SEG-01–10, U-01/03/04 y RT-06.

1. Revisar matriz requisito → código → prueba → evidencia; detectar omisiones antes de pulir.
2. Pruebas unitarias de reglas puras, integración MySQL y E2E críticos; revisión de seguridad/dependencias y secretos.
3. Chrome y Firefox, pantallas móvil/tableta/escritorio, QR y estados vacíos/error/carga; registrar versiones/tamaños.
4. Copia limpia Linux: instalar, configurar, iniciar, reservar, reiniciar/recrear contenedores sin borrar volúmenes y comprobar datos/imágenes.
5. Preparar scripts de inicio/detención Linux y Windows, sin asumir bash instalado en Windows; errores y requisitos explicados.
6. Copia limpia Windows en equipo compatible: mismos flujos con rutas que incluyan espacios; guardar versiones del host/Docker/WSL y resultados. Si no hay equipo Windows, marcar pendiente y explicar, no cerrar U-01.
7. Exportar/restaurar en BD de prueba y verificar contenido; nunca probar restore sobre la única copia de datos.

Cierre: pruebas pertinentes sin fallos relevantes, copias limpias Linux/Windows verificadas y almacenamiento estable. Cualquier excepción debe estar explicitada; no equivale a aprobación automática.

## B08 — GitHub y publicación pública

Dependencia: arquitectura gratuita viable, autorización externa y versión verificada; preferentemente B07. Cobertura: RT-07/08/09, ENT-A/B/D, U-02/05.

1. Revisar historial y exclusión de secretos; configurar/push a GitHub con visibilidad acordada y acceso de evaluación.
2. Preparar entorno público, persistencia MySQL/imágenes y variables; HTTPS, cookies y cuentas demo aisladas.
3. Publicar mediante proveedor compatible realmente gratuito; conservar evidencia de cuotas/caducidad y operación durante evaluación.
4. Verificar URL desde fuera del entorno local: catálogo, login ambos roles, reserva/ticket/cancelación, administración e API.
5. Comprobar persistencia tras reinicio/redeploy y mantener versión anterior/rollback no destructivo documentado.

Cierre: URL real accesible y flujos centrales verificados, repositorio GitHub completo y presupuesto respetado. Gratis pendiente o URL no probada mantiene ENT-A/RT-09 pendientes; no reemplazarlos por localhost.

## B09 — Documentación final, paquete y preparación de defensa

Dependencia: implementación final coherente, B07/B08 cerrados o limitaciones reportadas sin afirmar entrega completa. Cobertura: ENT-C/D/E/F, U-03/06, RT-08.

1. Completar README y manual por sistema operativo, requisitos/versiones, inicio/detención, actualización, respaldo/restauración y resolución de errores.
2. Generar PDF técnico con todos los apartados de ENT-C, diagramas/diccionario e historias con aceptación; renderizar y revisar el PDF.
3. Paquete limpio: fuente, migraciones/seed, scripts, .env.example, dependencias reproducibles, licencias e instrucciones; sin entornos personales, caches, secretos o datos privados.
4. Entregar por ZIP y GitHub; probar que el paquete coincide con la versión verificada. Entregar exportación separada si se requiere transferir datos ya creados.
5. Guía de comprensión: arquitectura, PDO/SQL, PHP/sesiones/roles, DOM/jQuery/AJAX/API, transacciones/QR/Docker; ejercicios menores reales de modificación.
6. Participación del estudiante y ensayo de defensa registrados con evidencia humana, no marcados como cumplidos solo por tener el guion.

Cierre: todos los entregables académicos y condiciones acordadas comprobados, documento coherente, instalación de receptor validada y pendientes transparentes. La defensa formal ocurre ante el docente; registrar cuando realmente se realice.

## Estrategia de pruebas (herramientas todavía propuestas)

Elegir herramientas gratuitas compatibles en B01. Candidatas: PHPUnit para PHP, pruebas de integración contra MySQL en Docker y Playwright para flujos de navegador; ninguna está instalada ni tiene comandos definidos todavía. Las dependencias de desarrollo y navegadores deben ser reproducibles y no obligar al receptor a instalar herramientas para usar la app.

| ID de prueba | Casos principales | Requisitos |
| --- | --- | --- |
| T-AUTH | Registro, duplicado, validación directa, login incorrecto/correcto, sesión y logout. | RF-01/02, RNF-03/04 |
| T-PERM | URLs/HTTP de admin, escalada de rol, reserva/ticket de otro usuario, CSRF. | RF-03/12/14/20, SEG-01/03 |
| T-CAT | Campos, estados, búsqueda/filtros combinados, vacío/error y umbral 20 %. | RF-04/05/06/07/10 |
| T-RES | Cantidades, total servidor, último cupo, simultaneidad, duplicado simultáneo y rollback. | RF-08/09/11/14, SEG-07 |
| T-CANCEL | >24 h, =24 h, <24 h, repetición, evento cancelado y carreras. | RF-13/21, D-05/07 |
| T-ADMIN | CRUD/integridad, capacidad/precio/categoría/fecha, reducción concurrente de capacidad. | RF-17/18/19/20/21 |
| T-DASH | Cinco base + dos derivados con dataset conocido, cero/empates y cancelados excluidos según regla. | RF-16, D-03 |
| T-ASYNC | Dos usos jQuery y tres operaciones AJAX, carga/error y resultados fuera de orden. | RT-01/02/03, RF-05/06 |
| T-API | Datos útiles reales, error/timeout/cuota/fecha fuera de horizonte, reservas continúan. | RF-15, RNF-04/05 |
| T-SEC | SQLi/XSS, secretos, sesión, uploads si existen, dependencias y mensajes seguros. | RNF-03/04, SEG-01–10 |
| T-UX | Chrome/Firefox, móvil/tableta/escritorio, foco/teclado, formularios, tablas/ticket/QR. | RNF-01/02/06 |
| T-PORT | Copia limpia Linux/Windows, arranque, datos tras recreación, exportación/restauración, rutas con espacios. | U-01/03, RT-06 |
| T-PUB | URL externa, auth/reservas/admin/MySQL, persistencia y límites gratuitos. | RT-09, ENT-A/D, U-02 |
| T-DOC | Historial, README, PDF, paquete, cuentas y correspondencia con código. | RT-07/08, ENT-B/C/D |

Pruebas automatizadas usan BD aislada y datos ficticios, nunca bases reales. Para cancelación usar reloj controlado, no esperas de 24 horas. Para concurrencia emitir peticiones realmente solapadas y verificar filas/cupos finales; dos operaciones secuenciales no demuestran resistencia a carreras.

## Evidencia y versionado

- Por bloque: RF/RNF/RT/U cubiertos, resumen, archivos, pruebas/comandos reales, resultado, captura o salida mínima útil, impacto en datos y limitaciones.
- Escribir evidencia extensa en docs/evidencias/Bxx.md y enlazar desde ESTADO. No incluir secretos ni datos personales.
- Commits pequeños, con mensaje claro y bloque; no agrupar toda la aplicación al final ni generar historial ficticio. Versiones de entrega etiquetadas cuando estén verificadas.
- Publicar en GitHub exige cuenta/nombre/visibilidad definidos; el repositorio local no prueba ese entregable.
- No borrar datos o debilitar controles para obtener pruebas verdes. Si una corrección cambia una regla, actualizar requisitos/decisiones/test/documentación.

## Orden inmediato

B00 → B01 → B02 → B03 → B04 → B05/B06 → B07 → B08 → B09. Evaluar viabilidad de publicación en B01 para evitar descubrir un costo incompatible al final. El primer resultado de negocio demostrable es B04; el cierre completo requiere los entregables restantes.
