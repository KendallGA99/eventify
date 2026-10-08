# Configuración

La aplicación toma variables del entorno del proceso PHP. Docker Compose las inyecta desde `.env`; nunca se sirve ni copia ese archivo a la imagen. El cargador no depende de rutas personales o de instalar PHP en el host. La conexión PDO reside en `app/Infrastructure/Database.php`.
