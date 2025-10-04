<?php
session_start();
require_once "../php/rutas.php";
require_once "../php/getOtenerConsecutivoCita.php";

$consecutivo = obtenerConsecutivo($_SESSION['id']);
$numero_tramite_auto = generarNumeroTramite($consecutivo);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitar Cita</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
    <script src="../js/validacionCita.js"></script>
</head>

<body>
    <!-- 
FALTA

Estado: 'pendiente','cancelado','inasistencia','completado'
disponibilidad_horaria: 
prioridad: 'baja','moderada','alta'

jornada -> No va, es la DISPONIBILIDAD HORARIA     
    -->
    <div class="container-fluid">
        <div class="row">
            <!-- nav -->
            <?php include 'nav.php'; ?>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Solicitar Nueva Cita</h1>
                </div>

                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>

                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Información de la Cita</h5>
                    </div>
                    <div class="card-body">
                        <!-- Mostrar número de trámite generado automáticamente -->
                        <div class="alert alert-info">
                            <strong>Número de Trámite Asignado:</strong>
                            <span class="badge bg-primary fs-6"><?php echo $numero_tramite_auto; ?></span>
                            <br><small>Este número se genera automáticamente</small>
                        </div>

                        <form id="formRegistro" action=<?php echo $rutaCitasPHP ?> method="POST" class="row g-3">
                            <input type="hidden" name="accion" value="insertar">
                            <input type="hidden" id="pagina" value="cita">
                            <input type="hidden" name="numero_tramite" value=<?php echo $numero_tramite_auto ?>>
                            <div class="mb-3">

                                <label for="cargo" class="form-label">Cargo</label>
                                <select class="form-select" id="cargo" name="id_cargo">
                                    <option value="">-- Selecciona una cargo --</option>
                                </select>
                                <!-- <input type="text" class="form-control" id="tipo_cita" name="tipo_cita"
                                placeholder="Ej: Consulta general, Examen médico, Control rutinario" required> -->
                            </div>
                            <div class="mb-3">

                                <label for="medico" class="form-label">Medico</label>
                                <select class="form-select" id="medico" name="id_medico">
                                    <option value="">-- Selecciona una medico --</option>
                                </select>
                                <!-- <input type="text" class="form-control" id="tipo_cita" name="tipo_cita"
                                placeholder="Ej: Consulta general, Examen médico, Control rutinario" required> -->
                            </div>

                            <div class="mb-3" id="jornada-container" style="display:block;">
                                <label for="jornada" class="form-label">Disponibilidad Horaria</label>
                                <label id="jornada" name="jornada" class="form-label text-danger fw-bold"></label>
                                <div class="row">
                                    <div class="col-md-6">

                                        <input type="date" class="form-control" id="fecha" name="fecha">
                                    </div>

                                    <div class="col-md-6">

                                        <select class="form-select" id="horario" name="horarios_disponible">
                                            <option value="">-- Selecciona una horario --</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="prioridad" class="form-label">Prioridad <span class="text-danger">*</span></label>
                                <select class="form-select" id="prioridad" name="prioridad" required>
                                    <option value="">Seleccionar la prioidad...</option>
                                    <option value="baja">Baja</option>
                                    <option value="moderada">Moderada</option>
                                    <option value="alta">Alta</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="estado" class="form-label">Estado <span class="text-danger">*</span></label>
                                <select class="form-select" id="estado" name="estado" required>
                                    <option value="">Seleccionar el estado...</option>
                                    <option value="pendiente">Pendiente</option>
                                    <option value="cancelado">Cancelado</option>
                                    <option value="inasistencia">Inasistencia</option>
                                    <option value="completado">Completado</option>
                                </select>
                            </div>

                            <!-- Campo oculto para el número de trámite (se genera automáticamente) -->
                            <input type="hidden" name="numero_tramite_auto" value="<?php echo $numero_tramite_auto; ?>">

                            <div class="alert alert-warning">
                                <small>✅ El número de trámite se genera automáticamente</small>
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href=<?php echo $rutaConsultarCitas; ?> class="btn btn-secondary me-md-2">Cancelar</a>
                                <button type="submit" class="btn btn-primary">Solicitar Cita</button>
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