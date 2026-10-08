# Instalación local de Eventify

## Estado de esta guía

Stack Docker Linux con PHP/MySQL, autenticación, reservas y administración implementados y probados parcialmente; ver [ESTADO.md](ESTADO.md). QA comprobó persistencia tras recrear contenedores. Arranque desde ZIP Linux dev.17 y restauración de datos QA comprobados; Windows, HTTP del destino restaurado y criterios finales siguen pendientes. La página inicial conserva el aviso de construcción hasta cerrar los requisitos.

## Requisitos comunes

- Docker con Compose v2 que admita `up --wait` (v2.20 o posterior como base de esta guía).
- Contenedores Linux, también cuando el host es Windows.
- Internet para descargar imágenes y construir por primera vez. La API externa necesita conexión; sus fallos se manejan sin bloquear reservas.
- Puerto local 8080 libre; modificar APP_PORT antes de iniciar si está ocupado.
- Espacio para imágenes/volúmenes y memoria suficiente para Docker/PHP/MySQL.

No hace falta instalar PHP ni MySQL en el host. El proyecto puede residir en una carpeta con espacios. La aplicación solo se expone en 127.0.0.1; MySQL no publica un puerto al host.

## Linux Mint del desarrollador

Equipo detectado: Linux Mint 22.3 basado en Ubuntu noble, x86_64. Docker no estaba instalado al iniciar B01 y sudo requiere contraseña interactiva.

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

Si el usuario no tiene acceso directo al servicio Docker, el script solicita sudo en su terminal para las operaciones Docker. No guarda la contraseña. No solicitarla al agente.

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

Scripts preparados para Windows PowerShell 5.1+, sin requerir bash/PHP/MySQL locales. No han sido ejecutados ni certificados en Windows todavía.

## Configuración y persistencia

Para el ensayo aislado de exportación/restauración y sus resultados reales, consultar [RESPALDO.md](RESPALDO.md). No restaurar sobre la instancia normal ni confundir una preparación de scripts con una prueba aprobada.

- `.env.example` contiene marcadores, no contraseñas utilizables. Los scripts crean `.env` y tres claves aleatorias diferentes. Nunca subir `.env` a GitHub ni incluirlo en el paquete público.
- Si `.env` ya existe, no se sobrescribe. Si contiene marcadores, el inicio falla y solicita corregirlo.
- APP_PORT configura puerto; APP_TIMEZONE define la zona de presentación futura. PHP/MySQL operan en UTC.
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

La imagen instala temporalmente `unzip` para extraer los paquetes de Composer durante el build y lo retira después. No se requiere instalarlo en Linux/Windows del receptor. Si aparece «zip extension and unzip/7z commands are both missing», comprobar que se está construyendo el Dockerfile vigente (dev.6 o posterior) y repetir la verificación; no borrar volúmenes para corregir este fallo de construcción.

Para comprobar únicamente un cambio en las pruebas de integración/MySQL, use `bash scripts/verify-integration-linux.sh` en Linux. Ejecuta el stack aislado de pruebas y guarda `storage/integration-status.txt` e `storage/integration.log` privados; conserva los volúmenes/datos de la aplicación. Este resultado es independiente del registro de verify-dev-linux.sh. Requiere acceso Docker y puede solicitar sudo en esa terminal. No sustituye pruebas unitarias o de entrega final cuando cambió la aplicación.


## Entorno separado de evaluación en Linux

`bash scripts/start-qa-linux.sh` levanta el proyecto Compose `eventify-qa` en http://localhost:8081 con base/volúmenes y configuración propios. No usa el override de desarrollo: el código se copia a la imagen. El script aplica migraciones, crea datos ficticios de forma idempotente y ensaya recreación de contenedores conservando volúmenes; compara diez tablas y archivos. No interactúe con QA hasta que termine la comparación para no alterar el snapshot durante la prueba. La instancia normal en 8080 conserva sus datos. Si 8081 está ocupado, resolver el conflicto antes de continuar; no detener servicios ajenos por inferencia.

Credenciales QA: `storage/qa/demo-credentials.json`, privadas/mode 600, nunca subir a Git o compartir. Claves de base: `storage/qa/qa.env`; registro/resultado: `storage/qa/start.log` y `status.txt`. La repetición conserva configuración/demostración existente. No usar down -v/reset para repetir. Es una preparación de QA: una ejecución correcta no certifica Windows, restauración, navegador completo ni URL pública.

## Cierre de entrega y comprensión

El ensayo desde ZIP Linux y sus límites están en COPIA-LIMPIA.md. Para el receptor Windows, seguir VERIFICACION-WINDOWS.md y registrar evidencia real antes de afirmar compatibilidad. DEFENSA.md ofrece un recorrido, mapa del código y ejercicios de comprensión; participación/dominio del estudiante permanecen como evidencia humana pendiente.
