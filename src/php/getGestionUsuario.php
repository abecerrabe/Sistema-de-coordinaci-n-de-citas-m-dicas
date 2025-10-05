<?php
$where = "estado = 'activo'";
$params = [];

// Si recibimos POST aplicamos filtros
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!empty($_POST['cedula'])) {
        $cedula = $_POST['cedula'];
        $where .= " AND numero_cedula LIKE '%$cedula%'";
    }
    if (!empty($_POST['nombre'])) {
        $nombre = $_POST['nombre'];
        $where .= " AND nombre_completo LIKE '%$nombre%'";
    }
    if (!empty($_POST['rol'])) {
        $rol = $_POST['rol'];
        $where .= " AND tipo_permiso = '$rol'";
    }
}

$usuarios = select("usuario", "*", $where, $params);
?>