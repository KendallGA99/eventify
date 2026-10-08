# Paquete de código EVENTIFY

B09/U-03/ENT-B: herramienta de preparación de entrega. El ZIP de desarrollo no certifica instalación limpia Linux/Windows, URL pública, restore real ni defensa. Los pendientes están en ESTADO/MATRIZ.

## Generación por el desarrollador

Desde el repositorio, con Python 3 y Git del host:

```bash
python3 scripts/package-source.py --ref v0.1.0-dev.14
```

Exporta el commit exacto de la referencia; no copia la carpeta de trabajo. Rechaza cambios de archivos versionados sin commit, referencias incompletas, secretos/SQL de datos/logs versionados, enlaces y submódulos. .env.example sí se incluye; storage solo admite .gitkeep. Esta revisión de nombres no reemplaza la revisión de contenido y secretos antes de versionar.

Salida privada en backups/entrega/eventify-desarrollo-SHA12: eventify-source.zip, manifest.json, SHA256SUMS.txt y LEAME-ENTREGA.txt. El manifiesto contiene referencia, commit, VERSION, huella del ZIP y huella/tamaño de cada archivo. SHA256SUMS permite verificar con sha256sum -c SHA256SUMS.txt en Linux; Windows puede comparar Get-FileHash eventify-source.zip -Algorithm SHA256 con el texto.

El ZIP incluye una carpeta Eventify y conserva los archivos del commit. No incluye .git, .env personal, vendor, volúmenes ni contenido de MySQL. Composer instala desde composer.lock durante la construcción de Docker; necesita Internet inicialmente. Una exportación de datos existentes se entrega separadamente y con permisos adecuados, siguiendo RESPALDO.md.

Si la misma referencia ya tiene un paquete en ese destino, el comando falla sin sobrescribirlo. Para un segundo ensayo indicar --output-dir backups/otra-entrega. Una exportación que falla puede dejar su directorio parcial para revisión; solo entregar un paquete cuya generación e integridad terminaron correctamente. No usar este ZIP como respaldo de reservas ya creadas.

## Persona receptora

No necesita Python ni Git para ejecutar el ZIP. Debe tener Docker/Compose, extraerlo en una carpeta local y seguir Eventify/docs/INSTALACION.md. El primer inicio genera claves únicas; la demostración local se carga mediante la operación separada del manual. No enviar claves del entorno del desarrollador.

Verificaciones separadas antes de entrega final: integridad del archivo recibido, arranque desde copia limpia, login ambos roles, reserva/ticket/cancelación, administración, API y persistencia; Linux y Windows deben tener evidencia propia. La etiqueta identifica código; no convierte pruebas pendientes en pruebas aprobadas.

## Evidencia inicial

13 comprobaciones en repositorios ficticios: referencia/manifest/huellas, exclusión de .env no versionado, permisos privados, conservación de entrega previa, rechazo de cambios sin commit, reproducibilidad ZIP y rechazo de .env/storage/certificados/SQL/enlaces versionados. Sin Docker ni datos reales. Exportación real se registra en ESTADO cuando ocurra.
