<?php
session_start();
require_once "../php/rutas.php";
require_once "../php/crud.php";

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
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Citas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <?php include 'nav.php'; ?>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Gestión de Citas</h1>
                </div>

                <!-- FILTROS -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header text-white">
                        <h5 class="mb-0">Filtrar Citas</h5>
                    </div>
                    <div class="card-body">
                        <form method="post" class="row g-3">

                            <div class="col-md-2">
                                <label class="form-label fw-bold">N° Trámite</label>
                                <input type="text" name="numero_tramite" placeholder="Número Tramite" class="form-control" value="<?= htmlspecialchars($filtros['numero_tramite']); ?>">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-bold">Fecha</label>
                                <input type="date" name="fecha" placeholder="Fecha" class="form-control" value="<?= htmlspecialchars($filtros['fecha']); ?>">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Médico</label>
                                <input type="text" name="medico" placeholder="Nombre del Medico" class="form-control" value="<?= htmlspecialchars($filtros['medico']); ?>">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Paciente</label>
                                <input type="text" name="paciente" placeholder="Nombre del Paciente" class="form-control" value="<?= htmlspecialchars($filtros['paciente']); ?>">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Prioridad</label>
                                <select name="prioridad" class="form-select">
                                    <option value="">Todas</option>
                                    <option value="baja" <?= $filtros['prioridad'] == 'baja' ? 'selected' : ''; ?>>Baja</option>
                                    <option value="moderada" <?= $filtros['prioridad'] == 'moderada' ? 'selected' : ''; ?>>Moderada</option>
                                    <option value="alta" <?= $filtros['prioridad'] == 'alta' ? 'selected' : ''; ?>>Alta</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Estado</label>
                                <select name="estado" class="form-select">
                                    <option value="">Todos</option>
                                    <option value="pendiente" <?= $filtros['estado'] == 'pendiente' ? 'selected' : ''; ?>>Pendiente</option>
                                    <option value="cancelado" <?= $filtros['estado'] == 'cancelado' ? 'selected' : ''; ?>>Cancelado</option>
                                    <option value="inasistencia" <?= $filtros['estado'] == 'inasistencia' ? 'selected' : ''; ?>>Inasistencia</option>
                                    <option value="completado" <?= $filtros['estado'] == 'completado' ? 'selected' : ''; ?>>Completado</option>
                                </select>
                            </div>
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <div class="col-md-1 d-flex align-items-end">
                                    <button type="submit" name="filtrar" class="btn btn-primary w-100">Filtrar</button>
                                </div>
                                <div class="col-md-1 d-flex align-items-end">
                                    <button type="submit" name="limpiar" class="btn btn-secondary w-100">Limpiar</button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>

                <!-- TABLA -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Lista de Citas</h5>
                        <a href="../pages/crearCita.php" class="btn btn-primary btn-sm">
                            <i class="bi bi-plus-circle"></i> Nueva Cita
                        </a>
                    </div>
                    <div class="card-body">
                        <?php if (count($citas) > 0): ?>
                            <div class="table-responsive">
                                <table class="table table-striped align-middle text-center">
                                    <thead class="table-light">
                                        <tr>
                                            <th>N° Trámite</th>
                                            <th>Paciente</th>
                                            <th>Médico</th>
                                            <th>Prioridad</th>
                                            <th>Estado</th>
                                            <th>Fecha</th>
                                            <th>Jornada</th>
                                            <th>Hora</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($citas as $cita): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($cita['numero_tramite']); ?></td>
                                                <td><?= htmlspecialchars($cita['nombre_paciente']); ?></td>
                                                <td><?= htmlspecialchars($cita['nombre_medico']); ?></td>
                                                <td class="text-uppercase"><?= htmlspecialchars($cita['prioridad']); ?></td>
                                                <td>
                                                    <span class=" text-uppercase badge bg-<?=
                                                                                            match ($cita['estado']) {
                                                                                                'pendiente' => 'warning',
                                                                                                'completado' => 'success',
                                                                                                'cancelado' => 'danger',
                                                                                                'inasistencia' => 'secondary',
                                                                                                default => 'light'
                                                                                            };
                                                                                            ?>"><?= htmlspecialchars($cita['estado']); ?></span>
                                                </td>
                                                <td><?= htmlspecialchars($cita['fecha_cita']); ?></td>
                                                <td>
                                                    <?php
                                                    $jornada = strtolower($cita['horario_atencion']);
                                                    echo $jornada === 'dia' ? 'Mañana' : ucfirst(htmlspecialchars($cita['horario_atencion']));
                                                    ?>
                                                </td>
                                                </td>
                                                <td><?= $cita['hora_llegada'] . " - " . $cita['hora_finalizacion']; ?></td>
                                                <td class="text-center">
                                                    <?php if ($cita['estado'] != 'cancelado'): ?>
                                                        <a
                                                            href="<?= htmlspecialchars($rutaCitasPHP) ?>?accion=modificarCita&id=<?= $cita['id_cita'] ?>"
                                                            class="btn btn-sm btn-outline-primary">
                                                            <i class="bi bi-pencil-square"></i> Editar
                                                        </a>
                                                        <a
                                                            href="<?= htmlspecialchars($rutaCitasPHP) ?>?accion=deleteCita&id=<?= $cita['id_cita'] ?>"
                                                            class="btn btn-sm btn-outline-danger"
                                                            onclick="return confirm('¿Seguro que deseas eliminar esta cita?')">
                                                            <i class="bi bi-trash"></i> Eliminar
                                                        </a>
                                                    <?php endif; ?>
                                                </td>
                                                
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <p class="text-muted">No se encontraron citas con los filtros aplicados.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>

</html>