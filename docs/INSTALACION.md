# Instalación local de Eventify

## Estado de esta guía

El proyecto incluye autenticación, catálogo, reservas, tickets con QR y administración. La referencia de desarrollo es dev.38; la publicación es dev.37. La instalación limpia Linux y la persistencia tienen evidencia sobre dev.33; dev.38 añade la imagen MySQL reducida con integración y TLS comprobados. El ZIP nuevo no tiene aceptación de instalación propia en Windows. Consulte [ESTADO.md](ESTADO.md) para distinguir estos resultados.

Ejecute los comandos desde la carpeta raíz de EVENTIFY.

## Entrega al compañero y cuentas externas

La entrega local consiste en un ZIP limpio y su manual. El receptor instala/configura Docker con contenedores Linux una vez, descomprime el paquete y ejecuta `iniciar-windows.cmd` en Windows o `bash scripts/start-linux.sh` en Linux. Compose inicia PHP y un MySQL propio en su computador; `DB_HOST: db` no utiliza Aiven. No necesita cuentas de Aiven, Render o GitHub ni iniciar sesión con la cuenta del desarrollador. Las cuentas de administrador/usuario de EVENTIFY son cuentas internas de prueba, gestionadas según la guía de datos.

La URL pública es un entregable adicional del enunciado y se administra por separado mediante Render/Aiven. Abrir esa URL no exige cuentas de hosting; gestionar o trasladar su alojamiento sí requiere acceso administrativo autorizado al proveedor. No compartir la contraseña personal del desarrollador ni incluir producción `.env`, certificados/credenciales privados, registros de `storage/` o asociaciones de GitHub en el ZIP. El paquete usa configuración local propia, generada por los scripts.

El primer arranque necesita Internet para imágenes/dependencias; la API meteorológica también requiere conexión. La entrega no debe describirse como ejecutable sin preparación del equipo: Docker/virtualización son requisitos del host. La instalación completa en Windows sigue pendiente de prueba real; los controles del paquete final deben confirmar que arranca sin configuración del desarrollador ni acceso al alojamiento público.

## Requisitos comunes

- Docker con Compose v2 que admita `up --wait` (v2.20 o posterior como base de esta guía).
- Contenedores Linux, también cuando el host es Windows.
- Internet para descargar imágenes y construir por primera vez. La API externa necesita conexión; sus fallos se manejan sin bloquear reservas.
- Puerto local 8080 libre; modificar APP_PORT antes de iniciar si está ocupado.
- Espacio para imágenes/volúmenes y memoria suficiente para Docker/PHP/MySQL.

No hace falta instalar PHP ni MySQL en el host. El proyecto puede residir en una carpeta con espacios. La aplicación solo se expone en 127.0.0.1; MySQL no publica un puerto al host.

## Linux Mint del desarrollador

Equipo detectado: Linux Mint 22.3 basado en Ubuntu noble, x86_64. Docker se instaló durante B01. Los permisos del servicio dependen de cada equipo; la instalación no modifica automáticamente grupos o sudoers.

Desde la carpeta del proyecto, instalar por una única vez:

```bash
bash scripts/install-docker-linux.sh
```

El script instala `docker.io` y `docker-compose-v2` de los repositorios de la distribución y habilita el servicio Docker. La contraseña se introduce en la terminal de sudo; nunca compartirla por chat. No elimina instalaciones existentes, volúmenes o bases, ni agrega automáticamente usuarios al grupo docker. Si encuentra Docker ya instalado, verifica Compose y no lo reemplaza.

Para otras distribuciones, seguir sus instrucciones oficiales. Linux Mint es un derivado: Docker no lo valida como plataforma oficial propia; aquí se usa el empaquetado de la distribución y deberá verificarse su funcionamiento real.

Después:

```bash
bash scripts/start-linux.sh
```

El script genera `.env` con claves únicas solo si no existe, valida Compose, construye e inicia servicios, espera a las comprobaciones de disponibilidad y aplica migraciones sin reinicializar los datos. Cuando termine correctamente: `http://localhost:8080`.

Si el usuario no tiene acceso directo al servicio Docker, el script solicita sudo en su terminal para las operaciones Docker. No guarda la contraseña. Introducirla únicamente en la terminal que solicita sudo.

Detener sin borrar datos:

```bash
bash scripts/stop-linux.sh
```

Verificaciones actuales del bloque:

```bash
bash scripts/test-linux.sh
```

