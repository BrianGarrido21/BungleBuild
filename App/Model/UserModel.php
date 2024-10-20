<?php 
class UserModel extends DataBase {
    private static $instance = null;

    private $user_id;
    private $name;
    private $email;
    private $password;
    private $rol;
    private $created_at;

    private function __construct() {
        parent::__construct();
        $this->table = "users";
    }

    // Método estático que controla la única instancia (Singleton)
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self(); 
        }
        return self::$instance;
    }

    // Método para cargar datos del usuario en el modelo (interno)
    private function loadUserData($data) {
        $this->user_id = $data['user_id'];
        $this->name = $data['name'];
        $this->email = $data['email'];
        $this->password = $data['password'];
        $this->rol = $data['rol'];
        $this->created_at = $data['created_at'];
    }


    public function getUserById($user_id) {
        $result = $this->select()->where("user_id = $user_id")->get();

        if ($result) {
            $this->loadUserData($result[0]);
            return $this; // Retorna el objeto del mismo UserModel con los datos cargados
        }
        return null;
    }

    // Obtener un usuario por su email
    public function getUserByEmail($email) {
        $result = $this->select("*")->where("email = $email")->get();
        if ($result) {
            $this->loadUserData($result[0]);
            return $this; // Retorna el objeto del mismo UserModel con los datos cargados
        }
        return null;
    }
    // Registrar al usuario
    public function register($data){
        if(!$this->getUserByEmail($data['email'])){
            $this->insert($data)->executeInsert();
        }else{
           return null;
        }
    }

    // Loguear al usuario
    public function logIn($email,$password) {
        $result = $this->select()->where("email = $email")->and("password = $password")->get();
        if ($result) {
            $this->loadUserData($result[0]);
            return $this; // Retorna el objeto del mismo UserModel con los datos cargados
        }
        return null;
    }

    // Actualizar usuario por su ID
    public function updateUser($user_id, $data) {
        return $this->update($data)->where("user_id = ?", [$user_id])->executeUpdate($this->query, $this->params);
    }

    // Eliminar un usuario por su ID
    public function deleteUser($user_id) {
        return $this->delete()->where("user_id = ?", [$user_id])->executeDelete($this->query, $this->params);
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