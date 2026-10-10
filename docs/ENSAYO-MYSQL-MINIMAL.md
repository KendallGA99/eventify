# Ensayo de MySQL sin el cliente Shell

B07/RNF-03/SEG-09. Receta incorporada en Dockerfile.mysql para dev.38 tras el ensayo aislado. No actualiza las instalaciones existentes ni el servidor administrado Aiven.

## Motivo y revisión previa

El escaneo MySQL conservado tiene 19 avisos de bibliotecas Python distribuidas dentro de mysqlsh. EVENTIFY accede al servidor por PDO y utiliza mysql, mysqladmin y mysqldump para inicialización, salud y respaldo. No se encontró una invocación de mysqlsh en aplicación, scripts o configuración del proyecto.

La [imagen oficial MySQL](https://github.com/docker-library/mysql/blob/master/8.4/Dockerfile.oracle) instala mysql-shell separadamente del servidor. Se examinó además el entrypoint del archivo de imagen realmente exportado: su única mención de mysqlsh es un comentario sobre el socket. La base RPM de esa misma exportación contiene mysql-community-server-minimal y mysql-shell; no se encontraron otros paquetes que requieran las capacidades declaradas por mysql-shell. Evidencia privada en storage/mysql-shell-review. Esta lectura no sustituye la resolución efectiva de dependencias o el arranque.

## Qué comprueba el ensayo

1. Construye la base corregida de gawk mediante la etapa mysql-with-shell de Dockerfile.mysql y una derivada con nombre propio.
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

El usuario habilitó sudo no interactivo; se verificaron identidad root y acceso Docker sin utilizar contraseñas. Las pruebas se ejecutaron de forma autónoma en proyectos efímeros.

## Resultados del 10 de octubre

- Primer intento mysql-minimal-ohVuHbye: falló la ausencia de /usr/lib/mysqlsh porque RPM dejó cachés Python sin propietario. No se relajó el control: se añadió comprobación de propiedad de todos los restos antes de retirar ese directorio dentro de la imagen. Un archivo aún perteneciente a cualquier RPM hace fallar la construcción.
- Candidata mysql-minimal-zntFADgx: exit0, 225 comprobaciones MySQL/HTTP, inventario RPM sin otros paquetes eliminados, seis huellas idénticas y respaldo/restauración exactos de dos filas ficticias. Servidor8.4.11 conservado.
- public-tls-Qsvpge91: exit0; conexión verificada, rechazo de CA/nombre ajenos, arranque/HTTP y recreación. public-no-tls-qG1NpFx4: exit0; TCP disponible pero producción rechaza conexión y arranque antes de migraciones; base vacía antes/después.
- Trivy0.75.0 con la misma DB del8oct, sin exclusiones:66→47 filas; exactamente19 retiradas de pip/pyOpenSSL/cryptography/paramiko/urllib3, ninguna añadida. Restantes1CRITICAL/21HIGH/22MEDIUM/2LOW/1UNKNOWN. Informes y comparación en storage/mysql-minimal-zntFADgx.

La receta final dev.38 conserva una etapa con Shell para repetir el experimento. Imagen final9321f68, árbol limpio: mysql-image-check-YmuG4fAM exit0;225 controles y TLS positivo/negativo exit0 (public-tls-NB0m9r8w, public-no-tls-wnPk03Vg). Escaneo final47 filas idénticas a candidata, identidad del informe comprobada contra el blob OCI exportado. No se atribuyeron resultados por inferencia. Quitar Shell elimina componentes sin uso; no corrige bibliotecas conservadas, libxml2/PHP, gosu o el host. Los proyectos de ensayo se retiraron sin tocar instalaciones existentes. Render/Aiven y el paquete dev.37 permanecen en su referencia.
