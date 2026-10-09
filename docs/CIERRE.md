# EVENTIFY: casos pendientes para la entrega

Corte: 8 de octubre de 2026, documentación dev.31; aplicación equivalente a dev.30. Este archivo organiza la aceptación que falta; ESTADO y las evidencias conservan lo ya ejecutado. Un caso se cierra con el resultado real y su versión, no por estar descrito aquí.

## Evidencia disponible

- Linux: ZIP dev.28 en ruta con espacios; login de ambos roles, reserva, ticket/QR, cancelación, historial y persistencia después de recrear web/MySQL. Restauración aislada de diez tablas y archivos comprobada.
- Aplicación: 219 comprobaciones MySQL/HTTP; imágenes local/pública dev.30 con sintaxis, 266 unidades y QR aprobados. El escaneo confirma el parche xz/liblzma, pero conserva otros avisos.
- Publicación dev.26: URL HTTPS, TLS MySQL, roles, reserva/ticket/cancelación, reinicio con datos conservados, 81 controles HTTPS y pronóstico útil tras un fallo 429 gestionado.
- Interfaz: recorridos Chrome/IAB y tamaños registrados; registro a 390 px con Tab, foco visible y validación vacía. Esto no reemplaza la matriz de Firefox ni el registro válido completo.

## Casos que faltan

| Caso | Qué hacer | Criterio de cierre y evidencia |
| --- | --- | --- |
| C-01 Registro completo | Recorrido de navegador comprobado en copia limpia dev.28: confirmación distinta, alta válida, duplicado, ingreso incorrecto/correcto, administración por URL y logout. | Resultado correcto. Autenticación sin cambios entre dev.28 y dev.31; controles de servidor y hashes cubiertos por integración previa. No sustituye Firefox ni instalación del ZIP final. Evidencia en ESTADO y B07. |
| C-02 Catálogo y AJAX | Comprobados filtros combinados, vacío, limpieza, agotados, rango invertido, carga, cancelación de consulta anterior y desconexión controlada en IAB/dev.28. | Resultados y recuperación correctos; red restaurada. Código relacionado idéntico a dev.31. Evidencia en ESTADO/B07; no entrega forzada de callback antiguo ni prueba Firefox. |
| C-03 Reserva con error de transporte | Interrumpida entrega de respuesta201 en IAB/dev.28 después de guardar una reserva ficticia de1entrada. | Aviso de resultado desconocido, confirmar deshabilitado; historial con una reserva, ticket/QR conservados y cupos40→39. Interceptación retirada; evidencia en ESTADO/B07. |
| C-04 API y estados | Interfaz comprobada en IAB/dev.28 con respuestas de prueba: error, busy, horizonte, caché anterior, éxito, HTTP429 y timeout8000ms. | Mensajes y reintento correctos; reserva habilitada. Simulaciones retiradas, sin consumo de cuota. Evidencia real del proveedor separada; detalles en ESTADO/B07. |
| C-05 Administración | Comprobados aforo, precio, fecha/estado con historial, finalización futura, categoría duplicada, slug HTML inválido y permisos por URL en IAB/dev.28. | Rechazos claros; GET nuevo confirma valores conservados y detalle mantiene precio histórico. Sin escrituras válidas; evidencia y límites en ESTADO/B07. Carreras cubiertas por integración previa. |
| C-06 Teclado y zoom | Recorrer catálogo, detalle, reserva, historial y panel; revisar 200 % de zoom y tamaños de la matriz. | Foco visible, orden usable, acciones accesibles y sin recortes/desbordamiento. El caso del formulario de registro no certifica todas las vistas. |
| C-07 Firefox | Ejecutar los flujos centrales y casos de error en Firefox real. | Versión del navegador, tamaños, resultados y capturas propios. Firefox instalado o prueba en IAB no lo cumplen. |
| C-08 Windows | Instalar una copia limpia en host compatible, ruta con espacios; ambos roles, reserva/cancelación y recreación con datos conservados. | Versiones Windows/Docker/WSL y registros reales. Sintaxis PowerShell no equivale a ejecución. Conservar bases y volúmenes. |
| C-09 Avisos de imágenes | Revisar grupos aún no tratados, runtime/módulos y nuevas correcciones de distribución; probar cambios concretos. | Documentar condiciones, parche o justificación con límites; no ocultar avisos para obtener un reporte vacío. AVISOS-IMAGENES detalla la revisión parcial. |
| C-10 Versión desplegada | Aplicar la versión elegida al entorno de evaluación después de sus pruebas y comprobar salud, roles, reservas/API/persistencia. | URL sirve esa referencia comprobada; identificar versión y límites. Hoy Render ejecuta dev.26, no dev.30. Rollback de código no restaura MySQL. |
| C-11 Paquete final | Exportar la referencia elegida, verificar manifiesto/huellas y probar ese paquete en ambos sistemas. | ZIP recibido coincide con commit/versionado y funciona desde copia limpia. ZIP dev.28 probado no certifica automáticamente otro ZIP. Sin configuración privada ni datos personales. |
| C-12 Documento y defensa | Completar datos de estudiante/docente, revisar PDF/manual, ensayar explicación y una modificación menor real. | Revisión y participación humanas registradas; acceso del evaluador y cuentas de prueba gestionados por separado. No atribuir dominio o autoría por generar el documento. |

## Orden de cierre

Completar casos de interfaz y seguridad; verificar Firefox y Windows; elegir la referencia que se entregará y comprobar su publicación; exportar y probar el paquete definitivo; sincronizar PDF/manual con esa referencia y realizar el ensayo humano. Se puede avanzar en documentos mientras falta un equipo, conservando el estado de borrador.

El receptor usa Docker/Compose y cuentas locales de EVENTIFY. No necesita asociar Render, Aiven ni GitHub para ejecutar el paquete local. Internet se requiere para la descarga inicial y el pronóstico. Si se transfieren datos ya creados, el respaldo/restauración es un entregable separado del ZIP de código.
