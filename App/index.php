<?php
require "config.php";

$data = DataBase::getInstance();


$data->setTable('users');


$results = $data->select('name','rol','created_at','user_id')->where('user_id = "1"')->and('rol = "administrative"')->get();

/*
if ($results) {

    print_r($results);
} else {
    echo "No se encontraron resultados.";
}
*/

include "View/add_user.php";