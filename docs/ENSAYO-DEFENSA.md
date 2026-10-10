# Ensayo de defensa de Eventify

Referencia de la aplicación y publicación: dev.41. Corrección nativa y controles en RUNTIME-CORREGIDO.md; protección de Compose conservada desde dev.40. Estudiante: Kendall Gamboa Arce. Docente: Rita Robles Loaiza.

Este material sirve para estudiar y practicar con el proyecto abierto. Los tiempos son una propuesta de preparación, no una duración exigida por el enunciado. Cada explicación debe poder relacionarse con una pantalla, una función o una prueba. La práctica del estudiante todavía no está registrada.

## Preparación

1. Seguir [INSTALACION.md](INSTALACION.md) y usar datos ficticios en una instalación de evaluación. Tener a mano las cuentas privadas, sin mostrarlas en pantalla.
2. Elegir un evento publicado que empiece dentro de más de 24 horas y tenga al menos dos espacios. Revisar su fecha: los datos de una demo anterior pueden haber vencido.
3. Antes de la exposición, comprobar el catálogo y el ingreso local. Abrir también la URL pública, pues el alojamiento gratuito puede tardar en despertar. La demo local sigue disponible si el proveedor está detenido; explicar la incidencia sin presentar localhost como la publicación.
4. Abrir los archivos de la tabla de lectura. Ocultar terminales con secretos, configuración privada y registros de cuentas.
5. Si va a reservar o editar, usar un conjunto de datos preparado para esa práctica; no repetir la siembra ni borrar volúmenes para corregir un error.

## Recorrido de 15 minutos

| Tiempo | Mostrar | Explicación que debe poder desarrollar |
| --- | --- | --- |
| 0–2 min | Inicio y catálogo | El sistema permite descubrir eventos y gestionar reservas; PHP sirve las pantallas y MySQL conserva usuarios, eventos e historial. |
| 2–4 min | Búsqueda y filtros | jQuery captura la interacción, AJAX solicita resultados y PHP valida los filtros. La búsqueda espera 300 ms y evita que una respuesta anterior reemplace la consulta nueva. |
| 4–7 min | Ingreso, reserva y ticket | La sesión identifica al usuario. El servidor calcula precio y disponibilidad; la reserva y el ticket se guardan en la misma transacción. El QR identifica la reserva. |
| 7–9 min | Mis experiencias y cancelación | Cancelar cambia el estado y conserva el historial. Solo las reservas confirmadas cuentan para los cupos. Se exigen más de 24 horas antes del inicio. |
| 9–11 min | Panel administrativo | Los permisos se verifican en PHP. Los indicadores salen de MySQL; los ingresos son potenciales, porque no hay cobros reales. |
| 11–13 min | Código de una reserva | Seguir controlador → servicio → transacción → consultas preparadas → respuesta. Explicar el bloqueo del evento antes de contar cupos. |
| 13–15 min | Prueba, despliegue y límites | Mostrar una prueba de borde, explicar Docker/Git y distinguir verificaciones aprobadas de Firefox, Windows y riesgos pendientes. |

No es necesario ejecutar todas las validaciones negativas durante una exposición corta. Practicarlas antes permite responder preguntas sin improvisar cambios en la base.

## Lectura guiada del código

| Archivo | Qué seguir |
| --- | --- |
| [public/index.php](../public/index.php) | Identificación de ruta/método y elección de controlador. Por qué una escritura no se acepta como GET. |
| [ReservationController.php](../app/Http/ReservationController.php) | CSRF, sesión, respuesta JSON y consultas restringidas al propietario. |
| [ReservationService.php](../app/Domain/ReservationService.php) | beginTransaction, lockEvent, cupos, duplicidad, precio de MySQL, ticket y commit/rollback. |
| [ReservationPolicy.php](../app/Domain/ReservationPolicy.php) | Reglas de tiempo, disponibilidad y últimos espacios. |
| [Session.php](../app/Security/Session.php) | Regeneración del identificador, token CSRF, vencimiento por inactividad y logout. |
| [Auth.php](../app/Security/Auth.php) | Hash/verificación de contraseña, rol USUARIO fijo al registrar y limitación de intentos. |
| [catalog.js](../public/assets/js/catalog.js) | Debounce, cancelación de petición y número de secuencia para respuestas tardías. |
| [experiences.js](../public/assets/js/experiences.js) | Actualización del historial y cancelación mediante AJAX. |
| [WeatherClient.php](../app/Infrastructure/WeatherClient.php) | URL fija, TLS, redirecciones deshabilitadas, timeout y límite de 128 KiB; JSON. |
| [TicketQr.php](../app/Support/TicketQr.php) | Identificador hexadecimal, matriz QR y SVG numérico; no valida acceso físico al evento. |

## Preguntas con razonamiento esperado

**¿Por qué no basta validar con JavaScript?** El visitante puede modificar una petición. PHP vuelve a validar cantidad, sesión, permisos, estado, fecha y cupos antes de guardar.

**¿Qué pasa si dos personas toman el último espacio?** Ambas pueden verlo libre en pantalla. El servicio bloquea la misma fila del evento con FOR UPDATE; la segunda transacción espera y vuelve a calcular los cupos con los cambios confirmados de la primera.

