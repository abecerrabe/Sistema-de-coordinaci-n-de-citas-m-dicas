<?php
session_start();
require_once "rutas.php";
require_once "crud.php";
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id_paciente = $_SESSION['id'] ?? "";
    $id_medico = post("id_medico");
    $fecha = post("fecha");
    $prioridad = post("prioridad");
    $estado = post("estado");
    $numero_tramite = post("numero_tramite");
    $horarios_disponible = post("horarios_disponible");

    list($horario_entrada, $horario_salida) = explode('|', $horarios_disponible);
}

$accion = $_REQUEST['accion'] ?? 'listar';

switch ($accion) {

    case "insertar":

        $datosDisponiblidad = [
            "id_medico "        => $id_medico,
            "hora_llegada"      => $horario_entrada,
            "hora_finalizacion" => $horario_salida
        ];

        insert("disponibilidad_horaria", $datosDisponiblidad);
        $idDisponibilidad = lastID("disponibilidad_horaria");

        if (!empty($idDisponibilidad)) {

            $datos = [
                "numero_tramite"            => $numero_tramite,
                "id_paciente"               => $id_paciente,
                "id_disponibilidad_horaria" => $idDisponibilidad,
                "fecha_cita"                => $fecha,
                "prioridad"                 => $prioridad,
                "estado"                    => $estado
            ];

            insert("cita", $datos, $rutaConsultarCitas);
        }

        break;
}
