<?php

class ErrorHandler {

    private $errors = [];
    private $customMessages = []; 
    private $format_prefix;
    private $format_suffix;

    public function __construct($format_prefix = '', $format_suffix = ''){
        $this->format_prefix = $format_prefix;
        $this->format_suffix = $format_suffix;
    }


    public function recordError($field, $description){
        $this->errors[$field] = $description;
    }


    public function setCustomErrorMessages($messages) {
        $this->customMessages = $messages;
    }


    public function hasErrors() {
        return count($this->errors) > 0;
    }


    public function hasError($field) {
        return isset($this->errors[$field]);
    }


    public function getError($field) {
        if (isset($this->errors[$field])) {
            return $this->errors[$field];
        } else {
            return '';
        }
    }


    public function getAllErrors() {
        return $this->errors;
    }


    public function countErrors() {
        return count($this->errors);
    }


    public function clearErrors() {
        $this->errors = array();
    }


    public function removeError($field) {
        if (isset($this->errors[$field])) {
            unset($this->errors[$field]);
        }
    }


    public function getFormattedError($field) {
        if ($this->hasError($field)) {
            return $this->format_prefix . $this->getError($field) . $this->format_suffix;
        } else {
            return '';
        }
    }


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


    public function getCustomError($field) {
        if (isset($this->customMessages[$field])) {
            return $this->customMessages[$field];
        } elseif ($this->hasError($field)) {
            return $this->getError($field);
        } else {
            return '';
        }
    }


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