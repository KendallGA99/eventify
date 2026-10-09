# Versionado, publicación y recuperación

## Política adoptada

El usuario autorizó commits y push progresivo a GitHub el 2026-10-08. Se reutiliza la identidad Git comprobada en Código Tico y la cuenta USUARIO_DESTINO, sin modificar ese proyecto. Repositorio privado creado: https://github.com/USUARIO_DESTINO/eventify. Se adoptó la propuesta privada como opción inicial durante el desarrollo. Remoto SSH: REPOSITORIO_DESTINO_PENDIENTE.

Cada avance coherente se guarda en un commit con referencia Bxx/RF. Las etiquetas son inmutables y se publican junto con la rama de trabajo; no sobrescribirlas ni hacer push forzado. `VERSION` identifica el código; `CHANGELOG.md` describe el alcance y la evidencia.

- `checkpoint-b00`: documentación inicial, commit 44da548.
- `v0.1.0-dev.1`: primera integración de código preparada; pruebas reales pendientes. Se crea después del commit correspondiente.
- `v0.1.0-dev.2`: catálogo/filtros e historial jQuery (B05 parcial), integración MySQL/API pendientes; rama codex/b05-catalogo.
- `v0.1.0-dev.3`: API meteorológica B05, cliente real/fixtures probados; imagen e integración MySQL pendientes; rama codex/b05-clima.
- `v0.1.0-dev.4`: gestión administrativa B06 parcial; validación/vistas probadas; MySQL/permisos/carreras pendientes; rama codex/b06-admin.
- Versiones estables `v0.x.y`: solo después de pasar pruebas aplicables, revisar datos/migraciones y registrar evidencia. Una etiqueta de desarrollo conserva código, no certifica funcionamiento.

No reconstruir artificialmente commits antiguos para aparentar que todos los bloques se cerraron. El código actual B01–B04 se guarda como una integración de desarrollo con pendientes explícitos. Los cambios siguientes sí se versionan por incremento coherente.

## Flujo por incremento

1. Revisar rama/cambios y requisitos; desarrollar en rama codex/.
2. Ejecutar comprobaciones aplicables, revisar secretos/migraciones y actualizar evidencia/estado.
3. Commit pequeño con propósito; no incluir .env, vendor, storage, copias de BD ni claves.
4. Push de rama y etiquetas de desarrollo. GitHub queda como respaldo verificable mediante SHA remoto.
5. Integración/revisión y pruebas completas antes de marcar estable. main inicia con esta primera base de desarrollo; no representa una release estable. Tras validar una versión estable, main conserva esa referencia y los siguientes cambios pasan por ramas/revisión.

## Código y datos se recuperan de manera distinta

Git conserva código/configuración de ejemplo/migraciones, no el contenido de MySQL ni los archivos privados del volumen. Un push no sustituye respaldo de la base. Antes de una actualización con impacto en datos, guardar una copia consistente de MySQL y archivos privados, con claves tratadas aparte.

Para revertir un cambio de código compatible con la base actual, revisar el diff y crear un commit con `git revert <SHA>`; probarlo y publicarlo. No utilizar reset/push forzado como procedimiento habitual, ni ejecutar comandos con marcadores sin sustituirlos por el SHA revisado.

Si una migración ya se aplicó, volver a una versión antigua que carece de ella puede bloquear el ejecutor y ser incompatible con los datos. Preparar una corrección hacia adelante o una versión de código que conserve el historial de migraciones y sea compatible. No borrar el registro técnico ni alterar firmas para forzar el arranque. Restaurar una copia de datos es una operación distinta: requiere revisar impacto y autorización si reemplaza datos existentes.

No borrar volúmenes. Conservar imagen/commit anterior y respaldo antes de una actualización estable. Validar salud, autenticación, reservas y persistencia después de recuperar. La recuperación real todavía debe ensayarse en una instalación aislada en B07/B08; este documento no certifica un rollback ejecutado.

## Pruebas en GitHub

Workflow preparado con permisos mínimos, checkout fijado, Docker/MySQL aislado, unidades, HTTP y JavaScript. Inicialmente solo ejecución manual (`workflow_dispatch`), sin despliegue ni servicios de negocio. No se dispara al publicar este checkpoint. Activación automática pendiente de confirmar cuota gratuita/política de gasto de Actions para el repositorio privado.

Registrar URL del remoto, rama/SHA/etiquetas publicados y resultados reales de checks en ESTADO. Si falla un check, conservar la versión previa y corregir; no marcarla estable por tener push exitoso.

## Referencias publicadas

Push inicial verificado: main y codex/plan-eventify en a0e0102; checkpoint-b00 apunta a 44da548 y v0.1.0-dev.1 a a0e0102. Los commits documentales posteriores registran esta evidencia sin mover las etiquetas.

