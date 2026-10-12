# Revisión de la entrega académica

Revisión del 11 de octubre de 2026. Fuente: enunciado de suficiencia, diez páginas, secciones 5 a 13. Este registro es interno; no sustituye el documento técnico ni certifica pruebas nuevas. Aplicación pública dev.46, instalador dev.52; reinstalación de Eventify con Docker existente confirmada por el usuario. La instalación inicial de Docker en un equipo compatible no se repitió.

## Entregables exigidos

| Entregable del enunciado | Contenido | Situación y cierre necesario |
| --- | --- | --- |
| A. Aplicación publicada | URL accesible con frontend, PHP, MySQL, autenticación y operaciones principales. | URL registrada: https://eventify-academico.onrender.com. Evidencia previa en ESTADO; comprobar disponibilidad y cuentas cerca de la evaluación. No se revalidó la URL en esta revisión documental. |
| B. Repositorio GitHub | Código completo, historial real, README, configuración Docker, database y docs; sin secretos. | Repositorio privado USUARIO_DESTINO/eventify con historial progresivo. Falta confirmar acceso del evaluador y acordar titularidad con el receptor. No basta el ZIP ni un repositorio nuevo con un commit masivo. |
| C. Documento técnico PDF | Portada/descripción, RF/RNF, historias y aceptación, sitemap, arquitectura, ER, diccionario, API, decisiones, seguridad, despliegue y conclusiones. | Fuente reorganizada para explicar el sistema, eliminando el diario de versiones y referencias a archivos internos. Validar el PDF renderizado y actualizar el estado Windows cuando termine el ensayo. |
| D. Cuentas de prueba | Administrador y usuario funcionales en la evaluación. | Instalación local nueva genera las dos cuentas y ocho eventos; acceso privado en storage/example-credentials.json. Accesos públicos distintos, se entregan por separado. Falta confirmar recepción y uso por estudiante/evaluador. |

La sección 11 exige además defensa técnica individual y permite solicitar una modificación menor en vivo. La sección 12 admite apoyo de herramientas, pero exige comprender, explicar y modificar la solución. El historial no se reescribe para atribuir trabajo a otra persona. El ZIP para Windows, los accesos de escritorio y el manual TXT son facilidades de entrega acordadas, no nuevos entregables obligatorios del cuadro A-D. No se exige otro PDF de instalación, presentación de diapositivas, video ni formato IEEE en ese cuadro.

## Cobertura del sistema

Se contrastaron los 21 RF y siete RNF con el enunciado completo y la matriz del repositorio. No se identificó un RF original sin implementación referenciada. Esto es una auditoría documental y de rutas de código; no una repetición de todos los flujos.

| Grupo | Cobertura de implementación | Evidencia y límite |
| --- | --- | --- |
| RF-01/02/03 | Registro, sesión, salida y autorización de roles en servidor. | Integración de autenticación/HTTP, controles públicos y recorridos registrados. |
| RF-04/05/06/07/10 | Catálogo, búsqueda, filtros combinados, detalle y umbral de últimos espacios. | Catálogo real, AJAX y pruebas de filtros/umbral; versión final debe seguir conservando esos campos y estados. |
| RF-08/09/11/12/13/14 | Reservas, cupos, duplicidad, historial propio, cancelación >24 h y ticket/QR. | Integración con carreras simultáneas, propiedad y bordes; no hay pasarela de pago ni escáner exigidos. |
| RF-15 | Pronóstico útil con Open-Meteo y respaldo MET Norway. | Éxito real previo, errores controlados y pruebas de caché/presupuesto; no garantizar disponibilidad del proveedor. |
| RF-16 | Cinco indicadores base y derivados de entradas, categoría e ingresos potenciales. | Consultas reales de AdminReadRepository y pruebas con datos conocidos. No son pagos cobrados. |
| RF-17/18/19/20/21 | Categorías, eventos, validación, filtros de reservas y cancelación con historial. | Pruebas administrativas y de concurrencia; autorización comprobada en servidor. |
| RNF-01/02/06 | Mensajes, navegación, diseño adaptable y Chrome/Firefox. | Firefox dev.45: 173 controles/22 pantallas/seis tamaños; recorridos posteriores de dev.46 dirigidos. La matriz antigua tenía referencias obsoletas de Firefox pendiente. |
| RNF-03/04 | Hashes, sesiones, CSRF, consultas preparadas, escape y errores seguros. | Evidencia vigente por versión en ESTADO; seguridad de imágenes dev.41 corresponde a su corte, no a un escaneo nuevo hoy. |
| RNF-05/07 | Paginación, imágenes locales, caché, índices y separación de responsabilidades. | Revisión de estructura; no se declara una carga máxima ni un benchmark no medido. |
| Tecnologías obligatorias | HTML5/CSS3/JS, PHP/MySQL, sesiones, jQuery y AJAX. | jQuery significativo en catálogo e historial; AJAX en catálogo, reservas, cancelación, historial y clima. |
| Docker y portabilidad | Dockerfile, Compose, ejemplo de entorno, migraciones, volúmenes y asistente Windows. | Parser y controles del asistente aprobados; instalación Windows completa y persistencia en el receptor pendientes. |

