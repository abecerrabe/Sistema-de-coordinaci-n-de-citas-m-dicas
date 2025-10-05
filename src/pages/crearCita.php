<?php
session_start();
require_once "../php/rutas.php";
require_once "../php/getOtenerConsecutivoCita.php";

if (!isset($_GET["id"])) {
    unset($_SESSION["dataTempCitas"]);
}
$consecutivo = obtenerConsecutivo($_SESSION['id']);
$numero_tramite_auto = isset($_SESSION["dataTempCitas"])  ? $_SESSION["dataTempCitas"]['numero_tramite'] : generarNumeroTramite($consecutivo);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitar Nueva Cita</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
    <script src="../js/validacionCita.js"></script>
    <script>
        const dataTempCitas = <?= isset($_SESSION["dataTempCitas"]) ? json_encode($_SESSION["dataTempCitas"]) : 'null' ?>;
    </script>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <?php include 'nav.php'; ?>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Solicitar Nueva Cita</h1>
                </div>

                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?= $error; ?></div>
                <?php endif; ?>

                <div class="card shadow-sm mb-4">
                    <div class="card-header text-white">
                        <h5 class="mb-0">Información de la Cita</h5>
                    </div>
                    <div class="card-body">
                        <!-- Número de trámite generado automáticamente -->
                        <div class="alert alert-info py-2 mb-4">
                            <strong>N° de Trámite:</strong>
                            <span class="badge bg-primary fs-6"><?= $numero_tramite_auto; ?></span>
                            <small class="d-block mt-1 text-muted">Este número se genera automáticamente.</small>
                        </div>

                        <form id="formRegistro" action="<?= $rutaCitasPHP ?>" method="POST" class="row g-3">
                            
                            <input type="hidden" id="pagina" value="cita">
                            <input type="hidden" name="numero_tramite" value="<?= $numero_tramite_auto ?>">
                            <div class="alert alert-warning mt-3 py-2">
                                <small>✅ El número de trámite se genera automáticamente y no se puede modificar.</small>
                            </div>
                            <!-- Cargo y Médico -->
                            <div class="col-md-6">
                                <label for="cargo" class="form-label fw-bold">Cargo</label>
                                <select class="form-select" id="cargo" name="id_cargo" required>
                                    <option value="">-- Selecciona un cargo --</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="medico" class="form-label fw-bold">Médico</label>
                                <select class="form-select" id="medico" name="id_medico" required>
                                    <option value="">-- Selecciona un médico --</option>
                                </select>
                            </div>

                            <!-- Disponibilidad -->
                            <div class="col-12" id="jornada-container" style="display:none;">
                                <label for="jornada" class="form-label fw-bold">Disponibilidad Horaria: </label>
                                <label id="jornada" class="text-danger fw-bold mb-2"></label>
                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <input type="date" class="form-control" id="fecha" name="fecha" required>
                                    </div>
                                    <div class="col-md-6">
                                        <select class="form-select" id="horario" name="horarios_disponible" required>
                                            <option value="">-- Selecciona un horario --</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Prioridad y Estado -->
                            <div class="col-md-6">
                                <label for="prioridad" class="form-label fw-bold">Prioridad <span class="text-danger">*</span></label>
                                <select class="form-select" id="prioridad" name="prioridad" required>
                                    <option value="">Selecciona la prioridad...</option>
                                    <option value="baja">Baja</option>
                                    <option value="moderada">Moderada</option>
                                    <option value="alta">Alta</option>
                                </select>
                            </div>
                            <?php if (isset($_SESSION["dataTempCitas"])): ?>
                                <input type="hidden" name="id_cita" value=<?=$_GET['id'];?> />
                                <input type="hidden" name="id_disponibilidad_horaria" value=<?=$_SESSION["dataTempCitas"]['id_disponibilidad_horaria'];?> />
                                <div class="col-md-6">
                                    <label for="estado" class="form-label fw-bold">Estado <span class="text-danger">*</span></label>
                                    <select class="form-select" id="estado" name="estado" required>
                                        <option value="">Selecciona el estado...</option>
                                        <option value="pendiente">Pendiente</option>
                                        <option value="cancelado">Cancelado</option>
                                        <option value="inasistencia">Inasistencia</option>
                                        <option value="completado">Completado</option>
                                    </select>
                                </div>
                            <?php endif; ?>

                            <input type="hidden" name="accion" value="<?= isset($_SESSION["dataTempCitas"]) ? 'modificar' : 'insertar' ?>">
                            <div class="d-flex justify-content-end gap-2 mt-3">
                                <a href="<?= $rutaConsultarCitas; ?>" class="btn btn-secondary">Cancelar</a>
                                <button type="submit" class="btn btn-primary">
                                    <?= isset($_SESSION["dataTempCitas"]) ? 'Modificar Cita' : 'Solicitar Cita'; ?>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>