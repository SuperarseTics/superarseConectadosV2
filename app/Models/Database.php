<?php
// app/Models/Database.php

class Database
{
    private $host = "localhost";
    private $db_name = "conectados_superarse"; // Asegúrate de que este sea el nombre de tu BD
    private $username = "root"; // Reemplaza con tu usuario
    private $password = "Superarse.2025";     // Reemplaza con tu contraseña
    public $conn;

    public function getConnection()
    {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8", $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // Configurar PDO para que devuelva arrays asociativos por defecto
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $exception) {
            // En un entorno de producción, registrar el error y mostrar un mensaje genérico
            echo "Error de conexión: " . $exception->getMessage();
            die();
        }
        return $this->conn;
    }
}
