<?php
// La variable $status viene de la URL.
$status = $_GET['status'] ?? null;

if ($status === 'success') {
    // ... (Tu código HTML de ÉXITO con echos)
    // Se usa $_GET['id'] y $_GET['clientTransactionId']
    exit();
} elseif ($status === 'failure') {
    // ... (Tu código HTML de FALLO con echos)
    exit();
}

// Si no se definió en el controlador, algo salió mal o es un acceso directo inválido.
if (!isset($GLOBALS['esPasarelaPayphone'])) {
    echo "Acceso directo a la pasarela no permitido. Inicia el pago desde el dashboard.";
    exit();
}

// Extraer variables de $GLOBALS para usarlas limpiamente
$clientTransactionId = $GLOBALS['clientTransactionId'];
$amount = $GLOBALS['amount'];
$amountWithoutTax = $GLOBALS['amountWithoutTax'];
$tax = $GLOBALS['tax'];
$referencia = $GLOBALS['referencia'];

// INICIO DEL HTML COMPLETO DE LA PASARELA (EL DOCTYPE/HTML que nos has pasado)
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pasarela de pagos - Superarse</title>
    <link rel="icon" type="image/png" href="/superarseconectadosv2/public/assets/img/logoSuperarse.png" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <script src="https://cdn.payphonetodoesposible.com/box/v1.1/payphone-payment-box.js" type="module"></script>
    <link href="https://cdn.payphonetodoesposible.com/box/v1.1/payphone-payment-box.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        // Configuración de colores personalizada para el CDN
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'superarse-morado-oscuro': '#4A148C', // Tu color de base
                        'superarse-morado-medio': '#673AB7', // Tu color intermedio
                        'superarse-rosa': '#E91E63', // Tu color de acento
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-gray-100 min-h-screen flex flex-col justify-between">
    <header class="bg-superarse-morado-oscuro text-white text-center py-3 shadow-sm">
        <div class="container">
            <p class="lead mb-0">Plataforma de Pagos - Superarse</p>
        </div>
    </header>

    <main class="container mx-auto p-4 flex-grow flex items-center justify-center">
        <div class="w-full max-w-lg">
            <h1 class="text-3xl font-bold text-center text-superarse-morado-oscuro mb-6">
                Procesando Pago
            </h1>
            <div id="pp-button" class="bg-white p-6 rounded-xl shadow-lg border border-gray-200">
                <p class="text-center text-gray-500">El botón de Payphone debe aparecer aquí.</p>
            </div>
        </div>
    </main>

    <script type="module">
        window.addEventListener('DOMContentLoaded', () => {
            const ppb = new PPaymentButtonBox({
                // Usando las variables definidas por el controlador
                token: '8W-4m1qWExjBDCoReWHZUSh4B1tNHuK8EGOviJbt4gI6j4pZ_HOxNVRYjevU9CJ-huw21fTmZz0qDOiA_NmzaA0bsVbcYWArG3SkIR3FLnC3qqE_REmuiKy9DefawP-No8nZ-EguZiWBSQHR7CDLiBNgacy7u45Ht2XsO1THDbo6lJS2VnpfmfS1VdCCALbTY7Z8iFFpXJp6IFGFC8NawUZIcVlrMAKSjHc1NF_e1wxgvZ4K8Jg1LKX6MSzsRJ9yloDEB1rWBroX2Lsze61au-D1L_e0-fV6XTwiUKi6vJRoEmNs7soTqEYrBjb6FM9hbmEEpxAzinOjkodgMQWkdT8lSuw',
                clientTransactionId: '<?= $clientTransactionId ?>',
                amount: <?= $amount ?>,
                amountWithoutTax: <?= $amountWithoutTax ?>,
                tax: <?= $tax ?>,
                currency: "USD",
                storeId: "d3fcb722-dfe9-4e7c-8b33-cf8fbd309006",
                reference: '<?= htmlspecialchars($referencia) ?>',

                // Redirección DE VUELTA al controlador
                successUrl: '/superarseconectadosv2/public/pago?status=success',
                failureUrl: '/superarseconectadosv2/public/pago?status=failure'
            }).render('pp-button');
        });
    </script>

    <div class="container-fluid" id="pp-button"></div>

    <footer class="bg-dark text-white text-center py-3">
        <div class="container">
            <p class="mb-0">&copy; 2025 Instituto Superarse. Todos los derechos reservados.</p>
        </div>
    </footer>
</body>

</html>