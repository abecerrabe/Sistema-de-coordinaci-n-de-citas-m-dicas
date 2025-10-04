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
                            <input type="hidden" name="numero_tramite" value=<?php echo $numero_tramite_auto ?>>
                            <div class="mb-3">
                                <label for="tipo_cita" class="form-label">Tipo de Solicitud <span class="text-danger">*</span></label>
                                <select class="form-select" id="tipo_cita" name="tipo_cita" required>
                                    <option value="">Seleccionar tipo de solicitud...</option>
                                    <option value="Consulta general">Consulta general</option>
                                    <option value="Examen médico">Examen médico</option>
                                    <option value="Médico especialista">Médico especialista</option>
                                </select>
                                <!-- <input type="text" class="form-control" id="tipo_cita" name="tipo_cita"
                                placeholder="Ej: Consulta general, Examen médico, Control rutinario" required> -->
                            </div>

                            <div class="mb-3">
                                <label for="jornada" class="form-label">Disponibilidad Horaria <span class="text-danger">*</span></label>
                                <select class="form-select" id="jornada" name="jornada" required>
                                    <option value="">Seleccionar jornada...</option>
                                    <option value="Mañana">Mañana (8:00 AM - 12:00 PM)</option>
                                    <option value="Tarde">Tarde (2:00 PM - 6:00 PM)</option>
                                </select>
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
                                    <option value="baja">Baja</option>
                                    <option value="moderada">Moderada</option>
                                    <option value="alta">Alta</option>
                                    <option value="alta">Alta</option>
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