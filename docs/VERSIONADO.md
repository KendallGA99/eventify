# Versionado, publicación y recuperación

## Política adoptada

El usuario autorizó commits y push progresivo a GitHub el 2026-10-08. Se reutiliza la identidad Git comprobada en Código Tico y la cuenta USUARIO_DESTINO, sin modificar ese proyecto. Repositorio privado creado: https://github.com/USUARIO_DESTINO/eventify. Se adoptó la propuesta privada como opción inicial durante el desarrollo. Remoto SSH: REPOSITORIO_DESTINO_PENDIENTE.

Cada avance coherente se guarda en un commit con referencia Bxx/RF. Las etiquetas son inmutables y se publican junto con la rama de trabajo; no sobrescribirlas ni hacer push forzado. `VERSION` identifica el código; `CHANGELOG.md` describe el alcance y la evidencia.

- `checkpoint-b00`: documentación inicial, commit 44da548.
- `v0.1.0-dev.1`: primera integración de código preparada; pruebas reales pendientes. Se crea después del commit correspondiente.
- `v0.1.0-dev.2`: catálogo/filtros e historial jQuery (B05 parcial), integración MySQL/API pendientes; rama codex/b05-catalogo.
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
