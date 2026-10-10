# Versionado, publicación y recuperación

## Política adoptada

El usuario autorizó commits y push progresivo a GitHub el 2026-10-08. Se reutiliza la identidad Git comprobada en Código Tico y la cuenta USUARIO_DESTINO, sin modificar ese proyecto. Repositorio privado creado: https://github.com/USUARIO_DESTINO/eventify. Se adoptó la propuesta privada como opción inicial durante el desarrollo. Remoto SSH: REPOSITORIO_DESTINO_PENDIENTE.

Cada avance coherente se guarda en un commit con referencia Bxx/RF. Las etiquetas son inmutables y se publican junto con la rama de trabajo; no sobrescribirlas ni hacer push forzado. `VERSION` identifica el código; `CHANGELOG.md` describe el alcance y la evidencia.

- `checkpoint-b00`: documentación inicial, commit 44da548.
- `v0.1.0-dev.1`: primera integración de código preparada; pruebas reales pendientes. Se crea después del commit correspondiente.
- `v0.1.0-dev.2`: catálogo/filtros e historial jQuery (B05 parcial), integración MySQL/API pendientes.
- `v0.1.0-dev.3`: API meteorológica B05, cliente real/fixtures probados; imagen e integración MySQL pendientes.
- `v0.1.0-dev.4`: gestión administrativa B06 parcial; validación/vistas probadas; MySQL/permisos/carreras pendientes.
- Versiones estables `v0.x.y`: solo después de pasar pruebas aplicables, revisar datos/migraciones y registrar evidencia. Una etiqueta de desarrollo conserva código, no certifica funcionamiento.

No reconstruir artificialmente commits antiguos para aparentar que todos los bloques se cerraron. El código actual B01–B04 se guarda como una integración de desarrollo con pendientes explícitos. Los cambios siguientes sí se versionan por incremento coherente.

## Flujo por incremento

1. Revisar rama/cambios y requisitos; desarrollar en una rama de trabajo.
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

Push inicial verificado: main y la rama de trabajo en a0e0102; checkpoint-b00 apunta a 44da548 y v0.1.0-dev.1 a a0e0102. Los commits documentales posteriores registran esta evidencia sin mover las etiquetas.

Incremento B05 parcial publicado y verificado: v0.1.0-dev.2 → bbae88b; la rama de trabajo en ese código antes de commits documentales de evidencia. main conserva 3da680b. No se ejecutó CI ni se marcó estable.

API B05 publicada y verificada: v0.1.0-dev.3 → 9ad4df4; main sigue 3da680b. Integración MySQL/imagen pendiente, sin CI ni despliegue público.

Administración B06 parcial publicada y verificada: v0.1.0-dev.4 → 779d4e9; main conserva 3da680b. MySQL/permisos/carreras y resto de B06 pendientes; sin CI ni despliegue.

Dashboard y consulta B06 publicados y verificados: v0.1.0-dev.5 → b5bc784; main conserva 3da680b. 177 reglas en herramienta temporal, integración objetivo pendiente; sin CI ni despliegue.

Dev.7 → 994ec41 fue verificada después de publicar: ejecución Docker Linux 2026-10-08T13:40:03Z exit_code=0, 177 reglas/QR y 152 checks MySQL/HTTP. No mover etiqueta ni main por registrar evidencia. Próximos cambios de código necesitan pruebas afectadas; no repetir suite por cambios puramente documentales.

Dev.8 → 27c91bf verificada en integración Docker Linux el 2026-10-08T13:52:00Z: exit_code=0, 170 checks MySQL/HTTP con cuatro carreras administrativas. Unidades/QR mantienen evidencia dev.7 porque la ampliación modifica solo tests/wrapper. Registrar aprobación sin mover etiqueta ni main; entrega estable sigue pendiente.


Dev.23 publicado: la rama de trabajo y etiqueta anotada `v0.1.0-dev.23` apuntan al commit `3badc8517d3a57edb6cd234711f82ecdd1115202`; push y referencias verificados con git ls-remote. Incremento de ensayo, no estable: PHP/MySQL y arranque sin TLS pendientes de ejecución. Dev.22 y anteriores conservados; main sin cambios.


Dev.24 publicado y verificado por git ls-remote: la rama de trabajo y etiqueta anotada `v0.1.0-dev.24` apuntan a `6baa7548fb0d6af0761c58be6c5bbdf98bba48b8`. Corrige evaluador tras ensayo dev.23 parcial; nuevo ensayo real pendiente. Etiquetas anteriores/main conservados, sin versión estable.


