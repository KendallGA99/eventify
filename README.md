# EVENTIFY

Plataforma académica PHP/MySQL para descubrir eventos, reservar entradas, consultar tickets digitales y administrar experiencias.

## Avance actual

Implementados autenticación/roles, catálogo con jQuery/AJAX, clima Open-Meteo, reservas/cancelación con cupos transaccionales, tickets QR privados y administración de categorías/eventos/reservas/dashboard. Versión de desarrollo vigente: **0.1.0-dev.8**, con cuatro carreras administrativas aprobadas.

Verificación real en Docker Linux aprobada el 2026-10-08: **177 comprobaciones de reglas**, QR y **170 comprobaciones de integración MySQL/HTTP** (ampliación aprobada a las 13:52 UTC), incluidas dos carreras de reservas y cuatro administrativas, precio histórico, permisos y dataset del dashboard. [Evidencia y límites](docs/evidencias/B07.md).

Pendientes: flujos completos de navegador Chrome/Firefox, persistencia/instalación limpia Linux y Windows, respaldo/restauración, URL pública gratuita, documentación técnica y defensa. La suite aprobada no certifica entrega final ni versión estable.

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

Completar flujos reales de navegador (B03–B06), QA portable/persistencia/restauración (B07), publicación (B08) y PDF/paquete/defensa (B09). Esquema/migraciones/demo y suite vigente de integración ya aprobados; GitHub privado con versiones publicado. No hay credenciales de prueba ni URL pública para entregar todavía.