**¿Dónde está guardado el total?** La reserva guarda unit_price y total como DECIMAL. El INSERT toma price de events y lo multiplica por la cantidad; no utiliza un total enviado por el navegador. Una edición posterior del evento no debe reescribir ese precio histórico.

**¿Cancelar aumenta un contador?** No hay un contador independiente de cupos libres. Se calcula capacidad menos la suma de cantidades CONFIRMADAS. CANCELADA deja de entrar en esa suma. Una cancelación repetida no libera dos veces.

**¿Qué ocurre si quedan exactamente 24 horas?** Se rechaza. La regla es estrictamente mayor que 24 horas: 86401 segundos permite y 86400 no. UTC evita que la presentación en Costa Rica cambie el instante comparado.

**¿Por qué una URL secreta de ticket no basta?** Aunque su identificador sea difícil de adivinar, el controlador también exige autenticación y propiedad o el permiso administrativo correspondiente. El QR no sustituye esa comprobación.

**¿Hashing y CSRF resuelven el mismo problema?** No. El hash permite verificar la contraseña sin almacenarla en texto plano. CSRF comprueba que una operación de escritura lleva el token de la sesión correspondiente.

**¿Qué ocurre al dejar la cuenta abierta sin actividad?** Después de más de 30 minutos, la siguiente petición pierde la identidad y renueva la sesión. El token anterior ya no autoriza escrituras y la cookie anterior no recupera los permisos. Session::start aplica la regla; [http-session-expiry.php](../tests/integration/http-session-expiry.php) la comprueba con una sesión temporal envejecida, sin cambiar el reloj del equipo.

**¿Cómo se impide registrarse como administrador?** Auth::register fija el rol USUARIO mediante SQL. Un campo role añadido a la petición no participa en esa asignación.

**¿Qué ocurre si el clima falla?** Se muestra un estado de indisponibilidad y la reserva sigue disponible. El servidor limita tiempo, tamaño y solicitudes. No se inventa un pronóstico ni se cambia el proveedor para eludir su cuota.

**¿Qué tiene de AJAX este proyecto?** Catálogo, historial, reserva, cancelación y clima tienen peticiones asincrónicas. Explicar al menos tres siguiendo sus rutas concretas; abrir la pestaña de red solo si puede hacerlo sin exponer cookies o credenciales.

**¿Git permite recuperar la base de datos?** No. Recupera código versionado. Los datos viven en MySQL y los volúmenes; necesitan respaldo y restauración separados. Hay que comprobar compatibilidad de migraciones antes de volver a una versión de código.

**¿Qué significa que haya avisos de seguridad?** Hay que distinguir un control de la aplicación de una vulnerabilidad de una biblioteca del contenedor. Los informes conservan avisos y condiciones de exposición; las pruebas funcionales no certifican ausencia de vulnerabilidades nativas.

**¿Para qué sirve no-new-privileges?** La web de Compose pide al kernel que sus procesos no puedan adquirir nuevos privilegios al ejecutar otro archivo. Apache aún inicia con su proceso principal root y atiende peticiones mediante trabajadores www-data. Se comprobó que estos trabajadores mantienen la restricción y que la integración MySQL funciona. Es una capa adicional: no corrige bibliotecas vulnerables ni acredita la configuración del proveedor público.

## Ejercicio verificable sin cambiar reglas

Abrir [reservation-policy.php](../tests/unit/reservation-policy.php). Antes de ejecutar, predecir los resultados de 86401, 86400, 86399 y 0 segundos. Explicar también por qué dos fechas con distinta zona horaria pueden representar el mismo instante.

En Linux, desde la carpeta extraída de Eventify y con el proyecto iniciado:

```bash
docker compose exec -T web php tests/unit/reservation-policy.php
```

Si esa instalación de Linux requiere elevación, usar `sudo -n docker compose exec -T web php tests/unit/reservation-policy.php` con la autenticación administrativa ya habilitada. Una instalación con un nombre Compose personalizado debe usar su mismo `-p` y archivo de entorno; no ejecutar contra otro proyecto por omitirlos.

Resultado esperado: `Reglas de reservación: 19 comprobaciones correctas.` Este ejemplo se ejecutó en la copia local dev.39 el 10 de octubre; no escribe reservas ni cambia fechas. La ejecución del estudiante todavía debe registrarse.

Como práctica de modificación, añadir en una rama propia el caso de cancelación a 48 horas y predecir true. Ejecutar de nuevo y explicar por qué el contador aumenta en uno. Conservar el diff y el resultado; no modificar la regla para conseguir que la prueba pase. Este ejercicio se propone, no se afirma ejecutado.

## Criterios de preparación

El ensayo está listo cuando el estudiante puede hacer el recorrido, localizar las funciones indicadas, explicar los resultados sin leer respuestas, reconocer un fallo de proveedor y realizar una modificación menor con su comprobación. Registrar fecha, quién participó, dificultad encontrada y qué se corrigió en [DEFENSA.md](DEFENSA.md).
