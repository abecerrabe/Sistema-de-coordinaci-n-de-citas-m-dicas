<?php
session_start();
require_once "../php/rutas.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Cargos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body class="bg-light d-flex align-items-center justify-content-center vh-100">

    <div class="card shadow-lg w-100" style="max-width: 700px;">
        <div class="card-body p-5">
            <h1 class="text-center text-primary mb-4">Registro de Cargos</h1>
            <form id="formRegistro" action=<?php echo $rutaCargosPHP ?> method="POST" class="row g-3">
                <?php if (isset($_GET['accion']) && $_GET['accion'] == 'crearCargo'): unset($_SESSION['dataTemp']) ?>
                    <input type="hidden" name="accionGestionar" value="crearCargo">
                <?php endif; ?>
                <!-- Nombre -->
                <div class="col-md-12">
                    <label for="nombre" class="form-label">Nombres</label>
                    <input type="text" class="form-control" id="nombre" name="nombre"
                        placeholder="Nombre del cargo"
                        value="<?php echo $_SESSION['dataTemp']['nombre_cargo'] ?? ''; ?>" required>
                </div>
                <!-- Descripcion de cargos -->
                <div class="col-md-12">
                    <label for="descripcion_cargo" class="form-label">Descripción del cargo</label>
                    <textarea class="form-control" name="descripcion_cargo" id="descripcion_cargo" placeholder="Descripción del cargo" required><?php echo $_SESSION['dataTemp']['descripcion_cargo'] ?? null; ?></textarea>
                </div>
                <!-- Estado (solo en edición) -->
                <?php if (isset($_GET["id"])): ?>
                    <div class="col-12">
                        <label for="estado" class="form-label">Estado</label>
                        <select class="form-select" id="estado" name="estado" required>
                            <option value="">-- Selecciona un estado --</option>
                            <option value="activo" <?php echo ($_SESSION['dataTemp']['estados'] ?? '') === 'activo' ? 'selected' : ''; ?>>Activo</option>
                            <option value="inactivo" <?php echo ($_SESSION['dataTemp']['estados'] ?? '') === 'inactivo' ? 'selected' : ''; ?>>Inactivo</option>
                        </select>
                    </div>
                <?php endif; ?>
                <!-- Botón -->
                <?php $esEdicion = (isset($_GET["id"]) && $_GET["id"] !== "Registrar"); ?>
                <input type="hidden" name="accion" value="<?php echo $esEdicion ? 'modificar' : 'insertar'; ?>">
                <input type="hidden" name="id" value="<?php echo $esEdicion ? ($_SESSION['dataTemp']['id'] ?? '') : ''; ?>">

                <div class="col-12">
                    <button type="submit" class="btn btn-primary w-100">
                        <?php echo $esEdicion ? 'Actualizar' : 'Regístrate'; ?>
                    </button>
                </div>
            </form>
        </div>
    </div>

</body>

</html>