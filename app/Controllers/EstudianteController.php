<?php
// app/Controllers/EstudianteController.php

require_once '../app/Models/UserModel.php';
require_once '../app/Models/PagoModel.php';
require_once '../app/Models/AsignaturaModel.php';
require_once '../app/Models/CredencialModel.php';
require_once '../app/Models/BancoModel.php';

class EstudianteController
{
    // Ruta base para redirecciones (¡Asegúrate de que coincida con tu carpeta!)
    private $basePath = "/superarseconectadosv2/public";

    // Constructor para asegurar que la sesión esté activa
    public function __construct()
    {
        // La inicialización de la sesión debería estar en el Front Controller (public/index.php).
        // Si el Front Controller no lo asegura, déjalo aquí.
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Muestra la vista principal del dashboard del estudiante.
     * Esta función es llamada por la ruta /estudiante/informacion.
     */
    public function informacion()
    {
        // 1. Verificación de Sesión (Seguridad)
        if (!isset($_SESSION['id_usuario']) || !isset($_SESSION['identificacion'])) {
            header("Location: " . $this->basePath . "/login");
            exit();
        }

        $idUsuario = $_SESSION['id_usuario'];
        $identificacion = $_SESSION['identificacion'];

        // Array final que se pasará a la vista
        $data = [];

        try {
            // 2. Carga y Uso de Modelos
            $userModel = new UserModel();
            $pagoModel = new PagoModel();
            $asignaturaModel = new AsignaturaModel();
            $credencialModel = new CredencialModel();
            $bancoModel = new BancoModel();

            // 3. Obtener todos los datos necesarios
            $infoPersonal = $userModel->getUserInfoByIdentificacion($identificacion);
            $infoPagos = $pagoModel->getPagosByIdentificacion($identificacion);
            $infoAsignaturas = $asignaturaModel->getAsignaturasByIdentificacion($identificacion);
            $infoCredenciales = $credencialModel->getCredencialesByUserId($idUsuario);
            $infoBancos = $bancoModel->getAllBancosActivos();

            // 4. Lógica de PREPARACIÓN DE DATOS (¡AQUÍ ESTÁ EL CAMBIO CLAVE!)

            // a) Bienvenida: Usamos el nombre de la sesión
            $data['nombreCompleto'] = $_SESSION['nombres_completos'] ?? 'Estudiante';

            // b) Extracción de datos y asignación de valores por defecto (evita errores en la vista)
            $data['infoPagos'] = $infoPagos ?? [
                'abono_total' => 'N/D',
                'saldo_total' => 'N/D',
                'observacion' => 'N/D'
            ];

            // c) Asignar el resto de la data (usamos el null coalescing para asegurar que siempre sea un array)
            $data['infoPersonal'] = $infoPersonal ?? [];
            $data['infoAsignaturas'] = $infoAsignaturas ?? [];
            $data['infoCredenciales'] = $infoCredenciales ?? [];
            $data['bancos'] = $infoBancos ?? [];

            // También incluimos el basePath para los scripts si es necesario
            $data['basePath'] = $this->basePath;

            // 5. Cargar la Vista, pasando solo el array $data
            // Nota: Se asume que tu vista está en 'app/Views/estudiante/index.php'
            require_once '../app/Views/estudiantes/index.php';
        } catch (Exception $e) {
            // Manejo de errores de base de datos o modelos
            error_log("Error en EstudianteController: " . $e->getMessage());

            // Redirigir al login con un error genérico
            header("Location: " . $this->basePath . "/login?error=error_sistema");
            exit();
        }
    }
}
