# EVENTIFY: casos pendientes para la entrega

Corte: 10 de octubre de 2026, aplicación local y pública dev.43; MySQL local con corrección dev.41. La referencia exacta del paquete documental y sus huellas se registran en ESTADO.md. Este archivo distingue trabajo terminado, verificaciones bloqueadas y actividades humanas. Las pruebas de versiones previas conservan su alcance; no se repiten sin un cambio o fallo que lo justifique.

## Evidencia disponible

- Linux: ZIP dev.33 exacto en ruta con espacios y puerto8086; ambos roles, reserva, ticket/QR, cancelación e historial. Recreación posterior conserva diez tablas/cinco archivos, ocho reservas y ocho tickets. Restauración QA aislada comprobada en su referencia anterior.
- Aplicación: 219 comprobaciones MySQL/HTTP sobre imagen MySQL dev.33 corregida; imágenes PHP local/pública dev.32 con sintaxis,266 unidades y QR aprobados. Escaneo confirma parches xz/liblzma y gawk; conserva otros avisos.
- Publicación histórica dev.39: Render Live en 0e3aae2; diez tablas conservadas, 81 controles HTTPS de usuarios y 36 de administración aprobados. Recorrido IAB de ingreso administrativo, panel, reserva existente, logout y catálogo. El pronóstico conserva evidencia útil anterior y manejo de indisponibilidad; no se atribuye un código upstream al último aviso sin diagnóstico.
- Experiencia dev.37: ojos independientes, login al inicio, perfil con nombre persistente y navegación por rol comprobados en8086. Nombre ficticio restaurado y sesiones cerradas; sin nuevas reservas. Documento técnico dev.41 de 18 páginas actualizado y revisado; incorpora la restricción de privilegios y las guías de defensa.
- Interfaz: recorridos Chrome/IAB y tamaños registrados; registro a 390 px con Tab, foco visible y validación vacía. Esto no reemplaza la matriz de Firefox ni el registro válido completo.

- MySQL dev.38: imagen final9321f68 con225 controles y TLS positivo/negativo aprobados;47 avisos restantes frente a66, sin nuevos ni exclusiones. Instalaciones existentes y Aiven conservados. Evidencia en ESTADO y ENSAYO-MYSQL-MINIMAL.

## Casos que faltan

| Caso | Qué hacer | Criterio de cierre y evidencia |
| --- | --- | --- |
| C-01 Registro completo | Recorrido de navegador comprobado en copia limpia dev.28: confirmación distinta, alta válida, duplicado, ingreso incorrecto/correcto, administración por URL y logout. | Resultado correcto. Autenticación sin cambios entre dev.28 y dev.31; controles de servidor y hashes cubiertos por integración previa. No sustituye Firefox ni instalación del ZIP final. Evidencia en ESTADO y B07. |
| C-02 Catálogo y AJAX | Comprobados filtros combinados, vacío, limpieza, agotados, rango invertido, carga, cancelación de consulta anterior y desconexión controlada en IAB/dev.28. | Resultados y recuperación correctos; red restaurada. Código relacionado idéntico a dev.31. Evidencia en ESTADO/B07; no entrega forzada de callback antiguo ni prueba Firefox. |
| C-03 Reserva con error de transporte | Interrumpida entrega de respuesta201 en IAB/dev.28 después de guardar una reserva ficticia de1entrada. | Aviso de resultado desconocido, confirmar deshabilitado; historial con una reserva, ticket/QR conservados y cupos40→39. Interceptación retirada; evidencia en ESTADO/B07. |
| C-04 API y estados | Interfaz comprobada en IAB/dev.28 con respuestas de prueba: error, busy, horizonte, caché anterior, éxito, HTTP429 y timeout8000ms. | Mensajes y reintento correctos; reserva habilitada. Simulaciones retiradas, sin consumo de cuota. Evidencia real del proveedor separada; detalles en ESTADO/B07. |
| C-05 Administración | Comprobados aforo, precio, fecha/estado con historial, finalización futura, categoría duplicada, slug HTML inválido y permisos por URL en IAB/dev.28. | Rechazos claros; GET nuevo confirma valores conservados y detalle mantiene precio histórico. Sin escrituras válidas; evidencia y límites en ESTADO/B07. Carreras cubiertas por integración previa. |
| C-06 Diseño adaptable y teclado | Redistribución y foco comprobados en los recorridos registrados. Dev.36: inicio/catálogo/ingreso/error405 a390/640/1280; panel/categorías/eventos/reservas y rechazo a390; búsqueda/limpieza y calendario abre/cierra conEscape. | Sin desbordamiento de página en los tamaños observados; focoTab/Shift+Tab entre búsqueda/categoría. Evidencia IAB en ESTADO/B07. Zoom200% retirado como condición de entrega por el usuario; no certifica todos los controles solo con teclado. |
| C-07 Firefox | Bloqueado por acceso: el control disponible expone Chrome/IAB, no Firefox. El usuario eligió mantener ese control y no autorizó el ensayo alternativo con geckodriver el 10 de octubre. | Capturas humanas previas acreditan solo sus casos. IAB dev.39 está terminado, pero no sustituye Firefox. Cerrar únicamente con evidencia Firefox real o un cambio explícito del requisito. |
| C-08 Windows | Instalar una copia limpia en host compatible, ruta con espacios; ambos roles, reserva/cancelación y recreación con datos conservados. | Versiones Windows/Docker/WSL y registros reales. Sintaxis PowerShell no equivale a ejecución. Conservar bases y volúmenes. |
| C-09 Avisos de imágenes | Corrección dev.41 comprobada: web local/pública y MySQL con cero avisos, base Trivy10oct12:33UTC, sin exclusiones. | 234 controles, unidades, TLS válido/CA/hostname/sinTLS, recreación y NoNewPrivs aprobados. Runtime cambiado a Alpine para la web; OpenSSL/gosu corregidos en MySQL. Ver RUNTIME-CORREGIDO y ESTADO. No certifica host, proveedor o ausencia de fallos futuros. |
| C-10 Versión desplegada | Render Free sirve dev.43 desde f9eac94, deploy dep-db57le7lk1mc7391a8u0 confirmado Live. | Dev.43: revisión de clima real en móvil/escritorio. Integración234 y HTTPS81 corresponden a dev.42; conservación de tablas con sus límites en ESTADO. Dev.42 disponible para rollback; la reversión del código no restaura MySQL. |
| C-11 Paquete final | Exportar la referencia elegida, verificar manifiesto/huellas y probar ese paquete en ambos sistemas. | ZIP recibido coincide con commit/versionado y funciona desde copia limpia. La exportación de código y PDF se sincroniza con dev.43; su referencia, revisión e integridad se registran en ESTADO. Hasta comprobar el paquete elegido en Windows, este caso permanece abierto. El usuario indicó no repetir instalación Linux; no se presenta esa decisión como una instalación nueva. ZIP dev.33 exacto instalado en Linux y probado en navegador; persistencia posterior al nuevo flujo aprobada; falta Windows. Sin configuración privada ni datos personales. |
| C-12 Documento y defensa | Estudiante Kendall Gamboa Arce y docente Rita Robles Loaiza incorporados. Documento y manual sincronizados con dev.43; resultado de revisión visual en ESTADO. Falta revisión del receptor y ensayo humano de explicación/modificación menor real. | Revisión y participación humanas registradas; acceso del evaluador y cuentas de prueba gestionados por separado. No atribuir dominio o autoría por generar el documento. |

