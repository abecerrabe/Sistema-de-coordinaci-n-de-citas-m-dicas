<?php

$idUsuario = $_SESSION['id'] ?? null;
// Citas pendientes
$consultarCantCitasPendientes = select(
    "cita",
    "COUNT(*) as total",
    "id_paciente = ? AND estado = 'pendiente'",
    [$idUsuario],
    'i'
);

// Citas cancelada
$consultarCantCitasCancelado = select(
    "cita",
    "COUNT(*) as total",
    "id_paciente = ? AND estado = 'cancelado'",
    [$idUsuario],
    'i'
);

// Citas confirmadas
$consultarCantCitasConfirmadas = select(
    "cita",
    "COUNT(*) as total",
    "id_paciente = ? AND estado = 'confirmada'",
    [$idUsuario],
    'i'
);

// Total de citas
$consultarCantCitasTotal = select(
    "cita",
    "COUNT(*) as total",
    "id_paciente = ?",
    [$idUsuario],
    'i'
);

// Citas
$citas = select(
    "cita
    inner join disponibilidad_horaria as disponibilidad on cita.id_disponibilidad_horaria = disponibilidad.id
    inner join medico on disponibilidad.id_medico = medico.id
    inner join usuario on medico.id_usuario = usuario.id
    inner join cargo on medico.id_cargo = cargo.id",
    "
    cita.numero_tramite, cita.prioridad, cita.estado, disponibilidad.hora_llegada, disponibilidad.hora_finalizacion,
    medico.horario_atencion, cargo.nombre_cargo, usuario.nombre_completo as nombre_medico
    ",
    "id_paciente = ? ORDER BY fecha_cita DESC LIMIT 5",
    [$idUsuario] ,
    'i'
);

// Valores para mostrar en las tarjetas
$countCitasPendientes = isset($consultarCantCitasPendientes[0]['total']) ? (int)$consultarCantCitasPendientes[0]['total'] : 0;
$countCitasConfirmadas = isset($consultarCantCitasConfirmadas[0]['total']) ? (int)$consultarCantCitasConfirmadas[0]['total'] : 0;
$countCitasCancelado = isset($consultarCantCitasCancelado[0]['total']) ? (int)$consultarCantCitasCancelado[0]['total'] : 0;
$countCitasTotal = isset($consultarCantCitasTotal[0]['total']) ? (int)$consultarCantCitasTotal[0]['total'] : 0;

$countCitas = isset($countCitas[0]['total']) ? (int)$consultarCantCitas[0]['total'] : 0;

?>