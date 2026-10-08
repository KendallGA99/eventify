# EVENTIFY

Plataforma académica PHP/MySQL para descubrir eventos, reservar entradas, consultar tickets digitales y administrar experiencias.

## Avance actual

Implementados autenticación/roles, catálogo con jQuery/AJAX, clima Open-Meteo, reservas/cancelación con cupos transaccionales, tickets QR privados y administración de categorías/eventos/reservas/dashboard. Versión de desarrollo vigente: **0.1.0-dev.17**, con QA separado, restauración real de datos y arranque desde ZIP Linux comprobados.

Verificación real en Docker Linux aprobada el 2026-10-08: **266 comprobaciones unitarias**, QR y **211 comprobaciones de integración MySQL/HTTP**, incluidas dos carreras de reservas y cuatro administrativas, precio histórico, permisos y dataset del dashboard. [Evidencia y límites](docs/evidencias/B07.md).

Pendientes: casos completos Chrome/Firefox, reserva nueva tras otra recreación, Windows, flujo HTTP restaurado, URL pública/TLS y revisión técnica/defensa humana. La suite aprobada no certifica entrega final ni versión estable.

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

Actualizar y verificar Linux: `bash scripts/verify-linux.sh`. **Pendiente:** comprobar integración actualizada, persistencia y Windows. El receptor no necesita PHP/MySQL instalados aparte de Docker. Internet es necesario en la preparación inicial y para la API de clima.

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

## Condiciones

Desarrollo Linux y entrega Linux/Windows; HTML/CSS/JavaScript/jQuery/AJAX + PHP + MySQL; herramientas gratuitas para uso académico; diseño adaptable; seguridad en servidor; pruebas e historial progresivo. URL pública requerida por suficiencia: proveedor y despliegue todavía pendientes. Versionado progresivo autorizado; identidad configurada, primer commit creado y repositorio privado [USUARIO_DESTINO/eventify](https://github.com/USUARIO_DESTINO/eventify) creado y push inicial verificado: ramas main/codex y etiquetas checkpoint-b00/v0.1.0-dev.1. Ver [VERSIONADO.md](docs/VERSIONADO.md).

## Se completará por bloques

Completar flujos reales de navegador (B03–B06), QA portable/persistencia/restauración (B07), publicación (B08) y PDF/paquete/defensa (B09). Esquema/migraciones/demo y suite vigente de integración ya aprobados; GitHub privado con versiones publicado. La demostración local genera claves ficticias únicas y privadas; la URL pública sigue pendiente.

## Entrega y preparación humana

- [Verificación Windows](docs/VERIFICACION-WINDOWS.md): procedimiento pendiente de equipo real.
- [Demostración y defensa](docs/DEFENSA.md): recorrido, mapa del código y ejercicios; no certifica dominio.
- [Revisión de seguridad](docs/SEGURIDAD.md): dependencias y controles comprobados, con límites.
- [Fuente del documento técnico](docs/TECNICO.md): borrador referido a dev.17.
