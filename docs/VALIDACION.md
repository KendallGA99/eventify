# Validación

Las comprobaciones combinan pruebas unitarias, integración PHP/MySQL, solicitudes HTTP y recorridos de navegador. Cada resultado corresponde a la versión indicada; una revisión documental no repite las pruebas de funcionamiento.

| Área | Resultado registrado | Alcance |
| --- | --- | --- |
| Aplicación dev.46 | 290 comprobaciones MySQL/HTTP aprobadas. | Autenticación, permisos, reservas, concurrencia, catálogo inicial y conservación de datos. |
| Interfaz dev.45 | 173 comprobaciones en Firefox; 22 pantallas y seis tamaños/orientaciones. | Navegación pública y privada, formularios, permisos y ausencia de desbordamiento. |
| Clima | 58 comprobaciones de proveedores y 44 del presupuesto. | Respuesta útil, respaldo, errores controlados y límites de consultas. |
| Persistencia | Datos conservados en reinicios y recreaciones de los ensayos registrados. | Tablas, reservas, tickets y archivos en los destinos probados. |
| Instalador dev.52 | 68 controles del asistente y ocho de espera en PowerShell 5.1. | Dependencias simuladas en esos controles; reinstalación real posterior confirmada por el operador. |

## Casos críticos

Las pruebas revisan registro, regeneración y caducidad de sesión, permisos administrativos, propiedad de tickets, CSRF, cantidades inválidas, precio autoritativo y cancelación a más de 24 horas. Las pruebas de concurrencia envían solicitudes simultáneas por el último cupo y por reservas duplicadas.

Los casos de integración están en `tests/integration`; las reglas aisladas, en `tests/unit`; la interfaz, en `tests/browser`. Las pruebas de datos utilizan una base separada de la aplicación.

## Límites

La reinstalación de Eventify en Windows fue confirmada por la persona que realizó el ensayo, después de retirar contenedores, volúmenes e imágenes anteriores de la aplicación. Docker Desktop y WSL ya estaban instalados. Los accesos de inicio y detención también fueron confirmados.

El ensayo utilizó una máquina virtual de 4 GiB. No equivale a una instalación inicial de Docker en todos los equipos ni certifica la estabilidad de una configuración con recursos insuficientes. Las comprobaciones meteorológicas dependen de proveedores externos; un fallo debe permitir continuar reservando.

El análisis de dependencias corresponde a las imágenes y a la base de vulnerabilidades de la fecha registrada en el documento técnico. No garantiza ausencia de vulnerabilidades futuras ni certifica el sistema anfitrión.
