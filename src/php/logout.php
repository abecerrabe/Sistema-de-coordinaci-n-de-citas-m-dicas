<?php
include_once '../php/rutas.php';
session_start();
session_unset();
session_destroy();
header("Location: $rutaInicio");
exit();
?>
