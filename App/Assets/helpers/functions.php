<?php 


    function old($field, $data = []) {
        // Verificar primero si hay datos del controlador
        if (isset($data[$field])) {
            return htmlspecialchars($data[$field], ENT_QUOTES, 'UTF-8');
        }

        // Si no, revisar el $_POST (en caso de errores)
        return isset($_POST[$field]) ? htmlspecialchars($_POST[$field], ENT_QUOTES, 'UTF-8') : '';
    }

    function selected($field, $data) {
        return (old($field) == $data) ? 'selected' : '';
    }
        
    function validDniCifNie($dni){
        $cif = strtoupper($dni);
        for ($i = 0; $i < 9; $i ++){
        $num[$i] = substr($cif, $i, 1);
        }
        // Si no tiene un formato valido devuelve error
        if (!preg_match('/((^[A-Z]{1}[0-9]{7}[A-Z0-9]{1}$|^[T]{1}[A-Z0-9]{8}$)|^[0-9]{8}[A-Z]{1}$)/', $cif)){
        return false;
        }
        // Comprobacion de NIFs estandar
        if (preg_match('/(^[0-9]{8}[A-Z]{1}$)/', $cif)){
        if ($num[8] == substr('TRWAGMYFPDXBNJZSQVHLCKE', substr($cif, 0, 8) % 23, 1)){
            return true;
        }else{
            return false;
        }
        }
        // Algoritmo para comprobacion de codigos tipo CIF
        $suma = $num[2] + $num[4] + $num[6];
        for ($i = 1; $i < 8; $i += 2){
        $suma += (int)substr((2 * $num[$i]),0,1) + (int)substr((2 * $num[$i]), 1, 1);
        }
        $n = 10 - substr($suma, strlen($suma) - 1, 1);
        // Comprobacion de NIFs especiales (se calculan como CIFs o como NIFs)
        if (preg_match('/^[KLM]{1}/', $cif)){
        if ($num[8] == chr(64 + $n) || $num[8] == substr('TRWAGMYFPDXBNJZSQVHLCKE', substr($cif, 1, 8) % 23, 1)){
            return true;
        }else{
            return false;
        }
        }
        // Comprobacion de CIFs
        if (preg_match('/^[ABCDEFGHJNPQRSUVW]{1}/', $cif)){
        if ($num[8] == chr(64 + $n) || $num[8] == substr($n, strlen($n) - 1, 1)){
            return true;
        }else{
            return false;
        }
        }
        // Comprobacion de NIEs
        // T
        if (preg_match('/^[T]{1}/', $cif)){
        if ($num[8] == preg_match('/^[T]{1}[A-Z0-9]{8}$/', $cif)){
            return true;
        }else{
            return false;
        }
        }
        // XYZ
        if (preg_match('/^[XYZ]{1}/', $cif)){
        if ($num[8] == substr('TRWAGMYFPDXBNJZSQVHLCKE', substr(str_replace(array('X','Y','Z'), array('0','1','2'), $cif), 0, 8) % 23, 1)){
            return true;
        }else{
            return false;
        }
        }
        // Si todavía no se ha verificado devuelve error
        return false;
    }