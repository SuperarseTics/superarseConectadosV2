<?php
// app/Controllers/PasantiaController.php

require_once '../app/Models/PasantiaModel.php';
require_once '../app/Models/UserModel.php';

class PasantiaController
{
    private $basePath = "/superarseconectadosv2/public";
    private $pasantiaModel;
    private $userModel;

    public function __construct()
    {
        $this->pasantiaModel = new PasantiaModel();
        $this->userModel = new UserModel();
        
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
            header("Location: " . $this->basePath . "/login");
            exit();
        }
    }

    public function index()
    {
        $userId = $_SESSION['id_usuario'];
        $practica = $this->pasantiaModel->getActivePracticaByUserId($userId);
        $docentes = $this->pasantiaModel->getActiveDocentes();
        $estudiante = $this->userModel->getUserInfoByIdentificacion($_SESSION['identificacion']);

        if ($practica) {
            $_SESSION['mensaje'] = "Ya tienes un registro de práctica en el sistema (ID: {$practica['id_practica']}).";
            header("Location: " . $this->basePath . "/estudiante/seguimiento"); 
            exit();
        }
        $data = [
            'docentes' => $docentes,
            'estudiante' => $estudiante,
            'mensaje' => $_SESSION['mensaje'] ?? null
        ];
        unset($_SESSION['mensaje']);
        include '../app/Views/estudiante/registro_pasantia_fase1.php';
    }

    public function saveFaseOne()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: " . $this->basePath . "/estudiante/registro?error=metodo_invalido");
            exit();
        }
        if (empty($_POST['modalidad']) || empty($_POST['tutor_emp_cedula']) || empty($_POST['entidad_ruc'])) {
            $_SESSION['mensaje'] = "Error: La modalidad, el RUC de la entidad y la cédula del tutor empresarial son obligatorios.";
            header("Location: " . $this->basePath . "/estudiante/registro");
            exit();
        }
        $userId = $_SESSION['id_usuario'];
        $estudiante = $this->userModel->getUserInfoByIdentificacion($_SESSION['identificacion']);
        
        if (!$estudiante) {
            $_SESSION['mensaje'] = "Error interno: No se pudo obtener la información completa del estudiante.";
            header("Location: " . $this->basePath . "/estudiante/registro");
            exit();
        }
        $data = [
            'user_id' => $userId,
            'programa' => $estudiante['programa'],
            'modalidad' => $_POST['modalidad'],

            'entidad_ruc' => trim($_POST['entidad_ruc']),
            'entidad_nombre_empresa' => $_POST['entidad_nombre_empresa'] ?? null,
            'entidad_razon_social' => $_POST['entidad_razon_social'] ?? null,
            'entidad_persona_contacto' => $_POST['entidad_persona_contacto'] ?? null,
            'entidad_telefono_contacto' => $_POST['entidad_telefono_contacto'] ?? null,
            'entidad_email_contacto' => $_POST['entidad_email_contacto'] ?? null,

            'tutor_emp_cedula' => trim($_POST['tutor_emp_cedula']),
            'tutor_emp_nombre_completo' => $_POST['tutor_emp_nombre_completo'] ?? null,
            'tutor_emp_funcion' => $_POST['tutor_emp_funcion'] ?? null,
            'tutor_emp_telefono' => $_POST['tutor_emp_telefono'] ?? null,
            'tutor_emp_email' => $_POST['tutor_emp_email'] ?? null,
            'tutor_emp_departamento' => $_POST['tutor_emp_departamento'] ?? null,
        ];
        $practica_id = $this->pasantiaModel->savePasantiaPhaseOne($data);

        if ($practica_id) {
            $_SESSION['mensaje'] = "Registro de práctica (ID: {$practica_id}) creado con éxito. Esperando aprobación.";
            header("Location: " . $this->basePath . "/estudiante/seguimiento");
            exit();
        } else {
            $_SESSION['mensaje'] = "Error al completar el registro. Inténtalo de nuevo.";
            header("Location: " . $this->basePath . "/estudiante/registro");
            exit();
        }
    }

    public function showProgramaTrabajo()
    {
        $userId = $_SESSION['id_usuario'];
        $practica = $this->pasantiaModel->getActivePracticaByUserId($userId);
        if (!$practica || !$practica['estado_fase_uno_completado']) {
            $_SESSION['mensaje'] = "La Fase 1 debe estar completa y aprobada para acceder al Programa de Trabajo.";
            header("Location: " . $this->basePath . "/estudiante/seguimiento");
            exit();
        }

        $practicaId = $practica['id_practica'];
        $programaTrabajo = $this->pasantiaModel->getProgramaTrabajo($practicaId);

        $data = [
            'practicaId' => $practicaId,
            'programaTrabajo' => $programaTrabajo,
            'mensaje' => $_SESSION['mensaje'] ?? null
        ];
        unset($_SESSION['mensaje']);

        include '../app/Views/estudiante/programa_trabajo.php';
    }

    public function addProgramaTrabajo()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: " . $this->basePath . "/estudiante/programa_trabajo");
            exit();
        }

        $userId = $_SESSION['id_usuario'];
        $practica = $this->pasantiaModel->getActivePracticaByUserId($userId);
        if (!$practica || !$practica['estado_fase_uno_completado']) {
            $_SESSION['mensaje'] = "Acceso denegado: Práctica no aprobada.";
            header("Location: " . $this->basePath . "/estudiante/seguimiento");
            exit();
        }

        $requiredFields = ['actividad_planificada', 'fecha_planificada'];
        foreach ($requiredFields as $field) {
            if (empty($_POST[$field])) {
                $_SESSION['mensaje'] = "Error: Faltan campos obligatorios para el programa de trabajo.";
                header("Location: " . $this->basePath . "/estudiante/programa_trabajo");
                exit();
            }
        }

        $data = [
            'practica_id' => $practica['id_practica'],
            'actividad_planificada' => $_POST['actividad_planificada'],
            'departamento_area' => $_POST['departamento_area'] ?? null,
            'funcion_asignada' => $_POST['funcion_asignada'] ?? null,
            'fecha_planificada' => $_POST['fecha_planificada']
        ];

        if ($this->pasantiaModel->addProgramaTrabajo($data)) {
            $_SESSION['mensaje'] = "Actividad planificada agregada con éxito.";
        } else {
            $_SESSION['mensaje'] = "Error al guardar la actividad planificada. Revisa los logs de la base de datos.";
        }

        header("Location: " . $this->basePath . "/estudiante/programa_trabajo");
        exit();
    }

    public function showActividadesDiarias()
    {
        $userId = $_SESSION['id_usuario'];
        $practica = $this->pasantiaModel->getActivePracticaByUserId($userId);
        if (!$practica || !$practica['estado_fase_uno_completado']) {
            $_SESSION['mensaje'] = "La Fase 1 debe estar completa y aprobada para acceder al reporte diario.";
            header("Location: " . $this->basePath . "/estudiante/seguimiento");
            exit();
        }

        $practicaId = $practica['id_practica'];
        $actividadesDiarias = $this->pasantiaModel->getActividadesDiarias($practicaId);

        $data = [
            'practicaId' => $practicaId,
            'actividadesDiarias' => $actividadesDiarias,
            'mensaje' => $_SESSION['mensaje'] ?? null
        ];
        unset($_SESSION['mensaje']);

        include '../app/Views/estudiante/actividades_diarias.php';
    }

    public function addActividadDiaria()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: " . $this->basePath . "/estudiante/actividades_diarias");
            exit();
        }

        $userId = $_SESSION['id_usuario'];
        $practica = $this->pasantiaModel->getActivePracticaByUserId($userId);
        if (!$practica || !$practica['estado_fase_uno_completado']) {
            $_SESSION['mensaje'] = "Acceso denegado: Práctica no aprobada.";
            header("Location: " . $this->basePath . "/estudiante/seguimiento");
            exit();
        }

        $requiredFields = ['actividad_realizada', 'horas_invertidas', 'fecha_actividad'];
        foreach ($requiredFields as $field) {
            if (empty($_POST[$field])) {
                $_SESSION['mensaje'] = "Error: Faltan campos obligatorios para el reporte diario.";
                header("Location: " . $this->basePath . "/estudiante/actividades_diarias");
                exit();
            }
        }

        $horas_invertidas = (float)($_POST['horas_invertidas']);
        if ($horas_invertidas <= 0 || $horas_invertidas > 6.00) {
            $_SESSION['mensaje'] = "Error: Las horas invertidas deben ser un valor entre 0.01 y 6.00.";
            header("Location: " . $this->basePath . "/estudiante/actividades_diarias");
            exit();
        }

        $data = [
            'practica_id' => $practica['id_practica'],
            'actividad_realizada' => $_POST['actividad_realizada'],
            'horas_invertidas' => $horas_invertidas,
            'observaciones' => $_POST['observaciones'] ?? null,
            'fecha_actividad' => $_POST['fecha_actividad']
        ];

        if ($this->pasantiaModel->addActividadDiaria($data)) {
            $_SESSION['mensaje'] = "Actividad diaria registrada con éxito.";
        } else {
            $_SESSION['mensaje'] = "Error al guardar la actividad diaria. Revisa los logs (posiblemente la validación de horas en el modelo falló).";
        }

        header("Location: " . $this->basePath . "/estudiante/actividades_diarias");
        exit();
    }
}