# EVENTIFY: estado actual y continuidad

Corte: 8 de octubre de 2026, America/Costa_Rica. Resumen operativo; [HISTORIAL.md](HISTORIAL.md) conserva los registros anteriores íntegros. Leer este archivo, el bloque activo de PLAN y los requisitos afectados al retomar. Los resultados siguientes tienen versiones y alcances distintos; no sumarlos como una sola suite.

## Alcance y autorizaciones

- Aplicación académica PHP/MySQL, HTML/CSS/JS/jQuery/AJAX y sesiones. Desarrollo Linux; entrega local Docker Compose Linux/Windows. No modificar Código Tico.
- Commits y push progresivos autorizados el 2026-10-08; repositorio privado [USUARIO_DESTINO/eventify](https://github.com/USUARIO_DESTINO/eventify), remoto SSH. Sin push forzado ni reescritura.
- Publicación Render Free/Aiven MySQL Free autorizada expresamente: integración GitHub solo EVENTIFY, presupuesto ₡0, sin tarjeta ni planes pagados. No necesita nuevas cuentas de proveedores el receptor del paquete local.
- Estudiante confirmado: Kendall Gamboa Arce. Docente confirmada: Rita Robles Loaiza. La fecha de entrega se omite por indicación del usuario. Defensa y comprensión humanas pendientes.

## Versiones y destinos

- Código/documentación actual: **0.1.0-dev.33**, rama `codex/b07-avisos-restantes`, desde `6a8443b`. Aplicación PHP igual a dev.30; dev.33 tiene parche gawk de MySQL construido, integración/TLS y escaneo comprobados; las instalaciones existentes conservan su versión anterior. Ninguna versión estable.
- `main` conservada: `3da680b74b41fa3da4069879edc1f649de9b97f2`. VERSIONADO contiene la política y referencias anteriores. Git recupera código; no restaura MySQL.
- Web pública: https://eventify-academico.onrender.com, **dev.30**, commit `390b1ef6dcbb9a9d76eaad8a910a80da364bb637`. Render `srv-db3vutm7bikc73ajj6og`, Free/Oregon/AutoDeployOff; misma rama de despliegue existente.
- Último deploy: `dep-db44q9cs728c739ikr20`, 8 de octubre, 20:10:45 CST, **Deploy succeeded/Live**, 35 s. Referencia anterior `cabd6f3` conservada.
- Copia limpia actual: `localhost:8085`, dev.32 `1dd0ed8dec5a15db82c9543a4ce803b492c52915`, proyecto `eventify-clean-20261009022129-10f84d13`. Dev.28/8084 conservada.
- Otros destinos conservados según última verificación anterior: normal8080, QA8081, restauración8082, copia limpia8083. No afirmar disponibilidad actual sin consultar. No detenerlos ni borrar volúmenes para reutilizar puertos.
- Docker29.1.3/Compose2.40.3. Esta sesión no tiene acceso al socket; sudo no interactivo tampoco lo resuelve. No cambiar grupos/socket/sudoers. Operaciones Docker necesarias se ejecutan en terminal del usuario.
- Configuración, cuentas, CA, registros y snapshots privados en storage/backups, ignorados por Git. Nunca imprimir credenciales ni incluirlos en el ZIP.

## Evidencia vigente

| Área | Resultado comprobado | Límite |
| --- | --- | --- |
| Imágenes dev.32 | Ambas construidas, sintaxis PHP8.4, 266 comprobaciones unitarias y QR; xz/liblzma corregido y módulos Apache revisados ausentes en ejecución. image-check-ERw4lEwZ, exit0. | Escaneo conserva145HIGH/2CRITICAL por imagen; revisión no cerrada. |
| MySQL/HTTP dev.33 | 219 comprobaciones sobre imagen MySQL corregida, tres migraciones; concurrencia de reserva, administración y presupuesto meteorológico. | No certifica Windows ni toda la publicación. |
| Copia limpia Linux dev.32 | ZIP exacto, ruta con espacios, migraciones y persistencia inicial; ambos roles, reserva/ticket/QR, cancelación e historial comprobados en8085. clean-20261009022129-10f84d13, exit0. | Recreación posterior al flujo y consulta de historial/ticket aprobadas; Windows pendiente. |
| Restauración | Diez tablas y archivos comparados en destino aislado; registros privados conservados. | No prueba toda transferencia a Windows. |
| Publicación dev.30 | Parche construido en Render; sintaxis, 39 pruebas de clima y44 de presupuesto en build. Snapshots antes/después idénticos en diez tablas. | No incluye nueva reserva/admin en esta ejecución. |
| HTTPS dev.30 | 81 controles posteriores correctos: dos usuarios, sesiones, CSRF, rutas privadas y tickets ajenos; sin escrituras de negocio. | No es auditoría universal ni revisión completa de imágenes. |
| API pública dev.30 | Pronóstico útil real después del deploy: 17,3–24,1°C/100% para evento ficticio del12oct; consultado8oct20:13CST. | Puede variar o fallar; no garantiza disponibilidad permanente. |
| Dependencias/secretos | Composer sin avisos, jQuery verificado y505 blobs sin secretos conocidos en ejecución anterior. | No prueba ausencia de avisos futuros o secretos desconocidos. |
| Windows | PowerShell5.1 real: seis scripts sin errores de parser. VM Win11 LTSC Evaluation identificada,4GiB. | Sin Docker/WSL ni ejecución completa; hace falta host compatible. |
| Documentación | PDF dev.33 actualizado con el resultado del parche; portada y evidencia renderizadas/revisadas, nombres confirmados y texto dentro de página. | Borrador: revisión y defensa humanas pendientes. |

Snapshots de publicación: `storage/aiven-evaluacion/snapshot-before-dev30.json` y `snapshot-after-dev30.json`, comparados antes de los logins de prueba. Capturas revisadas `/tmp/eventify-render-dev30.png` y `/tmp/eventify-publico-dev30.png`. No se cambiaron permisos, plan, base ni cuentas externas durante el deploy. Detalles en evidencias/B08.

## Aceptación de interfaz

C-01 a C-05 comprobados en IAB sobre dev.28; archivos de aplicación afectados sin diferencias respecto a la versión posterior. Detalles y límites en [evidencias/B07.md](evidencias/B07.md):

- Registro: confirmación distinta, alta válida ficticia, duplicado, login incorrecto/correcto, permisos por URL y logout.
- Catálogo: filtros combinados, vacío, fechas invertidas, carga, cancelación de consulta pendiente, desconexión de pestaña y recuperación.
- Reserva: una sola respuesta201 perdida después del commit; confirmar deshabilitado, consulta de historial/ticket y cupos40→39. Reserva ficticia conservada; no repetirla.
- Clima: fixtures locales de error/busy/horizonte/caché/éxito/HTTP429/timeout8s; sin llamadas al proveedor. Patrones retirados. Evidencia real pública separada.
- Administración: aforo bajo reservas, precio negativo, cambio de fecha/estado con historial, finalización futura, categoría duplicada y permisos ajenos; valores conservados.
- C-06 parcial: redistribución a390/768/1280/640px; foco visible y recorrido de detalle/reserva, historial y ticket. Una captura deformada del historial se descartó mediante observación directa; no se cambió CSS. Atajo de zoom IAB no modificó escala: **no certifica zoom200%**. Panel administrativo también recorrido con foco visible; faltan calendario nativo y zoom real.

Incidencia anterior de inspección: se imprimieron por error credenciales ficticias de la copia local; no se copiaron a Git/documentación. Detalle conservado en B07/HISTORIAL. No confundirlas con cuentas externas.

## Bloques y pendientes reales

- B01/B02 comprobados; B03–B06 implementados con integración y aceptación parcial de navegador.
- B07 abierto: Firefox real, Windows, calendario/zoom de C-06 y revisión de avisos de imágenes. [AVISOS-IMAGENES.md](AVISOS-IMAGENES.md) conserva condiciones y dudas: Apache/XML/kernel/curl/gosu/Python; no ocultar avisos. No repetir suites extensas sin cambio o duda concreta.
- B08: servicio real actualizado a dev.30; TLS, controles HTTPS, datos conservados y API útil. C-10 documentado para esa referencia, no para toda variante ni un paquete distinto.
- B09: portada/manual/PDF y exportación de desarrollo dev.32 preparados; instalación final, receptor y ensayo humano pendientes. [CIERRE.md](CIERRE.md) organiza los casos; no marcar estable por tener un ZIP.

## Próxima acción necesaria

Dev.32 aprobó construcción de imágenes, instalación limpia Linux y persistencia posterior al recorrido del navegador. Ya no hace falta repetir los dos ensayos ni el helper para esta referencia. Resultado `storage/dev32-persistence-UMW4MUkC`, inicio20:28:23CST/exit0: snapshots idénticos de diez tablas y cinco archivos, ocho reservas y ocho tickets. Login posterior confirma historialCANCELADA, ticket/QR y datos conservados. Captura /tmp/eventify-dev32-persistencia.png revisada.

Continuar Firefox y zoom real, revisión de avisos de imágenes y Windows en equipo compatible. Disponibilidad de Windows/Docker Desktop pendiente de confirmar. La fecha de entrega no es necesaria y deja de ser un pendiente. Defensa/participación humanas abiertas. No certificar entrega completa ni estabilidad por haber aprobado Linux.

## Exportación dev.32 confirmada

Etiqueta v0.1.0-dev.32 y rama publicadas; commit1dd0ed8dec5a15db82c9543a4ce803b492c52915, etiqueta anotada0c0c1b1ff0a8f3359a137a91083b1f6f19482734; refs remotas verificadas. Fuente en output/entrega/eventify-desarrollo-1dd0ed8dec5a/eventify-source.zip,188 archivos, SHA25677157fa2ef2e419fa8a234daeef2e196514eab1f5ddac3248d7ee20af436d8e9. Paquete de revisión con ese ZIP, PDF, guía, manifiesto y huellas: output/entrega/eventify-dev32-revision.zip, SHA25675a91451ca8e3f92272e5666b355bfe6d0ba83b8bee971fca51b8982efeaee18. Integridad/CRC comprobados; no instalación nueva ni entrega final certificada. PDF15 páginas con nombres/versión correctos, portada/evidencia revisadas y texto dentro de página. Registro posterior a la etiqueta solo documenta resultados, no cambia el paquete.

Ampliación C-06: panel administrativo dev.28 a390px, ocho enlaces recorridos mediante Tab y contorno3px visible; seis tarjetas327px y scrollWidth375 sobre viewport390. Captura /tmp/eventify-admin-teclado-dev28.png revisada. Sin formularios enviados ni cambios de negocio; sesión cerrada al finalizar.

Nuevo recorrido dev.32: reserva ficticia f5e075eec15b3bd971c1d487078aae79 cancelada y conservada; cupos40→38→40, ticket/QR sin vigencia. Ambos roles y logout correctos. Evidencia y límites en B07.

Intento de zoom en Chrome real (conector disponible): Ctrl+plus no cambió innerWidth1920/devicePixelRatio1/visualViewport.scale1. No se certifica200%. Inventario actual expone Chrome e IAB, no Firefox. Ensayo Firefox/zoom requiere interacción directa del usuario o un acceso compatible; no sustituirlo por otra ejecución IAB.

Paquete con fuente idéntica a la probada en Linux, PDF actualizado y resultados posteriores: output/entrega/eventify-dev32-linux-comprobado.zip, SHA256 83441a710cb0fdfd36c70a8b46b3681036cb9c79e23de41e72559e04d7dac18b. Se conservó el ZIP fuente188 archivos y su manifiesto originales; los documentos adjuntos explican que el estado interno de ese ZIP es del corte anterior. Integridad/CRC correctos. No es certificación Windows ni versión estable.

## Revisión de seguridad dev.33

Fecha de entrega omitida por indicación del usuario. Análisis exacto gosu amd64 del digest MySQL: capas y huella verificadas, binario oficial1.19/Go1.24.6; govulncheck1.8.0 sin exclusiones, ninguna función vulnerable detectada. Conserva57 coincidencias de módulo/paquete y47 filas Trivy; no auditoría de MySQL completo. ACL/ncurses/montajes/XML/Python contrastados; inventario completo por paquete en INVENTARIO-IMAGENES. Detalles, fuentes y evidencia privada en AVISOS-IMAGENES.

Oracle publica parche compatible para los tres avisos gawk. Dockerfile.mysql y cuatro Compose preparados, servidor/volúmenes sin cambiar. Ensayo scripts/test-mysql-image-linux.sh exige versión RPM y prueba integración, TLS correcto y rechazo sin TLS en destinos efímeros. Bash-n y git diff --check correctos; cuatro configuraciones Compose examinadas sin daemon: build correcto, MySQL sin puertos/privilegios y ensayos con tmpfs/sin volúmenes persistentes. PDF dev.33 de15 páginas: portada y ampliación revisadas, texto dentro de página. Construcción real y escaneo pendientes de ejecución Docker en terminal. No actualizar8080/8085 ni marcar dev.33 estable. Paquetes dev.32 anteriores conservan su evidencia e integridad originales.

Dev.33 publicada y referencias verificadas con git ls-remote: commit2f5ab7f5735eb7a4e58ff725d14f5ae9ea8afc03, etiqueta anotada b2ca86ad5474146423e752b793e6afd443d4f5d9, rama codex/b07-avisos-restantes. Etiqueta resuelta al mismo commit. Sin despliegue, construcción real todavía pendiente; main intacta.

## Parche gawk dev.33 comprobado

mysql-image-check-gpZ3It99, inicio8oct20:52:50CST, commit3489d430bf94c649c7bf7e61d6fb0e95fb531a33, árbol limpio, exit0. gawk5.1.0-6.el9_8.1 y servidor8.4.11. Integración219 controles correctos; public-tls-w378H7sm y public-no-tls-dKB7YKgK exit0, controles TLS/cuentas/recreación y rechazo/base vacía aprobados. Destinos efímeros retirados; instalaciones normales/copias existentes y Aiven conservadas.

Trivy0.75.0 sobre imagen exportada:69→66 avisos, desaparecen únicamente los tres gawk, ningún identificador/paquete nuevo con la misma DB. Restantes1CRITICAL/29HIGH/29MEDIUM/6LOW/1UNKNOWN, sin exclusiones. El pendiente de construcción/regresión/escaneo anterior queda superado para esta referencia. Comparación privada mysql-scan.json/comparison.json. Curl ampliado con condiciones y diferencias de severidad del proveedor en AVISOS-IMAGENES; no se modifican reportes. No hace falta repetir el ensayo del parche por esta actualización documental. Siguen pendientes los grupos no evaluados, Windows/Firefox/zoom y el paquete final; dev.33 no es estable.

PDF actualizado:15 páginas, resultado dev.33 y límites correctos; ampliación de seguridad renderizada/revisada, texto dentro de página. La actualización documental no cambia imágenes ni requiere repetir integración.


## Ampliación de avisos base

Revisión adicional documentada en AVISOS-BASE: 24 identificadores y 66 filas cotejados con el escaneo conservado (60 MEDIUM / 6 UNKNOWN). glibc: modos fopen fijos, conversiones/wordexp/DNS de depuración sin ruta directa encontrada, nscd ausente del inventario; limitaciones de llamadas nativas conservadas. zlib/Expat: discrepancias de rangos no resueltas; sin cambiar severidades. SQLite no es base usada por EVENTIFY; avisos dependen de su Session Extension, distinta de las sesiones PHP. Pod::Text/kernel sin severidad examinados sin declararlos inocuos.

Inspección estática de once ELF setuid/setgid en la imagen local dev.32: ninguno tiene RPATH/RUNPATH, configuración de imagen sin LOCALDOMAIN. Host actual kernel 7.0.0-38-generic, hardlinks protegidos y configuración search/domain sin valores largos; solo lecturas agregadas, sin contenido DNS o variables privadas. Falta verificar DNS efectivo de contenedores/proveedor y parches del kernel real; consulta Ubuntu sin resultado verificable. No cambios funcionales, builds o nuevas suites. Corregido conteo libxml2 a 7 HIGH / 1 CRITICAL / 2 MEDIUM / 2 LOW según JSON. Dev.33/etiqueta conservadas; revisión general y entrega final siguen abiertas.
