# EVENTIFY: estado y continuidad

Fecha de referencia: 2026-10-08, zona del usuario America/Costa_Rica.

## Resumen actual

- Usuario autorizó iniciar la construcción por bloques.
- B00: commit inicial creado: 44da548. Se conservó el snapshot documental que estaba en el índice.
- B01: stack Docker ejecutado por el usuario; salud PDO/MySQL 200 y nueve comprobaciones HTTP verificadas. Persistencia/reinicio todavía pendiente.
- Git local: rama `codex/plan-eventify`; identidad reutilizada de Código Tico y verificada con cuenta GitHub USUARIO_DESTINO. Código B01–B04 se prepara como 0.1.0-dev.1; no es estable. Repositorio privado creado: USUARIO_DESTINO/eventify. Push inicial confirmado en main y codex/plan-eventify, ambas a0e0102; etiquetas checkpoint-b00 → 44da548 y v0.1.0-dev.1 → a0e0102. Referencias verificadas mediante git ls-remote.
- Usuario autorizó versionado/push progresivo. Remoto: REPOSITORIO_DESTINO_PENDIENTE. No se modificó Código Tico ni se inventaron fechas de commits.
- Linux Mint 22.3: el usuario instaló Docker 29.1.3 y Compose 2.40.3. Codex no tiene acceso directo al socket; sudo requiere contraseña interactiva en la terminal del usuario. PHP/MySQL objetivo se ejecutarán dentro de contenedores.
- Herramientas temporales de verificación: PHP CLI 8.3.6 y Compose 2.40.3 extraídos de paquetes Ubuntu en /tmp, sin instalación global.
- Stack objetivo: PHP 8.4/Apache + MySQL 8.4 LTS en Docker; imágenes fijadas por digest. El usuario inició web/db saludables; imagen B02/B03 ejecutada; corrección HTTP posterior pendiente de reconstrucción.
- Vista inicial en construcción: comprobada por navegador en móvil/escritorio. Vista temporal en http://127.0.0.1:8085, sin base de datos; puede terminar al cerrar procesos/sesión. El proyecto Docker utiliza 8080.
- B02: esquema, migraciones, siembra opcional y reglas preparados; Migraciones 001/002 aplicadas en MySQL; suite aislada falló en comprobación HTTP de cookie. B03: pantallas/endpoints de registro, ingreso, cuenta, roles y salida preparados; imagen actualizada/integración pendientes. B04 parcial: reservas/cancelación transaccionales, AJAX, catálogo mínimo, historial y consulta privada de ticket preparados; QR SVG integrado y decodificado en prueba independiente; integración objetivo pendiente. Dashboard pendiente.
- Windows: scripts preparados, versión/equipo/prueba real pendientes.
- QR: BaconQrCode 3.1.1/enum 1.0.7 instalados, lockfile y auditoría preparados; contenedor actualizado pendiente. API/jQuery y otros módulos pendientes.
- Publicación: InfinityFree es candidata de investigación; PHP/MySQL real, API, secretos y límites aún no verificados. No se creó cuenta ni URL.

## Próxima acción necesaria

El usuario ejecuta en su terminal, desde la carpeta del proyecto:

```bash
bash scripts/verify-dev-linux.sh
```

La contraseña de sudo se introduce solo en esa terminal; no enviarla al chat. El arranque inicial ya ocurrió; falta reconstruir la imagen actualizada y ejecutar suites. El script no agrega permisos de grupo Docker ni elimina instalaciones/datos existentes.

El comando activa el modo de desarrollo con montajes de código de solo lectura, aplica migraciones y ejecuta pruebas de unidades y MySQL/HTTP aisladas. Permite comprobar siguientes cambios PHP/HTML/CSS sin reconstruir cada vez. Guarda resultado en storage/verification-status.txt y registro privado en storage/verification.log para consultar sin copiar todo al chat. Después verificar navegador y persistencia mediante reinicio conservando volúmenes.

## Pendientes no resueltos por inferencia

1. Próximos incrementos: commits/push/etiquetas progresivos, revisión y validación antes de versiones estables.
2. Windows/arquitectura/equipo receptor y posibilidad de validación real.
3. Proveedor público gratuito validado con MySQL, persistencia y API; localhost no reemplaza la URL pública.
4. Fecha de entrega, participación del estudiante y evidencia humana de defensa.
5. Adoptar definiciones de estados/ocupación/cancelación/QR al implementar cada bloque.

## Registro de bloques

