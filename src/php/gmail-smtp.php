<?php
// Usar el autoloader de Composer con una ruta absoluta
$vendorDir = __DIR__ . '/../../vendor/autoload.php';
if (file_exists($vendorDir)) {
    require_once $vendorDir;
} else {
    die('No se encontró el archivo de autoloader de Composer');
}

// Include IntregracionCorreo.php with proper path from gmail-smtp.php
$integrationPath = __DIR__ . '/IntregracionCorreo.php';
if (file_exists($integrationPath)) {
    require_once $integrationPath;
} else {
    die('No se pudo encontrar el archivo de configuración de correo: ' . $integrationPath);
}

use PHPMailer\PHPMailer\SMTP;

class GmailMailer {
    private $mailer;

    public function __construct() {
        $this->mailer = new PHPMailer\PHPMailer\PHPMailer(true);

        // Configurar el servidor SMTP
        $this->mailer->isSMTP();
        $this->mailer->Host = GmailConfig::HOST;                    // Configura el servidor SMTP de Gmail
        $this->mailer->SMTPAuth = true;                             // Habilita la autenticación SMTP
        $this->mailer->Username = GmailConfig::USERNAME;            // Nombre de usuario SMTP
        $this->mailer->Password = GmailConfig::PASSWORD;            // Contraseña SMTP
        $this->mailer->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS; // Tipo de encriptación
        $this->mailer->Port = GmailConfig::PORT;                    // Puerto SMTP (587 para STARTTLS)

        // Configuraciones adicionales para Gmail
        $this->mailer->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );

        // Tiempo de espera
        $this->mailer->Timeout = 30;

        // Caracteres especiales
        $this->mailer->CharSet = 'UTF-8';

        // Remitente
        $this->mailer->setFrom(GmailConfig::FROM_EMAIL, GmailConfig::FROM_NAME);
    }

    // Método para activar la depuración SMTP
    public function activarDepuracion() {
        $this->mailer->SMTPDebug = SMTP::DEBUG_SERVER;
    }

    // Método para desactivar la depuración SMTP
    public function desactivarDepuracion() {
        $this->mailer->SMTPDebug = SMTP::DEBUG_OFF;
    }

    public function enviarEmail($to, $subject, $htmlBody, $plainBody = '') {
        try {
            // Limpiar cualquier destinatario previo
            $this->mailer->clearAddresses();

            // Destinatario
            $this->mailer->addAddress($to);

            // Asunto
            $this->mailer->Subject = $subject;

            // Cuerpo del mensaje
            $this->mailer->Body = $htmlBody;
            $this->mailer->AltBody = $plainBody;
            $this->mailer->isHTML(true);

            // Enviar el correo
            $this->mailer->send();

            return [
                'success' => true,
                'message' => 'Email enviado exitosamente'
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $this->mailer->ErrorInfo
            ];
        }
    }
}
?>