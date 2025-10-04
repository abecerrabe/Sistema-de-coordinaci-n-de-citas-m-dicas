<?php 
session_start(); 
require_once "../php/rutas.php";
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registro de Usuario</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../css/style.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <script src="../js/validacionRol.js"></script>
</head>

<body class="bg-light d-flex align-items-center justify-content-center vh-100">

  <div class="card shadow-lg w-100" style="max-width: 700px;">
    <div class="card-body p-5">
      <h1 class="text-center text-primary mb-4">Registro de Usuario</h1>

      <?php if (isset($_SESSION["error"])): ?>
        <div class="alert alert-danger"><?php echo $_SESSION["error"]; ?></div>
        <?php unset($_SESSION["error"]); ?>
      <?php endif; ?>

      <form id="formRegistro" action=<?php echo $rutaUsuarioPHP?> method="POST" class="row g-3">
        <?php if (isset($_GET['accion']) && $_GET['accion'] == 'crear'): unset($_SESSION['dataTemp'])?>
          <input type="hidden" name="accionGestionar" value="crear">
        <?php endif; ?>

        <!-- Cedula -->
        <div class="col-md-6">
          <label for="cedula" class="form-label">Cédula</label>
          <input type="number" class="form-control" id="cedula" name="cedula"
            placeholder="Cédula del usuario"
            value="<?php echo $_SESSION['dataTemp']['numero_cedula'] ?? ''; ?>" required>
        </div>

        <!-- Nombre -->
        <div class="col-md-6">
          <label for="nombre" class="form-label">Nombre</label>
          <input type="text" class="form-control" id="nombre" name="nombre"
            placeholder="Nombre del usuario"
            value="<?php echo $_SESSION['dataTemp']['nombre_completo'] ?? ''; ?>" required>
        </div>

        <!-- Teléfono -->
        <div class="col-md-6">
          <label for="telefono" class="form-label">Teléfono</label>
          <input type="number" class="form-control" id="telefono" name="telefono"
            placeholder="Teléfono del usuario"
            value="<?php echo $_SESSION['dataTemp']['telefono'] ?? ''; ?>" required>
        </div>

        <!-- Correo -->
        <div class="col-md-6">
          <label for="correo" class="form-label">Correo</label>
          <input type="email" class="form-control" id="correo" name="correo"
            placeholder="Correo electrónico"
            value="<?php echo $_SESSION['dataTemp']['correo_electronico'] ?? ''; ?>" required>
        </div>

        <!-- Contraseña -->
        <div class="col-12">
          <label for="contrasena" class="form-label">Contraseña</label>
          <div class="input-group">
            <?php $contrasenaRequired = !(isset($_GET["id"])) ? 'required' : ''; ?>
            <input type="password" class="form-control" id="contrasena" name="contrasena"
              placeholder="Contraseña" <?php echo $contrasenaRequired; ?>>
            <button type="button" class="btn btn-outline-secondary" onclick="togglePassword()">
              <i id="toggleIcon" class="fa-solid fa-eye-slash"></i>
            </button>
          </div>
        </div>

        <!-- Rol -->
        <div class="col-12">
          <label for="rol" class="form-label">Rol</label>
          <select class="form-select" id="rol" name="rol" required>
            <option value="">-- Selecciona un rol --</option>
            <option value="paciente" <?php echo ($_SESSION['dataTemp']['tipo_permiso'] ?? '') === 'paciente' ? 'selected' : 'selected'; ?>>Paciente</option>
            <option value="medico" <?php echo ($_SESSION['dataTemp']['tipo_permiso'] ?? '') === 'medico' ? 'selected' : ''; ?>>Médico</option>
            <option value="administrador" <?php echo ($_SESSION['dataTemp']['tipo_permiso'] ?? '') === 'administrador' ? 'selected' : ''; ?>>Administrador</option>
          </select>
        </div>

        <!-- Estado (solo en edición) -->
        <?php if (isset($_GET["id"])): ?>
          <div class="col-12">
            <label for="estado" class="form-label">Estado</label>
            <select class="form-select" id="estado" name="estado" required>
              <option value="">-- Selecciona un estado --</option>
              <option value="activo" <?php echo ($_SESSION['dataTemp']['estado'] ?? '') === 'activo' ? 'selected' : ''; ?>>Activo</option>
              <option value="inactivo" <?php echo ($_SESSION['dataTemp']['estado'] ?? '') === 'inactivo' ? 'selected' : ''; ?>>Inactivo</option>
            </select>
          </div>
        <?php endif; ?>

        <!-- Especialidad (solo médicos) -->
        <div id="especialidad-container" style="display:none;" class="col-12">
          <label for="especialidad" class="form-label">Cargo</label>
          <select class="form-select" id="especialidad" name="id_cargo">
            <option value="">-- Selecciona una especialidad --</option>
          </select>

          <!-- Horario -->
          <div class="mt-3">
            <label for="horario" class="form-label">Horario de atención</label>
            <select class="form-select" name="horario_atencion">
              <option value="">-- Selecciona un horario --</option>
              <option value="dia" <?php echo ($_SESSION['dataTemp']['horario_atencion'] ?? '') === 'dia' ? 'selected' : ''; ?>>
                Mañana (8:00 am - 12:00 pm)
              </option>
              <option value="tarde" <?php echo ($_SESSION['dataTemp']['horario_atencion'] ?? '') === 'tarde' ? 'selected' : ''; ?>>
              Tarde (2:00 pm - 6:00 pm)
              </option>
            </select>
          </div>
        </div>

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

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
