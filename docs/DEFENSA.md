# Guía de comprensión y demostración de Eventify

B09/ENT-E/F. Esta guía propone un recorrido y ejercicios; no acredita autoría, dominio, participación ni defensa realizada. La persona responsable debe revisar el código y explicar sus decisiones con sus propias palabras. Registrar abajo solo actividades que realmente ocurran.

## Recorrido de demostración propuesto

Usar una instalación local/evaluación aislada con datos ficticios. Revisar VERSION y ESTADO antes de empezar; no demostrar sobre la única copia de datos de trabajo. No mostrar .env, contraseñas, dumps ni logs completos en la presentación.

1. Mostrar catálogo y combinar búsqueda/categoría/fecha/disponibilidad. Explicar jQuery, solicitud AJAX y resultados generados por PHP, sin recargar toda la página.
2. Iniciar sesión con usuario existente. Abrir una experiencia publicada futura con cupos; contrastar precio unitario y total visual.
3. Reservar una cantidad válida. Mostrar ticket/QR e historial; explicar que el servidor vuelve a calcular total y cupos.
4. Intentar otra reserva activa del mismo evento y explicar el rechazo. No repetir si el resultado del primer envío es incierto: consultar experiencias.
5. Cancelar una reserva propia de más de24h. Mostrar historial CANCELADA y cupos liberados; el registro y precio histórico permanecen.
6. Mostrar evento agotado/cancelado/próximo a iniciar y explicar sus restricciones. Si el clima está desactivado, declararlo; si está disponible, mostrar atribución y explicar el fallo controlado.
7. Cerrar sesión, ingresar como administrador y mostrar categorías/eventos, reservas y dashboard. Los ingresos son potenciales, no pagos recibidos. Mostrar una edición de prueba solo si se conoce su impacto y se conserva historial.
8. Explicar que la ejecución local no satisface la URL pública obligatoria. Mostrar las pruebas aprobadas y los pendientes, sin presentar Windows/Firefox/HTTPS como comprobados.

No son pruebas nuevas ni un guion de respuestas para aparentar conocimientos: practicar cada paso y registrar resultados reales. Para instalación/siembra/cuentas privadas consultar INSTALACION.md y DATOS.md.

## Preguntas y puntos del código para comprender

| Tema | Qué explicar con un ejemplo | Referencia |
| --- | --- | --- |
| Cliente-servidor | Navegador envía HTTP; Apache/PHP valida, PDO consulta MySQL y devuelve HTML/JSON. | public/index.php; docs/ARQUITECTURA.md |
| Sesión y roles | Cookie identifica una sesión; rol/estado se comprueban en servidor, no en un botón oculto. | app/Security/Session.php; Auth.php; app/Http/AdminController.php |
| Contraseñas y CSRF | Hash no es cifrado reversible; token CSRF protege escrituras de la sesión. | Auth.php; RegistrationInput.php; controladores |
| SQL y modelo | Consultas preparadas separan datos de SQL; claves foráneas conservan relaciones. | app/Infrastructure; database/migrations |
| Cupos y concurrencia | Dos clientes pueden ver el último cupo; transacción/lock obligan a revalidar antes de guardar. | app/Domain/ReservationService.php |
| Precio e historial | El precio visual no es autoridad; unit_price/total de reserva conservan el precio de compra simulado. | ReservationService.php; AdminService.php |
| Cancelación | Más de24h estrictas, propiedad, cambio de estado y liberación una vez; conservar historial. | ReservationPolicy.php; ReservationService.php |
| jQuery/AJAX | Catálogo e historial usan solicitudes asincrónicas y HTML del servidor escapado. | public/assets/js/catalog.js; experiences.js; app/Views |
| API externa | Host HTTPS fijo, timeout/error/cache/cuota; clima no debe bloquear una reserva. | WeatherClient.php; EventWeather.php; WeatherBudget.php |
| QR y permisos | QR identifica un ticket; no certifica acceso físico ni reemplaza vigencia/propiedad en servidor. | app/Domain; ReservationController.php |
| Docker y portabilidad | Contenedores Linux incluyen dependencias; volúmenes guardan datos. Linux host no obliga a Linux receptor. | Dockerfile; docker-compose.yml; INSTALACION.md |
| Migraciones/rollback | Git revierte código; no revierte MySQL. DDL puede confirmar parcialmente; preservar firmas y respaldos. | MigrationRunner.php; VERSIONADO.md; RESPALDO.md |

## Ejercicios menores sugeridos

Antes de modificar, crear una rama propia, revisar cambios y elegir una instalación de prueba. Conservar diferencia y resultado de verificación; no alterar migraciones aplicadas, permisos ni precios para facilitar el ejercicio.

- Cambiar un texto del estado vacío del catálogo y localizar la vista que lo produce. Verificar catálogo vacío y normal.
- Ajustar un espacio/color de una tarjeta y comprobar360/768/1440px y foco visible.
- Agregar una validación de un campo administrativo existente manteniendo cliente/servidor coherentes. Explicar el error y probar entrada válida/inválida.
- Escribir una comprobación del borde de cancelación: exactamente24h se rechaza; más de24h se permite. Explicar el reloj/UTC.

Son opciones de práctica, no cambios exigidos ni realizados. La modificación concreta de defensa depende del docente y de lo que la persona pueda explicar.

## Registro humano pendiente

| Actividad | Responsable real | Fecha | Evidencia/resultado |
| --- | --- | --- | --- |
| Lectura y explicación del código | Pendiente | Pendiente | Sin registrar |
| Demo completa de ambos roles | Pendiente | Pendiente | Sin registrar |
| Modificación menor explicada/verificada | Pendiente | Pendiente | Sin registrar |
| Revisión del PDF técnico | Pendiente | Pendiente | Sin registrar |
| Defensa individual ante docente | Pendiente | Pendiente | Sin registrar |

La evidencia automatizada y los aportes del asistente constan en Git/ESTADO. Completar responsables y participación reales; no atribuirles retrospectivamente el trabajo generado.
