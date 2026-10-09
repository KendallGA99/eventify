# EVENTIFY: estado actual y continuidad

Corte: 8 de octubre de 2026, America/Costa_Rica. Resumen operativo; [HISTORIAL.md](HISTORIAL.md) conserva los registros anteriores íntegros. Leer este archivo, el bloque activo de PLAN y los requisitos afectados al retomar. Los resultados siguientes tienen versiones y alcances distintos; no sumarlos como una sola suite.

## Alcance y autorizaciones

- Aplicación académica PHP/MySQL, HTML/CSS/JS/jQuery/AJAX y sesiones. Desarrollo Linux; entrega local Docker Compose Linux/Windows. No modificar Código Tico.
- Commits y push progresivos autorizados el 2026-10-08; repositorio privado [USUARIO_DESTINO/eventify](https://github.com/USUARIO_DESTINO/eventify), remoto SSH. Sin push forzado ni reescritura.
- Publicación Render Free/Aiven MySQL Free autorizada expresamente: integración GitHub solo EVENTIFY, presupuesto ₡0, sin tarjeta ni planes pagados. No necesita nuevas cuentas de proveedores el receptor del paquete local.
- Estudiante confirmado: Kendall Gamboa Arce. Docente confirmada: Rita Robles Loaiza. Fecha de entrega no proporcionada. Defensa y comprensión humanas pendientes.

## Versiones y destinos

- Código/documentación actual: **0.1.0-dev.32**, rama `codex/b09-cierre-documental`, desde `2d0fb3c`. Aplicación igual a dev.30; guiones de ensayo cambiados para puerto explícito e inventario Apache. Ninguna versión estable.
- `main` conservada: `3da680b74b41fa3da4069879edc1f649de9b97f2`. VERSIONADO contiene la política y referencias anteriores. Git recupera código; no restaura MySQL.
- Web pública: https://eventify-academico.onrender.com, **dev.30**, commit `390b1ef6dcbb9a9d76eaad8a910a80da364bb637`. Render `srv-db3vutm7bikc73ajj6og`, Free/Oregon/AutoDeployOff; misma rama de despliegue existente.
- Último deploy: `dep-db44q9cs728c739ikr20`, 8 de octubre, 20:10:45 CST, **Deploy succeeded/Live**, 35 s. Referencia anterior `cabd6f3` conservada.
- Copia limpia observada en navegador: `localhost:8084`, dev.28 `b92ed44968ac9116b35bf003a41486930f7e0d71`, proyecto `eventify-clean-20261009002340-886e6d4b`. Directorio privado `storage/clean-20261009002340-886e6d4b`.
- Otros destinos conservados según última verificación anterior: normal8080, QA8081, restauración8082, copia limpia8083. No afirmar disponibilidad actual sin consultar. No detenerlos ni borrar volúmenes para reutilizar puertos.
- Docker29.1.3/Compose2.40.3. Esta sesión no tiene acceso al socket; sudo no interactivo tampoco lo resuelve. No cambiar grupos/socket/sudoers. Operaciones Docker necesarias se ejecutan en terminal del usuario.
- Configuración, cuentas, CA, registros y snapshots privados en storage/backups, ignorados por Git. Nunca imprimir credenciales ni incluirlos en el ZIP.

## Evidencia vigente

| Área | Resultado comprobado | Límite |
| --- | --- | --- |
| Imágenes dev.30 | Local/pública construidas, sintaxis PHP8.4, 266 comprobaciones unitarias y QR; xz/liblzma5.8.1-1+deb13u2 comprobado. | Nueva guarda Apache dev.32 pendiente de ejecución. |
| MySQL/HTTP | 219 comprobaciones, tres migraciones; concurrencia de reserva, administración y presupuesto meteorológico. | No certifica Windows ni toda la publicación. |
| Copia limpia Linux | ZIP dev.28 en ruta con espacios: ambos roles, reserva/ticket/QR, cancelación/historial y persistencia tras recrear. | ZIP dev.32 necesita instalación propia. |
| Restauración | Diez tablas y archivos comparados en destino aislado; registros privados conservados. | No prueba toda transferencia a Windows. |
| Publicación dev.30 | Parche construido en Render; sintaxis, 39 pruebas de clima y44 de presupuesto en build. Snapshots antes/después idénticos en diez tablas. | No incluye nueva reserva/admin en esta ejecución. |
| HTTPS dev.30 | 81 controles posteriores correctos: dos usuarios, sesiones, CSRF, rutas privadas y tickets ajenos; sin escrituras de negocio. | No es auditoría universal ni revisión completa de imágenes. |
| API pública dev.30 | Pronóstico útil real después del deploy: 17,3–24,1°C/100% para evento ficticio del12oct; consultado8oct20:13CST. | Puede variar o fallar; no garantiza disponibilidad permanente. |
| Dependencias/secretos | Composer sin avisos, jQuery verificado y505 blobs sin secretos conocidos en ejecución anterior. | No prueba ausencia de avisos futuros o secretos desconocidos. |
| Windows | PowerShell5.1 real: seis scripts sin errores de parser. VM Win11 LTSC Evaluation identificada,4GiB. | Sin Docker/WSL ni ejecución completa; hace falta host compatible. |
| Documentación | PDF dev.32,15 páginas; portada y evidencia renderizadas/revisadas, nombres confirmados y texto dentro de página. | Borrador: fecha y revisión/defensa humanas pendientes. |

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
- B09: portada/manual/PDF y exportación de desarrollo dev.32 preparados; instalación final, fecha, receptor y ensayo humano pendientes. [CIERRE.md](CIERRE.md) organiza los casos; no marcar estable por tener un ZIP.

## Próxima acción necesaria

Desde la carpeta del proyecto, ejecutar en la terminal del usuario:

```bash
bash scripts/test-image-linux.sh
bash scripts/test-clean-linux.sh v0.1.0-dev.32 8085
```

Primer ensayo agrega inventario real `apache2ctl -M` de ambas imágenes, sin red/montajes/puertos, y rechaza los seis módulos con avisos si están habilitados. Segundo exporta la etiqueta, crea un proyecto y configuración propios, prueba migraciones/persistencia y conserva el destino8085. Siete controles de extracción/permisos con puerto8085 y rechazo de argumentos08085/65536 comprobados sin Docker; Bash-n/diff correctos. **No ejecutados estos nuevos ensayos con Docker todavía.**

Después leer status/test.log sin credenciales, probar8085 en navegador, continuar Firefox/zoom y revisión de imágenes. Windows necesita host compatible y Docker Desktop/WSL funcionando; respuesta del usuario solo confirmó nombres, no disponibilidad Windows. Completar paquete definitivo y su instalación en ambos sistemas cuando haya evidencia. La defensa formal se registra cuando ocurra.


## Exportación dev.32 confirmada

Etiqueta v0.1.0-dev.32 y rama publicadas; commit1dd0ed8dec5a15db82c9543a4ce803b492c52915, etiqueta anotada0c0c1b1ff0a8f3359a137a91083b1f6f19482734; refs remotas verificadas. Fuente en output/entrega/eventify-desarrollo-1dd0ed8dec5a/eventify-source.zip,188 archivos, SHA25677157fa2ef2e419fa8a234daeef2e196514eab1f5ddac3248d7ee20af436d8e9. Paquete de revisión con ese ZIP, PDF, guía, manifiesto y huellas: output/entrega/eventify-dev32-revision.zip, SHA25675a91451ca8e3f92272e5666b355bfe6d0ba83b8bee971fca51b8982efeaee18. Integridad/CRC comprobados; no instalación nueva ni entrega final certificada. PDF15 páginas con nombres/versión correctos, portada/evidencia revisadas y texto dentro de página. Registro posterior a la etiqueta solo documenta resultados, no cambia el paquete.

Ampliación C-06: panel administrativo dev.28 a390px, ocho enlaces recorridos mediante Tab y contorno3px visible; seis tarjetas327px y scrollWidth375 sobre viewport390. Captura /tmp/eventify-admin-teclado-dev28.png revisada. Sin formularios enviados ni cambios de negocio; sesión cerrada al finalizar.
