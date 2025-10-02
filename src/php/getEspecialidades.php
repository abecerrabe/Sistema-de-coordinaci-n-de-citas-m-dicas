<?php
session_start();
require_once "crud.php";

$options = '';
$especialidades = select("cargo", "id, nombre_cargo as nombre");

foreach ($especialidades as $row) {
    $selected = (isset($_SESSION['dataTemp']['id_cargo']) && $_SESSION['dataTemp']['id_cargo'] == $row['id']) 
        ? ' selected' 
        : '';
    
    $options .= '<option value="' . $row['id'] . '"' . $selected . '>' . $row['nombre'] . '</option>';
}

echo $options;
