# Ensayo de instalación desde ZIP en Linux

B07/U-01/U-03: comprobar la entrega desde una referencia Git concreta en una ruta con espacios, sin copiar configuración o datos personales. No certifica Windows, conexión pública ni reserva por tener los contenedores disponibles.

Desde el repositorio, con Docker/Compose, Python 3, Git y openssl disponibles para el desarrollador:

```bash
bash scripts/test-clean-linux.sh v0.1.0-dev.17
```

El receptor no requiere Python/Git: estas herramientas preparan el ensayo y ZIP. Su arranque normal sigue siendo `bash scripts/start-linux.sh` desde la carpeta extraída según INSTALACION.md.

El ensayo necesita acceso Docker; sudo solicita contraseña solo en su terminal si hace falta. Exporta exclusivamente el commit (rechaza cambios versionados sin commit), extrae un ZIP en `storage/clean-ID/Copia limpia con espacios/Eventify` y crea claves nuevas. El proyecto Compose `eventify-clean-ID` usa puerto 8083 y volúmenes nuevos, sin montajes del repositorio ni lectura de su .env. La base se llama eventify_qa dentro de este proyecto aislado para usar el comprobador existente; no comparte la base/volúmenes de eventify-qa.

Ejecuta el script de inicio incluido en el ZIP, aplica migraciones y siembra demostración explícitamente. Luego recrea web/db conservando volúmenes y compara tablas/archivos/imágenes. Los registros, referencia del ZIP y credenciales de prueba quedan privados en storage/clean-ID. No imprimir contraseñas ni subir logs/ZIP/env a Git.

Deja la aplicación disponible para comprobar en navegador: catálogo, login de ambos roles, reserva/ticket/QR, cancelación/historial y datos tras recreación. Solo entonces registrar esos criterios como aprobados. API desactivada para este ensayo: no certifica la API externa ni modifica su implementación.

No borra volúmenes ni detiene otras instancias. Cada ejecución usa un destino nuevo; si 8083 ya está ocupado, la nueva aplicación fallará al iniciar. Inspeccionar y gestionar los destinos previos como tarea separada, nunca eliminar datos para conseguir que el ensayo pase. Detener el destino probado con Docker Compose stop si procede, usando project.txt y el .env de esa copia; conservarlo para evidencia.

Dev.15 falló: sudo eliminó COMPOSE_PROJECT_NAME y el arranque recreó contenedores eventify con otra configuración, conservando volúmenes. No repetir esa versión. Dev.16 pasa --project explícito y comprueba el nombre/volúmenes/montajes/puerto del Compose efectivo antes de arrancar. Recuperar primero la instancia normal con bash scripts/start-dev-linux.sh desde el repositorio original. Corrección probada con sudo/Docker simulados; ejecución real de copia limpia y navegador pendientes. La portabilidad Windows sigue requiriendo un equipo compatible real.

Dev.16 aisló correctamente el destino y aprobó migraciones/persistencia, pero Apache devolvió500 por permisos600/700 de la extracción. Dev.17 conserva el directorio padre privado y normaliza la fuente644/directorios755 antes de crear .env600. No usar dev.16 para certificar HTTP. Cada nuevo ensayo sigue conservando los destinos anteriores; 8083 debe quedar libre al reemplazar el destino fallido.

Resultado dev.17 real: clean-20261008160801-e96614c4, 2026-10-08 16:08:01 UTC, exit0. Arranque/migraciones/demostración/recreación con persistencia inicial aprobados; Chrome confirmó login de ambos roles, reserva de dos entradas/ticket/QR y cancelación con historial. API desactivada. No certifica reserva nueva tras otra recreación, Windows ni todos los criterios finales.

## Continuar un ensayo existente después del navegador

`bash scripts/verify-existing-linux.sh <ID-clean> <ID-restore>` verifica los dos destinos y sus volúmenes existentes antes de mutar. Requiere Python3/curl en el host desarrollador, acceso Docker y ensayos previos aprobados. Recrea únicamente la copia limpia, compara todo el snapshot actual (incluye reservas/cancelaciones nuevas) y arranca el destino restaurado en8082 sin repetir importación, migraciones o demostración. No construye imágenes nuevas. Deja ambos disponibles para revisión autenticada; no borra volúmenes ni toca eventify/eventify-qa. El snapshot es consistente mientras web de la copia está detenido; si falla, intenta volver a iniciarlo.

Los IDs se eligen de los registros privados, no por buscar el más reciente automáticamente. Configuración local/base/puerto/montajes/volúmenes y propiedad se validan; un destino ausente o ajeno se rechaza. Registros en storage/existing-ID, incluyendo status, snapshots y credenciales restauradas, no se entregan ni se versionan. Seis respuestas HTTP200 prueban arranque básico, no login/reserva/QR; el recorrido de navegador sigue separado. Script preparado y ocho guardas simuladas correctas; ejecución real aún pendiente.
