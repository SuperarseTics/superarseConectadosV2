<?php

// 1. Configuración y Autoload
require_once '../app/Controllers/LoginController.php';
require_once '../app/Controllers/EstudianteController.php';
require_once '../app/Controllers/PagoController.php';

// Asegurar que la sesión esté iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// 2. Obtener la URI y limpiar el subdirectorio base
$uri = rtrim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

// Define la ruta base completa del proyecto en localhost
$basePath = '/superarseconectadosv2/public';

// Eliminar la base de la URI para que solo quede la ruta lógica (ej: /login/check)
if (strpos($uri, $basePath) === 0) {
    $uri = substr($uri, strlen($basePath));
}

// Asegurar que la URI de la página de inicio sea '/'
if ($uri === '') {
    $uri = '/';
}

$method = $_SERVER['REQUEST_METHOD'];

// 3. Bloque para subida de comprobante (Debe ir aquí para interceptar el POST)
if ($uri === '/pagos/upload-comprobante' && $method === 'POST') {
    // El require_once ya está arriba, si usas la lista inicial de requires.
    $controller = new PagoController();
    $controller->uploadComprobante();
    exit();
}

// 4. Despacho de Rutas (Lógica MVC)
switch ($uri) {
    case '/':
    case '/login':
        if (isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === true) {
            header("Location: " . $basePath . "/estudiante/informacion");
            exit();
        }
        // Si no está logueado, carga la vista de Login
        $controller = new LoginController();
        $controller->index();
        break;

    case '/login/check': // Ruta POST para validar la cédula
        if ($method === 'POST') {
            $controller = new LoginController();
            $controller->check();
        } else {
            header("Location: " . $basePath . "/login");
        }
        break;

    case '/estudiante/informacion': // Ruta para el dashboard del estudiante
        $controller = new EstudianteController();
        $controller->informacion(); // Usamos el método 'informacion' que tienes en tu código
        break;

    case '/logout':
        $controller = new LoginController();
        $controller->logout();
        header("Location: " . $basePath . "/login");
        exit();
        break;

    case '/pago':
        $controller = new PagoController();
        $controller->procesarPago(); // Ejecuta la lógica para mostrar la pasarela
        break;

    default:
        // Ruta no encontrada
        http_response_code(404);
        echo "404 - Página no encontrada: " . htmlspecialchars($uri);
        // Opcional: Redirigir al login
        // header("Location: " . $basePath . "/login");
        break;
}
