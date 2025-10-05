<?php
session_start();
require_once "rutas.php";
require_once "crud.php";
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id_paciente = $_SESSION['id'] ?? "";
    $id_cita = post("id_cita");
    $id_disponibilidad_horaria = post("id_disponibilidad_horaria");
    $id_medico = post("id_medico");
    $fecha = post("fecha");
    $prioridad = post("prioridad");
    $estado = post("estado", "pendiente");
    $numero_tramite = post("numero_tramite");
    $horarios_disponible = post("horarios_disponible");

    list($horario_entrada, $horario_salida) = explode('|', $horarios_disponible);
}

$accion = $_REQUEST['accion'] ?? 'listar';

switch ($accion) {

    case "insertar":

        $datosDisponiblidad = [
            "id_medico"        => $id_medico,
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
    case "modificar":
        
        $datosDisponiblidad = [
            "id_medico"        => $id_medico,
            "hora_llegada"      => $horario_entrada,
            "hora_finalizacion" => $horario_salida
        ];
         update(
            "disponibilidad_horaria",
           $datosDisponiblidad,
            "id = '$id_disponibilidad_horaria'",
        );
        
        
        $datos = [
            "numero_tramite"            => $numero_tramite,
            "id_paciente"               => $id_paciente,
            "id_disponibilidad_horaria" => $id_disponibilidad_horaria,
            "fecha_cita"                => $fecha,
            "prioridad"                 => $prioridad,
            "estado"                    => $estado
        ];

        update(
            "cita",
            $datos,
            "id = '$id_cita'",
            $rutaConsultarCitas
        );
        break;
    case "modificarCita":

        $id_cita = $_GET['id'];
        $condicion = "cita.id = ?";
        $params = [$id_cita];

        $citas = select(
            "
            cita
            inner join disponibilidad_horaria as disponibilidad on cita.id_disponibilidad_horaria = disponibilidad.id
            inner join medico on disponibilidad.id_medico = medico.id
            inner join cargo on medico.id_cargo = cargo.id
            ",
            "
            cargo.id as id_cargo, cargo.nombre_cargo, medico.id as id_medico, disponibilidad.hora_llegada, disponibilidad.hora_finalizacion, cita.* 
            ",
            $condicion,
            $params
        );

        if (!empty($citas)) {
            $_SESSION["dataTempCitas"] = $citas[0];
        }
        header("Location: $rutaCrearCitas?id=" . $id_cita);
        break;
    case 'deleteCita':
         $id_cita = $_GET['id'];
        update(
            "cita",
            ["estado" => 'cancelado'],
            "id = '$id_cita'",
            $rutaConsultarCitas
        );
        break;
}
