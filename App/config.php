<?php


// Configuración de la base de datos
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'BungleBuild');

// Configuración de rutas
define('BASE_URL', 'http://localhost/BungleBuild/App/');
define('ROOT_PATH', __DIR__ . '/');
define('ASSETS_PATH', ROOT_PATH . 'Assets/');
define('CONTROLLER_PATH', ROOT_PATH . 'Controller/');
define('MODEL_PATH', ROOT_PATH . 'Model/');
define('VIEW_PATH', ROOT_PATH . 'View/'); 
define('DOCS_PATH', ROOT_PATH . 'Docs/'); 

// Configuración del modo de depuración
define('DEBUG', true);

// Incluir helpers
require_once ASSETS_PATH . 'helpers/functions.php';

// Autoload para cargar clases automáticamente
spl_autoload_register(function ($class_name) {
    if (file_exists(CONTROLLER_PATH . $class_name . '.php')) {
        require_once CONTROLLER_PATH . $class_name . '.php';
    } elseif (file_exists(MODEL_PATH . $class_name . '.php')) {
        require_once MODEL_PATH . $class_name . '.php';
    }else {
        die("No se ha podido cargar la clase: $class_name");
    }
});