<?php
// Direct path from controller to model directory
require_once __DIR__ . '/../model/PasswordResetModel.php';
require_once __DIR__ . '/../gmail-smtp.php';
require_once __DIR__ . '/../config.php';

class PasswordResetController {

    private $model;
    private $mailer;

    public function __construct() {
        $this->model = new PasswordResetModel();
        $this->mailer = new GmailMailer();
    }

    public function requestReset($email) {
        // Validar si el usuario existe
        $user = $this->model->getUserByEmail($email);

        if (!$user) {
            return [
                'success' => false,
                'message' => 'No se encontró una cuenta asociada a este correo electrónico.'
            ];
        }

        // Generar token
        $token = $this->model->generateToken($user['id']);

        if (!$token) {
            return [
                'success' => false,
                'message' => 'Error al generar el token de restablecimiento. Por favor, inténtelo de nuevo.'
            ];
        }

        // Enviar correo con el enlace de restablecimiento
        $resetLink = 'http://localhost/Sistema-de-coordinaci-n-de-citas-m-dicas/src/pages/reset_password_form.php?token=' . $token;

        $subject = 'Restablecimiento de Contraseña - ' . APP_NAME;
        $htmlBody = '
        <html>
        <body>
            <h2>Solicitud de restablecimiento de contraseña</h2>
            <p>Hola ' . $user['nombre_completo'] . ',</p>
            <p>Hemos recibido una solicitud para restablecer la contraseña de tu cuenta.</p>
            <p>Si hiciste esta solicitud, haz clic en el siguiente enlace para restablecer tu contraseña:</p>
            <p><a href="' . $resetLink . '" target="_blank">Restablecer Contraseña</a></p>
            <p>Este enlace será válido durante 1 hora.</p>
            <p>Si no solicitaste restablecer tu contraseña, puedes ignorar este correo.</p>
            <br>
            <p>Saludos,<br>
            El equipo de ' . APP_NAME . '</p>
        </body>
        </html>';

        $response = $this->mailer->enviarEmail($user['correo_electronico'], $subject, $htmlBody);

        if ($response['success']) {
            return [
                'success' => true,
                'message' => 'Se ha enviado un enlace de restablecimiento a tu correo electrónico. Por favor, revisa tu bandeja de entrada.'
            ];
        } else {
            // Si falla el envío del correo, limpiar el token generado
            $this->model->clearToken($user['id']);

            return [
                'success' => false,
                'message' => 'Error al enviar el correo de restablecimiento: ' . $response['error']
            ];
        }
    }

    public function validateToken($token) {
        return $this->model->validateToken($token);
    }

    public function resetPassword($token, $password, $confirmPassword) {
        // Validar que las contraseñas coincidan
        if ($password !== $confirmPassword) {
            return [
                'success' => false,
                'message' => 'Las contraseñas no coinciden.'
            ];
        }

        // Validar la longitud de la contraseña
        if (strlen($password) < 6) {
            return [
                'success' => false,
                'message' => 'La contraseña debe tener al menos 6 caracteres.'
            ];
        }

        // Validar el token
        $user = $this->validateToken($token);

        if (!$user) {
            return [
                'success' => false,
                'message' => 'El enlace de restablecimiento es inválido o ha expirado.'
            ];
        }

        // Actualizar la contraseña
        $result = $this->model->resetPassword($user['id'], $password);

        if ($result) {
            return [
                'success' => true,
                'message' => 'Tu contraseña se ha restablecido correctamente.'
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Error al restablecer la contraseña. Por favor, inténtelo de nuevo.'
            ];
        }
    }
}
?>