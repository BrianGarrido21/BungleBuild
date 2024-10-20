<?php 


    function old($field) {
        return isset($_POST[$field]) ? htmlspecialchars($_POST[$field], ENT_QUOTES, 'UTF-8') : '';
    }
