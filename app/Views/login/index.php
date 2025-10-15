<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Superarse Conectados v2</title>
    <link rel="icon" type="image/png" href="/superarseconectadosv2/public/assets/img/logoSuperarse.png" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    'superarse-morado-oscuro': '#4A148C',
                    'superarse-morado-medio': '#673AB7',
                    'superarse-rosa': '#E91E63',
                }
            }
        }
    }
    </script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>


<body
    class="bg-gradient-to-r from-superarse-morado-oscuro via-superarse-morado-medio to-superarse-rosa min-h-screen flex flex-col">

    <header class="bg-transparent text-white w-full py-4 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 text-center">

            <img src="/superarseconectadosv2/public/assets/img/LOGO SUPERARSE PNG-02.png"
                onerror="this.onerror=null; this.src='/superarseconectados/assets/logos/LOGO SUPERARSE PNG-02.png';"
                alt="Logo de Superarse" class="logo h-20 w-auto mx-auto mb-4">

            <p class="text-xl font-light m-0 font-semibold">Superarse Conectados</p>
        </div>
    </header>

    <main class="flex-grow flex items-center justify-center p-4 pt-10">
        <div class="w-full max-w-md">
            <div class="bg-white p-8 rounded-xl shadow-2xl">
                <h1 class="text-3xl font-bold text-center text-superarse-morado-oscuro mb-6">Iniciar Sesión</h1>
                <p class="text-center text-gray-600 mb-8">Ingresa tu número de identificación para acceder a tu
                    información.</p>

                <form id="login-Form" action="/superarseconectadosv2/public/login/check" method="POST"
                    class="space-y-6">
                    <div class="mb-6">
                        <label for="cedula" class="block text-gray-700 text-sm font-semibold mb-2">Número de
                            Identificación (Cédula)</label>
                        <input type="text" id="cedula" name="numero_identificacion" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-superarse-morado-medio"
                            placeholder="Ej: 0912345678">
                    </div>
                    <button type="submit"
                        class="w-full bg-superarse-rosa hover:bg-superarse-morado-medio text-white font-bold py-2 px-4 rounded-lg transition duration-300">
                        Ingresar
                    </button>
                </form>
            </div>
        </div>
    </main>

    <div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-superarse-rosa text-white">
                    <h5 class="modal-title" id="errorModalLabel">Error de Acceso</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body text-center text-gray-700" id="errorMessage">
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn bg-superarse-morado-medio text-white"
                        data-bs-dismiss="modal">Aceptar</button>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-transparent text-white w-full py-3">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p class="text-sm m-0">&copy; 2025 Instituto Superarse. Todos los derechos reservados.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script src="/superarseconectadosv2/public/js/login.js"></script>
</body>

</html>