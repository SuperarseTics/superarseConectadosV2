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
    public function findByCedula($cedula) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE numero_identificacion = :cedula LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':cedula', $cedula);

        try {
            $stmt->execute();
            $user = $stmt->fetch();
            return $user ? $user : null;
        } catch (PDOException $e) {
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