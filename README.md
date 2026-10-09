# EVENTIFY

Plataforma académica PHP/MySQL para descubrir eventos, reservar entradas, consultar tickets digitales y administrar experiencias.

## Estado del proyecto

Eventify permite crear una cuenta, explorar eventos, reservar entradas, consultar tickets con QR y cancelar reservas. El administrador gestiona categorías, eventos y reservas desde su panel.

La versión pública está disponible en [eventify-academico.onrender.com](https://eventify-academico.onrender.com/eventos). La versión de desarrollo actual es **0.1.0-dev.32**. La web ejecuta dev.30 con el parche de imágenes xz/liblzma; diez tablas conservaron sus huellas y 81 controles HTTPS posteriores pasaron. Se comprobaron los accesos de ambos roles, la creación de un evento ficticio, una reserva con ticket, su cancelación y la conservación de los datos tras reiniciar la web.

Las últimas pruebas locales registradas incluyen 266 comprobaciones unitarias y 219 de integración con MySQL/HTTP. Corresponden a las ejecuciones indicadas en [ESTADO.md](docs/ESTADO.md); los cambios posteriores tienen sus propias verificaciones. Estos resultados no equivalen a una entrega final aprobada.

El pronóstico público tuvo una respuesta útil comprobada. El ZIP dev.32 ya tiene instalación limpia y persistencia comprobadas en Linux. Faltan Windows, Firefox, casos de interfaz y seguridad restantes, y el cierre de entrega. Los casos concretos están en [CIERRE.md](docs/CIERRE.md). El clima puede fallar sin impedir una reserva.

## Inicio local

Requiere Docker y Compose v2. El manual completo, limitaciones y requisitos de Windows están en [INSTALACION.md](docs/INSTALACION.md).

Linux Mint/Ubuntu sin Docker, una sola vez:

```bash
bash scripts/install-docker-linux.sh
```

Después, desde la carpeta del proyecto:

```bash
bash scripts/start-linux.sh
```

En Windows: instalar/abrir Docker Desktop en modo Linux y ejecutar `iniciar-windows.cmd`. Los scripts preparan claves locales únicas y conservan la configuración existente. Abrir `http://localhost:8080` cuando el inicio termine correctamente.

Detener: `bash scripts/stop-linux.sh` o `detener-windows.cmd`. Pruebas B01: `bash scripts/test-linux.sh` o `scripts/test-windows.ps1` desde PowerShell. No borrar volúmenes para resolver fallos.

Pruebas MySQL aisladas: `bash scripts/test-database-linux.sh`; demostración opcional: `bash scripts/demo-linux.sh`. Consulte [DATOS.md](docs/DATOS.md).

Actualizar y verificar Linux: `bash scripts/verify-linux.sh`. La instalación completa en Windows sigue pendiente de prueba. El receptor no necesita PHP/MySQL instalados aparte de Docker. Internet es necesario en la preparación inicial y para la API de clima.

## Documentación y continuidad

| Archivo | Uso |
| --- | --- |
| [AGENTS.md](AGENTS.md) | Protocolo e instrucciones permanentes. |
| [REQUISITOS.md](docs/REQUISITOS.md) | RF/RNF, criterios, decisiones y entregables. |
| [VERSIONADO.md](docs/VERSIONADO.md) | Commits, etiquetas, GitHub y recuperación compatible con datos. |
| [PLAN.md](docs/PLAN.md) | Bloques B00–B09 y estrategia de pruebas. |
| [ESTADO.md](docs/ESTADO.md) | Avance real y próxima acción. |
| [ARQUITECTURA.md](docs/ARQUITECTURA.md) | Stack, dependencias, licencias y opciones de publicación. |
| [INSTALACION.md](docs/INSTALACION.md) | Arranque, detención, configuración y problemas comunes. |
| [DATOS.md](docs/DATOS.md) | Modelo, migraciones, demostración y pruebas aisladas. |
| [Evidencia B04](docs/evidencias/B04.md) | Reservas/transacciones y dos carreras aprobadas; cuatro carreras administrativas también aprobadas. |
| [Evidencia B03](docs/evidencias/B03.md) | Autenticación/permisos/sesiones verificados por HTTP; flujo navegador completo pendiente. |
| [Evidencia B02](docs/evidencias/B02.md) | Validación y límites del modelo de datos. |
| [Evidencia B01](docs/evidencias/B01.md) | Pruebas ejecutadas y límites de la verificación. |

## Entrega al compañero

El ZIP local incluye el código, las migraciones y los scripts de inicio. Requiere Docker preparado en el equipo del receptor, pero no una cuenta de Aiven, Render o GitHub. PHP y MySQL se ejecutan en los contenedores; la configuración local se genera con claves propias.

El alojamiento público se administra por separado. Sus contraseñas, certificados, registros privados y configuración de producción no forman parte del paquete. Si se necesitan datos existentes, se entregan mediante una exportación y un procedimiento de restauración documentados.

## Tecnologías y alcance

HTML5, CSS3, JavaScript, jQuery, AJAX, PHP, sesiones PHP y MySQL. El proyecto se desarrolla en Linux y prepara una entrega con Docker Compose para Linux y Windows. Usa herramientas gratuitas para la evaluación académica, sin pagos reales ni aplicación móvil nativa.

El historial se conserva en el repositorio privado [USUARIO_DESTINO/eventify](https://github.com/USUARIO_DESTINO/eventify). Las versiones de desarrollo y el procedimiento de recuperación se describen en [VERSIONADO.md](docs/VERSIONADO.md). Revertir el código no restaura la base de datos.

## Entrega y preparación humana

- [Verificación Windows](docs/VERIFICACION-WINDOWS.md): procedimiento pendiente de equipo real.
- [Demostración y defensa](docs/DEFENSA.md): recorrido, mapa del código y ejercicios; no certifica dominio.
- [Revisión de seguridad](docs/SEGURIDAD.md): dependencias y controles comprobados, con límites.
- [Fuente del documento técnico](docs/TECNICO.md): borrador actualizado a dev.32, con la misma aplicación de dev.30, con límites y evidencia.