Incremento B05 parcial publicado y verificado: v0.1.0-dev.2 → bbae88b; rama codex/b05-catalogo en ese código antes de commits documentales de evidencia. main conserva 3da680b. No se ejecutó CI ni se marcó estable.

API B05 publicada y verificada: v0.1.0-dev.3 → 9ad4df4, rama codex/b05-clima; main sigue 3da680b. Integración MySQL/imagen pendiente, sin CI ni despliegue público.

Administración B06 parcial publicada y verificada: v0.1.0-dev.4 → 779d4e9, rama codex/b06-admin; main conserva 3da680b. MySQL/permisos/carreras y resto de B06 pendientes; sin CI ni despliegue.

Dashboard y consulta B06 publicados y verificados: v0.1.0-dev.5 → b5bc784, rama codex/b06-dashboard; main conserva 3da680b. 177 reglas en herramienta temporal, integración objetivo pendiente; sin CI ni despliegue.

Dev.7 → 994ec41 fue verificada después de publicar: ejecución Docker Linux 2026-10-08T13:40:03Z exit_code=0, 177 reglas/QR y 152 checks MySQL/HTTP. No mover etiqueta ni main por registrar evidencia. Próximos cambios de código necesitan pruebas afectadas; no repetir suite por cambios puramente documentales.

Dev.8 → 27c91bf verificada en integración Docker Linux el 2026-10-08T13:52:00Z: exit_code=0, 170 checks MySQL/HTTP con cuatro carreras administrativas. Unidades/QR mantienen evidencia dev.7 porque la ampliación modifica solo tests/wrapper. Registrar aprobación sin mover etiqueta ni main; entrega estable sigue pendiente.


Dev.23 publicado: rama `codex/b08-rechazo-sin-tls` y etiqueta anotada `v0.1.0-dev.23` apuntan al commit `3badc8517d3a57edb6cd234711f82ecdd1115202`; push y referencias verificados con git ls-remote. Incremento de ensayo, no estable: PHP/MySQL y arranque sin TLS pendientes de ejecución. Dev.22 y anteriores conservados; main sin cambios.


Dev.24 publicado y verificado por git ls-remote: `codex/b08-rechazo-sin-tls` y etiqueta anotada `v0.1.0-dev.24` apuntan a `6baa7548fb0d6af0761c58be6c5bbdf98bba48b8`. Corrige evaluador tras ensayo dev.23 parcial; nuevo ensayo real pendiente. Etiquetas anteriores/main conservados, sin versión estable.


Dev.25 → fb461ade36531e3c61710321372e3b307eba6977: corrección de acceso CA del trabajador; push de rama confirmado, desplegada manualmente en Render Free y flujos públicos básicos/reinicio/persistencia verificados. Etiqueta de desarrollo, sin estable. Main conserva referencia previa; Git rollback no restaura MySQL. Primer deploy dev.24 cancelado conservado como incidencia.


Dev.26 → cabd6f335d8952332ce6d0a71fcc515bb6f5b176: redacción inicial y diagnóstico seguro del clima. Render Live, lint y 83 comprobaciones de clima/presupuesto aprobadas en construcción. Respuesta externa HTTP429 identificada; pronóstico público pendiente. Etiqueta de desarrollo, sin marcar estable ni mover main. No modifica esquema o datos.


Dev.27 → deb3eeb5115e018c45d9969447bedb36a9d8534e: pruebas HTTPS y documentación. Push de rama y etiqueta v0.1.0-dev.27 confirmado con git ls-remote, incluida referencia anotada resuelta. 81 comprobaciones públicas aprobadas; Render mantiene aplicación dev.26. Main y etiquetas anteriores conservados.


Dev.28 → b92ed44968ac9116b35bf003a41486930f7e0d71: documento técnico y generador PDF actualizados; 15 páginas revisadas y ZIP de 185 archivos exportado por commit con integridad comprobada. Sin cambios funcionales, sin nuevo despliegue y sin instalación limpia de este ZIP; no estable.

Dev.29 → 8ef9bb6c9d17013793234b1334dfdd3f44c4a653: corrección Debian xz/liblzma preparada en ambas imágenes y ensayo aislado. Push de rama y etiqueta anotada v0.1.0-dev.29 confirmado por ls-remote, incluida referencia resuelta. Construcción, unidades y escaneo del parche pendientes; etiqueta de desarrollo, no estable. Render conserva dev.26, copia limpia dev.28; main intacta. El ensayo no actualiza esos destinos.


Dev.30 → 390b1ef6dcbb9a9d76eaad8a910a80da364bb637: rama y etiqueta anotada confirmadas por ls-remote. Construcción real de ambas variantes y unidades/QR aprobadas; escaneo confirma eliminación de los cuatro avisos xz/liblzma sin nuevos avisos. Los restantes siguen abiertos. No estable; Render continúa dev.26 y copias anteriores conservadas. Esta evidencia supera el pendiente de construcción de dev.29.
