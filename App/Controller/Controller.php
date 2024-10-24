<?php

class Controller {

    // Redirige al controlador y acción basado en la ruta indicada.
    public static function redirectTo($route, $params = []) {
        // Separar el controlador y la acción desde la ruta usando un punto como separador
        list($controllerName, $actionName) = explode('.', $route);

        // Convertir el nombre del controlador a la clase correspondiente (e.g., Task => TaskController)
        $controllerClass = ucfirst($controllerName) . 'Controller';

        // Comprobar si la clase del controlador existe
        if (class_exists($controllerClass)) {
            $controller = new $controllerClass();

            // Comprobar si el método del controlador existe
            if (method_exists($controller, $actionName)) {
                // Llamar al método del controlador con los parámetros proporcionados
                call_user_func_array([$controller, $actionName], $params);
            } else {
                // Si la acción no existe, lanzar un error o manejar la redirección
                throw new Exception("La acción $actionName no existe en el controlador $controllerClass.");
            }
        } else {
            // Si el controlador no existe, lanzar un error o manejar la redirección
            throw new Exception("El controlador $controllerClass no existe.");
        }
    }
   
    public static function render($view,$data = []) {

        $viewPath = VIEW_PATH . $view . '.php';

        if (file_exists($viewPath)) {
            include($viewPath);
        } else {
            echo "La vista $view no existe.";
        }
    }
    

}