<?php 


class ErrorHandler {

    private $errors = [];
    private $customMessages = []; 
    private $format_prefix;
    private $format_suffix;

    // Constructor que inicializa los prefijos y sufijos de formato para los errores.
    public function __construct($format_prefix = '', $format_suffix = ''){
        $this->format_prefix = $format_prefix;
        $this->format_suffix = $format_suffix;
    }

    // Método para registrar un error para un campo específico.
    public function recordError($field, $description){
        $this->errors[$field] = $description;
    }

    // Método para establecer mensajes de error personalizados para los campos.
    public function setCustomErrorMessages($messages) {
        $this->customMessages = $messages;
    }

    // Método que verifica si hay errores registrados.
    public function hasErrors() {
        return count($this->errors) > 0;
    }

    // Método que verifica si existe un error específico para un campo.
    public function hasError($field) {
        return isset($this->errors[$field]);
    }

    // Método para obtener el error asociado a un campo específico.
    public function getError($field) {
        if (isset($this->errors[$field])) {
            return $this->errors[$field];
        } else {
            return '';
        }
    }

    // Método que devuelve todos los errores registrados.
    public function getAllErrors() {
        return $this->errors;
    }

    // Método que cuenta el número de errores registrados.
    public function countErrors() {
        return count($this->errors);
    }

    // Método para limpiar (eliminar) todos los errores registrados.
    public function clearErrors() {
        $this->errors = array();
    }

    // Método para eliminar un error específico de un campo.
    public function removeError($field) {
        if (isset($this->errors[$field])) {
            unset($this->errors[$field]);
        }
    }

    // Método que devuelve un error formateado (con prefijo y sufijo) para un campo.
    public function getFormattedError($field) {
        if ($this->hasError($field)) {
            return $this->format_prefix . $this->getError($field) . $this->format_suffix;
        } else {
            return '';
        }
    }

    // Método que devuelve todos los errores formateados.
    public function getFormattedErrors() {
        if (!$this->hasErrors()) {
            return '';
        }

        $formattedErrors = '';
        foreach ($this->errors as $field => $error) {
            $formattedError = $this->format_prefix . $error . $this->format_suffix;
            $formattedErrors .= $formattedError . "<br>";
        }

        return $formattedErrors;
    }

    // Método que devuelve un mensaje de error personalizado para un campo (si existe).
    // Si no hay un mensaje personalizado, devuelve el error estándar.
    public function getCustomError($field) {
        if (isset($this->customMessages[$field])) {
            return $this->customMessages[$field];
        } elseif ($this->hasError($field)) {
            return $this->getError($field);
        } else {
            return '';
        }
    }

    // Método que devuelve todos los errores formateados con mensajes personalizados (si existen).
    public function getFormattedCustomErrors() {
        if (!$this->hasErrors()) {
            return '';
        }
        $formattedErrors = '';
        foreach ($this->errors as $field => $error) {
            $customMessage = $this->getCustomError($field);
            $formattedError = $this->format_prefix . $customMessage . $this->format_suffix;
            $formattedErrors .= $formattedError . "<br>";
        }

        return $formattedErrors;
    }
}