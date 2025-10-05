<?php
session_start();
require_once "../php/rutas.php";
require_once "../php/crud.php";
require_once "../php/getDasboard.php";
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Paciente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- nav -->
            <?php include 'nav.php'; ?>

            <!-- Contenido principal -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Bienvenido, <?php echo $nombre; ?></h1>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="card text-white bg-primary card-stat">
                            <div class="card-body text-center">
                                <h5 class="card-title text-uppercase">Citas Pendientes</h5>
                                <h2 class="card-text fw-bold"><?php echo !empty($countCitasPendientes) ? $countCitasPendientes : 0; ?></h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-white bg-success card-stat">
                            <div class="card-body text-center">
                                <h5 class="card-title text-uppercase">Citas Confirmadas</h5>
                                <h2 class="card-text fw-bold"><?php echo !empty($countCitasConfirmadas) ? $countCitasConfirmadas : 0; ?></h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-white bg-danger card-stat">
                            <div class="card-body text-center">
                                <h5 class="card-title text-uppercase">Citas Cancelado</h5>
                                <h2 class="card-text fw-bold"><?php echo !empty($countCitasCancelado) ? $countCitasCancelado : 0; ?></h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-white bg-warning card-stat">
                            <div class="card-body text-center">
                                <h5 class="card-title text-uppercase">Total de Citas</h5>
                                <h2 class="card-text fw-bold"><?php echo !empty($countCitasTotal) ? $countCitasTotal : 0; ?></h2>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card my-4">
                    <div class="card-header text-white">
                        <h5 class="mb-0">Últimas Citas</h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($citas)) : ?>
                            <div class="table-responsive">
                                <table class="table table-striped align-middle text-center">
                                    <thead>
                                        <tr>
                                            <th>N° Trámite</th>
                                            <th>Medico</th>
                                            <th>Prioridad</th>
                                            <th>Jornada</th>
                                            <th>Fecha de la cita</th>
                                            <th>Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($citas as $cita) : ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($cita['numero_tramite']); ?></td>
                                                <td><?php echo htmlspecialchars($cita['nombre_medico']); ?></td>
                                                <td class="text-uppercase"><?php echo htmlspecialchars($cita['prioridad']); ?></td>
                                                <td class="text-uppercase">
                                                    <?php
                                                    $jornada = strtolower($cita['horario_atencion']);
                                                    echo $jornada === 'dia' ? 'Mañana' : ucfirst(htmlspecialchars($cita['horario_atencion']));
                                                    ?>
                                                </td>
                                                <td><?php echo $cita['hora_llegada'] . " - " . $cita['hora_finalizacion']; ?></td>
                                                <td>
                                                    <?php
                                                    $estado = strtolower($cita['estado']);
                                                    $color = match ($estado) {
                                                        'pendiente' => 'warning',
                                                        'completado' => 'success',
                                                        'cancelado' => 'danger',
                                                        'inasistencia' => 'secondary',
                                                        default => 'light'
                                                    };
                                                    ?>
                                                    <span class="text-uppercase  badge bg-<?php echo $color; ?>"><?= htmlspecialchars($cita['estado']); ?></span>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else : ?>
                            <p class="text-muted text-center my-3">No tienes citas registradas.</p>
                        <?php endif; ?>
                    </div>
                </div>
        </div>
        </main>
    </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>