# Recorrido de Firefox y diseño adaptable

B07/C-06/C-07. Validación humana parcial con capturas aportadas por el usuario. La copia http://localhost:8086 ejecuta dev.43; instalación limpia y persistencia originales corresponden a dev.33. IAB dev.39 está comprobado; Firefox conserva los resultados parciales de la tabla siguiente. El 10 de octubre, el usuario eligió mantener solo el control de navegador disponible, que no expone Firefox, y no autorizó el ensayo alternativo con geckodriver. El caso permanece bloqueado por ese acceso. No repetir instalación, sembrar datos ni solicitar de nuevo la misma autorización sin cambios en las condiciones.

1. Abrir Firefox y consultar su versión en Ayuda/Acerca de Firefox. Abrir http://localhost:8086/eventos. Buscar Encuentro y limpiar filtros; comprobar un resultado y recuperación del catálogo, sin errores visibles.
2. Comprobar navegación con Tab y Shift+Tab: búsqueda, categoría, fechas, disponibilidad, estado y botón. El foco debe ser visible. Abrir los campos de fecha con teclado; comprobar selección/cancelación del calendario sin enviar un rango accidental.
3. Comprobar adaptación a móvil, tableta y escritorio: texto legible, controles alcanzables y sin desbordamiento horizontal. Zoom200% queda opcional por indicación del usuario; las capturas anteriores se conservan como evidencia adicional.
4. Ingresar con una cuenta ficticia USUARIO de la copia 8086. Las cuentas están en el registro privado storage/clean-20261009035638-598f411d/demo-credentials.json; no copiarlas a documentos, capturas o Git. Abrir Mis experiencias: la reserva del Encuentro de tecnología debe aparecer CANCELADA, con dos entradas; consultar su ticket y QR sin vigencia. No crear otra reserva para comprobar esa conservación.
5. Abrir /admin con ese usuario: debe rechazar acceso. Cerrar sesión y abrir la URL del ticket: debe pedir acceso. Ingresar como ADMINISTRADOR, abrir panel, categorías, eventos y reservas sin modificar datos. Cerrar sesión.
6. Comunicar versión, resultados y fallos con página/acción exacta. No enviar contraseñas ni capturas del archivo de cuentas. Puede conservar una captura del calendario con datos de demostración; el zoom200% es opcional.

| Comprobación | Estado |
| --- | --- |
| Versión Firefox real | Firefox 157.0.1 (64 bits), Linux Mint; ventana Acerca de visible. |
| Catálogo y filtros | Búsqueda Encuentro y un resultado visibles. Limpieza y combinaciones pendientes. |
| Teclado/foco/calendario | Foco visible y calendario Desde abierto. Selección/cancelación y navegación solo con teclado pendientes. |
| Zoom 200% en flujos centrales | Indicador nativo 200%; catálogo y parte visible del detalle legibles. Historial y ticket legibles en las capturas de sus distintos tramos. Inicio al200% no requerido para la entrega por decisión del usuario. |
| Historial, ticket y QR | Historial con dos entradas CANCELADAS; ticket, QR y aviso sin vigencia visibles al 200%. No se probó lectura del QR con escáner. |
| Roles y logout | Acceso autenticado como Usuario de prueba visible. Rechazo de /admin visible en captura del usuario sobre dev.33. Cierre de sesión y acceso posterior al ticket pendientes. |

La prueba Windows sigue su propio procedimiento en VERIFICACION-WINDOWS.md. Este recorrido no evalúa otra plataforma ni corrige avisos de bibliotecas.


Evidencia del 9 de octubre de 2026: cuatro capturas conservadas con huellas en storage/firefox-20261009. La búsqueda pertenece al formulario Eventify (q=Encuentro en URL y un resultado), no a Buscar en esta página. Calendario Desde visible completo dentro de la ventana y foco marcado. Esto no prueba cómo se abrió con teclado ni si la petición fue AJAX: una captura no observa su transporte. La copia 8086 corresponde a dev.33; PHP/CSS/JS de interfaz sin cambios en dev.34. No certificar por ello una instalación nueva o los flujos que faltan. Se observó el texto menor «1 eventos»; no impide el recorrido y no se cambió código durante la revisión.

Segunda evidencia del 9 de octubre: cinco capturas adicionales del historial y ticket, conservadas con huellas en el mismo registro privado (nueve archivos en total). La reserva cancelada conserva dos entradas y enlace al ticket. El QR se muestra completo y el aviso indica que el ticket ya no está vigente; se conserva el historial. Los tramos visibles al 200% son legibles. Esto acredita presentación y estado conservado, sin certificar el procedimiento de autenticación, escaneo del QR, bloqueo por rol o cierre de sesión. Se observa también el texto menor «1 entradas» en otra reserva de demostración; sin cambio funcional durante esta revisión.

La captura admin-denegado-original-200.png acredita el rechazo en Firefox, pero mostró texto sin diseño. Dev.35 sustituye esa presentación por access-denied.php con estilos existentes. Vista HTML estática revisada en IAB; la nueva respuesta PHP/403 y su presentación Firefox todavía requieren actualizar el contenedor y verificarlas. No atribuir la captura anterior a dev.35.

Captura posterior admin-denegado-dev35-110.png: nueva página de acceso restringido correctamente presentada en Firefox sobre8086, zoom nativo110%. El usuario confirma actualización ejecutada. Supera el pendiente visual dev.35 a ese zoom; cierre de sesión y nueva presentación dev.36 continúan pendientes.

Complemento HTTP dev.36 sobre8086:22 comprobaciones reales con sesiones independientes aprobadas, incluido ticket303a/ingresar traslogout,rol usuario403conCSS,panel administrador200yCSRF inválido403conCSS. Registro storage/dev36-http-check/resultados.json. Son pruebas de protocolo, no nuevas capturas Firefox ni evidencia de teclado/zoom.

Recorrido complementario IAB solicitado por el usuario: búsqueda/limpieza, focoTab/Shift+Tab,fecha escrita/borrada y calendarioEscape comprobados; adaptación390/640/1280sin desbordamiento en páginas observadas; roles y panel móvil revisados. storage/dev36-browser-check conserva capturas/medidas. No sustituye Firefox ni zoom nativo200%: el atajo de ampliación IAB no cambió las medidas del viewport.

## Continuación del 10 de octubre: pruebas restantes

El inventario de control volvió a mostrar Chrome e IAB, sin Firefox ni aplicaciones nativas disponibles. Se mantiene la decisión de no utilizar otra tecnología de automatización. La comprobación manual solicitada empieza por combinar nombre/categoría, limpiar filtros y escribir/borrar una fecha con foco Tab. Su resultado está pendiente; no se convierte en aprobado por haber enviado las instrucciones.

Registrar por separado, sin repetir los casos ya acreditados:

- Filtros: texto/categoría combinados, limpieza, fecha seleccionada y borrada, resultados y foco Tab/Shift+Tab.
- Perfil: ingresar como USUARIO, abrir Mi cuenta y volver al catálogo; no cambiar datos para comprobar la navegación.
- Logout/ticket: cerrar sesión y volver a la URL del ticket ya existente; debe pedir ingreso. No compartir el identificador o credenciales en capturas.
- Administración: ingresar como ADMINISTRADOR y abrir panel, categorías, eventos y reservas, sin guardar ediciones; cerrar sesión.

Anotar versión de Firefox, fecha, URL base, acción y resultado. Si hay un fallo, indicar el paso exacto y conservar una captura sin secretos. Estos resultados requieren Firefox real; los recorridos IAB anteriores no los sustituyen.
