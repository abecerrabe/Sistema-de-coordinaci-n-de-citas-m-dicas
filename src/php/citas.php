<?php
session_start();
require_once "rutas.php";
require_once "crud.php";
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id_paciente = $_SESSION['id'] ?? "";
    $tipo_cita = post("tipo_cita");
    $prioridad = post("prioridad");
    $estado = post("estado");
    $numero_tramite = post("numero_tramite");

    //$id_medico = post("id_medico");
    //$horario_entrada = post("horario_entrada");
    //$horario_salida = post("horario_salida");

    $id_medico = '1';
    $horario_entrada = '20:00:00';
    $horario_salida = '21:00:00';
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
                "id_paciente"               => 1,//$id_paciente,
                "id_disponibilidad_horaria" => $idDisponibilidad,
                "fecha_cita"                => date("Y-m-d"),
                "tipo_cita"                 => $tipo_cita,
                "prioridad"                 => 'moderada',//$prioridad,
                "estado"                    => 'pendiente',//$estado,
                "numero_tramite"            => $numero_tramite
            ];
            insert("cita", $datos, $rutaConsultarCitas);
        }



        break;
}
