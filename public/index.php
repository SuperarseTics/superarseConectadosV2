<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once '../app/Controllers/LoginController.php';
require_once '../app/Controllers/EstudianteController.php';
require_once '../app/Controllers/PagoController.php';
require_once '../app/Controllers/PasantiaController.php';
require_once '../vendor/autoload.php';
ini_set('display_errors', 1);
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$uri = rtrim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$basePath = '/superarseconectadosv2/public';
if (strpos($uri, $basePath) === 0) {
    $uri = substr($uri, strlen($basePath));
}
if ($uri === '') {
    $uri = '/';
}

$method = $_SERVER['REQUEST_METHOD'];
if ($uri === '/pagos/upload-comprobante' && $method === 'POST') {
    $controller = new PagoController();
    $controller->uploadComprobante();
    exit();
}

// === INICIO: Manejo de Rutas con Parámetros Variables ===
// Captura rutas como /pasantias/generatePdf/45, /pasantias/generatePdf/100, etc.
if (preg_match('/^\/pasantias\/generatePdf\/(\d+)$/', $uri, $matches)) {
    // $matches[1] contendrá el ID (ej: '45')
    $id_practica = (int) $matches[1];

    $controller = new PasantiaController();
    $controller->generatePdf($id_practica);
    exit(); // Detenemos la ejecución después de generar el PDF

}
// === FIN: Manejo de Rutas con Parámetros Variables ===

switch ($uri) {
    case '/':
    case '/login':
        if (isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === true) {
            header("Location: " . $basePath . "/estudiante/informacion");
            exit();
        }

        $controller = new LoginController();
        $controller->index();
        break;

    case '/login/check':
        if ($method === 'POST') {
            $controller = new LoginController();
            $controller->check();
        } else {
            header("Location: " . $basePath . "/login");
        }
        break;

    case '/estudiante/informacion':
        $controller = new EstudianteController();
        $controller->informacion();
        break;

    case '/logout':
        $controller = new LoginController();
        $controller->logout();
        header("Location: " . $basePath . "/login");
        exit();
        break;

    case '/pago':
        $controller = new PagoController();
        $controller->procesarPago();
        break;

    case '/pasantias/buscarEntidadPorRUC':
        $controller = new PasantiaController();
        $controller->buscarEntidadPorRUC();
        exit();

    case '/pasantias/saveFaseOne':
        if ($method === 'POST') {
            $controller = new PasantiaController();
            $controller->saveFaseOne();
        } else {
            header("Location: " . $basePath . "/login");
        }
        break;

    case '/pasantias/addActividadDiaria':
        if ($method === 'POST') {
            $controller = new PasantiaController();
            $controller->addActividadDiaria();
        } else {
            header("Location: " . $basePath . "/login");
        }
        break;

    case '/pasantias/updateActividadDiaria':
        if ($method === 'POST') {
            $controller = new PasantiaController();
            $controller->updateActividadDiaria();
        } else {
            header("Location: " . $basePath . "/login");
        }
        break;

    case '/pasantias/deleteActividadDiaria':
        if ($method === 'POST') {
            $id = $_POST['id'] ?? 0;
            $controller = new PasantiaController();
            $controller->deleteActividadDiaria($id);
        } else {
            header("Location: " . $basePath . "/login");
        }
        break;
    case '/pasantias/addProgramaTrabajo':
        if ($method === 'POST') {
            $controller = new PasantiaController();
            $controller->addProgramaTrabajo();
        } else {
            header("Location: " . $basePath . "/login");
        }
        break;

    case '/pasantias/updateProgramaTrabajo':
        if ($method === 'POST') {
            $controller = new PasantiaController();
            $controller->updateProgramaTrabajo();
        } else {
            header("Location: " . $basePath . "/login");
        }
        break;

    case '/pasantias/deleteProgramaTrabajo':
        if ($method === 'POST') {
            $controller = new PasantiaController();
            $controller->deleteProgramaTrabajo();
        } else {
            header("Location: " . $basePath . "/login");
        }
        break;


    default:
        http_response_code(404);
        echo "404 - Página no encontrada: " . htmlspecialchars($uri);
        break;
}
