<?php

$idUsuario = $_SESSION['id'] ?? null;
$rol = $_SESSION['tipo_permiso'] ?? null;

// --- Gestión de filtros persistentes en sesión ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Si presionó "Limpiar", vaciar filtros
    if (isset($_POST['limpiar'])) {
        unset($_SESSION['filtros_citas']);
        header("Location: consultarCitas.php");
        exit;
    }

    // Guardar filtros en sesión
    $_SESSION['filtros_citas'] = [
        'numero_tramite' => trim($_POST['numero_tramite'] ?? ''),
        'fecha'          => trim($_POST['fecha'] ?? ''),
        'medico'         => trim($_POST['medico'] ?? ''),
        'paciente'       => trim($_POST['paciente'] ?? ''),
        'prioridad'      => trim($_POST['prioridad'] ?? ''),
        'estado'         => trim($_POST['estado'] ?? '')
    ];
}

// --- Recuperar filtros activos ---
$filtros = $_SESSION['filtros_citas'] ?? [
    'numero_tramite' => '',
    'fecha' => '',
    'medico' => '',
    'paciente' => '',
    'prioridad' => '',
    'estado' => ''
];

// --- Construcción dinámica de la consulta ---
$where = [];
$params = [];
$types  = "";

// Filtros básicos
if ($filtros['numero_tramite']) {
    $where[] = "cita.numero_tramite LIKE ?";
    $params[] = "%{$filtros['numero_tramite']}%";
    $types .= "s";
}
if ($filtros['fecha']) {
    $where[] = "cita.fecha_cita = ?";
    $params[] = $filtros['fecha'];
    $types .= "s";
}
if ($filtros['medico']) {
    $where[] = "usuarioMedico.nombre_completo LIKE ?";
    $params[] = "%{$filtros['medico']}%";
    $types .= "s";
}
if ($filtros['paciente']) {
    $where[] = "usuarioPaciente.nombre_completo LIKE ?";
    $params[] = "%{$filtros['paciente']}%";
    $types .= "s";
}
if ($filtros['prioridad']) {
    $where[] = "cita.prioridad = ?";
    $params[] = $filtros['prioridad'];
    $types .= "s";
}
if ($filtros['estado']) {
    $where[] = "cita.estado = ?";
    $params[] = $filtros['estado'];
    $types .= "s";
}

// --- Filtro por rol ---
switch ($rol) {
    case 'paciente':
        $where[] = "usuarioPaciente.id = ?";
        $params[] = $idUsuario;
        $types .= "i";
        break;
    case 'medico':
        $where[] = "usuarioMedico.id = ?";
        $params[] = $idUsuario;
        $types .= "i";
        break;
    case 'administrador':
    default:
        // No se aplica filtro adicional
        break;
}

// --- Generar condición final ---
$condicion = count($where) ? implode(" AND ", $where) : "1=1";

// --- Ejecutar consulta segura ---
$citas = select(
    "cita
    INNER JOIN disponibilidad_horaria AS disponibilidad ON cita.id_disponibilidad_horaria = disponibilidad.id
    INNER JOIN medico ON disponibilidad.id_medico = medico.id
    INNER JOIN usuario AS usuarioMedico ON medico.id_usuario = usuarioMedico.id
    INNER JOIN usuario AS usuarioPaciente ON cita.id_paciente = usuarioPaciente.id",
    "
    cita.id AS id_cita,
    cita.numero_tramite,
    cita.fecha_cita,
    cita.prioridad,
    cita.estado,
    usuarioPaciente.nombre_completo AS nombre_paciente,
    usuarioPaciente.correo_electronico AS correo_paciente,
    usuarioPaciente.telefono AS telefono_paciente,
    disponibilidad.hora_llegada,
    disponibilidad.hora_finalizacion,
    usuarioMedico.nombre_completo AS nombre_medico,
    medico.horario_atencion
    ",
    $condicion,
    $params,
    $types
);
?>