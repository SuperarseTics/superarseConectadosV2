<?php
// app/Controllers/LoginController.php

require_once '../app/Models/UserModel.php';

class LoginController
{

    private $basePath = "/superarseconectadosv2/public";
    private $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    public function index()
    {
        include '../app/Views/login/index.php';
    }

    public function check()
    {
        if (!isset($_POST['numero_identificacion']) || empty($_POST['numero_identificacion'])) {
            header("Location: /login?error=campos_vacios");
            exit();
        }
        $cedula = trim($_POST['numero_identificacion']);
        $user = $this->userModel->findByCedula($cedula);
        if ($user) {
            $_SESSION['logged_in'] = true;
            $_SESSION['id_usuario'] = $user['id'];
            $_SESSION['identificacion'] = $user['numero_identificacion'];
            $_SESSION['nombres_completos'] = $user['primer_nombre'] . ' ' . $user['primer_apellido'];
            header("Location: " . $this->basePath . "/estudiante/informacion");
            exit();
        } else {
            header("Location: " . $this->basePath . "/login?error=cedula_no_encontrada");
            exit();
        }
    }

    public function logout()
    {
        session_destroy();
        header("Location: /login");
        exit();
    }
}