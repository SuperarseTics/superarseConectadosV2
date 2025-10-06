<?php
// app/Models/UserModel.php

require_once 'Database.php';

class UserModel {
    private $conn;
    private $table_name = "users";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    /**
     * Busca un estudiante por su número de identificación (cédula)
     * @param string $cedula El número de identificación del estudiante
     * @return array|null Los datos del usuario si es encontrado, o null
     */
    public function findByCedula($cedula) {
        // En tu tabla 'users', el campo es 'numero_identificacion'
        $query = "SELECT * FROM " . $this->table_name . " WHERE numero_identificacion = :cedula LIMIT 1";

        $stmt = $this->conn->prepare($query);
        
        // Limpiar y enlazar el parámetro
        $stmt->bindParam(':cedula', $cedula);

        try {
            $stmt->execute();
            $user = $stmt->fetch();
            
            // Si encuentra un resultado, lo retorna, si no, retorna false (o null, dependiendo de la configuración de PDO)
            return $user ? $user : null;
        } catch (PDOException $e) {
            // Manejo de error de consulta SQL
            error_log("Error al buscar usuario por cédula: " . $e->getMessage());
            return null;
        }
    }

    public function getUserInfoByIdentificacion($identificacion) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE numero_identificacion = :identificacion LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':identificacion', $identificacion);

        try {
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC); 
        } catch (PDOException $e) {
            error_log("Error al obtener información del usuario: " . $e->getMessage());
            return null;
        }
    }
}