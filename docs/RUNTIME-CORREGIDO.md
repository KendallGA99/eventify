# Corrección del entorno de ejecución — B07

El 10 de octubre se eligió corregir las dependencias nativas, en lugar de aceptar los riesgos de la base Debian anterior. La aplicación conserva PHP 8.4, Apache, MySQL 8.4 y sus bibliotecas PHP. No cambia el esquema ni los datos.

## Imagen web

Las recetas local y pública usan Alpine 3.24.2, fijada por digest, y paquetes de sus repositorios main/community. Apache 2.4.69 incorpora la corrección de CVE-2026-63292. libxml2 2.13.9-r2 supera la corrección de CVE-2026-6653, incorporada originalmente en 2.11.0; la versión Debian anterior volvía a una base 2.9.14 aunque su nombre empezara por 2.12.7. El diagnóstico se contrasta con el [tracker Debian](https://security-tracker.debian.org/tracker/CVE-2026-6653), no solo con la comparación de nombres de paquetes.

Se fijan también Expat 2.8.5, OpenSSL 3.5.9 y zlib 1.3.2-r1. El inventario completo y la fecha de la base del escáner acompañan la evidencia privada. PHP sigue en 8.4.26. Composer conserva su versión, huella y composer.lock; unzip se retira después de instalar las dos bibliotecas.

La configuración Apache es explícita: solo módulos necesarios, raíz pública, directorios internos inaccesibles, índices desactivados y reescritura de rutas. Los pequeños lanzadores apache2ctl y apache2-foreground pertenecen a este repositorio y ejecutan httpd del paquete Alpine. No se utilizan las herramientas de compilación de la imagen PHP Debian anterior.

El usuario/grupo de los procesos web conserva UID/GID 33, para reutilizar storage sin cambiar los propietarios de sus archivos. Se mantiene no-new-privileges en Compose. El arranque público conserva la validación de configuración, copia privada del certificado CA, comprobación TLS como usuario web y migraciones antes de servir HTTP.

## Imagen MySQL

El servidor y sus clientes permanecen en la imagen oficial MySQL 8.4 fijada por digest. Se actualizan los paquetes OpenSSL disponibles en Oracle Linux. gosu 1.19 se reconstruye desde un commit y archivo con SHA256 fijados, usando Go 1.27.2 en una etapa separada. Se actualizan sus dos dependencias a versiones corregidas y se conservan go.mod/go.sum en docker/gosu. El compilador y el código de construcción no entran en la imagen final.

La comprobación de construcción exige que gosu baje realmente al UID/GID de mysql. Las pruebas de integración y TLS verifican luego el arranque completo del servidor, sus clientes y la aplicación. MySQL Shell continúa retirado porque Eventify no lo utiliza.

## Alcance de la verificación

Un resultado de cero avisos describe esas imágenes y la base de vulnerabilidades usada en esa fecha; no garantiza ausencia de fallos futuros. No certifica el sistema anfitrión, Docker Desktop, el servicio gestionado Aiven ni la instalación Windows.

La misma imagen Linux se ejecutará mediante Docker en Windows. La corrección beneficia a ambos destinos; la instalación real Windows permanece pendiente hasta comprobarla allí. Las imágenes y etiquetas anteriores se conservan para volver a la versión previa si hiciera falta; un rollback de código no restaura datos.
