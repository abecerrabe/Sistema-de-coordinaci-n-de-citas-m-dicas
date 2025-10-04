<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sistema de Citas Médicas</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="src/css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <script src="src/js/validacionRol.js"></script>
</head>

<body class="bg-light">
  <div class="container">
    <div class="row justify-content-center align-items-center min-vh-100">
      <div class="col-md-6 col-lg-4">
        <div class="card shadow">
          <div class="card-body p-5">
            <h1 class="h4 fw-bold text-center text-primary mb-4">Iniciar Sesión</h1>

            <!-- Mensaje de error -->
            <?php if (isset($_SESSION["error"])): ?>
              <div class="alert alert-danger text-center">
                <?php echo $_SESSION["error"]; ?>
              </div>
              <?php unset($_SESSION["error"]); ?>
            <?php endif; ?>

            <!-- Formulario -->
            <form action="src/php/usuario.php" method="post">
              <input type="hidden" name="accion" value="login">

              <!-- Usuario -->
              <div class="mb-3">
                <label for="usuario" class="form-label">Usuario</label>
                <input type="email" class="form-control" id="usuario" name="usuario"
                  placeholder="Correo electrónico" required
                  value="<?php echo $_SESSION['correoTemp'] ?? ''; ?>">
              </div>

              <!-- Contraseña -->
              <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <div class="input-group">
                  <input type="password" class="form-control" id="contrasena" name="password"
                    placeholder="Contraseña" required>
                  <button type="button" class="btn btn-outline-secondary" onclick="togglePassword()">
                    <i id="toggleIcon" class="fa-solid fa-eye-slash"></i>
                  </button>
                </div>
              </div>

              <!-- Botón -->
              <div class="d-grid">
                <button type="submit" class="btn btn-primary">
                  Iniciar Sesión
                </button>
              </div>
            </form>

            <!-- Enlace de registro -->
            <p class="text-center mt-4">
              ¿No tienes cuenta?
              <a href="src/pages/registroUsuario.php" class="link-primary">Regístrate</a>
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
