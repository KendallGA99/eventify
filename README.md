# EVENTIFY

Preparación Windows dev.47: extraer el ZIP y abrir `INSTALAR-EVENTIFY.cmd`. Prepara WSL/Docker cuando faltan y crea accesos para iniciar/detener. Puede requerir permisos, reinicio y aceptar términos. Aplicación pública y catálogo conservan dev.46; instalación completa Windows pendiente.

Plataforma académica PHP/MySQL para descubrir eventos, reservar entradas, consultar tickets digitales y administrar experiencias.

## Estado del proyecto

Eventify permite crear una cuenta, explorar eventos, reservar entradas, consultar tickets con QR y cancelar reservas. El administrador gestiona categorías, eventos y reservas desde su panel.

La versión de desarrollo es **0.1.0-dev.43**, aplicada en la [web pública](https://eventify-academico.onrender.com/eventos) y en la instalación local de evaluación. Incluye respaldo meteorológico con MET Norway y una tarjeta de clima compacta, con fuente, licencia y límites del pronóstico en un desplegable.

Las dependencias nativas se corrigieron en dev.41: tres imágenes finales sin avisos en el escaneo del 10 de octubre, con regresión de 234 controles y TLS positivo/negativo. Es evidencia de ese corte, no una garantía permanente ni un análisis del host o del proveedor. [Detalle del entorno](docs/RUNTIME-CORREGIDO.md). Dev.42 verificó el respaldo con 58 controles de clima, 234 de integración y 81 HTTPS públicos; dev.43 conserva esos cambios y comprueba la presentación en móvil/escritorio. Las referencias exactas y límites están en [ESTADO](docs/ESTADO.md).

La instalación limpia Linux y su persistencia se comprobaron con el ZIP dev.33. Los paquetes posteriores tienen verificaciones propias de código e integridad; no equivalen a instalaciones nuevas. La exportación actual se identifica en ESTADO. Faltan las comprobaciones restantes de Firefox, la instalación Windows y la aceptación humana; la versión todavía no se declara estable. [CIERRE.md](docs/CIERRE.md) organiza los pendientes. El pronóstico público de respaldo mostró datos reales el 10 de octubre; si los proveedores fallan, se puede continuar con la reserva.

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

Detener: `bash scripts/stop-linux.sh` o `detener-windows.cmd`. Verificaciones locales: `bash scripts/test-linux.sh` o `scripts/test-windows.ps1` desde PowerShell. No borrar volúmenes para resolver fallos.

Pruebas MySQL aisladas: `bash scripts/test-database-linux.sh`; el primer arranque local vacío prepara ocho eventos y cuentas con claves únicas (`storage/example-credentials.json`). La demostración antigua de QA es independiente y no se ejecuta sobre ese catálogo. Consulte [DATOS.md](docs/DATOS.md).

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
| [Evidencia B03](docs/evidencias/B03.md) | Evidencia histórica de autenticación; aceptación de navegador y límites actuales en ESTADO. |
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
- [Fuente del documento técnico](docs/TECNICO.md): documento dev.41, corrección nativa, publicación y límites de cada evidencia.

- [Ensayo de defensa](docs/ENSAYO-DEFENSA.md): recorrido, lectura guiada, preguntas y ejercicio de reglas.
- [Seguridad de la entrega](docs/SEGURIDAD-ENTREGA.md): controles comprobados, riesgos residuales y condiciones para actualizarlos.