Dev.25 → fb461ade36531e3c61710321372e3b307eba6977: corrección de acceso CA del trabajador; push de rama confirmado, desplegada manualmente en Render Free y flujos públicos básicos/reinicio/persistencia verificados. Etiqueta de desarrollo, sin estable. Main conserva referencia previa; Git rollback no restaura MySQL. Primer deploy dev.24 cancelado conservado como incidencia.


Dev.26 → cabd6f335d8952332ce6d0a71fcc515bb6f5b176: redacción inicial y diagnóstico seguro del clima. Render Live, lint y 83 comprobaciones de clima/presupuesto aprobadas en construcción. Respuesta externa HTTP429 identificada; pronóstico público pendiente. Etiqueta de desarrollo, sin marcar estable ni mover main. No modifica esquema o datos.


Dev.27 → deb3eeb5115e018c45d9969447bedb36a9d8534e: pruebas HTTPS y documentación. Push de rama y etiqueta v0.1.0-dev.27 confirmado con git ls-remote, incluida referencia anotada resuelta. 81 comprobaciones públicas aprobadas; Render mantiene aplicación dev.26. Main y etiquetas anteriores conservados.


Dev.28 → b92ed44968ac9116b35bf003a41486930f7e0d71: documento técnico y generador PDF actualizados; 15 páginas revisadas y ZIP de 185 archivos exportado por commit con integridad comprobada. Sin cambios funcionales, sin nuevo despliegue y sin instalación limpia de este ZIP; no estable.

Dev.29 → 8ef9bb6c9d17013793234b1334dfdd3f44c4a653: corrección Debian xz/liblzma preparada en ambas imágenes y ensayo aislado. Push de rama y etiqueta anotada v0.1.0-dev.29 confirmado por ls-remote, incluida referencia resuelta. Construcción, unidades y escaneo del parche pendientes; etiqueta de desarrollo, no estable. Render conserva dev.26, copia limpia dev.28; main intacta. El ensayo no actualiza esos destinos.


Dev.30 → 390b1ef6dcbb9a9d76eaad8a910a80da364bb637: rama y etiqueta anotada confirmadas por ls-remote. Construcción real de ambas variantes y unidades/QR aprobadas; escaneo confirma eliminación de los cuatro avisos xz/liblzma sin nuevos avisos. Los restantes siguen abiertos. No estable; Render continúa dev.26 y copias anteriores conservadas. Esta evidencia supera el pendiente de construcción de dev.29.


Dev.31 → 4088cbe34aef3e9c7ec12ead72b9781fd61efac8: revisión editorial de documentos, guías y comentarios existentes. Rama y etiqueta anotada v0.1.0-dev.31 confirmadas con git ls-remote. PDF de15 páginas revisado y ZIP exportado desde esta referencia; aplicación igual a dev.30. Sin despliegue ni nueva instalación del paquete; etiqueta de desarrollo.


## Corte documental dev.32

Rama codex/b09-cierre-documental desde2d0fb3c, sin modificar main ni reescribir historial. Dev.32 incorpora nombres confirmados, revisión de teclado y publicación dev.30; agrega elección de puerto e inventario de módulos al ensayo de entrega. Aplicación sin cambios respecto a dev.30, pero nuevos guiones de verificación con Docker pendientes de ejecución. Etiqueta de desarrollo; no estable. Render permanece en390b1ef, con AutoDeployOff y configuración de rama existente.


## Dev.33: revisión de seguridad

Rama codex/b07-avisos-restantes desde6a8443b; main/etiquetas anteriores conservadas. Imagen MySQL derivada con parche gawk preparado, construcción/regresión/escaneo todavía pendientes. Código PHP igual a dev.30. Etiqueta de desarrollo, no estable; no se despliega automáticamente ni se actualizan bases existentes. Revisión de binario gosu e inventario son evidencia distinta de la corrección todavía no ejecutada.

Dev.33 publicada y referencias verificadas con git ls-remote: commit2f5ab7f5735eb7a4e58ff725d14f5ae9ea8afc03, etiqueta anotada b2ca86ad5474146423e752b793e6afd443d4f5d9, rama codex/b07-avisos-restantes. Etiqueta resuelta al mismo commit. Sin despliegue, construcción real todavía pendiente; main intacta.


Dev.33 verificada posteriormente sobre3489d430bf94c649c7bf7e61d6fb0e95fb531a33: ensayo mysql-image-check-gpZ3It99 exit0, gawk corregido,219 controles y TLS positivo/negativos. Escaneo confirma eliminación de tres avisos gawk sin nuevos; etiqueta no se mueve por registrar evidencia. No estable ni actualización de instalaciones/publicación. main y versiones anteriores conservadas.


