# Ensayo de herramientas de construcción

B07/C-09/SEG-09. Ensayo derivado aprobado el 9 de octubre de 2026 sobre b14fee3. Dev.34 incorpora ahora su limpieza a los Dockerfile; la comprobación final también fue aprobada sobre 6d41df5. Los paquetes e instalaciones dev.33 conservan su evidencia.

## Objetivo y aceptación

Comprobar si se pueden retirar binutils, compiladores, cabeceras y otras herramientas de desarrollo después de instalar PDO y dependencias. La revisión LOW detectó avisos repetidos de estos paquetes; retirar una herramienta del runtime reduce su disponibilidad allí, pero no corrige la cadena de construcción ni las bibliotecas que permanezcan.

Se construyen variantes derivadas local y pública. El ensayo debe conservar Apache, PHP/PDO y las bibliotecas requeridas; aprobar sintaxis, unidades e integración con MySQL en cada variante, y guardar inventarios e imágenes para el escaneo posterior. Un resultado correcto permite evaluar el siguiente cambio; no acredita por sí solo reducción de avisos, navegación completa o entrega estable.

## Procedimiento

Desde una terminal Linux con acceso a Docker:

```bash
cd "/home/usuario/Documentos/ChatGPT/Proyecto U"
bash scripts/verify-runtime-release-linux.sh
```

La ruta anterior corresponde al equipo de desarrollo; el script resuelve la raíz desde su propia ubicación. Las herramientas usadas por el ensayo son Docker/Compose y Python 3 del host, no requisitos adicionales del receptor para ejecutar Eventify.

Se construyen imágenes con nombres únicos. Los nombres de paquetes proceden de dpkg y un patrón cerrado; la eliminación usa purge sin autoremove. Primero se simula el plan y se rechaza si retira componentes requeridos; después se comprueban su presencia, PDO y configuración Apache. La distinción entre eliminación y autoremove está en el [manual oficial de APT](https://manpages.debian.org/trixie/apt/apt-get.8.en.html).

La integración usa un proyecto Compose propio, sin puertos, montajes del host o volúmenes persistentes. MySQL guarda sus datos en tmpfs y recibe claves nuevas, ajenas a .env. La configuración privada se crea en storage/runtime-check-*; no compartirla ni agregarla a Git. El cierre retira únicamente los contenedores y red de ese proyecto, sin borrar imágenes de evidencia ni tocar otros proyectos. El proceso deja status.txt, test.log, inventarios antes/después, identificadores y exportaciones local.tar/public.tar.

## Verificación realizada al preparar

- Sintaxis Bash y POSIX sh correctas; git diff --check sin errores.
- Generador Python real ejecutado y configuración normalizada mediante Docker Compose, sin necesitar daemon.
- Proyecto único, red interna, MySQL tmpfs, sin build heredado, puertos, montajes, privilegios ni lectura del .env del proyecto.
- Patrón revisado con herramientas amd64 y bibliotecas protegidas; gcc-14-base no se selecciona para eliminación.

El ensayo derivado terminó con exit0: 266 unidades y 219 comprobaciones MySQL por variante. Trivy conserva 424 registros frente a 2.011, sin pares nuevos; no implica ausencia de vulnerabilidades. Los registros están en storage/runtime-check-doJmuSur.

El comando actual verifica las imágenes finales dev.34 y el arranque público/TLS en proyectos efímeros. El script test-runtime-minimal-linux.sh corresponde al experimento b14fee3 anterior a la incorporación; no repetirlo sobre imágenes ya depuradas. La verificación final terminó con exit0 en runtime-release-pdzRD8Jx; los escaneos finales conservan los 424 avisos del ensayo. Docker sigue sin acceso directo desde esta sesión. La instalación Linux dev.33 ya aprobada conserva su resultado.
