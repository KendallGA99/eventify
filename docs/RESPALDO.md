# Ensayo de respaldo y restauración QA

Preparado en dev.11, pendiente de Docker/MySQL real. Objetivo B07/U-03: comprobar conservación antes de diseñar la entrega de datos a otra computadora. No usar este ensayo para restaurar datos sobre la instancia normal, producción o una base existente.

## Comando único en Linux

Desde el repositorio:

```bash
bash scripts/verify-qa-linux.sh
```

Primero actualiza QA con el código actual y comprueba persistencia. Después exporta e importa en un proyecto nuevo exclusivo del ensayo. Requiere Docker/Compose y acceso al servicio; sudo solicitará contraseña solo en la terminal si corresponde. No introduce PHP/MySQL locales ni amplía permisos del host. No sustituye pruebas de Windows.

## Datos e impacto

- Origen fijo eventify-qa, base eventify_qa; puerto 8081. La instancia normal 8080 queda intacta.
- Solo web QA se detiene durante snapshot/exportación para que no entren escrituras mientras se comparan los datos. Se reinicia después de exportar y en la salida por fallo cuando haya quedado detenida. No ejecutar migraciones/otras escrituras manuales mientras corre el ensayo.
- Exportación de las nueve tablas del esquema actual mediante mysqldump. SQL privado conserva cuentas ficticias y sus hashes, eventos, reservas/tickets, migraciones y límites; no imprimir ni subir este archivo a Git.
- Archivos duraderos transferidos: demo-credentials.json y qa-persistence-marker.bin. Galería SVG pertenece al código/imagen de la misma versión, no a uploads. Sesiones, caché meteorológica y locks no se transfieren. Si se agregan uploads u otros archivos persistentes, ampliar el protocolo antes de considerar completa una entrega.
- Destino: proyecto eventify-restore-fecha-aleatorio, base eventify_restore y volúmenes nuevos. Verifica que esté vacía antes de importar; claves del destino nuevas. No importa una copia de .env ni concede privilegios globales.
- Compara recuentos y hash de filas ordenadas por PK para nueve tablas, hash de los dos archivos y galería. Diferencias rechazan el ensayo. No implica prueba funcional HTTP del sistema restaurado ni ensayo de rollback de versiones.
- Detiene db del destino al finalizar, conservando sus volúmenes para inspección; no ejecuta down -v ni prune. Repetir crea otro destino y consume espacio, no reemplaza el anterior. La limpieza queda como acción separada expresamente autorizada.

## Resultado privado y límites

Registro conjunto en storage/qa/verification-status.txt y verification.log. Cada ensayo conserva storage/restore-ID con status.txt/test.log, restore.env, database.sql, storage.tar y snapshots de huellas. Directorio 700 y archivos 600 mediante umask; todo queda fuera de Git/raíz pública. No compartir las credenciales/registros ni el SQL de forma pública.

Solo exit_code=0, comparación correcta y mensaje final permiten registrar aprobación del ensayo ejecutado. Sintaxis y Docker simulado no equivalen a restauración real. Copia limpia Linux, Windows, transferencia a receptor y funcionamiento público permanecen separados.

## Verificación dirigida de la preparación

```bash
bash -n scripts/test-restore-qa-linux.sh scripts/verify-qa-linux.sh
python3 tests/orchestration/qa_restore.py
```

Las 40 aserciones del segundo comando usan Docker simulado para fallos de exportación/destino/comparación, reinicio de origen, rechazo de importaciones y privacidad de archivos. No descargan imágenes ni acceden a MySQL.

Se consultó el [manual oficial de mysqldump MySQL 8.4](https://dev.mysql.com/doc/refman/8.4/en/mysqldump.html): respaldo lógico reproducible, single-transaction para InnoDB y no-tablespaces/GTID OFF para evitar exigir privilegios globales innecesarios. Se omiten DROP TABLE y locks de carga; el destino debe ser vacío. SQL se transmite por bytes dentro de Linux/contenedores; una futura implementación PowerShell debe evitar redirección UTF-16 y validarse en Windows.
