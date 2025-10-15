<?php
// app/Controllers/EstudianteController.php

require_once '../app/Models/UserModel.php';
require_once '../app/Models/PagoModel.php';
require_once '../app/Models/AsignaturaModel.php';
require_once '../app/Models/CredencialModel.php';
require_once '../app/Models/BancoModel.php';

class EstudianteController
{
    private $basePath = "/superarseconectadosv2/public";

    public function __construct()
    {

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function informacion()
    {
        if (!isset($_SESSION['id_usuario']) || !isset($_SESSION['identificacion'])) {
            header("Location: " . $this->basePath . "/login");
            exit();
        }

        $idUsuario = $_SESSION['id_usuario'];
        $identificacion = $_SESSION['identificacion'];

        $data = [];

        try {
            $userModel = new UserModel();
            $pagoModel = new PagoModel();
            $asignaturaModel = new AsignaturaModel();
            $credencialModel = new CredencialModel();
            $bancoModel = new BancoModel();

            $infoPersonal = $userModel->getUserInfoByIdentificacion($identificacion);
            $infoPagos = $pagoModel->getPagosByIdentificacion($identificacion);
            $infoAsignaturas = $asignaturaModel->getAsignaturasByIdentificacion($identificacion);
            $infoCredenciales = $credencialModel->getCredencialesByUserId($idUsuario);
            $infoBancos = $bancoModel->getAllBancosActivos();
            $data['nombreCompleto'] = $_SESSION['nombres_completos'] ?? 'Estudiante';
            $data['infoPagos'] = $infoPagos ?? [
                'abono_total' => 'N/D',
                'saldo_total' => 'N/D',
                'observacion' => 'N/D'
            ];
            $data['infoPersonal'] = $infoPersonal ?? [];
            $data['infoAsignaturas'] = $infoAsignaturas ?? [];
            $data['infoCredenciales'] = $infoCredenciales ?? [];
            $data['bancos'] = $infoBancos ?? [];
            $data['basePath'] = $this->basePath;

            $vista_contenido = __DIR__ . '/../Views/dashboard/index.php'; 
            require_once __DIR__ . '/../Views/layouts/main_layout.php'; 
        } catch (Exception $e) {
            error_log("Error en EstudianteController: " . $e->getMessage());
            header("Location: " . $this->basePath . "/login?error=error_sistema");
            exit();
        }
    }
}