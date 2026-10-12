# Comprobación de entrega en Windows

B07/U-01/U-03. La prueba integral de instalación y uso sigue pendiente. Los scripts sí tienen una revisión de sintaxis con PowerShell 5.1 en Windows; ese resultado no acredita el funcionamiento de Docker ni de la aplicación. Usar un ZIP de la referencia acordada y una instalación independiente; conservar pruebas y resultados sin contraseñas.

## Antes de instalar

Consultar los [requisitos oficiales de Docker Desktop](https://docs.docker.com/desktop/setup/install/windows-install/), revisados el2026-10-08. Confirmar edición/build compatible y soporte vigente de Microsoft, arquitectura, virtualización, memoria y backend WSL2. La documentación consultada indica WSL2.1.5+ y8GB de RAM para el backend WSL2; volver a validar en la fecha de instalación. Docker Desktop contempla uso educativo gratuito en sus términos; la persona receptora instala/acepta las condiciones aplicables.

Registrar Windows/build/arquitectura, versión Docker Desktop, salida docker compose version, wsl --version y navegador. No confundir Windows host con contenedores Windows: Eventify usa contenedores Linux. No se requieren PHP/MySQL/Git/Python del host para iniciar el ZIP.

## Recorrido y aceptación

1. Extraer el ZIP en una ruta con espacios, por ejemplo Documentos/Proyecto Eventify. No copiar .env o datos del desarrollador. Comprobar que existen README, VERSION, .env.example, iniciar-windows.cmd y docs/INSTALACION.md.
2. Abrir Docker Desktop y esperar disponibilidad. Ejecutar iniciar-windows.cmd; comprobar que termina sin error, genera .env propio y aplica migraciones001–003. Si8080 está ocupado, elegir otro APP_PORT antes del arranque, sin detener servicios ajenos.
3. Abrir la URL indicada en Chrome y Firefox: inicio, catálogo, búsqueda/filtros, estados y vista móvil/tableta/escritorio. En dev.46, confirmar ocho eventos y abrir storage/example-credentials.json en privado para usar las cuentas iniciales. No ejecutar la demostración antigua sobre ese catálogo.
4. Obtener credenciales ficticias por el procedimiento privado; comprobar login/logout de usuario y administrador. Reservar un evento futuro con cupos, mostrar total/ticket/QR e historial, comprobar rechazo de duplicado y cancelar una reserva propia permitida. Mantener los registros ficticios de evidencia.
5. Comprobar que una cuenta de usuario no accede al admin/ticket ajeno y que la API meteorológica maneja indisponibilidad sin bloquear el flujo central. No debilitar seguridad para probar.
6. Ejecutar detener-windows.cmd e iniciar-windows.cmd: comprobar conservación de cuentas, reserva/historial e imágenes. Si se ensaya recreación de contenedores, hacerlo explícitamente conservando volúmenes y registrar antes/después; no usar down -v/prune.
7. Ejecutar las verificaciones Windows del manual: sintaxis/unidades/conexión y test-database-windows.ps1 para integración aislada. Registrar exit code y mensaje final, sin sumar números de otras versiones.
8. Si se entregan datos existentes, probar importación separada a destino vacío y su flujo HTTP. El ensayo Linux no certifica PowerShell ni transferencia en Windows. Detenerse ante discrepancias y conservar datos para diagnóstico.

## Registro de aceptación pendiente

| Criterio | Resultado actual | Evidencia necesaria |
| --- | --- | --- |
| Host y Docker compatibles | Pendiente | Versiones/arquitectura/backend |
| Arranque ZIP en ruta con espacios | Pendiente | Referencia ZIP, mensaje final, URL |
| Flujos Chrome y Firefox | Pendiente | Recorrido de ambos roles |
| Persistencia después de reinicio/recreación | Pendiente | Datos/QR/imágenes antes y después |
| Pruebas PHP/MySQL aisladas | Pendiente | Resultado de scripts de esta versión |
| Transferencia de datos si aplica | Pendiente | Importación a vacío y recorrido HTTP |
| Receptor puede explicar/operar inicio | Pendiente | Verificación humana real |

Este documento es un procedimiento, no una certificación. URL pública y defensa son entregables separados. Errores y límites deben quedar en ESTADO/MATRIZ sin sustituirlos por pruebas Linux.

## Diagnóstico del arranque tardío — 11 de octubre de 2026

La VM Windows tiene 4 GiB y tres CPU virtuales; el anfitrión tiene 7801 MiB en total, unos 522 MiB disponibles y 4551 MiB de swap ocupados en la primera muestra. No se aumentó la RAM: asignar 8 GiB al huésped consumiría la memoria física del anfitrión. Tampoco se modificaron CPU, discos, volúmenes o configuración de virtualización. La virtualización anidada del anfitrión está habilitada y la VM usa host-passthrough.

El registro de Docker informa que el motor linux/wsl quedó listo 15m16,969s después del inicio. El instalador dev.49 tiene una espera de 300 segundos: su mensaje de error no prueba que Docker no pueda arrancar después. WSL respondió con versión 3.0.1.0 y kernel 6.18.40.1; consulta directa al motor por named pipe devolvió Docker Engine29.8.2. No se encontró reinicio pendiente en la clave de Component Based Servicing consultada; esto no audita todas las causas de reinicio.

Se retomó dev.49 en la sesión del usuario mediante una tarea temporal sin contraseña ni elevación nueva. El primer reintento creó .env con claves únicas, confirmó Compose5.5.1 y falló durante Compose, sin capturar su salida nativa completa. La comprobación directa posterior de config --quiet y ps devolvió exit0. Un segundo arranque nativo con stdout/stderr separados comenzó a descargar capas y construir web/db. No se atribuye el fallo transitorio a una causa no demostrada. La presión de memoria y el timeout de consultas WMI aparecen en la evidencia; no bastan para demostrar la causa única.

El progreso de construcción no acredita todavía migraciones, ocho eventos, cuentas, navegador o persistencia. Windows sigue en evaluación sobre un equipo por debajo del requisito de RAM publicado por Docker. Evidencia privada: storage/windows-docker-diagnostico. La aceptación en un anfitrión compatible continúa pendiente.


## Diagnóstico real de la VM del usuario, 2026-10-08

Windows11 Enterprise LTSC Evaluation, versión10.0.26100/build26100, 64bits, RAM visible4GiB. VM QEMU/KVM windows11-ssms22; CPU host-passthrough y nested del host activado. Windows reporta VirtualizationFirmwareEnabled, VMMonitorModeExtensions y SLAT disponibles. No prueba que WSL2/Docker puedan ejecutar el stack.

WSL --version reportó subsistema no instalado. Docker Desktop no encontrado en su ruta habitual de Program Files; no se inspeccionaron todos los perfiles de usuario. Host Linux dispone de aproximadamente8GB físicos y presión de memoria/swap; no se amplió RAM de VM ni se reinició/instaló software. La documentación de Docker consultada pide8GB RAM para backendWSL2. KVM no figura como hipervisor soportado oficialmente por Docker Desktop; una prueba técnica eventual no implica soporte oficial. Fuentes: [instalación Windows](https://docs.docker.com/desktop/setup/install/windows-install/) y [VM/VDI](https://docs.docker.com/desktop/setup/vm-vdi/).

A través del canal de integración QEMU existente se ejecutó únicamente diagnóstico y el parser real de Windows PowerShell5.1.26100.1591 sobre fuentes en memoria: common/start/stop/demo/test/test-database-windows.ps1, seis scripts sin errores de sintaxis. No se ejecutaron sus acciones, no se creó .env ni se inició Docker. Esto supera la comprobación sintáctica anterior; arranque, pruebas MySQL, navegador/persistencia y compatibilidad completa siguen pendientes.

Para completar la prueba, preferir un Windows con recursos compatibles; no asignar toda la RAM del host a la VM, activar servicios de pago ni borrar datos para sortear el límite. No instalar/reiniciar esta VM automáticamente mientras conserve trabajo del usuario.


## Instalador CMD dev.47

El 11 de octubre se añadieron preparar WSL/Docker, continuación manual tras reinicio, detección de motor Linux, espera de Desktop y accesos de escritorio. No modifica características ni software en la VM de evaluación: se usaron parser y operaciones de sistema simuladas. Windows PowerShell5.1.26100.9444 analizó nueve fuentes sin errores; 57 comprobaciones correctas del asistente, incluidas claves de retorno, equipo incompatible, reinicios, errores, WSL mínimo, motor apagado/modo Windows y firma/editor del instalador de respaldo. CMD reales ejecutados en ambos formatos de carpeta, propagando errores del asistente. Accesos LNK reales creados y leídos únicamente en carpeta temporal con espacios/apóstrofo, después retirada. Evidencia privada: storage/windows-cmd-dev47.

Al probar la entrega real, abrir INSTALAR-EVENTIFY.cmd desde una carpeta definitiva. Registrar preparación de WSL, elevación, reinicio y repetición, instalación WinGet o respaldo firmado, aceptación inicial de Docker, navegador y accesos Iniciar/Detener. Comprobar que detener/reiniciar conserva cuentas/reservas y que los accesos se actualizan al mover la carpeta. No confundir estas verificaciones pendientes con las simulaciones ya aprobadas.


## Ensayo de RAM dev.48

El usuario pidió omitir el límite propio del instalador para validar. Se añadió un modo explícito de prueba, sin quitar la comprobación normal. Parser Windows PowerShell5.1.26100.9444 sobre nueve archivos correcto;61 comprobaciones aprobadas. Los casos nuevos verifican rechazo normal/aceptación con parámetro ante host simulado de4GiB, propagación del modo y destino del acceso directo de inicio. No se instaló Docker ni se modificó memoria/virtualización de la VM durante estas comprobaciones. Evidencia privada storage/windows-cmd-dev48/guest-result.json. Instalación completa pendiente.


## Aviso sin bloqueo dev.49

El usuario informó que seguía viendo el bloqueo de RAM. Sin atribuir una causa no comprobada al archivo que ejecutó, se cambió el asistente principal para advertir y continuar con menos de8GiB. Parser PowerShell5.1, nueve fuentes correctas y61 comprobaciones aprobadas: el host simulado4GiB continúa tanto sin parámetro como con el antiguo parámetro de validación. No se instaló Docker ni se reinició la VM. Evidencia privada storage/windows-cmd-dev49/guest-result.json. La instalación integral sigue pendiente.


## Indicador de descarga dev.50

Parser Windows PowerShell5.1 sobre nueve fuentes correcto;68 controles aprobados. Incluye dos descargas reales de contenido ficticio desde un servidor TCP/HTTP temporal en loopback de la VM, una con Content-Length y otra chunked sin total, verificando el contenido final. Servidor/job/archivos temporales retirados. Se comprueban porcentajes0/25/100, límite superior y ausencia de porcentaje sin total. Las operaciones de instalar WSL/Docker continúan simuladas; no se interrumpió la descarga del usuario ni se reinició la VM. Evidencia privada storage/windows-cmd-dev50.


## Resultado de recuperación del arranque, 11 de octubre de 2026

La construcción inicial agotó el límite de900s del proceso de diagnóstico; se conservó la caché y se reintentó sin borrar volúmenes. Compose dejó ambos contenedores saludables. El asistente completo todavía retornó1, por lo que no se certifican su finalización, accesos de escritorio o apertura automática del navegador.

El script normal de arranque ejecutado posteriormente mediante PowerShell, con política Bypass solo para el proceso y conexión explícita al motor, terminó0. Aplicó migraciones001–003, preparó ocho eventos y las cuentas y copió sus credenciales al archivo privado de la instalación. La verificación HTTP desde Windows devolvió200 para inicio, catálogo, ingreso y registro; se contaron ocho tarjetas en el catálogo. El500 observado antes de migrar dejó de reproducirse tras la inicialización. No hubo cambio de código, RAM, CPU, discos o servicios. La tarea temporal utilizada para retomar la instalación fue eliminada.

Evidencia privada: storage/windows-docker-diagnostico/start-script-result-2.json y http-final-result.json. Estas comprobaciones no cubren recorridos interactivos, persistencia, reinicio o instalación completa en un anfitrión compatible. Se mantienen pendientes los criterios de aceptación anteriores.


## Salida de Compose en Windows dev.51

La captura del usuario muestra `failed to get console: Controlador no válido` al llamar start-windows.ps1 desde el asistente mediante Out-Host. Se configuró la función compartida Invoke-EventifyCompose con las opciones oficiales `--ansi never --progress plain`, para no depender de una consola interactiva al mostrar el progreso. No se cambia esquema, reglas, claves, volúmenes o dependencias.

Corrección aplicada también al common-windows.ps1 del paquete dev.49 instalado en la VM, con copia previa en su almacenamiento privado; la instalación conserva VERSION49 y tiene este parche puntual. La prueba de comparación original se interrumpió únicamente en nuestro proceso4916 y sus hijos al detectar otra construcción activa; no se intervino en la construcción ajena ni en Docker Desktop. No se obtuvo una reproducción completa antes/después del error. PowerShell real aceptó la fuente corregida; config --quiet y ps --format json con salida redirigida terminaron0 y mostraron web/db saludables. La captura posterior del usuario confirma la página de inicio con estilos en Edge. El recorrido completo del asistente y su construcción desde cero no están certificados por estas comprobaciones. Evidencia privada storage/windows-docker-diagnostico/dev51-*.json.

Prueba posterior real con la función corregida: `Invoke-EventifyCompose up --no-build --detach --wait --wait-timeout 180 | Out-Host` terminó0; web/db saludables, catálogo HTTP200 con ocho tarjetas. Reutiliza imágenes existentes y no sustituye la prueba pendiente de construcción/instalador completo. Evidencia dev51-smoke-result.json.


## Espera visible del instalador dev.52

El usuario confirma que los accesos Iniciar/Detener fueron creados y que ambos funcionan. También confirma apertura de la aplicación en Windows. No se atribuye esa recuperación exclusivamente al instalador: se retomó Compose, se ejecutó el arranque para migrar/sembrar y se aplicó el parche de consola con intervención técnica. La RAM reducida es un factor plausible de demora, no una causa única demostrada. No borrar ni reinstalar para afinar el asistente.

Cobertura U-03/RT-06. Wait-EventifyDocker permite1800s en lugar de300s y muestra actividad sin porcentaje, tiempo transcurrido y avisos cada15s; finaliza el indicador tanto al quedar listo como al fallar. El asistente muestra cuatro etapas. El mensaje de timeout permite retomar el mismo CMD sin borrar datos. La descarga conserva el progreso real de dev.50 y Compose conserva salida simple de dev.51. No se cambian datos, imágenes, migraciones, RAM ni dependencias.

Verificación real Windows PowerShell: parser de funciones y dos archivos de pruebas correcto;68 controles del asistente aprobados y8 controles de espera aprobados, con disponibilidad simulada y reloj real. Incluyen Docker ya listo, arranque tardío, timeout recuperable, cierre del indicador y ausencia de porcentaje inventado. Dos descargas de prueba HTTP loopback y accesos temporales están incluidas en los68 controles; no se reinstala WSL/Docker ni se reinicia Windows. Evidencia privada storage/windows-docker-diagnostico/dev52-tests-result.json. No acredita esperar30min ante una instalación nueva real. Windows compatible, persistencia y flujos completos siguen pendientes.


## Reinstalación dev.52 confirmada por el usuario — 11 de octubre de 2026

El usuario eliminó la carpeta anterior y ejecutó la limpieza específica de Eventify en CMD. La captura muestra eliminación correcta de eventify-web-1/eventify-db-1, volúmenes eventify_mysql_data/eventify_app_storage e imágenes eventify-web:latest/eventify-db:latest. Los errores al repetir la eliminación indicaban que ya no existían. Docker Desktop y WSL permanecieron instalados; no se acredita una nueva instalación de esos componentes ni la eliminación completa de la caché de construcción.

Después de indicar que extrajera dev.52 y ejecutara INSTALAR-EVENTIFY.cmd, el usuario confirmó que la instalación terminó correctamente. En este segundo ensayo no se ejecutaron herramientas para construir, migrar o sembrar por fuera del instalador; el resultado de ejecución es una confirmación del usuario, no una inspección independiente de logs o versión. Se registra como reconstrucción e inicialización satisfactorias de Eventify con Docker existente en la VM de evaluación. Los accesos de inicio/detención habían sido probados y confirmados por el usuario en el ensayo anterior.

La prueba aporta evidencia de instalación de la aplicación desde base vacía y sin sus imágenes etiquetadas anteriores; no certifica Docker/WSL desde cero, host compatible, persistencia después de reinicio ni recorridos completos de ambos roles. No se repiten pruebas Linux, se cambia la aplicación o se marca estable. El ZIP dev.52 conserva su huella y contenido.
