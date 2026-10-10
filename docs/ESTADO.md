# EVENTIFY: estado actual y continuidad

Corte: 10 de octubre de 2026, America/Costa_Rica. Versión **0.1.0-dev.42**, todavía sin certificación estable. Leer este resumen y el bloque activo de PLAN; consultar requisitos por identificador. Los registros anteriores se conservan íntegros en [HISTORIAL.md](HISTORIAL.md).

## Alcance y autorizaciones

- Aplicación académica PHP/MySQL, HTML/CSS/JavaScript/jQuery/AJAX y sesiones. Desarrollo Linux y entrega local Docker Compose Linux/Windows. No modificar Código Tico.
- Commits y push progresivos autorizados desde el 8 de octubre. Repositorio privado [USUARIO_DESTINO/eventify](https://github.com/USUARIO_DESTINO/eventify), remoto SSH; sin push forzado ni reescritura. Main conserva `3da680b74b41fa3da4069879edc1f649de9b97f2`.
- Render Free/Aiven MySQL Free autorizados para evaluación, presupuesto ₡0, sin tarjeta ni planes pagados. El receptor del paquete local no necesita cuentas en esos proveedores ni asociar GitHub.
- Estudiante: Kendall Gamboa Arce. Docente: Rita Robles Loaiza. Fecha de entrega omitida por indicación del usuario. Comprensión y defensa requieren evidencia humana.
- Windows queda al final. Zoom200% es opcional; diseño adaptable y compatibilidad Chrome/Firefox se mantienen.

## Referencias vigentes

| Destino | Referencia y alcance |
| --- | --- |
| Rama actual | `codex/b07-clima-respaldo`. Dev.42 añade respaldo meteorológico y está aplicada a8086/Render; paquetes anteriores conservados. |
| Aplicación comprobada | Dev.42 sobre `bd7e3e5`: clima58, integración234, HTTPS público81 y pronóstico real de respaldo visible. Dev.41 conserva evidencia de corrección nativa; Windows/Firefox restantes pendientes. |
| Etiqueta anterior conservada | `v0.1.0-dev.37` resuelve a `7c3a3e28435566349efcd4b2a66eace89de978d2`; push confirmado. No moverla para actualizar documentos. |
| Web pública | https://eventify-academico.onrender.com, commit `bd7e3e5802c91e3a81412e7cae76060a7feec42a`, dev.42. |
| Render | Free, AutoDeployOff; deploy `dep-db57g12jnfac739gk1eg`, fuente bd7e3e5, iniciado10oct11:38:12CST y Live tras27,1s. Dev.41 conservada para rollback. |
| Local8086 | Web dev.42 y MySQL dev.41 saludables; diez tablas conservadas en la actualización. Proyecto `eventify-clean-20261009035638-598f411d`; instalación limpia original dev.33 y actualización dev.41 con evidencia propia. |
| Paquete documental dev.41 vigente | `output/entrega/eventify-desarrollo-636ef74f748a/eventify-source.zip`, 229 archivos, fuente `636ef74f748afc0933687c09620402c32a0d3fae` y PDF18 páginas adjunto. Integridad comprobada; Windows pendiente. |
| Paquete documental dev.40 conservado | `output/entrega/eventify-desarrollo-0ab59e9566ed/eventify-source.zip`, 219 archivos y PDF adjunto de17 páginas; fuente `0ab59e9566ed6181a1e56e75db3f6db842d741b2`. Integridad comprobada; instalación Windows pendiente. |
| Paquete documental dev.39 conservado | `output/entrega/eventify-desarrollo-82b47edbe4cf/eventify-source.zip`, 219 archivos, incluye guías de seguridad/defensa; misma aplicación dev.39. Integridad comprobada; instalación Windows pendiente. |
| Paquete dev.39 anterior | `output/entrega/eventify-desarrollo-37b1f642c294/eventify-source.zip`, commit `37b1f642c29429ad232a92acdc951267d202d141`, 217 archivos y PDF adjunto. CRC/huellas comprobados; instalación Windows pendiente. |
| Paquete dev.38 conservado | `output/entrega/eventify-desarrollo-f01ee0e3cf0f/eventify-source.zip`, commit `f01ee0e3cf0f784740390d33a997e0b6fb756fdf`,217 archivos, manifiesto/CRC/huellas correctos. Imagen MySQL final probada; aplicación pública permanece dev.37. Este ZIP no se ha instalado de nuevo en Linux ni Windows. |
| Paquete documental dev.37 conservado | `output/entrega/eventify-desarrollo-44bd3a9a9360/eventify-source.zip`, commit `44bd3a9a936005db52bda8de65c90037803ae335`, 213 archivos. Misma aplicación, configuración, migraciones, dependencias e imágenes declaradas que la web pública. |
| PDF dev.40 | Documento técnico de17 páginas, actualizado con restricción de privilegios local, publicación dev.39 y preparación de defensa; todavía borrador de evaluación. |

Huellas del paquete dev.38 indicado arriba:

ZIP SHA256: `ec52a996819fb7ff78af4619a57eda89114fc79615547e58223295dfc62dff94`.
PDF SHA256: `6074435a154bd3f89027566b41fc23c4849aa3950ebc70e92583a8af5bff5759`.

Las huellas dev.37 se conservan en el manifiesto de su propio paquete; no usarlas para validar dev.38.

CRC, inventario y huellas individuales del paquete comprobados. PDF renderizado completo y revisado; texto dentro de página. No afirmar instalación nueva de este ZIP ni Windows por equivalencia de código. Paquete anterior `7c3a3e284355` preservado; no sobrescribirlo.

## Evidencia comprobada

Cada fila tiene su propio alcance; no sumarlas como una sola suite.

| Área | Resultado y referencia | Evidencia privada |
| --- | --- | --- |
| Integración dev.37 | 225 controles MySQL/HTTP, sintaxis PHP8.4, 23 de registro/CSRF y7 de páginas de error; helper exit0 sobre ab710a6. Solo web8086 actualizada. | `storage/dev37-review` |
| Experiencia dev.37 | Ojos independientes, login al inicio para ambos roles, nombre guardado y conservado tras nueva sesión, navegación de administrador y perfil a390/768/1280 sin desbordamiento. Nombre ficticio original restaurado; sesiones cerradas. Sin nuevas reservas. | `storage/dev37-live` |
| Publicación dev.37 | Diez tablas idénticas antes/después del deploy, antes de logins/clima. 81 controles HTTPS de usuarios y31 de administrador aprobados: sesión, CSRF, propiedad, restricciones y estilos. Sin escrituras de negocio. | `storage/public-dev37` |
| Clima público dev.37 | Proveedor respondió HTTP429; aviso visible y acceso a reservar disponible. Reintento posterior también mostró indisponibilidad; su texto no acredita por sí solo el código upstream. No eludir cuotas ni inventar pronósticos. | `storage/public-dev37/weather-diagnostic.json`, `weather-retry.json` |
| API útil anterior | Respuesta real dev.30 del8oct20:13CST: 17,3–24,1°C/100% para evento ficticio del12oct. No atribuirla al último deploy. | `storage/aiven-evaluacion` |
| Rendimiento dirigido | Eventos local HTTP7ms inicial/4ms siguiente; IAB local carga101,9ms. Público activo carga1060,2ms. Muestras únicas; no percentiles, arranque en frío ni medición del Firefox del usuario. | `storage/public-dev37/*performance.json` |
| Linux y persistencia dev.33 | Fuente f4d6c5a en ruta con espacios; recorrido8086 y recreación posterior. Diez tablas/cinco archivos idénticos; ocho reservas/ocho tickets conservados. No repetir esta suite sin cambios o dudas. | `storage/dev33-persistence-lBxRJbW3` |
| Runtime dev.34 | Imágenes finales, unidades y TLS positivo/negativo aprobados sobre6d41df5. Retirados32 paquetes de construcción; escaneo con la misma DB pasó2011→424 filas por variante, sin avisos nuevos ni exclusiones. | `storage/runtime-release-pdzRD8Jx`; ver ENSAYO-RUNTIME |
| MySQL dev.38 | Imagen final9321f68: construcción,225 controles MySQL/HTTP, TLS positivo/negativo exit0. Escaneo47 filas idénticas a candidata, identidad confirmada contra blob OCI. Sin cambios de aplicación/esquema ni actualización de instalaciones. | `storage/mysql-image-check-YmuG4fAM` |
| MySQL dev.33 | gawk5.1.0-6.el9_8.1, servidor8.4.11; construcción,219 controles y TLS aprobados. Escaneo69→66 filas, solo tres gawk eliminadas. | `storage/mysql-image-check-gpZ3It99` |
| Firefox humano | Firefox157.0.1/Linux Mint: búsqueda, foco y calendario abierto; historial cancelado/ticket/QR al200%; rechazo administrativo con estilo al110%. Capturas parciales, no recorrido completo. | `storage/firefox-20261009` |
| Windows parcial | PowerShell5.1 real: seis scripts sin errores de parser. VM Win11 LTSC Evaluation,4GiB; sin Docker/WSL ni instalación completa acreditada. | Ver VERIFICACION-WINDOWS |

Registro, catálogo/AJAX, respuesta perdida de reserva, errores de clima y administración tienen aceptación IAB anterior con sus referencias en [evidencias/B07.md](evidencias/B07.md). IAB no sustituye Firefox. Restauración aislada anterior tiene evidencia propia; no certifica transferencia a Windows.

## Seguridad anterior: referencias de las instalaciones previas

- Dependencias PHP fijadas: consulta Packagist de9oct sin avisos para las dos bibliotecas. jQuery local con huella/licencia comprobadas. Esto no audita el sistema operativo.
- PHP/Apache conserva424 filas:1 crítica,91 altas,162 medias,166 bajas y4 sin clasificación;156 identificadores únicos. Base Trivy del8oct19:05UTC. MySQL dev.33 conserva66 filas; la candidata sin Shell conserva47, con comparación de la misma base, sin exclusiones. La imagen final dev.38 confirmó las mismas47 filas con identidad OCI verificada. Conteos de escaneos anteriores, sin exclusiones; no presentarlos como un escaneo nuevo.
- Libxml2/CVE-2026-6653 sigue vulnerable en Trixie según Debian. Digest PHP oficial sin cambios en la revisión previa. Parche nativo examinado, no instalado: modifica parser y estructuras públicas; exige pruebas de compatibilidad que las unidades de EVENTIFY no cubren.
- Apache/CVE-2026-63292 también sigue vulnerable en el paquete; su módulo mod_vhost_alias no estaba cargado en las imágenes examinadas. Esto delimita exposición, no corrige el paquete.
- Sin entrada XML directa encontrada en la aplicación; clima consume JSON limitado y QR genera SVG numérico. No descarta consumidores nativos. Gosu tiene análisis de funciones propio; Python/mysqlsh, kernel/host y demás avisos conservan sus límites.
- Referencias: [SEGURIDAD.md](SEGURIDAD.md), [AVISOS-IMAGENES.md](AVISOS-IMAGENES.md), [AVISOS-XML.md](AVISOS-XML.md), [AVISOS-BASE.md](AVISOS-BASE.md), [AVISOS-RESIDUALES.csv](AVISOS-RESIDUALES.csv). No hay aceptación automática del riesgo ni certificación estable.

## Pendientes y próxima acción

1. **C-07 Firefox:** completar limpieza/combinación de filtros, selección/borrado de fecha y teclado, perfil dev.37, logout/ticket y panel administrativo. Se solicitó comprobación humana: el control disponible no expone Firefox. [Procedimiento](VERIFICACION-FIREFOX.md).
2. **C-09 seguridad:** corrección dev.41 probada: tres imágenes finales sin avisos, 234 controles y TLS positivo/negativo aprobados. Aplicación a destinos y referencias de publicación se registran al final; no atribuir estos resultados a las versiones anteriores.
3. **C-08/C-11 Windows, al final:** instalación del paquete elegido en un Windows compatible con Docker Desktop/WSL2 y recursos suficientes; arranque, roles, reserva/ticket/cancelación y persistencia reales. La VM revisada no acredita esas condiciones. [Procedimiento](VERIFICACION-WINDOWS.md).
4. **C-12 entrega humana:** revisión del receptor, acceso del evaluador al repositorio privado/cuentas y ensayo de defensa. No inventar participación o dominio del código.
5. **Operación pública:** Aiven Free estaba apagado por inactividad y se reactivó antes del deploy. Render puede dormir. Comprobar ambos antes de evaluar; la disponibilidad del clima depende también del proveedor.

B01/B02 comprobados; B03–B06 implementados con evidencia por flujo. B07 sigue abierto por los pendientes anteriores. B08 tiene deploy dev.37 y controles posteriores con sus límites. B09 tiene manual/PDF/paquete preparados, sin aceptación final. [CIERRE.md](CIERRE.md) organiza los casos; implementado, probado y publicado son estados distintos.

## Operación y conservación

- Docker29.1.3/Compose2.40.3 en Linux. El usuario habilitó permisos temporales: sudo -n id devuelve root y sudo -n docker info funciona. Usar sudo -n sin contraseñas; no modificar grupos/socket/sudoers.
- Configuración, cuentas ficticias, CA, snapshots y logs en storage/backups ignorados por Git. Nunca imprimirlos ni incluirlos en ZIP/imágenes. Cuentas locales8086: `storage/clean-20261009035638-598f411d/demo-credentials.json`.
- Conservar destinos anteriores8080–8085 y todos sus volúmenes. Su disponibilidad actual no se presume. No borrar datos para resolver fallos ni reutilizar puertos deteniendo servicios ajenos.
- Rollback Git recupera código, no MySQL; revisar migraciones y respaldos antes de actualizar. Dev.37 no cambió esquema respecto a la publicación anterior.
- En este corte se ensaya y prepara la reducción de MySQL. No se recrearon las instalaciones existentes ni se repitió la aceptación Linux; no se cambió Aiven o la publicación dev.37.

## MySQL sin Shell: dev.38

Candidata `storage/mysql-minimal-zntFADgx`: integración225, respaldo/restauración de dos filas ficticias, inventario RPM y seis huellas conservadas correctos; solo desaparece mysql-shell. TLS `storage/public-tls-Qsvpge91` y rechazo sin TLS `storage/public-no-tls-qG1NpFx4` terminaron exit0. Escaneo Trivy0.75.0 con la base almacenada del8oct:66→47 filas, exactamente19 de Python retiradas, ninguna añadida. Restantes:1CRITICAL/21HIGH/22MEDIUM/2LOW/1UNKNOWN. Esto reduce superficie, no corrige libxml2, gosu ni el host.

El primer intento `storage/mysql-minimal-ohVuHbye` falló: RPM dejó cachés Python sin propietario. La receta ahora comprueba propiedad antes de eliminar solo el directorio mysqlsh dentro de la imagen. El intento fallido conserva evidencia y no afectó bases del usuario.

Dockerfile.mysql incorpora la receta; la etapa mysql-with-shell permite repetir la comparación. VERSION dev.38, sin cambios de aplicación/esquema. Imagen final sobre9321f68/árbol limpio: mysql-image-check-YmuG4fAM exit0;225 controles, TLS public-tls-NB0m9r8w exit0 y rechazo public-no-tls-wnPk03Vg exit0. Escaneo47 filas idénticas a la candidata; identidad confirmada contra el blob de configuración OCI, sin exclusiones. PDF actualizado a17 páginas y revisado visualmente. Etiqueta v0.1.0-dev.38 en4c833353f1e8978a248a1b83f8257f2e58ba19dd, push confirmado; conserva el incremento probado. Paquete217 archivos exportado y CRC/huellas comprobados; referencia final documental registrada después de exportar. No se instaló un ZIP nuevo ni se certificó Windows. Publicación y paquete dev.37 siguen vigentes; no se cambia su etiqueta. [Detalle](ENSAYO-MYSQL-MINIMAL.md).

## Exportación dev.38

Paquete217 archivos: SHA256 `1a4e84d401218fb5db71c5d0cb26428d3168c9ac4e484f912368070e450a7dee`. PDF17 páginas: SHA256 `6074435a154bd3f89027566b41fc23c4849aa3950ebc70e92583a8af5bff5759`. Archivo de referencia documental `2a738b703ad1a129ed53d75e2da9d784dd6f37af`, sin cambios de runtime respecto al ensayo9321f68. Versiones anteriores preservadas. Etiqueta de desarrollo dev.38 conserva4c83335; no se mueve por correcciones documentales. Seguridad residual, Firefox restante, Windows al final y aceptación humana siguen pendientes.

## Revisión posterior del 10 de octubre

- Registro Docker oficial: php:8.4-apache mantiene901b0dbcd2419cc9cd307ea05e57403449ce722ccd5b32335da6e8d39f2b1ee0. Debian mantiene vulnerables libxml2/CVE-2026-6653 y Apache/CVE-2026-63292 en Trixie; no se encontró corrección nueva de esa distribución en los trackers consultados. Gosu sigue en release1.19; su análisis de símbolos anterior conserva su alcance. No se compilaron bibliotecas personalizadas ni se repitieron escaneos sin candidata nueva.
- Comprobación pública de solo lectura: inicio, eventos, ingreso y registro HTTP200; /salud HTTP200/status ok. /.env, /.git/config, /config/database.php y ruta inexistente HTTP404 con hoja de estilo; X-Powered-By ausente en las seis respuestas dirigidas. No hubo escrituras de negocio ni sesiones autenticadas.
- Una consulta meteorológica devolvió status unavailable; este resultado no revela el código upstream. No atribuirle HTTP429 por el resultado histórico ni eludir cuotas.
- Evidencia privada: storage/security-refresh-20261010/php-registry.json y public-check.json. La consulta inicial a /health devolvió404: la ruta real de salud es /salud, que luego se comprobó correctamente. No hay fallo de salud por esa URL inexistente.
- Control de navegadores consultado: Chrome e IAB disponibles; Firefox no expuesto. VM Windows apagada; host7,6GiB totales y aproximadamente3GiB disponibles durante la consulta. No se inició ni amplió la VM, ni se ejecutó una instalación Windows con recursos insuficientes.

Siguiente cierre posible: evidencia real de Firefox mediante un canal compatible y Windows con recursos suficientes. La entrega estable también conserva la revisión/decisión sobre riesgos residuales y aceptación humana; no declararlas resueltas por esta revisión de disponibilidad. La web pública dev.37 y el paquete dev.38 permanecen en sus referencias; este cambio solo actualiza documentación.

## Manuales sincronizados con dev.38

Se revisaron README, INSTALACION y DATOS contra VERSION, migraciones001–003, DemoSeeder, ReservationService y la composición de la suite de integración. Se retiraron afirmaciones antiguas de funciones pendientes, aviso de construcción y publicación dev.30; README ahora distingue imagen/dev.38, aplicación pública/dev.37 e instalación limpia/dev.33. DATOS incorpora weather_budget y el acceso privado a las cuentas locales generadas, con instrucciones por sistema y proyecto Compose. No se copiaron ni imprimieron credenciales reales.

Enlaces locales, bloques de comandos y referencias de archivos comprobados; git diff --check correcto. Solo documentación, sin cambios de runtime ni suites Linux repetidas. Se solicitó el resultado humano del recorrido restante de Firefox, que no está expuesto al control disponible; hasta recibir evidencia, C-07 continúa pendiente. Windows permanece al final.

Paquete documental actualizado: `output/entrega/eventify-desarrollo-f01ee0e3cf0f/eventify-source.zip`,217 archivos, commit `f01ee0e3cf0f784740390d33a997e0b6fb756fdf`, SHA256 `ec52a996819fb7ff78af4619a57eda89114fc79615547e58223295dfc62dff94`. CRC, huellas de todos los archivos y coincidencia de manuales/receta MySQL comprobados. PDF17 páginas conservado sin cambios, huella6074435a154bd3f89027566b41fc23c4849aa3950ebc70e92583a8af5bff5759. Los paquetes anteriores permanecen; no hubo instalación nueva ni cambios de código. Esta referencia sustituye al paquete anterior para los manuales actualizados, sin mover etiquetas históricas.

## Recorrido autónomo de navegador y presentación dev.39

Por indicación del usuario, el recorrido se ejecuta desde IAB sobre8086/dev.37; no se exige intervención para repetirlo en ese navegador ni se atribuye a Firefox. Comprobados filtros combinados/limpieza, llenado/borrado de fechas, Tab búsqueda→categoría, calendario/Escape, login al inicio, nombre persistente y restaurado, historial/ticket cancelado/QR, logout y acceso directo al ticket redirigido al ingreso. Administrador: inicio, panel, categorías, eventos y reservas. Panel sin desbordamiento a390/768/1280; capturas en storage/browser-review-20261010. Sin reservas nuevas ni cancelaciones; nombre ficticio original restaurado.

Se detectaron dos detalles reales: singular administrativo y estado visual del historial de un evento publicado pasado. Dev.39 añade estado derivado a las consultas propias de reservas, conservando event_status persistido; la vista muestra el derivado. Solo lectura y textos, sin migraciones o cambios de reglas. Rama codex/b07-reservas-presentacion. Firefox conserva su alcance específico; Windows al final. El resultado posterior se registra a continuación.


### Resultado del recorrido y actualización local

Código `0e6be15`, dev.39: integración MySQL/HTTP225 comprobaciones correctas, helper exit0 y sintaxis PHP correcta. El ensayo usó el proyecto aislado eventify-tests; sus contenedores y red se retiraron al terminar. No se repitió la instalación limpia Linux.

Se reconstruyó y recreó únicamente web8086 mediante `up --build --no-deps --detach --wait web`. Contenedor saludable; identificador e imagen del contenedor MySQL idénticos antes y después. No hubo migraciones ni borrado de volúmenes.

IAB posterior confirmó FINALIZADO en historial y actualización AJAX, singular en lista y detalle administrativo, filtro administrativo por evento/estado y403 con estilo para el usuario. Perfil ficticio restaurado y sesiones cerradas; sin nuevas reservas ni cancelaciones. El panel anterior se comprobó a390/768/1280 sin desbordamiento. No atribuir ese recorrido a Firefox ni a una instalación Windows.

Evidencia privada: `storage/browser-review-20261010/resultado.json`, `integration.log`, `lint.log`, `web-update.log`, `db-before.txt`, `db-after.txt`, `runtime-reference.txt` y capturas `historial-dev39.png`/`admin-detalle-dev39.png`. Las capturas contienen únicamente datos ficticios de evaluación. Recorrido autónomo IAB terminado; no requiere que el usuario lo repita. C-07 conserva solamente su alcance específico de Firefox. Publicación dev.37, paquete/PDF dev.38, seguridad residual, Windows y aceptación humana conservan sus límites anteriores.


## Publicación dev.39: 10 de octubre

Render Free desplegó `0e3aae247da50deaa0150be4e235728cbe3db12a`; estado Live, duración32,7s. Etiqueta anotada `v0.1.0-dev.39` publicada y resuelta al mismo commit. Main y etiquetas anteriores conservados. No se cambiaron variables, plan, rama configurada o auto deploy; sin migraciones nuevas ni sembrado.

Diez tablas mantuvieron filas y huellas antes/después del despliegue, antes de autenticar. Después:81 controles HTTPS de usuarios y36 de administración correctos. IAB comprobó ingreso del administrador al inicio, panel, lista/detalle de la reserva cancelada existente, cierre de sesión y catálogo. Sin escrituras de negocio. Los casos de1 entrada/evento finalizado tienen evidencia local dev.39; no se crearon datos públicos para repetirlos.

Evidencia privada: `storage/public-dev39/`, snapshots, security.log, admin-security.log, result.json, render-live.png, catalogo-publico.png y admin-reserva.png. El paquete/PDF dev.38 permanece conservado; la nueva sincronización documental no equivale a instalación Windows. Seguridad residual, Firefox específico y aceptación humana siguen abiertos.

PDF dev.39 regenerado: 17 páginas renderizadas y revisadas visualmente; texto dentro de página. SHA256 `ab3e4fedaf7ed93b8dc50eb44cc7999f0f346dbcab1e7f4ffa23589231803583`. Manuales sincronizados con publicación dev.39; el paquete anterior se conserva y la exportación nueva se registra después de comprobarla.


## Paquete dev.39 sincronizado

Exportación `output/entrega/eventify-desarrollo-37b1f642c294/eventify-source.zip`, commit `37b1f642c29429ad232a92acdc951267d202d141`, 217 archivos. SHA256 `4d3279d80d8f87ca74ad3ac16ebd5ab81ffc1984e2df78c93575001aef4df5a3`. CRC, inventario y huellas individuales correctos; manuales, código de reservas y receta MySQL coinciden con Git. PDF de17 páginas incluido al lado del ZIP, SHA256 `ab3e4fedaf7ed93b8dc50eb44cc7999f0f346dbcab1e7f4ffa23589231803583`. Paquetes anteriores conservados. No se instaló este ZIP en Windows ni se repitió la instalación Linux. Esta referencia reemplaza al paquete dev.38 para la entrega de desarrollo; no modifica el commit público ni mueve etiquetas.


## Alcance de cierre confirmado el 10 de octubre

CIERRE.md y VERIFICACION-FIREFOX.md sincronizados con dev.39, paquete de 217 archivos y PDF de 17 páginas. C-10 comprobado; C-11 conserva la verificación Windows. No se repite Linux por instrucción del usuario. IAB terminado; no se solicita repetir ese recorrido.

El control de navegador consultado sigue exponiendo Chrome/IAB, sin Firefox. Ante la propuesta de prueba separada mediante geckodriver y perfil temporal, el usuario respondió «Mantener solo el control de navegador disponible». No se instaló ni ejecutó esa alternativa. C-07 conserva su evidencia parcial y bloqueo; no se reclasifica IAB como Firefox.

Consulta oficial Debian de libxml2/CVE-2026-6653 y Apache/CVE-2026-63292: Trixie continúa vulnerable; correcciones de forky/sid no se incorporan a esta base. C-09 conserva los riesgos documentados, sin aceptación automática. Sin cambios de runtime, nuevo escaneo, despliegue o pruebas Linux repetidas. La defensa y revisión del receptor siguen como actividades humanas de C-12. Por tanto, no es posible declarar que Windows sea el único pendiente con la evidencia y las condiciones actuales.


## Preparación de seguridad y defensa

Inspección local dev.39: PHP8.4.26/libxml2 2.9.14, extensiones XML presentes; display_errors/expose_php desactivados, sesiones estrictas. Apache sin vhost_alias/xml2enc/proxy/CGI en el inventario inspeccionado. Contenedor no privilegiado y puerto ligado a127.0.0.1. Esta evidencia local no certifica módulos del proceso Render ni corrige paquetes. C-09 permanece abierto. Resumen en SEGURIDAD-ENTREGA.md; registro privado storage/security-defense-20261010.

ENSAYO-DEFENSA.md añade recorrido de15 minutos, diez archivos de lectura, doce preguntas razonadas, condiciones de preparación y ejercicio de borde. El comando real de reservation-policy.php aprobó19 comprobaciones en la copia local; sin escrituras. Enlaces al código verificados. Preparación documental terminada; práctica, comprensión y defensa humanas aún no registradas. No cambia VERSION/runtime, publicación o paquete previo.

Exportación documental con las dos guías: commit `82b47edbe4cf1245c755c5ca6faf5fefeefcd98c`, 219 archivos, ZIP SHA256 `a620e371108cf439b31b807d281184440195a1f5106b251ba76ae73d6d6c3926`. CRC y todas las huellas individuales correctos; guías coinciden con fuente. PDF dev.39 adjunto conservado, SHA256 `ab3e4fedaf7ed93b8dc50eb44cc7999f0f346dbcab1e7f4ffa23589231803583`. No se generó un PDF distinto ni se repitieron pruebas de runtime por este incremento documental.


## Restricción de privilegios: dev.40

B07/RNF-03: `no-new-privileges:true` incorporado a la web de Compose y al servicio de integración. Objetivo y aceptación en SEGURIDAD-ENTREGA.md. Sin cambios de PHP, MySQL, esquema, rutas o reglas de negocio; VERSION identifica el incremento de configuración.

Prueba inicial con la imagen local en contenedor aislado: red none, sin puertos publicados ni volúmenes; Apache sirvió CSS, cinco trabajadores www-data y proceso principal con NoNewPrivs=1. Contenedor de ensayo retirado. `scripts/test-database-linux.sh` ejecutado con sudo no interactivo: 225 comprobaciones MySQL/HTTP correctas, exit0. Proyecto eventify-tests retirado por su propio cleanup; no se repitió instalación limpia Linux.

Aplicación local: `compose --env-file [configuración privada] -p eventify-clean-20261009035638-598f411d up --no-deps --detach --wait --wait-timeout 90 web`, sin reconstrucción porque la imagen de aplicación no cambió. Web saludable; proceso principal y cinco trabajadores con NoNewPrivs=1. Identificador e imagen de MySQL idénticos antes/después. No hubo migraciones, borrado o recreación de base/volúmenes. Inicio, catálogo, ingreso, registro, CSS y salud devolvieron HTTP200 con contenido.

Evidencia privada: storage/security-nnp-20261010/apache-smoke.log, integration.log, web-update.log, local-policy.txt, db-before.txt, db-after.txt y http.json. git diff --check correcto. Esta restricción reduce posibilidades de elevación mediante exec; no elimina root del proceso principal ni corrige CVE de bibliotecas. No aplica automáticamente a Render. Publicación y paquete documental dev.39 permanecen vigentes; la nueva exportación de código se registra abajo. Firefox específico, seguridad residual, Windows y ensayo humano siguen pendientes. No se marca una versión estable.


Referencia dev.40: commit `6a4e2fdf7746920ab0e947741cdd10595d5e99fd`, rama codex/b07-contenedor-sin-privilegios y etiqueta v0.1.0-dev.40 con push confirmado. Exportación de código `output/entrega/eventify-desarrollo-6a4e2fdf7746/eventify-source.zip`, 219 archivos, SHA256 `0268b8865eff060780f324a2228a8eedd0781fae95eb9c9321fd0679d462e82b`. CRC, huella del ZIP y todas las huellas individuales correctos; ambas recetas Compose incluyen la restricción. Este ZIP es de desarrollo, sin una instalación nueva ni certificación Windows. El PDF dev.39 y su paquete documental anterior permanecen conservados; no se adjunta ni presenta ese PDF como uno regenerado para dev.40.


## Sincronización documental dev.40

README, CIERRE y TECNICO sincronizados con VERSION dev.40. El documento distingue aplicación/publicación dev.39, protección Compose local dev.40, evidencia histórica Linux dev.33 y los pendientes humanos. Incluye no-new-privileges y las guías de ensayo/seguridad; corrige frases históricas que parecían describir una publicación dev.37 vigente. No cambia código de aplicación, infraestructura o datos.

PDF regenerado, 17 páginas renderizadas y revisadas visualmente; sin caracteres fuera de página. SHA256 `9118d409b739c090a364c6418335fd2b246e8ecb69011a80de711524fd9cf36b`. Consulta oficial del10oct confirma que Trixie continúa vulnerable en CVE-2026-6653/libxml2 y CVE-2026-63292/Apache; no se encontró en esas páginas una corrección Trixie para incorporar. No se repiten builds/escaneos sin una candidata. La exportación documental se registra después de verificar su integridad.


Exportación documental dev.40 comprobada: fuente `0ab59e9566ed6181a1e56e75db3f6db842d741b2`, ZIP `output/entrega/eventify-desarrollo-0ab59e9566ed/eventify-source.zip`,219 archivos, SHA256 `adce6305e78405f70fc85044593a19943bbacee1b17f4515179fe01ed036d409`. PDF17 páginas adjunto con SHA256 `9118d409b739c090a364c6418335fd2b246e8ecb69011a80de711524fd9cf36b`. CRC, inventario y cada huella individual correctos; README, documento técnico, dos guías y Compose coinciden con Git. entrega-manifest.json identifica ambos artefactos. Paquetes anteriores conservados; sin nueva instalación Linux/Windows, despliegue o pruebas de aplicación por estos cambios documentales.


## Sesiones: ampliación de evidencia B07/RNF-03

Objetivo: acreditar dos controles ya implementados en Session::start que no tenían un caso HTTP específico: rechazo de identificador inventado y revocación después de más de1800 segundos sin actividad. Criterios: sesión desconocida sustituida sin conservar archivo, pérdida de identidad AJAX y permisos administrativos al caducar, identificador renovado, archivo anterior eliminado, CSRF anterior rechazado y cookie anterior incapaz de recuperar acceso.

Se añadió tests/integration/http-session-expiry.php y dos comprobaciones a http-auth.php. La prueba de caducidad exige APP_ENV=test/DB_DATABASE=eventify_test y modifica únicamente last_activity del archivo de su propia sesión temporal. No altera tiempo del host, reglas de negocio o sesiones del navegador. Sintaxis PHP de los dos archivos correcta en imagen de pruebas; git diff --check correcto.

Primera ejecución falló en «Cookie inicial protegida»: el nuevo caso había creado una sesión antes de la petición que esperaba emitirla. No fue un fallo de la aplicación ni se relajó la comprobación: se movió el caso al primer acceso administrativo que ya probaba esa cookie. Evidencia conservada en storage/session-expiry-20261010/attempt-1.log, exit1,130 comprobaciones previas correctas.

Ejecución corregida: scripts/test-database-linux.sh, sudo no interactivo, 234 comprobaciones MySQL/HTTP correctas y exit0. Nuevo entorno efímero eventify-tests, retirado al terminar; evidencia storage/session-expiry-20261010/integration.log. La inspección no cambió bibliotecas ni realizó un escaneo nuevo. No se repitió instalación limpia Linux ni se desplegó o actualizó una instalación.

Incremento solo de pruebas y guías; VERSION conserva dev.40 y las etiquetas no se mueven. El paquete/PDF dev.40 anterior sigue disponible y no incluye este archivo de pruebas nuevo; no es un cambio de su comportamiento de aplicación. Seguridad residual nativa, Firefox específico, Windows y ensayo humano mantienen sus pendientes. La prueba automatizada no se presenta como práctica realizada por el estudiante.


## Disponibilidad de correcciones y matriz vigente

Consulta real de metadatos APT en contenedor temporal derivado de la imagen web local: apt-get update y apt-cache policy terminaron exit0. apache2, curl, libcurl4t64, libxml2, libexpat1, libc6 y libssl3t64 tienen instalada la misma versión que su candidata Trixie. No se encontró en esos repositorios una actualización para ensayar; no equivale a ausencia de vulnerabilidades. Sin instalar paquetes, construir imágenes o repetir escaneos. Contenedor temporal retirado; instalaciones, volúmenes y datos intactos. Evidencia storage/security-candidates-20261010/apt-candidates.txt, image-id.txt y result.json; versiones y fuentes en SEGURIDAD-ENTREGA.md.

Trackers oficiales consultados: libxml2/6653, Apache/63292, Expat/66046 y curl/80229 mantienen Trixie vulnerable. Reconsulta limitada a esos cuatro avisos, sin cambiar severidades, conteos o aceptación. No repetir esta consulta en cada continuación sin un cambio de versión, candidata o tiempo relevante que lo justifique.

Control de navegador consultado nuevamente: Chrome e IAB disponibles; Firefox no expuesto, aplicaciones nativas vacías. Se conserva la decisión del usuario de no usar geckodriver. No se navegaron pestañas personales ni se afirma una prueba Firefox.

MATRIZ.md estaba fechada8oct e indicaba como pendientes varios casos ya comprobados. Se sincronizaron RF-01/02/03/04/05/06/07/08/18/19, RNF-01/02/03/04/06, jQuery/AJAX, portabilidad y entregables contra CIERRE, ESTADO y B07. Se conserva el alcance de cada evidencia:234 integración aislada, IAB dev.39, C-01–05 previos, ZIP Linux dev.33, PDF/paquete dev.40. Se retiró zoom como bloqueo y se mantuvieron resultados históricos sin reescribirlos. No cambia el comportamiento de la aplicación; no se repiten pruebas funcionales ni se regenera el paquete por actualizar este índice.

Siguiente avance material: una corrección compatible nueva para C-09, evidencia real de Firefox mediante el canal autorizado, equipo Windows compatible al final o participación humana para revisión/defensa. El seguimiento y las pruebas adicionales no sustituyen esas condiciones. Las guías están preparadas y no se introducen funcionalidades para aparentar un cierre.


## Corrección nativa autorizada — B07/SEG-09

El usuario solicitó corregir los riesgos, sin limitar el cambio al paquete Debian anterior. Se preparó una base web Alpine 3.24.2 con PHP8.4.26/Apache2.4.69 y bibliotecas corregidas; se conservan PHP/MySQL, UID/GID33 y los controles de arranque público. Decisión y procedencia en RUNTIME-CORREGIDO.md. Rama codex/b07-runtime-corregido, sin cambios de esquema o datos.

Primera candidata web: 234 comprobaciones MySQL/HTTP correctas. Recetas local/pública construidas, sintaxis/unidades aprobadas; arranque público inválido rechazado, TLS válido aceptado, CA/hostname incorrectos rechazados, recreación y rechazo de servidor sin TLS aprobados. Evidencia storage/security-alpine-20261010, storage/image-check-VzQVjwp8 y storage/runtime-release-4qJKdhtX. Escaneo actualizado al10oct12:33:35UTC de la candidata web: cero avisos sin exclusiones. La imagen de prueba inicial detectó zlib pendiente y se incorporó 1.3.2-r1 antes de este resultado.

MySQL: reconstrucción inicial de gosu con Go1.27.2 elimina los avisos del compilador anterior; la base nueva del escáner detectó cuatro filas restantes (dos OpenSSL, moby/sys/user y x/sys). Se preparan sus actualizaciones y se exige repetir integración/TLS con la imagen final antes de actualizar instalaciones. No presentar todavía esta candidata MySQL ni los destinos anteriores como corregidos.


Imágenes finales dev.41 aprobadas: web local/publica en image-check-VzQVjwp8; MySQL en mysql-image-check-jflBfUpi, exit0. Integración final234, arranque TLS, CA/hostname incorrectos, recreación y servidor sin TLS verificados con las nuevas recetas. También31 comprobaciones de orquestación y sintaxis shell correctas. Apache aislado con cinco trabajadores UID33 y NoNewPrivs1 sirve CSS.

Escáner Trivy0.75.0, base UpdatedAt2026-10-10T12:33:35.352381306Z, descargada17:03:38UTC: local0, pública0, MySQL0 avisos, sin exclusiones. Cada reporte se vinculó al blob de configuración dentro de su exportación OCI; los identificadores de manifiesto Docker difieren de esos blobs y no se confunden. Evidencia storage/security-alpine-20261010/*-final-scan.json y final-scan-summary.json. Cero avisos no garantiza ausencia de vulnerabilidades desconocidas o futuras ni audita host/proveedor.

Se conserva respaldo SQL completo y archivo de storage antes de actualizar8086. El primer dump sin --no-tablespaces informó falta de PROCESS; se corrigió usando esa opción, sin ampliar permisos, y el respaldo completo terminó exit0. Snapshot público de diez tablas guardado antes del despliegue, sin imprimir contenido o credenciales. Instalación y publicación dev.41 se registran después de verificarlas; las versiones anteriores siguen disponibles.


## Aplicación de dev.41 y publicación

Commit de corrección28c73cdd63386527ad4a07e03a8c1385198d9bf7 y etiqueta anotada v0.1.0-dev.41 con push confirmado. Main y etiquetas anteriores intactas. 8086 actualizado con las imágenes finales comprobadas, sin reconstruir ni borrar volúmenes: web061d447b, MySQLce89253c. Ambos saludables; snapshot antes/después idéntico en diez tablas, ocho reservas y ocho tickets. Recorrido IAB: inicio, catálogo, login al inicio, historial, ticket/QR existente, perfil y logout correctos; sin nuevas reservas ni cambios del perfil. Respaldo SQL completo y storage conservados.

Render Free desplegó ese mismo commit: dep-db5744rrjlhs73clgpj0,33,5s, Live10oct11:13:25CST. Logs confirman Apache2.4.69, ninguna migración repetida y salud200. Antes de las pruebas de login se compararon diez tablas Aiven y sus huellas: idénticas. No se modificó configuración, plan, permisos ni servidor Aiven. 81 controles HTTPS de usuarios aprobados; comprobación administrativa posterior registrada a continuación. Captura storage/security-alpine-20261010/render-dev41.png. La web pública reconstruye la receta; su imagen propia no se exportó ni escaneó desde Render, por lo que los cero avisos pertenecen a las imágenes finales locales comprobadas.


Verificación pública posterior dev.41: 81 controles HTTPS de usuarios y36 de administración correctos, sin escrituras de negocio. IAB confirmó catálogo y detalle con estilos; el clima mostró su aviso de indisponibilidad y mantuvo acceso a reservar, sin atribuirle un código upstream no observado. Evidencia public-security.log, public-admin-security.log y public-event.png. La actualización de las dependencias no certifica la disponibilidad permanente de Open-Meteo.


Documento técnico dev.41 regenerado:18 páginas renderizadas y revisadas visualmente; texto dentro de página. SHA256 d4e5534d8163722e29da2dfa4dd9434faac52698cbff079371b1aa38b2dbe173. Incluye corrección nativa, regresión, referencias locales/públicas y pendientes reales; los conteos de imágenes antiguas se conservan como historia. El siguiente paquete corresponde a su propio commit documental, manteniendo la etiqueta probada28c73cd.


Exportación documental dev.41 inicial bfe15dd:229 archivos, CRC/inventario/huellas individuales correctos, PDF18 páginas adjunto. ZIP SHA256988b29c2e81ae451ef0b2319f261c42b17915d0285df7bd40cb45e9b64306abf. La primera ejecución usó el destino por defecto backups/entrega; se conservó y se exportó explícitamente a output/entrega. La verificación posterior detectó que MATRIZ aún describía C-09 abierto; se sincronizó la matriz y se prepara una exportación documental posterior con esas referencias coherentes. No cambia aplicación ni imágenes probadas.


Exportación documental dev.41 final: fuente 636ef74f748afc0933687c09620402c32a0d3fae, ZIP output/entrega/eventify-desarrollo-636ef74f748a/eventify-source.zip, 229 archivos, SHA256 618552996894d5ce5ca0612a305a5c7e7e9da9b3916b02af6cc0b34fdfc6c18a. CRC, inventario y cada huella individual correctos; matriz C-09 coherente. PDF18 páginas adjunto, SHA256 d4e5534d8163722e29da2dfa4dd9434faac52698cbff079371b1aa38b2dbe173. entrega-manifest.json identifica ambos. Paquetes anteriores conservados; no se afirma instalación nueva de este ZIP ni Windows.

## Seguimiento del clima público dev.41 — 10oct11:26CST

La captura del usuario muestra un fallo real del pronóstico en el detalle público. Se verificó el registro de Render del deploy dev.41: `eventify_weather reason=http_status status=429` a las11:15:32 y11:26:25CST. El segundo registro corresponde a un único reintento desde el botón de la página, después de los diez minutos de caché negativa; volvió a mostrarse el aviso sin bloquear la reserva. No se vació la caché, reinició el presupuesto ni desactivó TLS.

En el contenedor web local dev.41, el cliente real obtuvo una respuesta con16 fechas. `allow_url_fopen=1` y certificados predeterminados de OpenSSL disponibles. Esto confirma que el runtime corregido puede consultar al proveedor; no prueba disponibilidad del pronóstico desde Render. El HTTP429 confirma rechazo por límite del proveedor; una IP compartida del alojamiento es una posibilidad, no una causa demostrada. Open-Meteo documenta límites y ausencia de garantía de disponibilidad en su plan gratuito: https://open-meteo.com/en/pricing.

RF-15 mantiene la evidencia anterior de integración útil y el manejo del error, pero la consulta pública actual sigue indisponible. No registrar este incidente como resuelto ni atribuirlo a Windows/Linux: ocurre en la conexión desde el servicio público. No hubo cambios de código, dependencias, configuración o datos de negocio en esta revisión.

## B07/RF-15 — Respaldo meteorológico en preparación

Objetivo: mostrar un pronóstico real en Render cuando Open-Meteo rechace la consulta, sin gasto, rotación de IP, proxies externos ni debilitamiento de TLS. Rama codex/b07-clima-respaldo, dev.42. Se conserva Open-Meteo y se añade MET Norway como proveedor independiente de respaldo. Criterios: respuesta útil y atribución visibles, muestras por fecha Costa Rica sin inventar extremos o probabilidad de lluvia, errores conservan reservas, caché y límites respetados. Sin migraciones ni cambios de datos de negocio.

MET Norway Locationforecast es gratuito, sin cuenta ni clave, datos CC BY4.0, cobertura mundial y horizonte de hasta nueve días; tampoco ofrece garantía de disponibilidad. Se identifican las peticiones con el nombre y URL pública real de Eventify. Coordenadas del evento redondeadas a cuatro decimales, nunca ubicación del visitante. Caché de respuesta por ubicación durante al menos una hora y hasta Expires, revalidación If-Modified-Since y soporte gzip; redirecciones limitadas al recurso HTTPS de api.met.no. Máximo dos consultas de proveedor por intento de presupuesto: hasta60/h y20/min, muy inferior a20/s; la caché evita repetir entre eventos de una misma ubicación. Las fechas fuera del respaldo siguen dependiendo de Open-Meteo; no presentar ese horizonte como equivalente a16 días.

Fuentes verificadas10oct: https://api.met.no/doc/TermsOfService, https://api.met.no/doc/License y https://api.met.no/doc/locationforecast/HowTO. Prueba exploratoria HTTPS200 con89 muestras para San José ficticio; no acredita funcionamiento desde Render.

Verificación candidata dev.42: imágenes local/pública construidas con las mismas dependencias nativas corregidas, sintaxis PHP y unidades aprobadas. Clima amplía39→58 controles: fecha Costa Rica, cobertura incompleta, unidades/valores/fechas inválidos, fuente, caché compartida, respaldo, error de ambos proveedores y resultado anterior/caducidad. Integración aislada MySQL:234 controles correctos, proyecto eventify-weather42-tests con datos efímeros, detenido al terminar sin tocar instalaciones existentes. Sintaxis JavaScript correcta con Node del runtime disponible.

Cliente MET Norway real desde el contenedor:17,9–26,4°C en las muestras del12oct; segunda lectura conserva contenido y archivo de caché. La primera comprobación exploratoria usó igualdad estricta de arrays JSON y falló por tipos numéricos tras serializar; corregida a equivalencia de contenido más hash de archivo, sin cambiar comportamiento ni datos. La construcción inicial de imágenes llevaba56 controles; las imágenes finales repiten58. Publicación todavía no verificada en este punto.

### Aplicación y resultado público de dev.42

Commit bd7e3e5802c91e3a81412e7cae76060a7feec42a con push confirmado. Solo se recreó la web8086 con eventify-weather-web:0.1.0-dev.42, conservando MySQL dev.41, volúmenes y diez tablas idénticas. IAB verificó detalle con atribución nueva; el pronóstico local sigue desactivado por su configuración previa, sin modificarla para la prueba. Cliente real MET y simulación del fallo principal se verificaron por separado.

Render deploy dep-db57g12jnfac739gk1eg, iniciado10oct11:38:12CST, Live después de27,1s, mismo commit. La comparación Aiven detectó cambio únicamente en weather_budget; nueve tablas restantes, incluidas cuentas/eventos/reservas/tickets, conservan filas y huellas. La primera comparación estricta informó diferencia; se comprobó por tabla sin atribuir identidad al contador de solicitudes. No hubo escrituras de negocio de esta revisión.

Recorrido IAB público: detalle del taller, pronóstico real de MET Norway para12oct con muestras17,9–26,4°C, fuente y fecha10oct11:39CST visibles. No muestra probabilidad diaria inventada; ingreso para reservar sigue disponible. Evidencia storage/weather-dev42/public-weather.png y public-weather.txt. El incidente observado queda corregido mediante respaldo, sin prometer disponibilidad permanente ni cubrir con MET fechas fuera de su horizonte. Dependencias nativas conservadas; el escaneo de cero avisos documentado corresponde a las imágenes dev.41, no se presenta como un nuevo escaneo de dev.42.

Seguridad pública posterior:81 controles HTTPS correctos, sin escrituras de negocio. Revisión adaptable: ancho solicitado390px, ancho efectivo reportado por el navegador375px, contenido375px y panel327px; captura completa revisada, sin desbordamiento horizontal. Vista de escritorio y atribución correctas. El viewport temporal se restauró. No se repitió instalación limpia Linux ni se modificó Windows.

Documento técnico dev.42 actualizado: 18 páginas renderizadas y revisadas, sin texto fuera de página; diagrama y explicación incluyen ambos proveedores. PDF SHA256 62970d6de74941b0eca0232678043d5ed5da2fffbff6081ae1de90e09d5e0e44. Etiqueta anotada v0.1.0-dev.42 apunta al código probado bd7e3e5; push confirmado. La exportación documental posterior conserva su propia referencia.
