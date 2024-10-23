<?php

class TaskModel implements Paginator {

    private $db;
    private $table = 'tasks';  // Nombre de la tabla de tareas

    public function __construct() {
        $this->db = Database::getInstance();
        $this->db->setTable($this->table); 
    }

    // Crear una nueva tarea
    public function createTask($data) {
        return $this->db->insert($data);
    }

    // Obtener tarea por ID
    public function getTaskById($task_id) {
        return $this->db->select()->where('task_id = ?', [$task_id])->get();
    }

    // Actualizar una tarea por ID
    public function updateTask($task_id, $data) {
        return $this->db->update($data)->where('task_id = ?', [$task_id])->execute();
    }

    // Eliminar una tarea por ID
    public function deleteTask($task_id) {
        return $this->db->delete()->where('task_id = ?', [$task_id])->execute();
    }

    // Obtener todas las tareas
    public function getAllTasks() {
        return $this->db->select()->get();
    }

    // Obtener tareas con paginación (Implementación de la interfaz)
    public function getPaginatedResults($itemsPerPage, $currentPage) {
        $offset = ($currentPage - 1) * $itemsPerPage;
        return $this->db->select()->limit($itemsPerPage, $offset)->get();
    }

    // Contar el número total de tareas (Implementación de la interfaz)
    public function getTotalItems() {
        $result = $this->db->select('COUNT(*) as total')->get();
        return $result[0]['total'];
    }

    // Obtener el número total de páginas (Implementación de la interfaz)
    public function getTotalPages($itemsPerPage) {
        $totalItems = $this->getTotalItems();
        return ceil($totalItems / $itemsPerPage);
    }

    // Obtener la página actual (Implementación de la interfaz)
    public function getCurrentPage() {
        return isset($_GET['page']) ? (int) $_GET['page'] : 1;
    }

    // Obtener el número de elementos por página (Implementación de la interfaz)
    public function getItemsPerPage() {
        return isset($_GET['items_per_page']) ? (int) $_GET['items_per_page'] : 10;
    }

    // Determinar si hay una página anterior (Implementación de la interfaz)
    public function hasPreviousPage($currentPage) {
        return $currentPage > 1;
    }

    // Determinar si hay una página siguiente (Implementación de la interfaz)
    public function hasNextPage($currentPage, $itemsPerPage) {
        return $currentPage < $this->getTotalPages($itemsPerPage);
    }

    // Obtener tareas por estado
    public function getTasksByStatus($status) {
        return $this->db->select()->where('status = ?', [$status])->get();
    }

    // Asignar un operario a una tarea
    public function assignWorker($task_id, $worker_id) {
        return $this->db->update(['assigned_worker' => $worker_id])
                        ->where('task_id = ?', [$task_id])->execute();
    }
}