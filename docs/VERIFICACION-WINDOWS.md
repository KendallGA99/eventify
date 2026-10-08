# Comprobación de entrega en Windows

B07/U-01/U-03. Preparada, todavía no ejecutada en un equipo Windows. Usar un ZIP de la referencia acordada y una instalación independiente; conservar pruebas y resultados sin contraseñas.

## Antes de instalar

Consultar los [requisitos oficiales de Docker Desktop](https://docs.docker.com/desktop/setup/install/windows-install/), revisados el2026-10-08. Confirmar edición/build compatible y soporte vigente de Microsoft, arquitectura, virtualización, memoria y backend WSL2. La documentación consultada indica WSL2.1.5+ y8GB de RAM para el backend WSL2; volver a validar en la fecha de instalación. Docker Desktop contempla uso educativo gratuito en sus términos; la persona receptora instala/acepta las condiciones aplicables.

Registrar Windows/build/arquitectura, versión Docker Desktop, salida docker compose version, wsl --version y navegador. No confundir Windows host con contenedores Windows: Eventify usa contenedores Linux. No se requieren PHP/MySQL/Git/Python del host para iniciar el ZIP.

## Recorrido y aceptación

1. Extraer el ZIP en una ruta con espacios, por ejemplo Documentos/Proyecto Eventify. No copiar .env o datos del desarrollador. Comprobar que existen README, VERSION, .env.example, iniciar-windows.cmd y docs/INSTALACION.md.
2. Abrir Docker Desktop y esperar disponibilidad. Ejecutar iniciar-windows.cmd; comprobar que termina sin error, genera .env propio y aplica migraciones001–003. Si8080 está ocupado, elegir otro APP_PORT antes del arranque, sin detener servicios ajenos.
3. Abrir la URL indicada en Chrome y Firefox: inicio, catálogo, búsqueda/filtros, estados y vista móvil/tableta/escritorio. Ejecutar la demostración explícita según DATOS.md, únicamente en esta instalación local.
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
