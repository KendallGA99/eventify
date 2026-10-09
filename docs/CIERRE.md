# EVENTIFY: casos pendientes para la entrega

Corte: 8 de octubre de 2026, documentación dev.33; aplicación PHP equivalente a dev.30, MySQL con parche gawk comprobado. Este archivo organiza la aceptación que falta; ESTADO y las evidencias conservan lo ya ejecutado. Un caso se cierra con el resultado real y su versión, no por estar descrito aquí.

## Evidencia disponible

- Linux: ZIP dev.33 exacto en ruta con espacios y puerto8086; ambos roles, reserva, ticket/QR, cancelación e historial. Recreación posterior conserva diez tablas/cinco archivos, ocho reservas y ocho tickets. Restauración QA aislada comprobada en su referencia anterior.
- Aplicación: 219 comprobaciones MySQL/HTTP sobre imagen MySQL dev.33 corregida; imágenes PHP local/pública dev.32 con sintaxis,266 unidades y QR aprobados. Escaneo confirma parches xz/liblzma y gawk; conserva otros avisos.
- Publicación actual dev.30: actualización Render comprobada, diez tablas conservadas,81 controles HTTPS posteriores y pronóstico útil. Flujos de reserva/admin y reinicio también tienen evidencia en la versión pública anterior; no atribuir esos recorridos a una ejecución nueva.
- Interfaz: recorridos Chrome/IAB y tamaños registrados; registro a 390 px con Tab, foco visible y validación vacía. Esto no reemplaza la matriz de Firefox ni el registro válido completo.

## Casos que faltan

| Caso | Qué hacer | Criterio de cierre y evidencia |
| --- | --- | --- |
| C-01 Registro completo | Recorrido de navegador comprobado en copia limpia dev.28: confirmación distinta, alta válida, duplicado, ingreso incorrecto/correcto, administración por URL y logout. | Resultado correcto. Autenticación sin cambios entre dev.28 y dev.31; controles de servidor y hashes cubiertos por integración previa. No sustituye Firefox ni instalación del ZIP final. Evidencia en ESTADO y B07. |
| C-02 Catálogo y AJAX | Comprobados filtros combinados, vacío, limpieza, agotados, rango invertido, carga, cancelación de consulta anterior y desconexión controlada en IAB/dev.28. | Resultados y recuperación correctos; red restaurada. Código relacionado idéntico a dev.31. Evidencia en ESTADO/B07; no entrega forzada de callback antiguo ni prueba Firefox. |
| C-03 Reserva con error de transporte | Interrumpida entrega de respuesta201 en IAB/dev.28 después de guardar una reserva ficticia de1entrada. | Aviso de resultado desconocido, confirmar deshabilitado; historial con una reserva, ticket/QR conservados y cupos40→39. Interceptación retirada; evidencia en ESTADO/B07. |
| C-04 API y estados | Interfaz comprobada en IAB/dev.28 con respuestas de prueba: error, busy, horizonte, caché anterior, éxito, HTTP429 y timeout8000ms. | Mensajes y reintento correctos; reserva habilitada. Simulaciones retiradas, sin consumo de cuota. Evidencia real del proveedor separada; detalles en ESTADO/B07. |
| C-05 Administración | Comprobados aforo, precio, fecha/estado con historial, finalización futura, categoría duplicada, slug HTML inválido y permisos por URL en IAB/dev.28. | Rechazos claros; GET nuevo confirma valores conservados y detalle mantiene precio histórico. Sin escrituras válidas; evidencia y límites en ESTADO/B07. Carreras cubiertas por integración previa. |
| C-06 Teclado y zoom | Redistribución y teclado parciales comprobados en IAB: detalle, historial, ticket y panel administrativo. Catálogo revisado previamente. | Foco visible en controles recorridos; falta zoom real200%, calendario nativo. El atajo del navegador integrado no cambió escala. Ver ESTADO/B07. |
| C-07 Firefox | Ejecutar los flujos centrales y casos de error en Firefox real; procedimiento en VERIFICACION-FIREFOX.md. | Versión del navegador, tamaños, resultados y capturas propios. Firefox instalado o prueba en IAB no lo cumplen. |
| C-08 Windows | Instalar una copia limpia en host compatible, ruta con espacios; ambos roles, reserva/cancelación y recreación con datos conservados. | Versiones Windows/Docker/WSL y registros reales. Sintaxis PowerShell no equivale a ejecución. Conservar bases y volúmenes. |
| C-09 Avisos de imágenes | Revisar grupos aún no tratados, runtime/módulos y nuevas correcciones de distribución; probar cambios concretos. | Documentar condiciones, parche o justificación con límites; no ocultar avisos para obtener un reporte vacío. Parche gawk dev.33 construido, integración/TLS aprobados y tres avisos eliminados sin nuevos; AVISOS-IMAGENES detalla los grupos restantes y límites. Dev.34 aprobó imágenes finales, unidades y arranque público/TLS aislado; 32 paquetes retirados y escaneo 2.011 → 424 sin avisos nuevos. Riesgos restantes documentados en ENSAYO-RUNTIME y AVISOS-IMAGENES. |
| C-10 Versión desplegada | Aplicar la versión elegida al entorno de evaluación después de sus pruebas y comprobar salud, roles, reservas/API/persistencia. | URL sirve esa referencia comprobada; identificar versión y límites. Render actualizado a dev.30; controles posteriores y límites en ESTADO. Rollback de código no restaura MySQL. |
| C-11 Paquete final | Exportar la referencia elegida, verificar manifiesto/huellas y probar ese paquete en ambos sistemas. | ZIP recibido coincide con commit/versionado y funciona desde copia limpia. ZIP dev.33 exacto instalado en Linux y probado en navegador; persistencia posterior al nuevo flujo aprobada; falta Windows. Sin configuración privada ni datos personales. |
| C-12 Documento y defensa | Estudiante Kendall Gamboa Arce y docente Rita Robles Loaiza incorporados. Falta revisión PDF/manual y ensayo de explicación/modificación menor real. | Revisión y participación humanas registradas; acceso del evaluador y cuentas de prueba gestionados por separado. No atribuir dominio o autoría por generar el documento. |

## Orden de cierre

Por indicación del usuario: completar primero Firefox, teclado/zoom y seguridad; dejar Windows para el final. Después, elegir la referencia que se entregará y comprobar su publicación; exportar y probar el paquete definitivo; sincronizar PDF/manual con esa referencia y realizar el ensayo humano. Se puede avanzar en documentos mientras falta un equipo, conservando el estado de borrador.

El receptor usa Docker/Compose y cuentas locales de EVENTIFY. No necesita asociar Render, Aiven ni GitHub para ejecutar el paquete local. Internet se requiere para la descarga inicial y el pronóstico. Si se transfieren datos ya creados, el respaldo/restauración es un entregable separado del ZIP de código.
