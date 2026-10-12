# Pruebas de interfaz

`firefox-qa.py` comprueba navegación, filtros, formularios, sesiones, perfiles, tickets y administración con Firefox y un perfil temporal. Usa Selenium y geckodriver; estas herramientas no son dependencias de la aplicación.

La configuración recibe una URL local, la versión evaluada, la ruta de geckodriver, un archivo privado de cuentas y un directorio de resultados. El archivo de cuentas contiene correo, contraseña y rol; no se incluye en Git. El script restringe el destino a localhost o 127.0.0.1.

Se comprueban tamaños efectivos, foco de teclado y desbordamiento horizontal. Las capturas y resultados indican el alcance del recorrido. La prueba del filtro de fecha asigna el valor y emite change; no acredita interacción manual con el calendario nativo. Los resultados registrados se resumen en [Validación](../../docs/VALIDACION.md).