La ampliación para promover usuarios a administradores fue solicitada posteriormente; no debe presentarse como una exigencia original del profesor.

## Organización propuesta del paquete

- Instalacion/: INSTALAR-EVENTIFY.cmd, LEAME-PRIMERO.txt y Eventify/ con la aplicación ejecutable, scripts Windows, Docker, migraciones, recursos y licencias.
- Documentos/: Documento-Tecnico-Eventify.pdf y una hoja breve con URL pública y enlace al repositorio. No copiar automáticamente todos los diarios internos en esta carpeta.
- Accesos/: instrucciones para localizar las claves locales generadas. Credenciales públicas entregadas por canal privado, sin incorporarlas al repositorio ni a un ZIP de difusión general.

El código completo y los documentos operativos conservan su lugar en GitHub. El instalador no depende de AGENTS, ESTADO, informes históricos ni herramientas de auditoría para ejecutarse. Una selección de archivos para el receptor se debe hacer mediante un empaquetador reproducible y un manifiesto, conservando la fuente íntegra y el historial en Git; no mediante borrado del proyecto de trabajo. La carpeta del instalador debe conservar todos los archivos realmente llamados por sus scripts.

## Redacción, comentarios e historial

LEAME-PRIMERO.txt se simplificó a extracción, instalación, inicio/detención, cuentas, comprobación y errores habituales. Se quitaron discusiones de pruebas anteriores y referencias al sistema de desarrollo. Se mantiene el aviso real sobre RAM, reinicios, datos y validación Windows pendiente.

El documento técnico explica la aplicación y los límites vigentes. No necesita un relato del sistema operativo del desarrollador ni enlaces a los archivos de seguimiento. Los documentos internos y las recetas de contenedores sí pueden mencionar plataformas cuando técnicamente corresponda; Windows usa Docker y WSL, y esa arquitectura no se oculta ni se cambia por una limpieza editorial.

Se revisaron comentarios de app/, config/, database/, public/index.php y JavaScript propio. Los comentarios aclaran reglas reales de seguridad, transacciones, caché y concurrencia; no se encontraron referencias a ChatGPT, Codex o inteligencia artificial en ese alcance. No se borran licencias, avisos de bibliotecas ni encabezados generados de Composer.

Para que el receptor tenga el código con versiones, se puede transferir el repositorio original o preparar un espejo Git, conservando autores, fechas, ramas y etiquetas. No se ejecuta una transferencia sin definir cuenta/destino y accesos. Un git archive contiene fuente, no historial. El estudiante debe revisar el código y hacer sus propios cambios reales; no se fabrican commits retrospectivos ni se declara que realizó aportes ajenos.

## Pendientes concretos

1. Terminar la instalación Windows que está en curso; comprobar ambos roles, reserva/ticket/cancelación y persistencia del paquete elegido.
2. Incorporar el resultado real en manual/PDF y elegir la referencia final, manteniendo versiones anteriores.
3. Crear el paquete organizado, revisar cada entrada, huellas y exclusión de claves; no reemplazar el instalador mientras se descarga en la VM.
4. Confirmar acceso del evaluador al GitHub privado y entregar ambas cuentas públicas de forma segura.
5. Revisión del documento por Kendall y ensayo de explicación/modificación menor. La defensa se registra cuando ocurra.

No repetir las pruebas Linux por una edición de documentación. No declarar estable la entrega por haber generado el PDF.
