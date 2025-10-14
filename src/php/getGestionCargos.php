<?php
require_once "../php/rutas.php";

$where = "estados = 'activo'";
$params = [];

// Si recibimos POST aplicamos filtros
if ($_SERVER["REQUEST_METHOD"] === "POST") {

     // Si presionó "Limpiar", vaciar filtros
    if (isset($_POST['limpiar'])) {
        unset($_SESSION['filtros_cargos']);
        header("Location: $paginaGestionCargos");
        exit;
    }

    // Guardar filtros en sesión
    $_SESSION['filtros_cargos'] = [
        'nombre_cargo'     => trim($_POST['nnombre_cargo'] ?? ''),
        'descripcion_cargo'=> trim($_POST['fdescripcion_cargo'] ?? ''),
    ];

    if (!empty($_POST['nombre_cargo'])) {
        $nombre_cargo = $_POST['nombre_cargo'];
        $where .= " AND nombre_cargo LIKE '%$nombre_cargo%'";
    }
    if (!empty($_POST['descripcion_cargo'])) {
        $descripcion_cargo = $_POST['descripcion_cargo'];
        $where .= " AND descripcion_cargo LIKE '%$descripcion_cargo%'";
    }
}

// --- Borra los filtros activos ---
$filtros = $_SESSION['filtros_cargos'] ?? [
    'nombre_cargo' => '',
    'descripcion_cargo' => ''
];

$cargos = select("cargo", "*", $where, $params);
?>