# Recorrido de Firefox y zoom

B07/C-06/C-07. Validación humana parcial con capturas aportadas por el usuario. La copia Linux dev.33 está disponible en http://localhost:8086; su instalación, recorrido IAB y persistencia ya se comprobaron. No repetir instalación ni sembrar nuevos datos para esta prueba. Registrar versión de Firefox y resultados; una lista de pasos no acredita su ejecución.

1. Abrir Firefox y consultar su versión en Ayuda/Acerca de Firefox. Abrir http://localhost:8086/eventos. Buscar Encuentro y limpiar filtros; comprobar un resultado y recuperación del catálogo, sin errores visibles.
2. Comprobar navegación con Tab y Shift+Tab: búsqueda, categoría, fechas, disponibilidad, estado y botón. El foco debe ser visible. Abrir los campos de fecha con teclado; comprobar selección/cancelación del calendario sin enviar un rango accidental.
3. Cambiar el zoom desde el menú de Firefox a 200%. Registrar el porcentaje visible. Comprobar inicio, catálogo, detalle, ticket e historial: texto legible, controles alcanzables y sin contenido esencial cortado. Restaurar 100% al terminar. Achicar la ventana no equivale a esta prueba.
4. Ingresar con una cuenta ficticia USUARIO de la copia 8086. Las cuentas están en el registro privado storage/clean-20261009035638-598f411d/demo-credentials.json; no copiarlas a documentos, capturas o Git. Abrir Mis experiencias: la reserva del Encuentro de tecnología debe aparecer CANCELADA, con dos entradas; consultar su ticket y QR sin vigencia. No crear otra reserva para comprobar esa conservación.
5. Abrir /admin con ese usuario: debe rechazar acceso. Cerrar sesión y abrir la URL del ticket: debe pedir acceso. Ingresar como ADMINISTRADOR, abrir panel, categorías, eventos y reservas sin modificar datos. Cerrar sesión.
6. Comunicar versión, resultados y fallos con página/acción exacta. No enviar contraseñas ni capturas del archivo de cuentas. Guardar una captura del zoom 200% y del calendario cuando sea posible, con datos de demostración únicamente.

| Comprobación | Estado |
| --- | --- |
| Versión Firefox real | Firefox 157.0.1 (64 bits), Linux Mint; ventana Acerca de visible. |
| Catálogo y filtros | Búsqueda Encuentro y un resultado visibles. Limpieza y combinaciones pendientes. |
| Teclado/foco/calendario | Foco visible y calendario Desde abierto. Selección/cancelación y navegación solo con teclado pendientes. |
| Zoom 200% en flujos centrales | Indicador nativo 200%; catálogo y parte visible del detalle legibles. Historial y ticket legibles en las capturas de sus distintos tramos. Inicio pendiente. |
| Historial, ticket y QR | Historial con dos entradas CANCELADAS; ticket, QR y aviso sin vigencia visibles al 200%. No se probó lectura del QR con escáner. |
| Roles y logout | Acceso autenticado como Usuario de prueba visible. Rechazo de /admin, cierre de sesión y acceso posterior al ticket pendientes. |

La prueba Windows sigue su propio procedimiento en VERIFICACION-WINDOWS.md. Este recorrido no evalúa otra plataforma ni corrige avisos de bibliotecas.


Evidencia del 9 de octubre de 2026: cuatro capturas conservadas con huellas en storage/firefox-20261009. La búsqueda pertenece al formulario Eventify (q=Encuentro en URL y un resultado), no a Buscar en esta página. Calendario Desde visible completo dentro de la ventana y foco marcado. Esto no prueba cómo se abrió con teclado ni si la petición fue AJAX: una captura no observa su transporte. La copia 8086 corresponde a dev.33; PHP/CSS/JS de interfaz sin cambios en dev.34. No certificar por ello una instalación nueva o los flujos que faltan. Se observó el texto menor «1 eventos»; no impide el recorrido y no se cambió código durante la revisión.

Segunda evidencia del 9 de octubre: cinco capturas adicionales del historial y ticket, conservadas con huellas en el mismo registro privado (nueve archivos en total). La reserva cancelada conserva dos entradas y enlace al ticket. El QR se muestra completo y el aviso indica que el ticket ya no está vigente; se conserva el historial. Los tramos visibles al 200% son legibles. Esto acredita presentación y estado conservado, sin certificar el procedimiento de autenticación, escaneo del QR, bloqueo por rol o cierre de sesión. Se observa también el texto menor «1 entradas» en otra reserva de demostración; sin cambio funcional durante esta revisión.
