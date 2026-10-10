# XML: avisos residuales de dev.34

Referencia histórica: estos resultados corresponden a las imágenes anteriores. Dev.41 corrige el entorno y sus tres imágenes finales no reportan avisos en el escaneo del10oct. Consulte [RUNTIME-CORREGIDO.md](RUNTIME-CORREGIDO.md) y [ESTADO.md](ESTADO.md) para versiones aplicadas, pruebas y límites.


Revisión del 9 de octubre de 2026, B07/C-09/SEG-09. Nueve identificadores altos del informe final no tenían mención individual en las revisiones anteriores. Este documento completa su descripción, sin descartarlos ni cambiar su severidad.

| Fuente Debian | Condición |
| --- | --- |
| [CVE-2026-66046](https://security-tracker.debian.org/tracker/CVE-2026-66046) | Expat: consumo excesivo de CPU al procesar muchos atributos XML. No requiere entidades externas; deshabilitar XXE por sí solo no cubre esta condición. |
| [CVE-2026-76956](https://security-tracker.debian.org/tracker/CVE-2026-76956) | Expat: cálculo insuficiente de entropía en getentropy favorece colisiones durante el análisis XML. |
| [CVE-2026-77214](https://security-tracker.debian.org/tracker/CVE-2026-77214) | Expat: XML_ParseBuffer acepta una longitud que excede el buffer previamente asignado; depende de cómo lo use el consumidor nativo. |
| [CVE-2026-93990](https://security-tracker.debian.org/tracker/CVE-2026-93990) | Expat: acepta secuencias UTF-16 mal formadas; el impacto cambia según XML_UNICODE y el consumidor. |
| [CVE-2026-86138](https://security-tracker.debian.org/tracker/CVE-2026-86138) | libxml2: desbordamiento al agregar nombres cualificados mediante xmlDictAddQString. |
| [CVE-2026-86139](https://security-tracker.debian.org/tracker/CVE-2026-86139) | libxml2: desbordamiento entero en xmlURIEscapeStr. La tabla Trixie sigue vulnerable; las notas mencionan introducción en 2.13.0 y requieren aclaración respecto al paquete basado en 2.9.14. |
| [CVE-2026-86140](https://security-tracker.debian.org/tracker/CVE-2026-86140) | libxml2: desbordamiento en xmlSnprintfElements al preparar información de validación. |
| [CVE-2026-86142](https://security-tracker.debian.org/tracker/CVE-2026-86142) | libxml2: desbordamiento en evaluación de XPointer al saturarse el cálculo de longitud. |
| [CVE-2026-86143](https://security-tracker.debian.org/tracker/CVE-2026-86143) | libxml2: callbacks de escritura pueden recibir longitudes negativas por desbordamiento; no se limita a lectura de XML. |

Las páginas Debian consultadas mantienen vulnerables los paquetes Trixie indicados; las correcciones listadas pertenecen a otras versiones/distribuciones. No mezclar sid/forky con Trixie ni interpretar ausencia de FixedVersion en Trivy como inexistencia de una corrección del proyecto original. Para CVE-2026-86139 se conserva la discrepancia entre tabla y nota; no se etiqueta como falso positivo.

## Alcance del proyecto

La búsqueda en app, public, bin y archivos Composer no encontró llamadas a DOMDocument, SimpleXML, XMLReader, XMLWriter, xml_parse, XInclude o XPointer. WeatherClient lee HTTPS con validación de certificado, limita la respuesta a 128 KiB y decodifica JSON. TicketQr invoca el codificador de matriz y construye SVG con rectángulos numéricos; el payload solo acepta un identificador hexadecimal de 64 caracteres. No usa SvgImageBackEnd ni procesa XML aportado por el visitante.

Esto limita las rutas de entrada revisadas, pero no audita todos los consumidores de Expat/libxml2 en Apache/PHP o sus bibliotecas. El aviso de escritura no queda descartado solo por no leer XML. No se hicieron pruebas de explotación, no se retiraron bibliotecas necesarias ni se aplicó un parche manual. Permanecen como riesgos de dependencias sin cierre técnico.

El crítico [CVE-2026-6653](https://security-tracker.debian.org/tracker/CVE-2026-6653) se reconsultó: Trixie sigue vulnerable según Debian. Su condición es análisis de XML manipulado; el alcance de las rutas anteriores tampoco basta para declararlo resuelto.

## Inventario vigente

AVISOS-RESIDUALES.csv enumera los 156 identificadores únicos de las 424 filas finales PHP/Apache, agregando paquetes y conservando referencias a las revisiones existentes. Tener una referencia documental no significa tener un parche o un descarte. Las filas de MySQL y los riesgos del host se mantienen en sus informes propios.


## Reconsulta después de la aceptación dev.37

9 de octubre de 2026. Se reconsultaron los trackers oficiales de [libxml2/CVE-2026-6653](https://security-tracker.debian.org/tracker/CVE-2026-6653) y [Apache/CVE-2026-63292](https://security-tracker.debian.org/tracker/CVE-2026-63292). Ambos mantienen Trixie vulnerable; sus tablas sitúan correcciones en forky/sid. La nota Debian de libxml2 clasifica el asunto como menor y sin DSA previsto, lo que no elimina la severidad crítica del escaneo conservado. No se encontró en esas fuentes un parche de Trixie que pueda incorporarse directamente. No se mezclaron distribuciones ni se alteró la imagen verificada. Esto no constituye una reconsulta exhaustiva de los 156 identificadores ni un nuevo escaneo; los conteos siguen perteneciendo al informe dev.34.


## Evaluación de corrección manual y distribución, dev.37

9 de octubre de 2026. Consulta autenticada de solo lectura al registro oficial Docker: php:8.4-apache mantiene el digest fijado 901b0dbcd2419cc9cd307ea05e57403449ce722ccd5b32335da6e8d39f2b1ee0; no hay una actualización de esa etiqueta que permita atribuir una corrección nueva.

Se inspeccionó el [cambio oficial citado por Debian, 463bbeec](https://github.com/GNOME/libxml2/commit/463bbeeca1805b5c4828f50d0fefc4eebaf620df): afecta siete archivos y modifica el control de amplificación de entidades, además de estructuras públicas de entities.h y parser.h. No es un reemplazo aislado de una línea. Aplicarlo manualmente sobre el paquete Debian exigiría estudiar los backports ya presentes, compatibilidad binaria de PHP/Apache y pruebas nativas de libxml2; las 225 pruebas de EVENTIFY no cubren ese trabajo. No se compiló ni instaló una biblioteca personalizada, ni se afirma que el parche sea imposible.

Decisión de esta revisión: conservar la imagen reproducible y sus mitigaciones de entrada ya comprobadas; no importar forky/sid ni sustituir una biblioteca crítica sin evidencia. El aviso continúa abierto como dependencia, junto con los demás XML residuales. Esta decisión no es aceptación automática del riesgo ni certificación de una versión estable.


## Reconsulta del 10 de octubre de 2026

Los trackers oficiales de [libxml2/CVE-2026-6653](https://security-tracker.debian.org/tracker/CVE-2026-6653) y [Apache/CVE-2026-63292](https://security-tracker.debian.org/tracker/CVE-2026-63292) mantienen Trixie vulnerable; las versiones corregidas mostradas pertenecen a forky/sid. Consulta directa del registro oficial Docker: la etiqueta php:8.4-apache conserva el digest fijado901b0dbcd2419cc9cd307ea05e57403449ce722ccd5b32335da6e8d39f2b1ee0. No se encontró una actualización nueva de esa etiqueta ni corrección Trixie en esas dos fuentes. No se cambió el runtime, no se reescaneó con la misma base y no se certifica seguridad completa. Evidencia de digest en storage/security-refresh-20261010/php-registry.json.
