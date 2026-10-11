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


## Diagnóstico real de la VM del usuario, 2026-10-08

Windows11 Enterprise LTSC Evaluation, versión10.0.26100/build26100, 64bits, RAM visible4GiB. VM QEMU/KVM windows11-ssms22; CPU host-passthrough y nested del host activado. Windows reporta VirtualizationFirmwareEnabled, VMMonitorModeExtensions y SLAT disponibles. No prueba que WSL2/Docker puedan ejecutar el stack.

WSL --version reportó subsistema no instalado. Docker Desktop no encontrado en su ruta habitual de Program Files; no se inspeccionaron todos los perfiles de usuario. Host Linux dispone de aproximadamente8GB físicos y presión de memoria/swap; no se amplió RAM de VM ni se reinició/instaló software. La documentación de Docker consultada pide8GB RAM para backendWSL2. KVM no figura como hipervisor soportado oficialmente por Docker Desktop; una prueba técnica eventual no implica soporte oficial. Fuentes: [instalación Windows](https://docs.docker.com/desktop/setup/install/windows-install/) y [VM/VDI](https://docs.docker.com/desktop/setup/vm-vdi/).

A través del canal de integración QEMU existente se ejecutó únicamente diagnóstico y el parser real de Windows PowerShell5.1.26100.1591 sobre fuentes en memoria: common/start/stop/demo/test/test-database-windows.ps1, seis scripts sin errores de sintaxis. No se ejecutaron sus acciones, no se creó .env ni se inició Docker. Esto supera la comprobación sintáctica anterior; arranque, pruebas MySQL, navegador/persistencia y compatibilidad completa siguen pendientes.

Para completar la prueba, preferir un Windows con recursos compatibles; no asignar toda la RAM del host a la VM, activar servicios de pago ni borrar datos para sortear el límite. No instalar/reiniciar esta VM automáticamente mientras conserve trabajo del usuario.