## Orden de cierre

Por indicación del usuario: priorizar diseño adaptable, compatibilidad de navegador y seguridad; dejar Windows para el final. Zoom200% es una comprobación opcional y deja de bloquear la entrega. Después, elegir la referencia que se entregará y comprobar su publicación; exportar y probar el paquete definitivo; sincronizar PDF/manual con esa referencia y realizar el ensayo humano. Se puede avanzar en documentos mientras falta un equipo, conservando el estado de borrador.

El receptor usa Docker/Compose y cuentas locales de EVENTIFY. No necesita asociar Render, Aiven ni GitHub para ejecutar el paquete local. Internet se requiere para la descarga inicial y el pronóstico. Si se transfieren datos ya creados, el respaldo/restauración es un entregable separado del ZIP de código.


## Situación al solicitar dejar únicamente Windows pendiente

- C-01 a C-06: evidencia existente conservada; recorrido autónomo IAB dev.39 terminado. No pedir al usuario que repita lo comprobado en ese navegador.
- C-10: publicación dev.39 comprobada. C-11: código dev.41, manuales y PDF preparados; referencia de exportación e integridad en ESTADO; su aceptación Windows sigue pendiente junto con C-08.
- C-07: requiere Firefox real. El usuario eligió mantener solo el control de navegador disponible tras explicarse su limitación. No se instaló geckodriver ni se utilizó un mecanismo alternativo.
- C-09: cerrado para las imágenes dev.41 verificadas y los avisos conocidos de ese corte. Los informes anteriores siguen como evidencia histórica; no describen la nueva base. Referencia y límites en RUNTIME-CORREGIDO.md.
- C-12: la documentación está preparada; la participación, comprensión y defensa corresponden al estudiante y no pueden certificarse automáticamente.

Por estas condiciones todavía no es correcto afirmar que solo falta Windows. Las tareas técnicas completadas permanecen cerradas; no se introducen nuevos requisitos ni se convierten límites del proveedor en trabajo de implementación ficticio. Continuar cuando exista una corrección compatible, evidencia de Firefox o una decisión explícita de alcance/riesgo del responsable.


Dev.40 añade no-new-privileges en Compose, con integración225 y Apache/HTTP locales comprobados. Es una restricción adicional del runtime local; no corrige los avisos C-09 ni cambia Render. La revisión documental no repite pruebas Linux ni atribuye resultados a Firefox o Windows.
