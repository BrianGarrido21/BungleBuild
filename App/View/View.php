<?php

class View {

    public static function render($view,$data = []) {

        $viewPath = VIEW_PATH . $view . '.php';

        if (file_exists($viewPath)) {
            include($viewPath);
        } else {
            echo "La vista $view no existe.";
        }
    }
    
}
