# AJAX y servicios externos

| Operación | Entrada | Respuesta y protección |
| --- | --- | --- |
| GET /api/eventos | q, category, from, to, availability, state, page validados. | JSON html/page/message; solo eventos públicos, fragmento escapado. |
| POST /api/reservas | csrf, event_id, quantity. | 201 con message/id/ticket_url/available; sesión, CSRF y transacción. |
| POST /api/reservas/{id}/cancelar | csrf. | JSON message; propietario y regla de 24 h; errores seguros. |
| GET /api/experiencias | Cookie de sesión. | JSON html/message del usuario; sin sesión 401. |
| GET /api/eventos/{slug}/clima | Slug de evento público. | JSON status/message y forecast cuando válido; desconocido 404. |

Los errores de negocio usan códigos HTTP acordes (401, 403, 404, 409, 422); métodos no admitidos 405. Un error inesperado puede devolver 500 con texto seguro desde el manejador global, por lo que el cliente debe tolerar respuesta no JSON. No es una API de pagos.

jQuery aporta dos funciones significativas: catálogo con debounce/aborto/control de secuencia e historial actualizado tras cancelar. AJAX tiene más de tres operaciones. El total visual orienta al usuario; el precio definitivo lo calcula PHP/MySQL.

La confirmación cliente tiene espera máxima de 15 s incluyendo JSON. Bloquea doble envío; ante timeout/red/5xx o respuesta inválida dirige a consultar experiencias y evita reintento inmediato desde el formulario. Abortar la espera no cancela una reserva ya confirmada en MySQL. El recorrido de Chrome comprobó el aviso y la navegación al ticket.

Open-Meteo se consulta desde PHP por HTTPS, coordenadas validadas y destino fijo. Lectura acotada a 3 s y 128 KiB, sin seguir redirects; unidades/rangos/fecha revisados. Pronóstico diario solo para evento público futuro dentro de 15 días, con atribución. Caché positiva 1 h, negativa 10 min y resultado anterior hasta 6 h. El fallo permite continuar con la reserva.

La aplicación mantiene una cuota global MySQL de 30 intentos/h y 10/min, consumida antes del HTTP externo bajo lock de fila. Los errores externos cuentan; falla de presupuesto omite la llamada. La caché puede perderse en alojamiento efímero, pero la cuota MySQL debe conservarse. Reglas/callback, migración, límites en MySQL y persistencia/restauración comprobados. Dos carreras reales del presupuesto fueron comprobadas en la integración MySQL de 219 controles.