| Bloque | Estado | Evidencia | Limitación |
| --- | --- | --- | --- |
| B00 | Commit creado | 44da548: documentación inicial | No certifica implementación ni entrega |
| B01 | Implementado parcialmente; cierre pendiente | [Evidencia B01](evidencias/B01.md), [arquitectura](ARQUITECTURA.md), [manual](INSTALACION.md) | Persistencia sin probar; Windows no probado |
| B02 | Implementado; cierre pendiente | [Modelo de datos](DATOS.md), [evidencia B02](evidencias/B02.md) | Migraciones aplicadas; suite aislada sin aprobar |
| B03 | Implementado; cierre pendiente | [Evidencia B03](evidencias/B03.md) | Corrección cookie/CSRF preparada; repetir integración |
| B04 | Implementación parcial | [Evidencia B04](evidencias/B04.md) | QR independiente comprobado; integración/concurrencia y navegador pendientes |
| B05 | Pendiente | Catálogo/jQuery/AJAX/API | Depende B04 |
| B06 | Pendiente | CRUD/admin/dashboard | Depende B04 |
| B07 | Pendiente | QA integral/Linux/Windows/persistencia | Depende B05/B06 |
| B08 | Pendiente | GitHub/publicación | Requiere autorizaciones externas y proveedor viable |
| B09 | Pendiente | PDF/paquete/defensa | Requiere versión final comprobada |

## Verificaciones de B01

- Compose config válido, redes/puertos comprobados; sintaxis de scripts Linux correcta.
- 11 archivos PHP con sintaxis correcta usando CLI temporal 8.3, no imagen objetivo 8.4.
- 4 comprobaciones de configuración + 7 de claves/persistencia de configuración/redes + 9 respuestas HTTP correctas; alcance limitado a B01.
- Navegador in-app: móvil 390×844 (una columna) y escritorio 1280×900 (tres), sin desbordamiento horizontal observado.
- Salud 503 segura cuando no hay MySQL; no afirmar conexión funcional.
- Arranque Docker y salud real comprobados; persistencia, Windows, URL pública y defensa pendientes.
- El usuario ejecutó la instalación de Docker. No se borraron datos, no hubo gastos, commits ni publicación.

## Verificaciones de B02

- 19 archivos PHP con sintaxis válida mediante CLI temporal 8.3.6.
- 4 comprobaciones de configuración y 19 de reglas correctas.
- Scripts Bash y Compose de pruebas comprobados estáticamente.
- Suite MySQL ejecutada por el usuario: llegó hasta HTTP y falló en la comprobación de emisión de cookie. Corrección preparada, suite completa aún no aprobada.
- Migraciones 001/002 ejecutadas; demo se ejecutó en la suite aislada antes del fallo HTTP. No cerrar B02 hasta repetir y completar la suite.

## Verificaciones de B03

- Sintaxis PHP válida con CLI temporal 8.3.6; 16 comprobaciones de registro/CSRF correctas. Total de unidades: 39.
- Nueve respuestas HTTP comprobadas en el Docker anterior a B03, salud 200.
- Suite MySQL ampliada ejecutada por el usuario: falló al esperar Set-Cookie en una sesión ya existente. Corrección de test y respuesta CSRF 403 preparadas; repetir.
- No marcar autenticación terminada hasta comprobar la imagen actualizada y navegador.

## Verificaciones de B04

- 19 unidades nuevas de cantidades/importes correctas; total vigente 58.
- Suite preparada con rollback y dos carreras de conexiones independientes; no ejecutada todavía.
- Sitio HTTP continúa respondiendo 500 ante CSRF inválido: versión anterior sigue activa. Activación del modo dev/corrección aún pendiente.

- QR SVG real generado con dependencia compatible en entorno temporal y decodificado con ZBar, identificador exacto sin datos personales. Plataforma del proyecto ^8.4 permanece intacta; imagen objetivo pendiente.
- Composer validate/audit correctos con advertencias documentadas; JS reservations.js con sintaxis válida.

## Plantilla después de cada bloque

```text
Fecha y bloque:
Objetivo y requisitos:
Archivos/decisiones modificados:
Pruebas y comandos ejecutados:
Resultados observados:
Pruebas no ejecutadas y motivo:
Impacto en datos y despliegue:
Commit/PR/URL, si existen:
Riesgos/pendientes:
Próxima acción:
```

Actualizar con hechos; evidencias extensas en docs/evidencias/Bxx.md. No duplicar estados contradictorios ni marcar compatibilidad/defensa sin comprobación.

## Versionado adoptado

Ver [VERSIONADO.md](VERSIONADO.md) y CHANGELOG.md. Workflow manual preparado; no se ejecutó ni se habilitó despliegue. Rollback real de código/datos pendiente de ensayo aislado.

Publicación inicial: https://github.com/USUARIO_DESTINO/eventify (privado). SSH autenticado y push verificado; sin .env/vendor/datos privados en el índice. CI manual preparado y no ejecutado. No se desplegó la aplicación ni se restauró/borró MySQL.
