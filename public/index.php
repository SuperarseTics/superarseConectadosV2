<?php
require_once '../app/Controllers/LoginController.php';
require_once '../app/Controllers/EstudianteController.php';
require_once '../app/Controllers/PagoController.php';
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

    default:
        http_response_code(404);
        echo "404 - Página no encontrada: " . htmlspecialchars($uri);
        break;
}