<?php

class TaskModel implements Paginator {

    private $db;
    private $table = 'task';  // Nombre de la tabla de tareas
    private $uploadDir = DOCS_PATH . 'tasks/'; // Directorio base para las tareas en Docs
    
    public function __construct() {
        $this->db = Database::getInstance();
        $this->db->setTable($this->table); 
    }

   

     // Crear una nueva tarea
     public function createTask($data, $files) {
        try {

            $taskDir = $this->uploadDir . $this->sanitizeDirectoryName($data['description']);

            // Crear directorio
            if (!is_dir($taskDir)) {
                mkdir($taskDir, 0777, true);
            }

            // Procesar archivos subidos
            $data['sumary_file'] = $this->uploadFile($files['sumary_file'], $taskDir);
            $data['img_file'] = $this->uploadFile($files['img_file'], $taskDir);

            // Insertar los datos de la tarea en la base de datos junto con las rutas de los archivos
            return $this->db->insert($data);

        } catch (Exception $e) {
            // Capturar cualquier excepción y registrar el error
            error_log("Error al crear la tarea: " . $e->getMessage()."\n");
            return false;
        }
    }

    

    public function deleteTask($task_id) {
        try {
            // Obtener la tarea antes de eliminarla
            $task = $this->getTaskById($task_id);
    
            if ($task) {
                // Eliminar los archivos asociados (sumary_file e img_file)
                if (file_exists($task['sumary_file'])) {
                    unlink($task['sumary_file']); // Eliminar archivo resumen
                }
                if (file_exists($task['img_file'])) {
                    unlink($task['img_file']); // Eliminar archivo imagen
                }
    
                // Eliminar el directorio de la tarea si está vacío
                $taskDir = dirname($task['sumary_file']); 
                if (is_dir($taskDir) && count(scandir($taskDir)) == 2) {
                    rmdir($taskDir);
                }
    
                // Ahora proceder a eliminar la tarea de la base de datos
                return $this->db->delete()
                                ->where('task_id = ?', [$task_id])->execute();
            } else {
                throw new Exception("La tarea con ID $task_id no existe.");
            }
        } catch (Exception $e) {
            // Capturar cualquier excepción y registrar el error
            error_log("Error al eliminar la tarea: " . $e->getMessage()."\n");
            return false;
        }
    }

    // Obtener tarea por ID
    public function getTaskById($task_id) {
        return $this->db->select()
                        ->where('task_id = ?', [$task_id])->get();
    }

    // Actualizar una tarea por ID
    public function updateTask($task_id, $data) {
        return $this->db->update($data)
                        ->where('task_id = ?', [$task_id])->execute();
    }

    // Obtener todas las tareas
    public function getAllTasks() {
        return $this->db->select()->get();
    }

    // Obtener tareas con paginación (Implementación de la interfaz)
    public function getPaginatedResults($itemsPerPage, $currentPage) {
        $offset = ($currentPage - 1) * $itemsPerPage;
        return $this->db->select()
                        ->limit($itemsPerPage, $offset)->get();
    }

    // Contar el número total de tareas (Implementación de la interfaz)
    public function getTotalItems() {
        // Realizar la consulta para contar las tareas
        $result = $this->db->select('COUNT(*) as total')->get();
    
        // Comprobar si el resultado es válido
        if (isset($result[0]['total'])) {
            return  intval($result[0]['total']);   // Devolver como string
        }
    
        // En caso de que no haya resultados, devolver "0" como string
        return "0";
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

    // Método para determinar el rango de páginas a mostrar
    public function getPaginationRange($currentPage, $totalPages) {
        $startPage = max(1, $currentPage - 1); // Mostrar siempre al menos 1 página antes
        $endPage = min($totalPages, $currentPage + 1); // Mostrar siempre al menos 1 página después

        // Si estamos en la primera página, mostrar hasta 3 páginas en total
        if ($currentPage == 1) {
            $endPage = min($totalPages, $currentPage + 2);
        }

        return ['startPage' => $startPage, 'endPage' => $endPage];
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
        return $this->db->select()
                        ->where('status = ?', [$status])->get();
    }

    // Asignar un operario a una tarea
    public function assignWorker($task_id, $worker_id) {
        return $this->db->update(['assigned_worker' => $worker_id])
                        ->where('task_id = ?', [$task_id])->execute();
    }

    // Función auxiliar para subir un archivo
    private function uploadFile($file, $directory) {
        $allowedTypes = ['application/pdf', 'image/jpeg', 'image/png'];
        $maxFileSize = 5 * 1024 * 1024; // 5 MB

        // Verificar si no hubo error al subir el archivo
        if ($file['error'] === UPLOAD_ERR_OK) {
            // Validar el tipo de archivo
            if (!in_array($file['type'], $allowedTypes)) {
                throw new Exception("Tipo de archivo no permitido. Solo se permiten PDF, JPG, PNG.");
            }

            // Validar el tamaño del archivo
            if ($file['size'] > $maxFileSize) {
                throw new Exception("El archivo excede el tamaño máximo permitido de 5 MB.");
            }

            // Obtener el nombre original del archivo
            $fileName = basename($file['name']);

            // Definir la ruta completa donde se guardará el archivo
            $filePath = $directory . '/' . $fileName;

            // Mover el archivo subido a la carpeta designada
            if (move_uploaded_file($file['tmp_name'], $filePath)) {
                // Devolver la ruta relativa, quitando el DOCS_PATH
                return str_replace(DOCS_PATH, '/Docs/', $filePath);
            } else {
                throw new Exception("Error al mover el archivo.");
            }
        } else {
            throw new Exception("Error en la subida del archivo.");
        }
    }

    // Función auxiliar para sanitizar el nombre del directorio
    private function sanitizeDirectoryName($name) {
        // Remover caracteres no válidos para nombres de carpetas
        return preg_replace('/[^a-zA-Z0-9-_]/', '_', strtolower($name));
    }

    public function getAllAutonomousCommunities() {
        $result = $this->db->setTable('autonomous_communities')  // Cambiar a la tabla de comunidad
                            ->select()->get();
        $this->db->setTable($this->table);  // Volver a la tabla original 'task'
        return $result; 
    }

    public function getAllProvinces() {
        $result = $this->db->setTable('provinces')  // Cambiar a la tabla de provincias
                            ->select()->get();
        $this->db->setTable($this->table);  // Volver a la tabla original 'task'
        return $result;
    }

    public function getProvincesByCommunity($community_Id) {
        $result = $this->db->setTable('provinces')
                            ->select()
                            ->where('comunidad_id = ?', [$community_Id])->get();
        $this->db->setTable($this->table);  // Volver a la tabla original 'task'
        return $result;
    }
}