Requiere el stack iniciado; comprueba sintaxis PHP, configuración, reglas de reservación y conexión PDO/MySQL. Incluye reglas actuales de registro/reserva/catálogo/clima/administración y QR; la integración MySQL/HTTP aislada se ejecuta por separado.

La prueba de integración MySQL/HTTP se ejecuta con `bash scripts/test-database-linux.sh`, en una base efímera aislada. La demostración opcional se ejecuta con `bash scripts/demo-linux.sh`. Más detalles en [DATOS.md](DATOS.md).

## Desarrollo local con cambios inmediatos (Linux)

Durante la construcción puede usar `bash scripts/verify-dev-linux.sh`: guarda registro y resultado en storage/verification.log y storage/verification-status.txt (privados, ignorados por Git y fuera de la raíz pública). aplica el override `docker-compose.dev.yml`, verifica y mantiene el mismo almacenamiento de la aplicación. App/public/config/bin/tests/database se montan de solo lectura desde el proyecto, por lo que los cambios en PHP/HTML/CSS se ven sin reconstruir. No se montan .env ni todo el repositorio dentro de la raíz pública.

Cambios de extensiones, dependencias o imagen todavía requieren reconstrucción; nuevas migraciones requieren ejecutarse expresamente. La suite MySQL aislada sigue requiriendo Docker y no usa los volúmenes de negocio. La entrega y sus pruebas de copia limpia utilizan `start-linux.sh` o el inicio Windows, con el código incorporado en la imagen.

Para volver a la imagen empaquetada: `bash scripts/start-linux.sh`; conserva datos. Este modo de desarrollo no certifica la entrega Windows.

## Windows del receptor

