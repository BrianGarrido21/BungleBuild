<?php

class TaskController extends Controller
{
    private $taskModel;
    private $errorHandler;

    public function __construct()
    {
        // Crear instancia del modelo de tareas
        $this->taskModel = new TaskModel();
        // Inicializar un manejador de errores (opcional)
        $this->errorHandler = new ErrorHandler();
    }

    // Acción para mostrar todas las tareas
    public function index() {
        // Obtener la página actual y el número de elementos por página
        $currentPage = $this->taskModel->getCurrentPage();
        $itemsPerPage = $this->taskModel->getItemsPerPage();
    
        // Validar que $itemsPerPage no sea 0 para evitar división por cero
        $itemsPerPage = ($itemsPerPage <= 0) ? 10 : $itemsPerPage; // Valor predeterminado de 10 si es 0 o menor
    
        // Obtener el total de ítems (tareas)
        $totalItems = $this->taskModel->getTotalItems();
    
        // Obtener el total de páginas
        $totalPages = $this->taskModel->getTotalPages($itemsPerPage);
    
        // Si el total de páginas no es válido, asegúrate de que al menos sea 1
        $totalPages = ($totalPages <= 0) ? 1 : $totalPages; 
    
        // Obtener las tareas con paginación
        $tasks = $this->taskModel->getPaginatedResults($itemsPerPage, $currentPage);
    
        // Rango de páginas para la paginación
        $paginationRange = $this->taskModel->getPaginationRange($currentPage, $totalPages);
    

        // Renderizar la vista showTasks.php, pasando los datos necesarios
        self::render('task/showTasks', compact('tasks', 'currentPage', 'itemsPerPage', 'totalPages', 'paginationRange'));
    }

    // Acción para mostrar una tarea específica
    public function show($task_id) {
        $task = $this->taskModel->getTaskById($task_id);
        if (!$task) {
            $this->errorHandler->addError('task', 'La tarea no existe.');
            self::render('task/error', ['errors' => $this->errorHandler]);
            return;
        }

        // Renderizar la vista de detalles de la tarea
        self::render('task/show', compact('task'));
    }

    // Acción para crear una nueva tarea
    public function create() {
        if ($_POST) {
            $data = $_POST;
            $files = $_FILES;

            if ($this->taskModel->createTask($data, $files)) {
                Controller::redirectTo('task.index');
            } else {
                $this->errorHandler->addError('task', 'Error al crear la tarea.');
                self::render('task/error', ['errors' => $this->errorHandler]);
            }
        } else {
            self::render('task/create');
        }
    }

    // Acción para eliminar una tarea
    public function delete($task_id) {
        if ($this->taskModel->deleteTask($task_id)) {
            Controller::redirectTo('task.index');
        } else {
            $this->errorHandler->addError('task', 'Error al eliminar la tarea.');
            self::render('task/error', ['errors' => $this->errorHandler]);
        }
    }

    // Acción para actualizar una tarea existente
    public function update($task_id) {
        if ($_POST) {
            $data = $_POST;
            if ($this->taskModel->updateTask($task_id, $data)) {
                Controller::redirectTo('task.show', [$task_id]);
            } else {
                $this->errorHandler->addError('task', 'Error al actualizar la tarea.');
                self::render('task/error', ['errors' => $this->errorHandler]);
            }
        } else {
            $task = $this->taskModel->getTaskById($task_id);
            if ($task) {
                self::render('task/edit', compact('task'));
            } else {
                $this->errorHandler->addError('task', 'La tarea no existe.');
                self::render('task/error', ['errors' => $this->errorHandler]);
            }
        }
    }

    // Acción para filtrar tareas por estado
    public function filterByStatus($status)
    {
        $tasks = $this->taskModel->getTasksByStatus($status);
        self::render('task/index', compact('tasks'));
    }

    // Acción para asignar un operario a una tarea
    public function assignWorker($task_id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $worker_id = $_POST['worker_id'];
            if ($this->taskModel->assignWorker($task_id, $worker_id)) {
                Controller::redirectTo('task.show', [$task_id]);
            } else {
                $this->errorHandler->addError('task', 'Error al asignar el operario.');
                self::render('task/error', ['errors' => $this->errorHandler]);
            }
        } else {
            self::render('task/assign', compact('task_id'));
        }
    }
}