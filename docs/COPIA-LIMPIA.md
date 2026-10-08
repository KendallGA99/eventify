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
