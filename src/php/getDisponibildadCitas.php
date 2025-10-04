<?php
require_once 'crud.php';

$id_medico = isset($_GET['id_medico']) ? intval($_GET['id_medico']) : 0;
$fecha = isset($_GET['fecha']) ? trim($_GET['fecha']) : '';

// Obtener las citas y horarios del médico para esa fecha
$disponibilidad = select(
    "cita
     INNER JOIN disponibilidad_horaria ON cita.id_disponibilidad_horaria = disponibilidad_horaria.id 
     INNER JOIN medico ON medico.id = disponibilidad_horaria.id_medico",
    "cita.fecha_cita, disponibilidad_horaria.hora_llegada, disponibilidad_horaria.hora_finalizacion, medico.horario_atencion",
    "disponibilidad_horaria.id_medico = ? AND cita.fecha_cita = ?",
    [$id_medico, $fecha],
    "is"
);

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

// Crear objetos DateTime
$inicio = new DateTime($horaInicio);
$fin = new DateTime($horaFin);

// Intervalo de 1 hora
$intervalo = new DateInterval("PT1H");

// Generar rango base
$rango = new DatePeriod($inicio, $intervalo, $fin);

// Construir un arreglo con las horas ocupadas (de la consulta SQL)
$ocupadas = [];
foreach ($disponibilidad as $fila) {
    $ocupadas[] = [
        'inicio' => $fila['hora_llegada'],
        'fin' => $fila['hora_finalizacion']
    ];
}

// Generar las opciones del select
$options = "";

foreach ($rango as $hora) {
    $horaStr = $hora->format("H:i:s");
    $horaFinal = $hora->add($intervalo)->format("H:i:s");

    // Verificar si este rango se cruza con alguno ocupado
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

    // Si no está ocupado, agregarlo como opción
    if (!$ocupado) {
        $options .= "<option value='$horaStr|$horaFinal'>$horaStr - $horaFinal</option>";
    }
}

echo $options;