1. Comprobar requisitos vigentes en [Docker Desktop para Windows](https://docs.docker.com/desktop/setup/install/windows-install/). Registrar versión de Windows, arquitectura y disponibilidad de virtualización; no dar por compatible cualquier Windows.
2. Instalar Docker Desktop, habilitar su backend Linux compatible (normalmente WSL2), reiniciar si lo requiere y abrir Docker Desktop.
3. Descomprimir el proyecto completo.
4. Ejecutar `iniciar-windows.cmd` o, desde PowerShell en la carpeta, `powershell.exe -NoProfile -ExecutionPolicy Bypass -File .\scripts\start-windows.ps1`.
5. Abrir la URL que indique el script al finalizar correctamente.

El ajuste ExecutionPolicy se aplica al proceso lanzado, no cambia la política permanente del sistema. Los scripts deben provenir del paquete del proyecto que se revisó. Si una política administrada los impide, resolverlo con el administrador del equipo; no alterar políticas globales.

Para detener: `detener-windows.cmd`. Para pruebas: `powershell.exe -NoProfile -ExecutionPolicy Bypass -File .\scripts\test-windows.ps1`.

Scripts preparados para Windows PowerShell 5.1+, sin requerir bash/PHP/MySQL locales. La sintaxis de seis scripts fue analizada en Windows PowerShell5.1 real; su ejecución completa con Docker y la compatibilidad de entrega siguen pendientes.

## Configuración y persistencia

Para el ensayo aislado de exportación/restauración y sus resultados reales, consultar [RESPALDO.md](RESPALDO.md). No restaurar sobre la instancia normal ni confundir una preparación de scripts con una prueba aprobada.

- `.env.example` contiene marcadores, no contraseñas utilizables. Los scripts crean `.env` y tres claves aleatorias diferentes. Nunca subir `.env` a GitHub ni incluirlo en el paquete público.
- Si `.env` ya existe, no se sobrescribe. Si contiene marcadores, el inicio falla y solicita corregirlo.
- APP_PORT configura puerto; APP_TIMEZONE define la zona de presentación. PHP/MySQL operan en UTC.
- Los datos MySQL residen en el volumen `mysql_data`, los archivos en `app_storage`.
- Detener/iniciar y reconstruir la imagen no deben borrar esos volúmenes. Persistencia por recreación comprobada en QA; la instalación receptora sigue requiriendo su propia validación.
- No ejecutar `docker compose down -v`, `docker volume prune` ni eliminar `.env` para reparar errores. Puede perder datos o romper acceso al volumen existente.
- Las variables MYSQL_* crean el usuario/base solo en el primer volumen vacío; cambiar la contraseña en `.env` no cambia la almacenada en una base existente. Planificar una rotación real, no resetear datos.
- Transferir código a otra computadora crea una instalación independiente. Para transferir datos existentes se requiere exportación/restauración separada; consultar RESPALDO.md y verificar el destino.

## Errores comunes

| Mensaje/situación | Acción |
| --- | --- |
| Docker no encontrado | Instalar Docker siguiendo la sección de su sistema. |
| Servicio Docker no disponible | Abrir Docker Desktop o comprobar servicio/permisos en Linux. |
| Puerto ocupado | Cambiar APP_PORT a un puerto libre en .env. |
| Imagen no descarga | Revisar conexión; no desactivar verificación TLS. |
| Timeout de disponibilidad | Consultar logs con `docker compose logs --tail 50 web db`; redactar cualquier dato sensible antes de compartir. |
| Salud devuelve 503 | PHP no consigue consultar MySQL; no significa que la conexión esté aprobada. Revisar servicio y configuración. |

## Fuentes consultadas en B01

- [Instalación Docker Engine y derivados](https://docs.docker.com/engine/install/).
- [Docker Desktop para Windows](https://docs.docker.com/desktop/setup/install/windows-install/).
- [Docker Compose](https://docs.docker.com/compose/).

Las condiciones pueden cambiar; comprobar nuevamente antes de preparar la entrega Windows final.

La imagen instala temporalmente `unzip` para extraer los paquetes de Composer durante el build y lo retira después. No se requiere instalarlo en Linux/Windows del receptor. Si aparece «zip extension and unzip/7z commands are both missing», comprobar que se está construyendo el Dockerfile vigente (incluido en su paquete) y repetir la verificación; no borrar volúmenes para corregir este fallo de construcción.

Para comprobar únicamente un cambio en las pruebas de integración/MySQL, use `bash scripts/verify-integration-linux.sh` en Linux. Ejecuta el stack aislado de pruebas y guarda `storage/integration-status.txt` e `storage/integration.log` privados; conserva los volúmenes/datos de la aplicación. Este resultado es independiente del registro de verify-dev-linux.sh. Requiere acceso Docker y puede solicitar sudo en esa terminal. No sustituye pruebas unitarias o de entrega final cuando cambió la aplicación.


## Entorno separado de evaluación en Linux

`bash scripts/start-qa-linux.sh` levanta el proyecto Compose `eventify-qa` en http://localhost:8081 con base/volúmenes y configuración propios. No usa el override de desarrollo: el código se copia a la imagen. El script aplica migraciones, crea datos ficticios de forma idempotente y ensaya recreación de contenedores conservando volúmenes; compara diez tablas y archivos. No interactúe con QA hasta que termine la comparación para no alterar el snapshot durante la prueba. La instancia normal en 8080 conserva sus datos. Si 8081 está ocupado, resolver el conflicto antes de continuar; no detener servicios ajenos por inferencia.

Credenciales QA: `storage/qa/demo-credentials.json`, privadas/mode 600, nunca subir a Git o compartir. Claves de base: `storage/qa/qa.env`; registro/resultado: `storage/qa/start.log` y `status.txt`. La repetición conserva configuración/demostración existente. No usar down -v/reset para repetir. Es una preparación de QA: una ejecución correcta no certifica Windows, restauración, navegador completo ni URL pública.

## Cierre de entrega y comprensión

El ensayo desde ZIP Linux y sus límites están en COPIA-LIMPIA.md. Para el receptor Windows, seguir VERIFICACION-WINDOWS.md y registrar evidencia real antes de afirmar compatibilidad. DEFENSA.md ofrece un recorrido, mapa del código y ejercicios de comprensión; participación/dominio del estudiante permanecen como evidencia humana pendiente.


## Ensayo de una nueva referencia sin ocupar destinos anteriores

Desde el repositorio de desarrollo, `bash scripts/test-clean-linux.sh f4d6c5a05011c57e47295b06cedf11129a052681 8086` exporta esa referencia, genera configuración nueva e inicia un proyecto aislado en una ruta con espacios. El puerto es opcional; por defecto8083. Seleccione uno libre: no detenga otras instalaciones para reutilizar su puerto. Este ensayo requiere Git/Python/OpenSSL en el equipo de desarrollo; el receptor no los necesita para iniciar el ZIP ya extraído.

Los datos de demostración se crean solo en el destino nuevo del ensayo, explícitamente; los volúmenes anteriores permanecen. Al terminar correctamente quedan registros y credenciales ficticias privados en storage/clean-ID. Para la referencia f4d6c5a, el destino8086 ya aprobó recorrido de navegador y persistencia posterior. Ejecutar nuevamente este guion crearía otra copia distinta; no repetirlo para acreditar la ya comprobada. Windows sigue pendiente.