Cierre documental Linux dev.33: 227461a59f429b755f7258de68c412c69209d07c confirmado en remoto. Manual/PDF y revisión inicial LOW actualizados; fuente del paquete conserva f4d6c5a, instalación y persistencia comprobadas. Windows, Firefox y límites de seguridad abiertos; etiqueta anterior no movida, main intacta y sin nuevo despliegue.


## Dev.34: incorporación del runtime reducido

Rama codex/b07-runtime-aislado, basada en 58e3b66. El ensayo b14fee3 aprobó imágenes derivadas, unidades e integración; dev.34 incorpora esa misma receta en ambos Dockerfile. Construcción final y TLS pendientes. No se cambia main, la etiqueta dev.33, los paquetes existentes ni el despliegue público.


Dev.34 comprobada posteriormente sobre 6d41df54a4df19c7c5895de6eac8120d263809d3: runtime-release-pdzRD8Jx exit0, construcción final/unidades/TLS y escaneos idénticos al ensayo reducido. Sin despliegue; main y paquetes dev.33 conservados. Las actualizaciones siguientes solo registran esta evidencia.


## Dev.37: experiencia verificada y exportación

Implementación d8829a7, ajuste de expectativa de prueba ab710a6 y corte documental 7c3a3e28435566349efcd4b2a66eace89de978d2. Integración aislada225 controles aprobada sobre ab710a6; experiencia y perfil comprobados después en web8086. Rama codex/b07-experiencia-final y etiqueta anotada v0.1.0-dev.37 confirmadas en origin; etiqueta resuelta a7c3a3e2. Main conservada. Fuente exportada:213 archivos con manifiesto y huellas en output/entrega/eventify-desarrollo-7c3a3e284355. Paquete de desarrollo; sin instalación nueva de este ZIP, sin certificación Windows y sin nuevo deploy. El registro posterior no mueve la etiqueta.


Publicación dev.37: Render despliega7c3a3e28435566349efcd4b2a66eace89de978d2, mismo commit de etiqueta/paquete; dep-db4n8cbncjis73c7m4t0 Live el9oct17:09:37CST. Comparación de diez tablas y112 controles HTTPS dirigidos correctos. Referencia anterior390b1ef conservada para rollback compatible sin cambio de esquema. Rama de seguimiento y AutoDeployOff conservados; desplegar un commit específico no cambia la rama configurada. Registro posterior en codex/b08-publicacion-dev37; no mueve la etiqueta ni declara estabilidad final.


## Dev.39: presentación y publicación

Implementación0e6be15, corte0e3aae247da50deaa0150be4e235728cbe3db12a.225 controles MySQL/HTTP y recorrido IAB local aprobados. Etiqueta anotada v0.1.0-dev.39 confirmada en origin y resuelta a0e3aae2. Render Free publicó ese mismo commit, dep-db52frajnfac7390qf80 Live: diez tablas conservadas y117 controles HTTPS posteriores. Main y versiones anteriores intactos; desarrollo, sin certificación estable. Documentación posterior conserva su referencia propia para exportación.


## Dev.41: corrección nativa comprobada

Rama codex/b07-runtime-corregido, commit28c73cdd63386527ad4a07e03a8c1385198d9bf7 y etiqueta anotada v0.1.0-dev.41 confirmados en origin. Tres imágenes finales sin avisos en base Trivy10oct12:33UTC;234 controles, unidades y TLS positivo/negativo aprobados. Web y MySQL8086 actualizados, diez tablas y ocho reservas/tickets conservados. Render Free publicó el mismo commit, dep-db5744rrjlhs73clgpj0, Live10oct11:13:25CST; diez tablas conservadas y117 controles HTTPS posteriores. Main y referencias anteriores intactas. La documentación y exportación posteriores conservan su propio commit; no mueven esta etiqueta ni certifican Windows.

## Respaldo meteorológico dev.42

Rama codex/b07-clima-respaldo; código bd7e3e5802c91e3a81412e7cae76060a7feec42a con push confirmado. Render dep-db57g12jnfac739gk1eg, iniciado10oct11:38:12CST y Live tras27,1s. Local8086 conserva MySQL dev.41 y volúmenes. Pruebas:58 controles de clima,234 MySQL/HTTP y81 HTTPS públicos; pronóstico de respaldo visible y muestra móvil revisada. Código dev.41 y su despliegue anterior conservados para rollback; sin cambio de esquema. Los documentos y el paquete posterior tienen su propia referencia y no alteran el código publicado. No marcar estable: Windows, Firefox restante y defensa humana pendientes.
