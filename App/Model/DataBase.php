<?php

class DataBase {
    protected $connection = null;
    protected $query = "";
    protected $table = "";
    protected $selectExecuted = false;
    protected $whereExecuted = false;
    protected $insertExecuted = false;
    protected $updateExecuted = false;
    protected $deleteExecuted = false;
    protected $params = []; 

    protected function __construct() {
        try {
            $this->connection = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            throw new Exception("Could not connect to the database. " . $e->getMessage());
        }
    }

    // Método select
    protected function select(...$fields) {
        if (empty($fields)) {
            $fields = ["*"];
        }

        $fields = implode(", ", $fields);

        $this->query = "SELECT " . $fields . " FROM " . $this->table;
        $this->selectExecuted = true;  
        return $this;  
    }

    // Método where (acepta tanto condiciones directas como con placeholders)
    protected function where($condition = "", $params = []) {
        if (!$this->selectExecuted && !$this->insertExecuted && !$this->updateExecuted && !$this->deleteExecuted) {
            throw new Exception("You must call select() or insert() before calling where().");
        }

        // Si no se pasan parámetros, asumimos que la condición incluye los valores directamente
        if (empty($params)) {
            $this->query .= " WHERE " . $condition;
        } else {
            // Si hay parametros, los usamos como placeholders
            $this->query .= " WHERE " . $condition;
            $this->params = array_merge($this->params, $params);  // Guardamos los parámetros
        }

        $this->whereExecuted = true;
        return $this;  
    }

    // Método and (acepta tanto condiciones directas como con placeholders)
    protected function and($condition = "", $params = []) {
        if (!$this->whereExecuted) {
            throw new Exception("You must call where() before calling and().");
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
    protected function get($params = []) {
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
    protected function executeStatement($query = "", $params = []) {
        try {
            $stmt = $this->connection->prepare($query);
            if ($stmt === false) {
                throw new Exception("Unable to prepare statement: " . $query);
            }


            $stmt->execute($params);
            
            return $stmt;
        } catch (PDOException $e) {
            throw new Exception("Execution error: " . $e->getMessage());
        }
    }

    // ------------------------------------------------------------------------------------

    // Método insert
    protected function insert($data = []) {
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
    protected function executeInsert($query = "", $params = []) {
        try {
            $stmt = $this->connection->prepare($query);
            if ($stmt === false) {
                throw new Exception("Unable to prepare insert statement: " . $query);
            }
            $stmt->execute($params);
            $this->reset();
            return $this->connection->lastInsertId();  
        } catch (PDOException $e) {
            throw new Exception("Insert error: " . $e->getMessage());
        }
    }

     // ------------------------------------------------------------------------------------

    // Método update (acepta tanto datos directos como con placeholders)
    protected function update($data = []) {
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
    protected function executeUpdate($query = "", $params = []) {
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
        }
    }

    // ------------------------------------------------------------------------------------


    protected function delete() {
    
        $this->query = "DELETE FROM " . $this->table . " " . $this->query;  // Usamos el query ya construido (con where)
        
        return $this;
    }
    
    // Ejecutar el delete con los parámetros
    protected function executeDelete($query = "", $params = []) {
        try {
            $stmt = $this->connection->prepare($query);
            if ($stmt === false) {
                throw new Exception("Unable to prepare delete statement: " . $query);
            }
    
            $stmt->execute($params);
            $this->reset();
            return true;
        } catch (PDOException $e) {
            throw new Exception("Delete error: " . $e->getMessage());
        }
    }

    // ------------------------------------------------------------------------------------

    // Resetear el estado de la consulta
    protected function reset() {
        $this->query = "";  
        $this->selectExecuted = false;
        $this->whereExecuted = false;
        $this->insertExecuted = false;
        $this->params = []; 
        $this->updateExecuted = false;
    }
}

