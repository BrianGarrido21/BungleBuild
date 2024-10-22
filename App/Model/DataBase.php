<?php
class DataBase {
    private static $instance = null;
    private $connection = null;
    private $query = "";
    private $table = "";
    private $selectExecuted = false;
    private $whereExecuted = false;
    private $insertExecuted = false;
    private $updateExecuted = false;
    private $deleteExecuted = false;
    private $params = [];

    // Constructor privado para evitar múltiples instancias
    private function __construct() {
        try {
            $this->connection = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            throw new Exception("Could not connect to the database. " . $e->getMessage());
        }
    }

    // Método estático para obtener la instancia única
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance; 
    }

    public function setTable($table) {
        // Asignar el nombre de la tabla a la propiedad table
        $this->table = $table;
        return $this;  // Retornamos $this para permitir el encadenamiento de métodos
    }

    // Evitar que la clase sea clonada
    public function __clone() { }

    // Evitar que la clase sea deserializada
    public function __wakeup() { }

    // Método select
    public function select(...$fields) {
        if (empty($fields)) {
            $fields = ["*"];
        }

        $fields = implode(", ", $fields);

        $this->query = "SELECT " . $fields . " FROM " . $this->table;
        $this->selectExecuted = true;  
        return $this;  
    }

    // Método where (acepta tanto condiciones directas como con placeholders)
    public function where($condition = "", $params = []) {
        // Verificar si se ha ejecutado select(), insert(), update() o delete() antes de aplicar where
        if (!$this->selectExecuted && !$this->insertExecuted && !$this->updateExecuted && !$this->deleteExecuted) {
            throw new Exception("You must call select(), insert(), update(), or delete() before calling where().");
        }
    
        // Si se pasan parámetros, asumimos que se usan placeholders, lo que es seguro
        if (!empty($params)) {
            $this->query .= " WHERE " . $condition;
            $this->params = array_merge($this->params, $params);
        } else {
            // Si no hay parámetros, validamos que la condición no tenga SQL peligroso
            if ($this->containsDangerousSql($condition)) {
                throw new Exception("Invalid SQL in WHERE clause");
            }
            $this->query .= " WHERE " . $condition;
        }
    
        $this->whereExecuted = true;
        return $this;
    }
    
    public function containsDangerousSql($input) {
        $dangerousKeywords = ['DROP', 'TRUNCATE', 'DELETE', 'UPDATE'];
        foreach ($dangerousKeywords as $keyword) {
            if (stripos($input, $keyword) !== false) {
                return true;
            }
        }
        return false;
    }

    // Método and (acepta tanto condiciones directas como con placeholders)
    public function and($condition = "", $params = []) {
        if (!$this->whereExecuted) {
            throw new Exception("You must call where() before calling and().");
            $this->logError("Execution error: " . $e->getMessage());
        }

        // Si no se pasan parámetros, asumimos que la condición incluye los valores directamente
        if (empty($params)) {
            $this->query .= " AND " . $condition;
        } else {
            // Si hay parámetros, los usamos como placeholders
            $this->query .= " AND " . $condition;
            $this->params = array_merge($this->params, $params);  // Guardamos los parámetros
        }

        return $this;
    }

    // Método get para obtener los resultados
    public function get($params = []) {
        try {
            // Usamos los parámetros almacenados si no se pasan a get()
            $params = empty($params) ? $this->params : $params;

            $stmt = $this->executeStatement($this->query, $params);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $this->reset();
            return $result;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
        return false;
    }

    // Ejecutar la consulta preparada con los parámetros
    
    private function executeStatement($query = "", $params = []) {
        try {
            $stmt = $this->connection->prepare($query);
            if ($stmt === false) {
                throw new Exception("Unable to prepare statement: " . $query);
            }
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            $this->logError("Execution error: " . $e->getMessage());
            throw new Exception("Execution error: " . $e->getMessage());
        }
    }

    // Método insert
    public function insert($data = []) {
        if (empty($data)) {
            throw new Exception("Insert data cannot be empty.");
        }

        $columns = implode(", ", array_keys($data));
        $placeholders = implode(", ", array_fill(0, count($data), "?"));

        $this->query = "INSERT INTO " . $this->table . " (" . $columns . ") VALUES (" . $placeholders . ")";
        $this->insertExecuted = true;

        $this->executeInsert($this->query, array_values($data));

        return $this;
    }

    // Ejecutar el insert con parámetros
    private function executeInsert($query = "", $params = []) {
        try {
            $stmt = $this->connection->prepare($query);
            if ($stmt === false) {
                throw new Exception("Unable to prepare insert statement: " . $query);
            }
            $stmt->execute($params);
            $this->reset();
            return $this->connection->lastInsertId();  
        } catch (PDOException $e) {
            $this->logError("Insert error: " . $e->getMessage());
        throw new Exception("Insert error: " . $e->getMessage());
        }
    }

    // Método update (acepta tanto datos directos como con placeholders)
    public function update($data = []) {
        if (empty($data)) {
            throw new Exception("Update data cannot be empty.");
        }

        $set = [];
        foreach ($data as $column => $value) {
            $set[] = $column . " = ?";
        }

        $this->query = "UPDATE " . $this->table . " SET " . implode(", ", $set);
        $this->updateExecuted = true;

        // Guardar los valores en params para que se usen más adelante
        $this->params = array_merge($this->params, array_values($data));

        return $this;
    }

    // Ejecutar el update con parámetros
    public function executeUpdate($query = "", $params = []) {
        try {
            $stmt = $this->connection->prepare($query);
            if ($stmt === false) {
                throw new Exception("Unable to prepare update statement: " . $query);
            }
            $stmt->execute($params);
            $this->reset();
            return true;
        } catch (PDOException $e) {
            throw new Exception("Update error: " . $e->getMessage());
            $this->logError("Update error: " . $e->getMessage());

        }
    }

    // Método delete
    public function delete() {
        $this->query = "DELETE FROM " . $this->table . " " . $this->query;  // Usamos el query ya construido (con where)
        return $this;
    }

    // Ejecutar el delete con los parámetros
    private function executeDelete($query = "", $params = []) {
        try {
            $stmt = $this->connection->prepare($query);
            if ($stmt === false) {
                throw new Exception("Unable to prepare delete statement: " . $query);
            }

            $stmt->execute($params);
            $this->reset();
            return true;
        } catch (PDOException $e) {
            $this->logError("Delete error: " . $e->getMessage());
            throw new Exception("Delete error: " . $e->getMessage());
        }
    }

    // Resetear el estado de la consulta
    private function reset() {
        $this->query = "";  
        $this->selectExecuted = false;
        $this->whereExecuted = false;
        $this->insertExecuted = false;
        $this->params = []; 
        $this->updateExecuted = false;
    }

    private function logError($message) {
        error_log($message, 3,  ROOT_PATH.'Logs/file.log');
    }

    public function beginTransaction() {
        $this->connection->beginTransaction();
    }
    
    public function commit() {
        $this->connection->commit();
    }
    
    public function rollBack() {
        $this->connection->rollBack();
    }

    public function escapeIdentifier($identifier) {
        return "`" . str_replace("`", "``", $identifier) . "`";
    }
}