<?php
// app/Controllers/LoginController.php

require_once '../app/Models/UserModel.php';

class LoginController
{

    private $basePath = "/superarseconectadosv2/public"; // Ruta base del proyecto
    private $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        // Asegurar que la sesión esté iniciada para usar $_SESSION
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    // Muestra la vista de login
    public function index()
    {
        // Incluye la vista de login (con el formulario)
        include '../app/Views/login/index.php';
    }

    // Maneja la petición POST del formulario de login
    public function check()
    {
        // 1. Validar que se recibió la cédula
        if (!isset($_POST['numero_identificacion']) || empty($_POST['numero_identificacion'])) {
            // Redirige con error=campos_vacios
            header("Location: /login?error=campos_vacios");
            exit();
        }

        $cedula = trim($_POST['numero_identificacion']);

        // 2. Buscar usuario en el modelo
        $user = $this->userModel->findByCedula($cedula);

        if ($user) {
            // 3. Usuario encontrado: Iniciar Sesión y Redirigir
            // Guardar solo los datos esenciales y seguros en la sesión
            $_SESSION['logged_in'] = true;
            $_SESSION['id_usuario'] = $user['id'];
            $_SESSION['identificacion'] = $user['numero_identificacion'];
            $_SESSION['nombres_completos'] = $user['primer_nombre'] . ' ' . $user['primer_apellido'];

            // Redirigir al módulo de información
            header("Location: " . $this->basePath . "/estudiante/informacion");
            exit();
        } else {
            // 4. Usuario NO encontrado: Redirigir con error
            header("Location: " . $this->basePath . "/login?error=cedula_no_encontrada");
            exit();
        }
    }

    // Cierra la sesión
    public function logout()
    {
        session_destroy();
        header("Location: /login");
        exit();
    }
}
