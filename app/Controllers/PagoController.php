<?php
// app/Controllers/PagoController.php
require_once '../app/Models/PaymentModel.php';

class PagoController
{
    private $basePath = "/superarseconectados/public";
    private $pagoModel;

    public function __construct()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $this->pagoModel = new PaymentModel(); // Inicializar el modelo
    }

    public function uploadComprobante()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_POST['user_id'] ?? null;
            $bancoSeleccionado = $_POST['banco_seleccionado'] ?? 'Desconocido';
            $file = $_FILES['comprobante'] ?? null;

            if (!$userId || !$file || $file['error'] !== UPLOAD_ERR_OK) {
                // Manejar error: redirigir o mostrar mensaje
                header("Location: " . ($_SERVER['HTTP_REFERER'] ?? '/superarseconectadosv2/public/estudiante/index?error=no_file_or_user'));
                exit();
            }

            $uploadDir = '../public/uploads/comprobantes/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            // Generar nombre único para el archivo
            $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $newFileName = 'comprobante_user_' . $userId . '_' . time() . '.' . $fileExtension;
            $targetFilePath = $uploadDir . $newFileName;

            if (move_uploaded_file($file['tmp_name'], $targetFilePath)) {

                $senderEmail    = getenv('SMTP_USER');
                $senderPassword = getenv('SMTP_PASS');
                $recipientEmail = getenv('CORREO_MATRICULAS');

                require '../vendor/autoload.php';

                $mail = new PHPMailer\PHPMailer\PHPMailer(true); // true habilita excepciones

                try {
                    // Ajustes del Servidor SMTP (Microsoft 365 / Exchange Online)
                    $mail->isSMTP();
                    $mail->Host       = getenv('SMTP_HOST');
                    $mail->SMTPAuth   = true;
                    $mail->Username   = $senderEmail;
                    $mail->Password   = $senderPassword;
                    $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port       = 587;
                    $mail->CharSet    = 'UTF-8';

                    // Remitente y Destinatario
                    $mail->setFrom($senderEmail, 'Sistema de Estudiantes Superarse'); // Usa tu correo real
                    $mail->addAddress($recipientEmail, 'Departamento de Matrículas');

                    // Contenido del correo
                    $mail->isHTML(true);
                    $mail->Subject = "✅ Nuevo Comprobante de Pago de Estudiante: " . htmlspecialchars($userId);

                    $message = "
            <html>
            <head><title>Comprobante de Pago Recibido</title></head>
            <body>
                <p>Estimado Departamento de Matrículas,</p>
                <p>Se ha recibido un nuevo comprobante de pago de un estudiante.</p>
                <p><strong>ID de Estudiante:</strong> " . htmlspecialchars($userId) . "</p>
                <p><strong>Banco Seleccionado:</strong> " . htmlspecialchars($bancoSeleccionado) . "</p>
                <p>El archivo se adjunta a este correo para su validación.</p>
                <p style='font-size: 10px; color: #777;'>Ruta de archivo en el servidor: /uploads/comprobantes/" . htmlspecialchars($newFileName) . "</p>
            </body>
            </html>";

                    $mail->Body = $message;

                    // Adjunto
                    $mail->addAttachment($targetFilePath, $newFileName);

                    // Enviar
                    $mail->send();

                    // Éxito: Redirigir al usuario con mensaje de éxito
                    header("Location: " . ($_SERVER['HTTP_REFERER'] ?? '/superarseconectadosv2/public/estudiante/index?success=comprobante_enviado'));
                    exit();
                } catch (Exception $e) {
                    // Fallo: Registrar el error de correo y redirigir con un error
                    error_log("Error al enviar correo con comprobante: {$mail->ErrorInfo}");
                    header("Location: " . ($_SERVER['HTTP_REFERER'] ?? '/superarseconectadosv2/public/estudiante/index?error=mail_failed'));
                    exit();
                }
            } else {
                // Error al mover el archivo
                error_log("Error al mover el archivo subido: " . $file['tmp_name']);
                header("Location: " . ($_SERVER['HTTP_REFERER'] ?? '/superarseconectadosv2/public/estudiante/index?error=upload_failed'));
                exit();
            }
        }
        // Si no es POST, redirigir
        header("Location: " . ($_SERVER['HTTP_REFERER'] ?? '/superarseconectadosv2/public/estudiante/index'));
        exit();
    }

    public function procesarPago()
    {
        $userId = $_SESSION['id_usuario'] ?? null;
        $status = $_GET['status'] ?? null;
        $vista = $_GET['vista'] ?? null; // Capturamos la bandera 'pasarela'

        // --- A. MANEJO DE RESPUESTA DE PAYPHONE (SUCCESS/FAILURE) ---
        if ($status === 'success' || $status === 'failure') {
            // Carga la vista de la pasarela para mostrar el mensaje (la vista usará $_GET['status'])
            require_once '../Views/estudiantes/pagos.php';
            return;
        } elseif ($vista === 'pasarela' && isset($_GET['cantidad']) && $userId) {

            $cantidad_url = $_GET['cantidad'] ?? 0;
            $cantidad_base = max(0.0, floatval($cantidad_url));
            $referencia_raw = $_GET['referencia'] ?? "Pago de Superarse";

            // Generar ID único, registrar en DB (PENDING)
            list($usec, $sec) = explode(" ", microtime());
            $milisegundos = round($usec * 1000);
            $tiempo_actual = date("H_i_s", $sec) . '_' . sprintf('%03d', $milisegundos);
            $clientTransactionId = "Superarse_" . $tiempo_actual;

            // 2. DEFINIR LAS VARIABLES QUE LA VISTA NECESITA
            // Estas variables se hacen accesibles a la vista
            $GLOBALS['clientTransactionId'] = $clientTransactionId;
            $GLOBALS['amount'] = intval(round($cantidad_base * 100)); // En centavos
            $GLOBALS['amountWithoutTax'] = $GLOBALS['amount'];
            $GLOBALS['tax'] = 0.0;
            $GLOBALS['referencia'] = htmlspecialchars($referencia_raw);
            $GLOBALS['esPasarelaPayphone'] = true;

            // 3. CARGA DE LA VISTA (La vista de la pasarela)
            require_once __DIR__ . '/../Views/estudiantes/pagos.php';
            return;
        } else {
            // Acceso inválido, redirigir al dashboard
            header("Location: /superarseconectadosv2/public/estudiante/pagos");
            exit();
        }
    }
}
