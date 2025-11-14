<?php
require_once 'src/php/conexion.php';

// SQL to add reset token columns
$sql = "ALTER TABLE usuario ADD COLUMN reset_token VARCHAR(255) NULL, ADD COLUMN reset_token_expiration DATETIME NULL;";

if ($conn->query($sql) === TRUE) {
    echo "Tabla usuario actualizada correctamente con las columnas para restablecimiento de contraseña.";
} else {
    echo "Error actualizando tabla: " . $conn->error;
}

$conn->close();
?>