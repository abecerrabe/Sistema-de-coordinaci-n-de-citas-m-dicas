<?php
// Archivo de configuración para credenciales de correo
// Este archivo debe mantenerse fuera del control de versiones (.gitignore)

// ADVERTENCIA CRÍTICA: La contraseña actual ('proyectoingenieria1') NO ES una contraseña de aplicación válida
// El diagnóstico ha mostrado: "535-5.7.8 Username and Password not accepted"
// Para solucionar esto, debes crear una contraseña de aplicación de Gmail:

// PASOS PARA CREAR UNA CONTRASEÑA DE APLICACIÓN:
// 1. Habilita la verificación en dos pasos en tu cuenta de Google
// 2. Ve a https://myaccount.google.com/apppasswords
// 3. Selecciona "Correo" y "Otro" (o un nombre como "Sistema-Citas-Medicas")
// 4. Copia la contraseña generada y reemplaza 'proyectoingenieria1' con esa contraseña

// Credenciales de Gmail SMTP
define('GMAIL_USERNAME', getenv('GMAIL_USERNAME') ?: 'proyectoingenieria2025kamjy@gmail.com');
define('GMAIL_PASSWORD', getenv('GMAIL_PASSWORD') ?: 'chezgvpgeuoskvce'); // ¡REEMPLAZA ESTA CONTRASEÑA!

// Configuración de la aplicación
define('APP_NAME', 'sistema de coordinación de citas médicas');
define('APP_URL', 'http://localhost');
?>