<?php
session_start();

require_once "rutas.php";
require_once "crud.php";

// Guardar datos temporales en sesión
function setDataTemp($data)
{
    $_SESSION["dataTemp"] = [
        "nombre_cargo"      => $data['nombreCargo'],
        "descripcion_cargo" => $data['descripcionCargo'],
        "estados"            => $data['estados']
    ];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    print_r($_POST);
    $accion = post('accion');
    $id = post('id');
    $nombreCargo = post('nombre');
    $descripcionCargo = post('descripcion_cargo');
    $estados = post('estados', "activo");

    $accionGestionar = post('accionGestionar');

    $data = compact('nombreCargo', 'descripcionCargo', 'estados');

    switch ($accion) {
        case "insertar":

            setDataTemp($data);

            // insertar cargo
            insert("cargo", [
                "nombre_cargo"      => $nombreCargo,
                "descripcion_cargo" => $descripcionCargo,
                "estados"            => $estados
            ], $rutaGestionCargos);

            unset($_SESSION["dataTemp"]);
            break;

        case "modificar":
            $datosActualizar = [
                "nombre_cargo"      => $nombreCargo,
                "descripcion_cargo" => $descripcionCargo,
                "estados"            => $estados
            ];

            update("cargo", $datosActualizar, "id = '$id'");
            header("Location: $rutaGestionCargos");
            break;
        default:
            echo "No se recibió ninguna acción válida de POST.";
            break;
    }
} else {
    $accion = $_GET["accion"] ?? null;
    $id = $_GET["id"] ?? null;

    switch ($accion) {
        case 'modificarCargo':
            $cargos = select("cargo", "*", "id = ?", [$id]);
            print_r($cargos);
            $_SESSION["dataTemp"] = $cargos[0];

            header("Location: $rutaRegistrarCargos?id=" . $id);
            exit();
            break;
            
            case 'deleteCargos':
            update(
                "cargo",
                ["estados" => 'inactivo'],
                "id = '$id'",
                $rutaGestionCargos
            );
            break;

        default:
            echo "No se recibió ninguna acción válida de GET.";
            break;
    }
}
