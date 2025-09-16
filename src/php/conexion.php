<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "hospital";
$port = 3307;

$conn = new mysqli($host, $user, $pass, $db, $port);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
} else {
    //echo "Conectado correctamente a la BD";
}
?>
