# QA de Firefox

Ensayo de desarrollo con Firefox real y geckodriver. Se ejecuta en un perfil temporal, sobre una instalación local con cuentas ficticias ya existentes. No crea reservas, guarda perfiles ni modifica eventos. Las sesiones y los contadores de autenticación sí cambian al ingresar/cerrar sesión.

Requiere Firefox, Python, Selenium de `requirements.txt` y geckodriver oficial de Mozilla. Estas herramientas son del evaluador; no son dependencias para ejecutar Eventify ni se incorporan a la imagen de la aplicación. Instalar Selenium en un entorno Python separado. No usar el perfil personal de Firefox.

```bash
python tests/browser/firefox-qa.py \
  --base http://localhost:8086 \
  --application-version 0.1.0-dev.45 \
  --driver /ruta/al/geckodriver \
  --accounts /ruta/privada/cuentas-evaluacion.json \
  --output /ruta/privada/resultados-firefox
```

El archivo privado de cuentas es una lista JSON con `email`, `password` y `role` (`USUARIO`/`ADMINISTRADOR`). No incluirlo en Git ni adjuntarlo a una incidencia. El script restringe el destino a localhost/127.0.0.1.

Comprueba búsqueda, combinación/limpieza, fechas, vacío, foco Tab/Shift+Tab, ojo de contraseña, ingreso al inicio, perfil, historial cancelado, QR cargado, permisos por URL, logout y panel/categorías/eventos/reservas. WebDriver BiDi fija anchos efectivos de390/768/1280; se verifica la medida real y ausencia de desbordamiento. Captura cada pantalla tras terminar sus animaciones.

La fecha se asigna al control y se emite change para comprobar el filtrado en Firefox. No presentar ese caso como escritura con teclas o selección visual del calendario nativo. Los intentos de entrada segmentada del ensayo del10oct no introdujeron una fecha completa mediante WebDriver; tampoco acreditan un fallo al hacerlo manualmente. El resultado JSON conserva este límite. No se hace escaneo óptico del QR ni instalación Windows.

Evidencia del10oct2026: Firefox157.0.1, geckodriver0.37.1 y Selenium4.51.0; 54 comprobaciones aprobadas sobre dev.43. Selenium tiene licencia Apache2.0; geckodriver, MPL2.0. Descarga de geckodriver desde el repositorio oficial, con digest SHA256 del release verificado. [Perfiles temporales de Mozilla](https://firefox-source-docs.mozilla.org/testing/geckodriver/Profiles.html).

Revisión visual dev.45: 22 pantallas a360/390/768/1280/1440 y844×390 (horizontal),173 comprobaciones. Incluye inicio, registro, ingreso,404, catálogo/filtros desplegables, detalle/agotado/cancelado, perfil/historial/ticket, permisos, panel y formularios administrativos. Se guardan capturas completas a390/1440 y capturas de cada viewport; revisar visualmente además de comprobar el ancho.
