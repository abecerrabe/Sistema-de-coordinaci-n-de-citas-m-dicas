<?php
require_once 'crud.php';

$id_medico = isset($_GET['id_medico']) ? intval($_GET['id_medico']) : 0;
$fecha = isset($_GET['fecha']) ? trim($_GET['fecha']) : '';
$id_cita_actual = isset($_GET['id_cita']) ? intval($_GET['id_cita']) : 0;

// Obtener las citas y horarios del médico para esa fecha
$disponibilidad = select(
    "cita
     INNER JOIN disponibilidad_horaria ON cita.id_disponibilidad_horaria = disponibilidad_horaria.id 
     INNER JOIN medico ON medico.id = disponibilidad_horaria.id_medico",
    "cita.id AS id_cita, cita.estado, cita.fecha_cita, disponibilidad_horaria.hora_llegada, disponibilidad_horaria.hora_finalizacion, medico.horario_atencion",
    "disponibilidad_horaria.id_medico = ? AND cita.fecha_cita = ? AND cita.estado != 'cancelado'",
    [$id_medico, $fecha],
    "is"
);
print_r($disponibilidad);

// Si no hay registros, asumimos jornada por defecto
$horario_atencion = $disponibilidad[0]['horario_atencion'] ?? 'dia';

// Definir rangos según jornada
if ($horario_atencion === "dia") {
    $horaInicio = "08:00:00";
    $horaFin = "12:00:00";
} else {
    $horaInicio = "14:00:00";
    $horaFin = "18:00:00";
}

$inicio = new DateTime($horaInicio);
$fin = new DateTime($horaFin);
$intervalo = new DateInterval("PT1H");
$rango = new DatePeriod($inicio, $intervalo, $fin);

// Construir arreglo con las horas ocupadas (excepto la cita actual)
$ocupadas = [];
foreach ($disponibilidad as $fila) {
    if ($fila['id_cita'] != $id_cita_actual) { // Ignorar la cita actual
        $ocupadas[] = [
            'inicio' => $fila['hora_llegada'],
            'fin' => $fila['hora_finalizacion']
        ];
    }
}

// Generar las opciones del select
$options = "";

foreach ($rango as $hora) {
    $horaStr = $hora->format("H:i:s");
    $horaFinal = $hora->add($intervalo)->format("H:i:s");

    $ocupado = false;
    foreach ($ocupadas as $o) {
        if (
            ($horaStr >= $o['inicio'] && $horaStr < $o['fin']) ||
            ($horaFinal > $o['inicio'] && $horaFinal <= $o['fin'])
        ) {
            $ocupado = true;
            break;
        }
    }

    // Si no está ocupado, agregarlo
    if (!$ocupado) {
        $options .= "<option value='$horaStr|$horaFinal'>$horaStr - $horaFinal</option>";
    }
}

// Si estamos editando, asegurarse de incluir la hora actual aunque esté ocupada
if ($id_cita_actual > 0) {
    $cita_actual = select(
        "cita INNER JOIN disponibilidad_horaria ON cita.id_disponibilidad_horaria = disponibilidad_horaria.id",
        "disponibilidad_horaria.hora_llegada, disponibilidad_horaria.hora_finalizacion",
        "cita.id = ?",
        [$id_cita_actual],
        "i"
    );

    if (!empty($cita_actual)) {
        $h_inicio = $cita_actual[0]['hora_llegada'];
        $h_fin = $cita_actual[0]['hora_finalizacion'];

        // Verificar si ya existe en las opciones
        if (strpos($options, "$h_inicio|$h_fin") === false) {
            $options = "<option value='$h_inicio|$h_fin'>$h_inicio - $h_fin</option>" . $options;
        }
    }
}

echo $options;
