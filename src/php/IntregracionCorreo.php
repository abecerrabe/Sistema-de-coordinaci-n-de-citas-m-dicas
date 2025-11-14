<?php
// Include config.php with proper path from IntregracionCorreo.php
$configPath = __DIR__ . '/config.php';
if (file_exists($configPath)) {
    require_once $configPath;
} else {
    die('No se pudo encontrar el archivo de configuración: ' . $configPath);
}

class GmailConfig {
    // Gmail SMTP
    const HOST = 'smtp.gmail.com';
    const PORT = 587;

    // Obtener credenciales desde variables de entorno o archivo de configuración
    const USERNAME = GMAIL_USERNAME;
    const PASSWORD = GMAIL_PASSWORD;

    // Remitente
    const FROM_EMAIL = GMAIL_USERNAME;
    const FROM_NAME = APP_NAME;

    // URLs
    const APP_NAME = 'sistema de coordinación de citas médicas';
    const APP_URL = 'http://localhost';
}
?>