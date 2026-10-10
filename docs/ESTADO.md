# EVENTIFY: estado actual y continuidad

Corte: 9 de octubre de 2026, America/Costa_Rica. Versión **0.1.0-dev.37**, todavía sin certificación estable. Leer este resumen y el bloque activo de PLAN; consultar requisitos por identificador. Los registros anteriores se conservan íntegros en [HISTORIAL.md](HISTORIAL.md).

## Alcance y autorizaciones

- Aplicación académica PHP/MySQL, HTML/CSS/JavaScript/jQuery/AJAX y sesiones. Desarrollo Linux y entrega local Docker Compose Linux/Windows. No modificar Código Tico.
- Commits y push progresivos autorizados desde el 8 de octubre. Repositorio privado [USUARIO_DESTINO/eventify](https://github.com/USUARIO_DESTINO/eventify), remoto SSH; sin push forzado ni reescritura. Main conserva `3da680b74b41fa3da4069879edc1f649de9b97f2`.
- Render Free/Aiven MySQL Free autorizados para evaluación, presupuesto ₡0, sin tarjeta ni planes pagados. El receptor del paquete local no necesita cuentas en esos proveedores ni asociar GitHub.
- Estudiante: Kendall Gamboa Arce. Docente: Rita Robles Loaiza. Fecha de entrega omitida por indicación del usuario. Comprensión y defensa requieren evidencia humana.
- Windows queda al final. Zoom200% es opcional; diseño adaptable y compatibilidad Chrome/Firefox se mantienen.

## Referencias vigentes

| Destino | Referencia y alcance |
| --- | --- |
| Rama actual | `codex/b07-mysql-minimal`, desde `8554ef4`. Ensayo de imagen derivada, sin cambiar aplicación o imagen de entrega. Paquete conservado en su referencia documental. |
| Aplicación comprobada | `ab710a671d40024bf84cc46026af4c4cd54a948d`, dev.37. Los cambios documentales posteriores no modifican su código. |
| Etiqueta conservada | `v0.1.0-dev.37` resuelve a `7c3a3e28435566349efcd4b2a66eace89de978d2`; push confirmado. No moverla para actualizar documentos. |
| Web pública | https://eventify-academico.onrender.com, commit `7c3a3e28435566349efcd4b2a66eace89de978d2`, dev.37. |
| Render | Servicio `srv-db3vutm7bikc73ajj6og`, Free/Oregon/AutoDeployOff. Deploy `dep-db4n8cbncjis73c7m4t0`, Live el 9 de octubre a las17:09:37 CST, 34,8s. Referencia anterior `390b1ef` conservada para rollback. |
| Local8086 | Web actualizada a dev.37; base y volúmenes conservados. Proyecto `eventify-clean-20261009035638-598f411d`. Instalación limpia/persistencia originales corresponden a dev.33 `f4d6c5a05011c57e47295b06cedf11129a052681`. |
| Paquete documental | `output/entrega/eventify-desarrollo-44bd3a9a9360/eventify-source.zip`, commit `44bd3a9a936005db52bda8de65c90037803ae335`, 213 archivos. Misma aplicación, configuración, migraciones, dependencias e imágenes declaradas que la web pública. |
| PDF acompañado | Documento técnico de16 páginas, actualizado con publicación dev.37 y limitaciones; todavía borrador de evaluación. |

ZIP SHA256: `d9494a9798e542ab3a47e3df9181ac7df01fbd8262a36b5df92b41055e138bf4`.
PDF SHA256: `a0189715ec1c8337738762718f628fc260ad8fb20021e57ea8a7522f4326b361`.

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
| MySQL dev.33 | gawk5.1.0-6.el9_8.1, servidor8.4.11; construcción,219 controles y TLS aprobados. Escaneo69→66 filas, solo tres gawk eliminadas. | `storage/mysql-image-check-gpZ3It99` |
| Firefox humano | Firefox157.0.1/Linux Mint: búsqueda, foco y calendario abierto; historial cancelado/ticket/QR al200%; rechazo administrativo con estilo al110%. Capturas parciales, no recorrido completo. | `storage/firefox-20261009` |
| Windows parcial | PowerShell5.1 real: seis scripts sin errores de parser. VM Win11 LTSC Evaluation,4GiB; sin Docker/WSL ni instalación completa acreditada. | Ver VERIFICACION-WINDOWS |

Registro, catálogo/AJAX, respuesta perdida de reserva, errores de clima y administración tienen aceptación IAB anterior con sus referencias en [evidencias/B07.md](evidencias/B07.md). IAB no sustituye Firefox. Restauración aislada anterior tiene evidencia propia; no certifica transferencia a Windows.

## Seguridad: estado y límites

- Dependencias PHP fijadas: consulta Packagist de9oct sin avisos para las dos bibliotecas. jQuery local con huella/licencia comprobadas. Esto no audita el sistema operativo.
- PHP/Apache conserva424 filas:1 crítica,91 altas,162 medias,166 bajas y4 sin clasificación;156 identificadores únicos. Base Trivy del8oct19:05UTC. MySQL conserva66 filas. Conteos de escaneos anteriores, sin exclusiones; no presentarlos como un escaneo nuevo.
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

- Docker29.1.3/Compose2.40.3 en Linux; la sesión no tiene acceso al socket ni sudo no interactivo. Operaciones Docker necesarias requieren terminal del usuario; no modificar grupos/socket/sudoers.
- Configuración, cuentas ficticias, CA, snapshots y logs en storage/backups ignorados por Git. Nunca imprimirlos ni incluirlos en ZIP/imágenes. Cuentas locales8086: `storage/clean-20261009035638-598f411d/demo-credentials.json`.
- Conservar destinos anteriores8080–8085 y todos sus volúmenes. Su disponibilidad actual no se presume. No borrar datos para resolver fallos ni reutilizar puertos deteniendo servicios ajenos.
- Rollback Git recupera código, no MySQL; revisar migraciones y respaldos antes de actualizar. Dev.37 no cambió esquema respecto a la publicación anterior.
- En este corte solo se ordenó documentación: estado anterior archivado íntegro, resumen y cierre sincronizados. Sin pruebas Linux repetidas ni cambios de aplicación, bases, permisos o servicios.

## Siguiente ensayo: MySQL sin Shell

Se prepara una candidata para retirar mysql-shell y sus bibliotecas Python, sin modificar Dockerfile.mysql ni los destinos existentes. La base RPM exportada no muestra otros paquetes que requieran sus capacidades; el entrypoint solo lo menciona en un comentario. Es una posibilidad de reducción de superficie, no una corrección acreditada de los19 avisos Python.

`scripts/test-mysql-minimal-linux.sh` construye una derivada aislada, exige RPM sin dependencias forzadas, compara inventario y huellas de las herramientas conservadas, ejecuta integración y respaldo/restauración ficticios y exporta la imagen. Bash-n y Python embebido correctos; configuración generada sin puertos/montajes del usuario, con red interna/tmpfs y permisos privados comprobados. No construcción ni pruebas de runtime todavía. Docker y sudo no interactivo reconsultados: ambos sin acceso.

Próxima intervención necesaria: ejecutar el ensayo en la terminal del usuario. Después revisar resultados, escanear la candidata y comprobar TLS antes de considerar su incorporación. [Alcance y criterios](ENSAYO-MYSQL-MINIMAL.md). Windows queda al final; Firefox conserva la comprobación humana pendiente. No se repitieron pruebas sobre las instalaciones Linux existentes.
