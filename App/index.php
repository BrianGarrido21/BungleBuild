<?php
session_start();
require "config.php";

$data = DataBase::getInstance();


$data->setTable('users');


$results = $data->select()->where('user_id = "1"')->and('rol = "administrative"')->get();


if ($results) {
    echo '<pre>';
    print_r($results);
} else {
    echo "No se encontraron resultados.";
}


// include "View/add_user.php";