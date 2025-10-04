<?php
session_start();
require_once "crud.php";

$options = '';

if (!isset($_GET['id_cargo'])) {
    echo json_encode([]);
    exit();
}

$idCargo = intval($_GET['id_cargo']);
$medicos = select(
    "medico
    INNER JOIN cargo ON medico.id_cargo = cargo.id 
    INNER JOIN usuario ON medico.id_usuario = usuario.id",
    "medico.id as id_medico, usuario.nombre_completo, medico.horario_atencion",
    "usuario.estado = 'activo' AND cargo.estados = 'activio' AND medico.id_cargo = ?", 
    [$idCargo], "i"
);

header('Content-Type: application/json');
echo json_encode($medicos);

exit();

/* foreach ($medico as $row) {
    $selected = (!empty($_SESSION['dataTemp']['id_medico']) && $_SESSION['dataTemp']['id_medico'] == $row['id']) 
        ? ' selected' 
        : '';
    $options .= '<option value="' . $row['id_medico'] . '"' . $selected . '>' . $row['nombre_completo'] . '</option>';
}

echo $options; */
