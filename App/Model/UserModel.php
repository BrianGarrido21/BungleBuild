<?php 
class UserModel {

    private $user_id;
    private $name;
    private $email;
    private $password;
    private $rol;
    private $created_at;

    public function __construct($data) {
        $this->user_id = $data['user_id'];
        $this->name = $data['name'];
        $this->email = $data['email'];
        $this->password = $data['password'];
        $this->rol = $data['rol'];
        $this->created_at = $data['created_at'];
    }


    // Métodos getters para acceder a los datos del usuario
    public function getUserId() {
        return $this->user_id;
    }

    public function getName() {
        return $this->name;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getRol() {
        return $this->rol;
    }

    public function getCreatedAt() {
        return $this->created_at;
    }

    // Métodos setters para modificar los datos del usuario si es necesario
    public function setName($name) {
        $this->name = $name;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function setRol($rol) {
        $this->rol = $rol;
    }
}