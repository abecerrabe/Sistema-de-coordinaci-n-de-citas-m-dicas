<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Restablecer Contraseña - Sistema de Citas Médicas</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body class="bg-light d-flex align-items-center justify-content-center vh-100">
  <div class="card shadow-lg w-100" style="max-width: 400px;">
    <div class="card-body p-5">
      <h1 class="h4 fw-bold text-center text-primary mb-4">Restablecer Contraseña</h1>

      <?php
      require_once '../php/controller/PasswordResetController.php';

      $token = $_GET['token'] ?? '';
      $controller = new PasswordResetController();
      $validToken = $controller->validateToken($token);

      if (!$token) {
        echo '<div class="alert alert-danger text-center">No se proporcionó un token de restablecimiento válido.</div>';
        echo '<div class="text-center"><a href="reset_password_request.php" class="link-primary">Solicitar nuevo enlace</a></div>';
      } elseif (!$validToken) {
        echo '<div class="alert alert-danger text-center">El enlace de restablecimiento es inválido o ha expirado.</div>';
        echo '<div class="text-center"><a href="reset_password_request.php" class="link-primary">Solicitar nuevo enlace</a></div>';
      } else {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
          $password = $_POST['password'] ?? '';
          $confirmPassword = $_POST['confirm_password'] ?? '';

          $result = $controller->resetPassword($token, $password, $confirmPassword);

          if ($result['success']) {
            echo '<div class="alert alert-success text-center">' . htmlspecialchars($result['message']) . '</div>';
            echo '<div class="text-center"><a href="../../index.php" class="btn btn-primary">Iniciar Sesión</a></div>';
          } else {
            echo '<div class="alert alert-danger text-center">' . htmlspecialchars($result['message']) . '</div>';
          }
        } else {
      ?>
          <form method="POST" action="">
            <div class="mb-3">
              <label for="password" class="form-label">Nueva Contraseña</label>
              <div class="input-group">
                <input type="password" class="form-control" id="password" name="password" placeholder="Nueva contraseña" required>
                <button type="button" class="btn btn-outline-secondary" onclick="toggleNewPassword()">
                  <i id="toggleIcon" class="fa-solid fa-eye-slash"></i>
                </button>
              </div>
            </div>

            <div class="mb-3">
              <label for="confirm_password" class="form-label">Confirmar Nueva Contraseña</label>
              <div class="input-group">
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirmar contraseña" required>
                <button type="button" class="btn btn-outline-secondary" onclick="toggleCongfirPassword()">
                  <i id="toggleIcon" class="fa-solid fa-eye-slash"></i>
                </button>
              </div>              
            </div>

            <div class="d-grid">
              <button type="submit" class="btn btn-primary">
                Restablecer Contraseña
              </button>
            </div>
          </form>

          <div class="text-center mt-3">
            <a href="../../index.php" class="link-primary">Volver al Inicio</a>
          </div>
      <?php
        }
      }
      ?>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="../js/validacionRol.js"></script>
</body>

</html>