# Revisión de experiencia de Eventify

9 de octubre de 2026. B07, dev.37. Mejora solicitada por el usuario: presentación terminada, navegación clara, controles de contraseña y perfil útil. Es una ampliación de experiencia de uso; no una nueva exigencia del enunciado académico. Windows queda para el final.

## Cambios

| Área | Comportamiento |
| --- | --- |
| Registro e ingreso | Cada contraseña tiene su propio botón para mostrar/ocultar, etiqueta accesible y estado anunciado. Sin JavaScript sigue siendo un campo de contraseña usable; el botón no se muestra. No se guarda la contraseña en almacenamiento del navegador ni se envía al alternar. |
| Después de ingresar | Redirección fija al inicio, sin destinos enviados por el cliente. La sesión se regenera como antes. |
| Navegación | Visitante: eventos, ingresar y crear cuenta. Sesión abierta: eventos, experiencias y cuenta; administración solo para ese rol. El servidor sigue comprobando permisos. |
| Inicio | Mensaje definitivo y acceso al catálogo; eliminado el aviso de preparación. La navegación refleja la sesión actual. |
| Perfil | Editar nombre completo, conservar correo como dato de acceso, consultar reservas y cerrar sesión. Administrador tiene acceso al panel. |
| Seguridad de perfil | POST con CSRF, identidad de sesión y nombre validado en servidor. UPDATE preparado; campos id, email y role enviados por cliente no intervienen. Solo se actualiza full_name de una cuenta activa. Sin migraciones. |
| Presentación | Tarjetas, separación de secciones y botones coherentes. Transiciones cortas y entrada suave; movimiento desactivado con prefers-reduced-motion. Sin carruseles o dependencias añadidas. |
| Textos | Singular/plural en entradas de historial/ticket y contador de resultados AJAX. Se mantienen avisos reales de cancelación, falta de cupos y ticket sin vigencia. |

No se agregan botones ficticios de recuperación o cambio de correo/contraseña: necesitarían un flujo propio de verificación. El perfil permite la edición real del nombre solicitada; no promete ajustes inexistentes.

## Revisión y evidencia

- Revisión de código de inicio, autenticación, perfil, catálogo, detalles, reservas, ticket, administración y respuestas de error. Las pruebas previas de esos flujos conservan su referencia; no se atribuyen automáticamente a dev.37.
- Sintaxis de todos los PHP con CLI8.3 extraído del paquete oficial Ubuntu; registro/CSRF23 y ErrorPage7 comprobaciones correctas. JavaScript ui.js pasa node --check.
- Vistas reales renderizadas por PHP con datos ficticios a archivos de revisión: registro, inicio visitante, inicio con sesión y perfil. En IAB a390/768/1280px, doce mediciones sin desbordamiento horizontal. Capturas privadas en storage/dev37-preview, revisadas visualmente. Estas páginas son una vista previa y no prueban guardado en MySQL.
- En esa vista previa, ambos ojos alternan solo su propio campo, actualizan aria-pressed/etiqueta y no envían el formulario. Contraseña de ejemplo retirada antes de capturar.
- Integración HTTP/MySQL ampliada para redirección al inicio, navegación autenticada, CSRF de perfil, nombre malformado, actualización válida e intento de cambiar propietario/correo/rol. Preparada; no ejecutada todavía en Docker.
- Helper privado storage/dev37-review/verify-linux.sh: integración con MySQL efímero antes de actualizar solo web8086; después sintaxis, registro/CSRF, ErrorPage y salud. Conserva DB, volúmenes y datos del destino. Se requiere terminal con acceso Docker.

## Pendientes para aceptar esta versión

Ejecutar el helper y revisar su resultado; después probar en la instalación actualizada el ingreso al inicio, nombre guardado y conservado tras nueva sesión, ambos roles, botones de contraseña y diseño adaptable. Usar datos ficticios y restaurar el nombre de prueba después. Verificar estabilidad de reservas y estado de tickets sin crear otras reservas innecesarias. Actualizar paquete, PDF y publicación con la referencia elegida después de aceptar los cambios. Seguridad residual y Windows conservan sus requisitos separados; no se marca estable por esta revisión visual.

## Resultado posterior de aceptación local

Segundo helper sobre ab710a6:exit0,225 controles MySQL/HTTP,sintaxis PHP8.4 y30registro/errores correctos. Web8086 actualizada. En navegador real se comprobaron ojos,ingreso alinicio,perfil guardado ypersistente trasnuevoingreso; nombreoriginal restaurado. Perfil390/768/1280sin desbordamiento; historial ypanel disponibles,ambas sesiones cerradas. Registros storage/dev37-review ystorage/dev37-live. El pendiente de integración local anterior queda superado; nueva instalación limpia,publicación,seguridad residual yWindows no se dan por comprobados.
