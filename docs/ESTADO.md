# EVENTIFY: estado actual y continuidad

Corte: 10 de octubre de 2026, America/Costa_Rica. Versión **0.1.0-dev.38**, todavía sin certificación estable. Leer este resumen y el bloque activo de PLAN; consultar requisitos por identificador. Los registros anteriores se conservan íntegros en [HISTORIAL.md](HISTORIAL.md).

## Alcance y autorizaciones

- Aplicación académica PHP/MySQL, HTML/CSS/JavaScript/jQuery/AJAX y sesiones. Desarrollo Linux y entrega local Docker Compose Linux/Windows. No modificar Código Tico.
- Commits y push progresivos autorizados desde el 8 de octubre. Repositorio privado [USUARIO_DESTINO/eventify](https://github.com/USUARIO_DESTINO/eventify), remoto SSH; sin push forzado ni reescritura. Main conserva `3da680b74b41fa3da4069879edc1f649de9b97f2`.
- Render Free/Aiven MySQL Free autorizados para evaluación, presupuesto ₡0, sin tarjeta ni planes pagados. El receptor del paquete local no necesita cuentas en esos proveedores ni asociar GitHub.
- Estudiante: Kendall Gamboa Arce. Docente: Rita Robles Loaiza. Fecha de entrega omitida por indicación del usuario. Comprensión y defensa requieren evidencia humana.
- Windows queda al final. Zoom200% es opcional; diseño adaptable y compatibilidad Chrome/Firefox se mantienen.

## Referencias vigentes

| Destino | Referencia y alcance |
| --- | --- |
| Rama actual | `codex/b07-mysql-minimal`, desde `8554ef4`. Receta MySQL reducida dev.38; imagen final comprobada sobre9321f68. Aplicación pública y paquete dev.37 conservados. |
| Aplicación comprobada | `ab710a671d40024bf84cc46026af4c4cd54a948d`, dev.37. Los cambios documentales posteriores no modifican su código. |
| Etiqueta conservada | `v0.1.0-dev.37` resuelve a `7c3a3e28435566349efcd4b2a66eace89de978d2`; push confirmado. No moverla para actualizar documentos. |
| Web pública | https://eventify-academico.onrender.com, commit `7c3a3e28435566349efcd4b2a66eace89de978d2`, dev.37. |
| Render | Servicio `srv-db3vutm7bikc73ajj6og`, Free/Oregon/AutoDeployOff. Deploy `dep-db4n8cbncjis73c7m4t0`, Live el 9 de octubre a las17:09:37 CST, 34,8s. Referencia anterior `390b1ef` conservada para rollback. |
| Local8086 | Web actualizada a dev.37; base y volúmenes conservados. Proyecto `eventify-clean-20261009035638-598f411d`. Instalación limpia/persistencia originales corresponden a dev.33 `f4d6c5a05011c57e47295b06cedf11129a052681`. |
| Paquete dev.38 | `output/entrega/eventify-desarrollo-2a738b703ad1/eventify-source.zip`, commit `2a738b703ad1a129ed53d75e2da9d784dd6f37af`,217 archivos, manifiesto/CRC/huellas correctos. Imagen MySQL final probada; aplicación pública permanece dev.37. Este ZIP no se ha instalado de nuevo en Linux ni Windows. |
| Paquete documental dev.37 conservado | `output/entrega/eventify-desarrollo-44bd3a9a9360/eventify-source.zip`, commit `44bd3a9a936005db52bda8de65c90037803ae335`, 213 archivos. Misma aplicación, configuración, migraciones, dependencias e imágenes declaradas que la web pública. |
| PDF dev.38 | Documento técnico de17 páginas, actualizado con reducción MySQL, publicación dev.37 y limitaciones; todavía borrador de evaluación. |

Huellas del paquete dev.38 indicado arriba:

ZIP SHA256: `1a4e84d401218fb5db71c5d0cb26428d3168c9ac4e484f912368070e450a7dee`.
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

## Seguridad: estado y límites

- Dependencias PHP fijadas: consulta Packagist de9oct sin avisos para las dos bibliotecas. jQuery local con huella/licencia comprobadas. Esto no audita el sistema operativo.
- PHP/Apache conserva424 filas:1 crítica,91 altas,162 medias,166 bajas y4 sin clasificación;156 identificadores únicos. Base Trivy del8oct19:05UTC. MySQL dev.33 conserva66 filas; la candidata sin Shell conserva47, con comparación de la misma base, sin exclusiones. La imagen final dev.38 confirmó las mismas47 filas con identidad OCI verificada. Conteos de escaneos anteriores, sin exclusiones; no presentarlos como un escaneo nuevo.
- Libxml2/CVE-2026-6653 sigue vulnerable en Trixie según Debian. Digest PHP oficial sin cambios en la revisión previa. Parche nativo examinado, no instalado: modifica parser y estructuras públicas; exige pruebas de compatibilidad que las unidades de EVENTIFY no cubren.
- Apache/CVE-2026-63292 también sigue vulnerable en el paquete; su módulo mod_vhost_alias no estaba cargado en las imágenes examinadas. Esto delimita exposición, no corrige el paquete.
- Sin entrada XML directa encontrada en la aplicación; clima consume JSON limitado y QR genera SVG numérico. No descarta consumidores nativos. Gosu tiene análisis de funciones propio; Python/mysqlsh, kernel/host y demás avisos conservan sus límites.
- Referencias: [SEGURIDAD.md](SEGURIDAD.md), [AVISOS-IMAGENES.md](AVISOS-IMAGENES.md), [AVISOS-XML.md](AVISOS-XML.md), [AVISOS-BASE.md](AVISOS-BASE.md), [AVISOS-RESIDUALES.csv](AVISOS-RESIDUALES.csv). No hay aceptación automática del riesgo ni certificación estable.

## Pendientes y próxima acción

1. **C-07 Firefox:** completar limpieza/combinación de filtros, selección/borrado de fecha y teclado, perfil dev.37, logout/ticket y panel administrativo. Se solicitó comprobación humana: el control disponible no expone Firefox. [Procedimiento](VERIFICACION-FIREFOX.md).
2. **C-09 seguridad:** dependencia libxml2 y condiciones residuales siguen abiertas. No repetir escaneos con la misma base ni compilaciones sin una corrección candidata; no ocultar avisos o sustituir bibliotecas sin regresión.
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
