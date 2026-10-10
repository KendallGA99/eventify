# Ensayo de MySQL sin el cliente Shell

B07/RNF-03/SEG-09. Candidata experimental; no modifica `Dockerfile.mysql`, los Compose de entrega, VERSION ni las instalaciones existentes.

## Motivo y revisión previa

El escaneo MySQL conservado tiene 19 avisos de bibliotecas Python distribuidas dentro de mysqlsh. EVENTIFY accede al servidor por PDO y utiliza mysql, mysqladmin y mysqldump para inicialización, salud y respaldo. No se encontró una invocación de mysqlsh en aplicación, scripts o configuración del proyecto.

La [imagen oficial MySQL](https://github.com/docker-library/mysql/blob/master/8.4/Dockerfile.oracle) instala mysql-shell separadamente del servidor. Se examinó además el entrypoint del archivo de imagen realmente exportado: su única mención de mysqlsh es un comentario sobre el socket. La base RPM de esa misma exportación contiene mysql-community-server-minimal y mysql-shell; no se encontraron otros paquetes que requieran las capacidades declaradas por mysql-shell. Evidencia privada en storage/mysql-shell-review. Esta lectura no sustituye la resolución efectiva de dependencias o el arranque.

## Qué comprueba el ensayo

1. Construye la base corregida de gawk mediante el Dockerfile.mysql existente y una derivada con nombre propio.
2. Ejecuta `rpm -e --test mysql-shell` antes de retirarlo. Usa RPM sin forzar dependencias ni autoremove; si hay un conflicto, la construcción falla.
3. Comprueba que solo desaparece mysql-shell del inventario RPM. Verifica las huellas de mysqld, mysql, mysqladmin, mysqldump, gosu y entrypoint antes y después.
4. Comprueba ausencia del ejecutable y directorio de Shell y conserva las versiones de las herramientas SQL.
5. Ejecuta la integración MySQL/HTTP existente contra esta candidata. Es una regresión por un cambio de imagen, no una repetición de aceptación de la instalación Linux conservada.
6. Ensaya mysqldump y restauración de dos filas ficticias; compara sus identificadores y texto. Solo se elimina la tabla de prueba dentro de la base efímera y se restaura inmediatamente desde su dump.
7. Exporta la imagen candidata para un escaneo posterior, conservando identidad, inventarios, commit y resultado.

El proyecto Compose tiene nombre único, archivo y env-file explícitos, red interna, ninguna publicación de puertos, ningún montaje de carpetas del usuario y MySQL en tmpfs. La configuración genera contraseñas propias; no lee ni modifica .env. La limpieza se limita al proyecto de ensayo. Los registros y el dump ficticio quedan en storage/mysql-minimal-XXXXXXXX, fuera de Git. Los nombres de imagen son minúsculos.

## Ejecución y límites

Desde la raíz del proyecto:

```bash
bash scripts/test-mysql-minimal-linux.sh
```

La sesión actual no tiene acceso al servicio Docker ni a sudo no interactivo; la construcción debe ejecutarse en la terminal del usuario. Bash-n, compilación del Python embebido y generación de configuración comprobados. Se verificaron nombre/imagen candidata, aislamiento, tmpfs y permisos privados del archivo generado. No se han ejecutado todavía construcción, RPM, SQL, integración ni exportación de esta candidata.

Si termina correctamente, revisar el escaneo y comprobar TLS antes de proponer incorporarla a Dockerfile.mysql. No reducir el contador por inferencia ni afirmar que quitar Shell corrige sus bibliotecas. El objetivo es eliminar un componente no utilizado del runtime. Libxml2/PHP y los avisos de gosu se revisan por separado; el ensayo no los corrige. Render/Aiven no se actualizan y los paquetes dev.37 conservan su referencia.
