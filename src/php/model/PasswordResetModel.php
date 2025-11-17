<?php
// Include conexion.php using the correct relative path from model directory to php directory
// Model is in src/php/model/ and conexion.php is in src/php/, so path is ../conexion.php

// Path from model directory to conexion.php
$modelDir = dirname(__DIR__); // This gets us to src/php/ from src/php/model/
$conexionPath = $modelDir . '/conexion.php';

if (file_exists($conexionPath)) {
    require_once $conexionPath;
} else {
    die('No se pudo encontrar el archivo de conexión a la base de datos: ' . $conexionPath);
}

class PasswordResetModel {

    public function getUserByEmail($email) {
        global $conn;

        $stmt = $conn->prepare("SELECT id, nombre_completo, correo_electronico FROM usuario WHERE correo_electronico = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_assoc();
    }

    public function generateToken($userId) {
        // Generar un token único
        $token = bin2hex(random_bytes(32));
        $expiration = date('Y-m-d H:i:s', strtotime('+1 hour')); // Token válido por 1 hora

        global $conn;

        $stmt = $conn->prepare("UPDATE usuario SET reset_token = ?, reset_token_expiration = ? WHERE id = ?");
        $stmt->bind_param("ssi", $token, $expiration, $userId);

        if ($stmt->execute()) {
            return $token;
        }

        return false;
    }

    public function validateToken($token) {
        global $conn;

        $stmt = $conn->prepare("SELECT id, nombre_completo, correo_electronico FROM usuario WHERE reset_token = ? AND reset_token_expiration > NOW()");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_assoc();
    }

    public function resetPassword($userId, $newPassword) {
        global $conn;

        // Encriptar la nueva contraseña
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("UPDATE usuario SET password_usuario = ?, reset_token = NULL, reset_token_expiration = NULL WHERE id = ?");
        $stmt->bind_param("si", $hashedPassword, $userId);

        return $stmt->execute();
    }

    public function clearToken($userId) {
        global $conn;

        $stmt = $conn->prepare("UPDATE usuario SET reset_token = NULL, reset_token_expiration = NULL WHERE id = ?");
        $stmt->bind_param("i", $userId);

        return $stmt->execute();
    }
}
?>