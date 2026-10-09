# Recorrido de Firefox y zoom

B07/C-06/C-07. Procedimiento pendiente de ejecución humana en Firefox real. La copia Linux dev.33 está disponible en http://localhost:8086; su instalación, recorrido IAB y persistencia ya se comprobaron. No repetir instalación ni sembrar nuevos datos para esta prueba. Registrar versión de Firefox y resultados; una lista de pasos no acredita su ejecución.

1. Abrir Firefox y consultar su versión en Ayuda/Acerca de Firefox. Abrir http://localhost:8086/eventos. Buscar Encuentro y limpiar filtros; comprobar un resultado y recuperación del catálogo, sin errores visibles.
2. Comprobar navegación con Tab y Shift+Tab: búsqueda, categoría, fechas, disponibilidad, estado y botón. El foco debe ser visible. Abrir los campos de fecha con teclado; comprobar selección/cancelación del calendario sin enviar un rango accidental.
3. Cambiar el zoom desde el menú de Firefox a 200%. Registrar el porcentaje visible. Comprobar inicio, catálogo, detalle, ticket e historial: texto legible, controles alcanzables y sin contenido esencial cortado. Restaurar 100% al terminar. Achicar la ventana no equivale a esta prueba.
4. Ingresar con una cuenta ficticia USUARIO de la copia 8086. Las cuentas están en el registro privado storage/clean-20261009035638-598f411d/demo-credentials.json; no copiarlas a documentos, capturas o Git. Abrir Mis experiencias: la reserva del Encuentro de tecnología debe aparecer CANCELADA, con dos entradas; consultar su ticket y QR sin vigencia. No crear otra reserva para comprobar esa conservación.
5. Abrir /admin con ese usuario: debe rechazar acceso. Cerrar sesión y abrir la URL del ticket: debe pedir acceso. Ingresar como ADMINISTRADOR, abrir panel, categorías, eventos y reservas sin modificar datos. Cerrar sesión.
6. Comunicar versión, resultados y fallos con página/acción exacta. No enviar contraseñas ni capturas del archivo de cuentas. Guardar una captura del zoom 200% y del calendario cuando sea posible, con datos de demostración únicamente.

| Comprobación | Estado |
| --- | --- |
| Versión Firefox real | Pendiente |
| Catálogo y filtros | Pendiente |
| Teclado/foco/calendario | Pendiente |
| Zoom 200% en flujos centrales | Pendiente |
| Historial, ticket y QR | Pendiente |
| Roles y logout | Pendiente |

La prueba Windows sigue su propio procedimiento en VERIFICACION-WINDOWS.md. Este recorrido no evalúa otra plataforma ni corrige avisos de bibliotecas.
