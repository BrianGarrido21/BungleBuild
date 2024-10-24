<?php

class UserModel implements Paginator {

    private $db;
    private $table = 'users';

    public function __construct() {
        $this->db = Database::getInstance();
        $this->db->setTable($this->table);
    }

    // Método para registrar un usuario
    public function registerUser($data) {
        try {
            return $this->db->insert($data);
        } catch (Exception $e) {
            error_log("Error to register user: " . $e->getMessage()."\n");
            return false;
        }
    }

    // Método para autenticar un usuario
    public function authenticateUser($data) {
        try {
            $user = $this->db->select()->where('email = ?', [$data['email']])->get();
            if ($user && password_verify($data['password'], $user['password'])) {
                return $user;
            }
            return false;
        } catch (Exception $e) {
            error_log("Error to authenticate user: " . $e->getMessage())."\n";
            return false;
        }
    }

    // Implementación del método para obtener usuarios con paginación
    public function getPaginatedResults($itemsPerPage, $currentPage) {
        $offset = ($currentPage - 1) * $itemsPerPage;
        return $this->db->select()->limit($itemsPerPage, $offset)->get();
    }

    // Implementación del método para contar el número total de usuarios
    public function getTotalItems() {
        $result = $this->db->select('COUNT(*) as total')->get();
        return $result[0]['total'];
    }

    // Implementación del método para obtener el número total de páginas
    public function getTotalPages($itemsPerPage) {
        $totalItems = $this->getTotalItems();
        return ceil($totalItems / $itemsPerPage);
    }

    // Implementación del método para obtener la página actual
    public function getCurrentPage() {
        return isset($_GET['page']) ? (int) $_GET['page'] : 1;
    }

    // Implementación del método para obtener el número de elementos por página
    public function getItemsPerPage() {
        return isset($_GET['items_per_page']) ? (int) $_GET['items_per_page'] : 10;
    }

    // Implementación del método para determinar si hay una página anterior
    public function hasPreviousPage($currentPage) {
        return $currentPage > 1;
    }

    // Implementación del método para determinar si hay una página siguiente
    public function hasNextPage($currentPage, $itemsPerPage) {
        return $currentPage < $this->getTotalPages($itemsPerPage);
    }

    // Otros métodos de UserModel
    public function updateUser($data) {
        try {
            return $this->db->update([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'],
                'rol' => $data['rol']
            ])->where('user_id = ?', [$data['user_id']])->execute();
        } catch (Exception $e) {
            error_log("Error to update the user: " . $e->getMessage());
            return false;
        }
    }

    public function deleteUser($user_id) {
        try {
            return $this->db->delete()->where('user_id = ?', [$user_id])->execute();
        } catch (Exception $e) {
            error_log("Error to delete the user: " . $e->getMessage());
            return false;
        }
    }
}