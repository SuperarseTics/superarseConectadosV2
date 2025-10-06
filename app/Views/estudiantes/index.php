<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido(a) <?php echo htmlspecialchars($data['nombreCompleto']); ?> - Superarse Conectados v2</title>
    <link rel="icon" type="image/png" href="/superarseconectadosv2/public/assets/img/logoSuperarse.png" />
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

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-gradient-to-r from-superarse-morado-oscuro via-superarse-morado-medio to-superarse-rosa 
             min-h-screen flex flex-col pt-20">

    <header class="bg-superarse-morado-oscuro shadow-lg fixed top-0 left-0 w-full z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
            <h1 class="text-xl font-bold text-white">Superarse Conectados v2</h1>
            <div class="flex items-center space-x-4">
                <span class="text-white text-sm hidden sm:block">
                    Bienvenido(a), <?php echo htmlspecialchars($data['nombreCompleto']); // USANDO $data 
                                    ?>
                </span>
                <a href="<?php echo $data['basePath'] ?? '/superarseconectadosv2/public'; ?>/login/logout" class="bg-superarse-rosa hover:bg-superarse-morado-medio text-white text-sm font-semibold py-1 px-3 rounded-full transition duration-300 shadow-md">
                    <i class="fas fa-sign-out-alt"></i> Salir
                </a>
            </div>
        </div>
    </header>

    <main class="flex-grow flex justify-center pt-4 w-full">
        <div class="w-full flex justify-center">
            <div class="bg-white p-6 md:p-10 rounded-xl shadow-2xl w-4/5">

                <div class="flex flex-wrap justify-center border-b border-gray-200 mb-6 -mx-2">
                    <button class="tab-button p-3 text-sm whitespace-nowrap font-medium text-gray-600 hover:text-superarse-morado-oscuro border-b-2 border-transparent hover:border-superarse-rosa transition duration-150" data-tab="informacion">
                        <i class="fas fa-info-circle mr-2"></i> Información
                    </button>
                    <button class="tab-button p-3 text-sm whitespace-nowrap font-medium text-gray-600 hover:text-superarse-morado-oscuro border-b-2 border-transparent hover:border-superarse-rosa transition duration-150" data-tab="asignaturas">
                        <i class="fas fa-book-open mr-2"></i> Asignaturas
                    </button>
                    <button class="tab-button p-3 text-sm whitespace-nowrap font-medium text-gray-600 hover:text-superarse-morado-oscuro border-b-2 border-transparent hover:border-superarse-rosa transition duration-150" data-tab="credenciales">
                        <i class="fas fa-lock mr-2"></i> Credenciales
                    </button>
                    <button class="tab-button p-3 text-sm whitespace-nowrap font-medium text-gray-600 hover:text-superarse-morado-oscuro border-b-2 border-transparent hover:border-superarse-rosa transition duration-150" data-tab="pagos">
                        <i class="fas fa-dollar-sign mr-2"></i> Pagos
                    </button>
                </div>

                <div id="tab-content">
                    <div id="informacion" class="tab-pane hidden">
                        <h3 class="text-xl font-semibold text-superarse-morado-oscuro mb-4">Datos Personales</h3>

                        <div class="bg-white p-4 rounded-lg shadow-xl border border-gray-200">

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-4">

                                <div class="flex flex-col border-b md:border-b-0 pb-2 md:pb-0">
                                    <span class="text-sm font-medium text-left text-gray-500">Código Estudiantil</span>
                                    <span class="text-base text-gray-900 font-semibold">
                                        <?php echo htmlspecialchars($data['infoPersonal']['codigo_matricula'] ?? 'N/D'); ?>
                                    </span>
                                </div>

                                <div class="flex flex-col border-b md:border-b-0 pb-2 md:pb-0">
                                    <span class="text-sm font-medium text-left text-gray-500">Nombres Completos</span>
                                    <span class="text-base text-gray-900 font-semibold">
                                        <?php echo htmlspecialchars($data['nombreCompleto']); ?>
                                    </span>
                                </div>

                                <div class="flex flex-col border-b md:border-b-0 pb-2 md:pb-0">
                                    <span class="text-sm font-medium text-left text-gray-500">Tipo de Identificación</span>
                                    <span class="text-base text-gray-900">
                                        <?php echo htmlspecialchars($data['infoPersonal']['tipo_identificacion'] ?? 'N/D'); ?>
                                    </span>
                                </div>

                                <div class="flex flex-col border-b md:border-b-0 pb-2 md:pb-0">
                                    <span class="text-sm font-medium text-left text-gray-500">Número de Identificación</span>
                                    <span class="text-base text-gray-900">
                                        <?php echo htmlspecialchars($data['infoPersonal']['numero_identificacion'] ?? 'N/D'); ?>
                                    </span>
                                </div>

                                <div class="flex flex-col border-b md:border-b-0 pb-2 md:pb-0">
                                    <span class="text-sm font-medium text-left text-gray-500">Correo Institucional</span>
                                    <span class="text-base text-gray-900">
                                        <?php echo htmlspecialchars($data['infoPersonal']['usuario'] ?? 'N/D'); ?>
                                    </span>
                                </div>

                                <div class="flex flex-col border-b md:border-b-0 pb-2 md:pb-0">
                                    <span class="text-sm font-medium text-left text-gray-500">Estado / Cond. Matrícula</span>
                                    <span class="text-base text-gray-900">
                                        <?php echo htmlspecialchars($data['infoPersonal']['estado'] ?? 'N/D') . ' / ' . htmlspecialchars($data['infoPersonal']['cond_matricula'] ?? 'N/D'); ?>
                                    </span>
                                </div>

                                <div class="flex flex-col border-b md:border-b-0 pb-2 md:pb-0">
                                    <span class="text-sm font-medium text-left text-gray-500">Teléfono</span>
                                    <span class="text-base text-gray-900">
                                        <?php echo htmlspecialchars($data['infoPersonal']['celular'] ?? 'N/D'); ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <h3 class="text-xl font-semibold text-superarse-morado-oscuro mb-4 mt-6">Datos Académicos</h3>
                        <div class="bg-white p-4 rounded-lg shadow-xl border border-gray-200">

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-4">

                                <div class="flex flex-col border-b md:border-b-0 pb-2 md:pb-0">
                                    <span class="text-sm font-medium text-left text-gray-500">Periodo Académico</span>
                                    <span class="text-base text-gray-900">
                                        <?php echo htmlspecialchars($data['infoPersonal']['periodo'] ?? 'N/D'); ?>
                                    </span>
                                </div>

                                <div class="flex flex-col border-b md:border-b-0 pb-2 md:pb-0">
                                    <span class="text-sm font-medium text-left text-gray-500">Carrera</span>
                                    <span class="text-base text-gray-900">
                                        <?php echo htmlspecialchars($data['infoPersonal']['programa'] ?? 'N/D'); ?>
                                    </span>
                                </div>

                                <div class="flex flex-col border-b md:border-b-0 pb-2 md:pb-0 md:col-span-2">
                                    <span class="text-sm font-medium text-left text-gray-500">Nivel</span>
                                    <span class="text-base text-gray-900">
                                        <?php
                                        $nivelActual = htmlspecialchars($data['infoPersonal']['nivel'] ?? 'N/D');
                                        echo $nivelActual;
                                        if ($nivelActual !== 'N/D' && !in_array(strtoupper(trim($nivelActual)), ['NIVEL 1', 'N1', 'PRIMERO', '1'])) {
                                            echo ' - ¡Recuerda prematricularte para el siguiente nivel!';
                                            echo '<br>';
                                            echo '<a href="https://site2.q10.com/Prematricula" target="_blank" class="text-superarse-rosa hover:underline font-semibold">¡Prematrículate aquí!</a>';
                                            echo ' / ';
                                            echo '<a href="..." target="_blank" class="text-blue-600 hover:underline">¿Cómo me prematriculo?</a>';
                                        }
                                        ?>
                                    </span>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div id="asignaturas" class="tab-pane hidden">
                    <h3 class="text-xl font-semibold text-superarse-morado-oscuro mb-6">Asignaturas Registradas por Nivel</h3>

                    <?php
                    // USANDO $data['infoAsignaturas']
                    if (isset($data['infoAsignaturas']['SQL_ERROR'])): ?>
                        <p class="text-red-500 font-bold">Error al cargar asignaturas: <?php echo htmlspecialchars($data['infoAsignaturas']['SQL_ERROR']); ?></p>
                    <?php elseif (!empty($data['infoAsignaturas'])): ?>

                        <?php
                        // Iterar sobre cada grupo de niveles (N1, N2, N3, N4, N5, Otras)
                        foreach ($data['infoAsignaturas'] as $nivel => $asignaturas): // USANDO $data
                            // Solo mostrar si el nivel tiene asignaturas
                            if (!empty($asignaturas)):
                        ?>
                                <div class="mb-8 p-4 bg-gray-50 rounded-xl shadow-inner border border-gray-200">
                                    <h4 class="text-lg font-bold text-superarse-rosa mb-4 border-b pb-2">
                                        Nivel <?php echo htmlspecialchars($nivel); ?>
                                    </h4>

                                    <ul class="space-y-3">
                                        <?php foreach ($asignaturas as $a): ?>
                                            <li class="p-3 bg-white rounded-lg shadow-sm flex justify-between items-center transition duration-200 hover:bg-gray-100">
                                                <div>
                                                    <span class="font-semibold text-gray-800"><?php echo htmlspecialchars($a['nombre']); ?></span>
                                                    <span class="text-xs text-gray-500 ml-2">(<?php echo htmlspecialchars($a['codigo']); ?>)</span>
                                                </div>
                                                <div class="flex items-center space-x-4 text-sm">
                                                    <span class="font-medium px-2 py-1 rounded-full 
                                        <?php echo strtolower($a['estado']) == 'activa' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'; ?>">
                                                        <?php echo htmlspecialchars($a['estado']); ?>
                                                    </span>
                                                </div>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                        <?php
                            endif;
                        endforeach;
                        ?>

                    <?php else: ?>
                        <p class="text-gray-500">No hay asignaturas registradas para tu programa.</p>
                    <?php endif; ?>
                </div>
                <div id="credenciales" class="tab-pane hidden">
                    <h3 class="text-xl font-semibold text-superarse-morado-oscuro mb-4">Credenciales de Acceso a Plataformas</h3>
                    <?php if (!empty($data['infoCredenciales'])): // USANDO $data 
                    ?>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <?php foreach ($data['infoCredenciales'] as $c): // USANDO $data 
                            ?>
                                <div class="bg-gray-50 p-4 rounded-lg shadow-md border border-gray-200">
                                    <h4 class="font-bold text-superarse-morado-oscuro mb-3 text-lg"><?php echo htmlspecialchars($c['plataforma']); ?></h4>
                                    <p class="text-sm text-gray-700">
                                        <i class="fas fa-user-circle mr-2"></i> Usuario: <strong class="text-superarse-rosa"><?php echo htmlspecialchars($c['usuario_acceso']); ?></strong>
                                    </p>
                                    <p class="text-sm text-gray-700 mt-2">
                                        <i class="fas fa-key mr-2"></i> Contraseña: <strong class="text-red-600"><?php echo htmlspecialchars($c['clave_acceso']); ?></strong>
                                    </p>
                                    <p class="text-sm text-gray-700 mt-2">
                                        <i class="fas fa-link mr-2"></i> Link: <a href="<?php echo htmlspecialchars($c['link_acceso']); ?>" target="_blank" class="text-blue-600 hover:underline break-all">Acceder Aquí</a>
                                    </p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-gray-500">No hay credenciales de acceso a plataformas registradas.</p>
                    <?php endif; ?>
                </div>
                <div id="pagos" class="tab-pane hidden">
                    <div id="student-id-data"
                        data-id="<?php echo htmlspecialchars($data['infoPersonal']['numero_identificacion'] ?? 'SIN_ID'); // USANDO $data 
                                    ?>"
                        style="display: none;">
                    </div>

                    <h3 class="text-xl font-semibold text-superarse-morado-oscuro mb-4">Resumen Financiero</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">

                        <div class="bg-green-100 p-4 rounded-lg shadow-md border border-green-300">
                            <p class="text-sm font-medium text-green-700">ABONO TOTAL</p>
                            <p class="text-2xl font-bold text-green-800 mt-1">
                                $<?php echo htmlspecialchars($data['infoPagos']['abono_total'] ?? '0.00'); // USANDO $data 
                                    ?>
                            </p>
                        </div>

                        <div class="bg-red-100 p-4 rounded-lg shadow-md border border-red-300">
                            <p class="text-sm font-medium text-red-700">SALDO TOTAL PENDIENTE</p>
                            <p class="text-2xl font-bold text-red-800 mt-1">
                                $<?php echo htmlspecialchars($data['infoPagos']['saldo_total'] ?? '0.00'); // USANDO $data 
                                    ?>
                            </p>
                        </div>

                        <div class="bg-blue-100 p-4 rounded-lg shadow-md border border-blue-300 md:col-span-1">
                            <p class="text-sm font-medium text-blue-700">ESTADO / OBSERVACIÓN</p>
                            <p class="text-base text-blue-800 mt-2">
                                <?php echo nl2br(htmlspecialchars($data['infoPagos']['observacion'] ?? 'Sin observaciones de pago.')); // USANDO $data 
                                ?>
                            </p>
                        </div>
                    </div>
                    <hr class="my-8 border-gray-300">
                    <h3 class="text-xl font-semibold text-superarse-morado-oscuro mb-4">Pago por Transferencia Bancaria</h3>
                    <p class="text-gray-700 mb-6">Selecciona una de las siguientes cuentas para realizar tu transferencia:</p>

                    <div class="flex space-x-4 mb-6" id="bank-buttons">
                        <?php if (!empty($data['bancos'])): // USANDO $data 
                        ?>
                            <?php foreach ($data['bancos'] as $index => $banco): // USANDO $data 
                            ?>
                                <button
                                    type="button"
                                    class="bank-tab-button px-6 py-3 rounded-lg text-white font-semibold transition duration-300 
                                    <?php echo ($index === 0) ? 'bg-superarse-morado-oscuro hover:bg-superarse-morado-medio' : 'bg-gray-400 hover:bg-gray-500'; ?>"
                                    data-bank-id="<?php echo htmlspecialchars($banco['id']); ?>"
                                    data-bank-name="<?php echo htmlspecialchars($banco['nombre_banco']); ?>"
                                    data-account-type="<?php echo htmlspecialchars($banco['tipo_cuenta']); ?>"
                                    data-account-number="<?php echo htmlspecialchars($banco['numero_cuenta']); ?>">
                                    <?php echo htmlspecialchars($banco['nombre_banco']); ?>
                                </button>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-gray-500">No hay información de bancos disponible.</p>
                        <?php endif; ?>
                    </div>

                    <div id="bank-details" class="bg-gray-50 p-6 rounded-lg shadow-md border border-gray-200 mb-8">
                        <p class="mb-2"><strong class="text-superarse-morado-oscuro">Banco:</strong> <span id="selected-bank-name"></span></p>
                        <p class="mb-2"><strong class="text-superarse-morado-oscuro">Tipo de cuenta:</strong> <span id="selected-account-type"></span></p>
                        <p class="mb-2"><strong class="text-superarse-morado-oscuro">Número de Cuenta:</strong> <span id="selected-account-number"></span></p>
                        <p class="mb-2"><strong class="text-superarse-morado-oscuro">RUC:</strong> 1792951704001</p>
                        <p class="mb-4"><strong class="text-superarse-morado-oscuro">Nombre del Beneficiario:</strong> Instituto Superior Tecnológico Superarse</p>
                    </div>

                    <h4 class="text-lg font-semibold text-superarse-morado-oscuro mb-3">
                        Paso 3: Envía tu Notificación de Pago
                    </h4>

                    <div class="mt-4 p-3 bg-yellow-100 border border-yellow-400 text-yellow-800 rounded-lg mb-6">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        IMPORTANTE: Al hacer clic, se abrirá tu correo. **Debes adjuntar el comprobante manualmente** y verificar que tu ID y el banco seleccionado sean correctos antes de enviar.
                    </div>

                    <form id="upload-form" onsubmit="return false;">
                        <a href="#"
                            id="send-comprobante-btn"
                            class="bg-superarse-rosa hover:bg-superarse-morado-medio text-white font-bold py-3 px-6 rounded-lg shadow-md transition duration-300 opacity-50 cursor-not-allowed">
                            Abrir Correo y Notificar Pago
                        </a>
                    </form>
                    <hr class="my-8 border-gray-300">
                    <h3 class="text-xl font-semibold text-superarse-morado-oscuro mb-4 mt-6">Pago a través de Payphone</h3>
                    <div class="bg-gray-50 p-6 rounded-lg shadow-md border border-gray-200">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="cantidad" class="block text-sm font-medium text-gray-700 mb-1">
                                    <i class="fas fa-money-bill-wave mr-1 text-superarse-rosa"></i> Cantidad a Pagar (USD)
                                </label>
                                <input
                                    type="text"
                                    id="cantidad"
                                    class="block w-full border border-gray-300 rounded-lg shadow-sm p-3 focus:ring-superarse-morado-oscuro focus:border-superarse-morado-oscuro"
                                    placeholder="Ej: 50.00 o 12.50"
                                    inputmode="decimal">
                            </div>

                            <div>
                                <label for="referencia" class="block text-sm font-medium text-gray-700 mb-1">
                                    <i class="fas fa-tag mr-1 text-superarse-rosa"></i> Referencia / Descripción
                                </label>
                                <input
                                    type="text"
                                    id="referencia"
                                    class="block w-full border border-gray-300 rounded-lg shadow-sm p-3 focus:ring-superarse-morado-oscuro focus:border-superarse-morado-oscuro"
                                    placeholder="Ej: Pago Matrícula, Cuota de Marzo, etc.">
                            </div>
                        </div>

                        <p class="mt-6 text-sm text-gray-600">
                            Al hacer clic, serás redirigido a la pasarela de Payphone para completar el pago.
                        </p>

                        <button
                            id="payphone-link"
                            class="mt-4 py-3 px-8 bg-superarse-rosa text-white font-bold rounded-lg shadow-lg transition duration-300 hover:bg-pink-700 focus:outline-none focus:ring-4 focus:ring-pink-300" target="_blank">
                            <i class="fas fa-credit-card mr-2"></i> Pagar con Payphone
                        </button>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </main>

    <script>
        const DATOS_ESTUDIANTE = <?php echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT); ?>;
    </script>
    <script src="/superarseconectadosv2/public/js/datos.js"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="/superarseconectadosv2/public/js/transferencia.js"></script>

    <script src="/superarseconectadosv2/public/js/payphone.js"></script>

</body>

</html>