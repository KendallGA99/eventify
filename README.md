# EVENTIFY

Plataforma académica PHP/MySQL para descubrir eventos, reservar entradas, consultar tickets digitales y administrar experiencias.

## Avance actual

Construcción por bloques iniciada. B00 documentado; B01 tiene base Docker/PHP, vista inicial y scripts Linux/Windows. Compose, sintaxis, configuración y respuestas HTTP verificadas parcialmente. B02 incorpora esquema, migraciones y demostración opcional; 115 comprobaciones de configuración/reglas correctas en herramientas temporales. El arranque Docker y la conexión MySQL de la versión inicial ya se comprobaron. B03 prepara registro, ingreso, roles y cuenta; su integración está pendiente. B04 parcial agrega reservas/cancelación, historial y consulta privada de ticket; QR local implementado y comprobado de forma independiente; integración objetivo pendiente. B05 parcial agrega catálogo, filtros/paginación y actualización del historial con jQuery; probado con fixtures, API de clima real comprobada independientemente; MySQL objetivo pendiente. Administración completa aún por implementar.

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
| [Evidencia B04](docs/evidencias/B04.md) | Reservas, transacciones y pruebas de concurrencia pendientes. |
| [Evidencia B03](docs/evidencias/B03.md) | Autenticación, permisos, sesiones y pruebas pendientes. |
| [Evidencia B02](docs/evidencias/B02.md) | Validación y límites del modelo de datos. |
| [Evidencia B01](docs/evidencias/B01.md) | Pruebas ejecutadas y límites de la verificación. |

## Condiciones

Desarrollo Linux y entrega Linux/Windows; HTML/CSS/JavaScript/jQuery/AJAX + PHP + MySQL; herramientas gratuitas para uso académico; diseño adaptable; seguridad en servidor; pruebas e historial progresivo. URL pública requerida por suficiencia: proveedor y despliegue todavía pendientes. Versionado progresivo autorizado; identidad configurada, primer commit creado y repositorio privado [USUARIO_DESTINO/eventify](https://github.com/USUARIO_DESTINO/eventify) creado y push inicial verificado: ramas main/codex y etiquetas checkpoint-b00/v0.1.0-dev.1. Ver [VERSIONADO.md](docs/VERSIONADO.md).

## Se completará por bloques

Validación real del esquema/migraciones y datos demo (B02), cuentas/auth (B03), reservas/tickets (B04), catálogo/API (B05), administración (B06), QA portable (B07), GitHub/publicación (B08) y PDF/paquete/defensa (B09). No hay credenciales de prueba ni URL pública para entregar todavía.
