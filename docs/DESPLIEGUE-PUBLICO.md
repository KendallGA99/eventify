# B08: aceptación de publicación académica

Preparación revisable, 2026-10-08. No es un despliegue realizado ni un comando listo para ejecutar. Requisitos RT-09/ENT-A/U-02/RNF-03/04. Candidata propia: Render Free Docker PHP + Aiven MySQL Free; no reemplaza MySQL con otro motor. Cuenta/región/versión del motor y capacidad real pendientes.

## Antes de crear servicios

- Conservar referencia Git probada y revisar exclusión de secretos. Dar al proveedor solo acceso necesario al repositorio privado; acordar autorización de integración y visibilidad pública de la app.
- Verificar en panel plan Free, sin tarjeta/créditos trial ni subida automática. Aiven: un MySQL Free por organización, 1GB y76 conexiones; Render: cuotas compartidas, suspensión/despertar y almacenamiento efímero. No prometer disponibilidad continua.
- Implementar/verificar arranque público: Apache escucha puerto accesible de la plataforma; public es la única raíz; configuración y migración fallidas impiden servir. Probar migraciones repetidas y lock existente sin borrado ni endpoint instalador.
- Preparar CA como archivo privado de ejecución, variables obligatorias y APP_ENV=production. Nunca copiar .env/CA/contraseñas al Git, build o imagen. Separar usuario de migraciones y usuario de aplicación con permisos pertinentes si proveedor lo permite; registrar límites reales.
- Preparar aprovisionamiento explícito de cuentas ficticias con claves únicas fuera de Git. DemoSeeder sigue bloqueado en production; no sembrar administradores conocidos ni hacerlo en cada arranque.

## Prueba técnica con MySQL externo

Guardar fecha, referencia, versión PHP/MySQL y resultado sin host/usuario/clave sensibles. Verificar soporte del esquema: utf8mb4, collation exacta de migraciones, CHECK y columnas generadas; migraciones001–003, prepared statements y transacciones/locks. No cambiar el esquema para ocultar incompatibilidad sin revisar el requisito.

PHP8.4/mysqlnd: TLS verificado con CA confiable y hostname correcto debe conectar y reportar Ssl_cipher. CA ajena, hostname incorrecto y conexión sin TLS deben rechazarse. No desactivar verificación para pasar. Las40 guardas de configuración y219 checks locales no sustituyen estas conexiones externas.

## Persistencia y sesiones: decisión propia

Datos duraderos en MySQL: usuarios/eventos/reservas/tickets/auth_limits/weather_budget y metadatos de migración. Galería SVG procede de la imagen del código; no hay uploads en el alcance. Caché de pronóstico descartable y presupuesto MySQL durable.

Para instancia académica única se mantienen sesiones PHP en archivos. Reiniciar/redeploy/dormir puede invalidarlas y requerir ingresar nuevamente; no se afirma login persistente entre reinicios. Durante ejecución normal deben mantenerse entre peticiones. Cookies Secure/HttpOnly/SameSite y logout efectivos. No transferir sesiones en un respaldo. Verificar que el reinicio no convierta cookies anteriores en acceso no autenticado ni altere reservas.

## Prueba de aceptación desde URL externa

1. Registrar dominioHTTPS, referenciaSHA, plan/cuotas y fecha; visitar desde fuera de Linux/VM, sin túnel temporal como sustituto de persistencia.
2. Catálogo/detalle/imagen, búsqueda/filtros AJAX, registro válido y errores; acceder con ambos roles. Probar URL administrativa/ticket/QR ajenos y CSRF según matriz.
3. Reserva nueva, precio servidor/cupos, ticket/QR, intento duplicado y cancelación permitida con historial. No repetir una reserva si respuesta de transporte es incierta: consultar historial primero.
4. Administración: categorías/edición de evento con historial/transiciones/dashboard/reservas; conservar precios históricos y datos.
5. Open-Meteo real desde proveedor, atribución/timeout/horizonte/límites y degradación. Deshabilitar API sirve para diagnóstico, no cubreRF-15.
6. Antes/después de reiniciar o redeploy: comparar registros/galería/presupuesto; ingresar otra vez si sesión se perdió y abrir ticket anterior. Verificar HTTPS/cookies en navegador real.
7. Preparar respaldo/restauración y rollback compatible; no revertir migraciones ni borrar tablas/volúmenes para volver de versión. Conservar evidencia privada y resultados redactados.

## Qué sigue pendiente

Arranque público/aprovisionamiento implementados y probados, autorización concreta para cuentas/servicios/integración, negociación TLS real, URL externa y aceptación anterior. Hasta aprobarlos ENT-A/RT-09 permanecen pendientes. Plan gratuito documentado no equivale a publicación verificada ni reemplaza prueba Windows/Firefox y defensa humana.

Fuentes revalidadas: [Render gratis](https://render.com/docs/free), [servicios/puertos](https://render.com/docs/web-services), [MySQL Free Aiven](https://aiven.io/docs/products/mysql/concepts/mysql-free-tier).
