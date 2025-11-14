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

      <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
        <?php
        require_once '../php/controller/PasswordResetController.php';

        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $message = [
                'success' => false,
                'message' => 'Por favor, introduce un correo electrónico válido.'
            ];
        } else {
            $controller = new PasswordResetController();
            $message = $controller->requestReset($email);
        }
        ?>
        <?php if ($message['success']): ?>
          <div class="alert alert-success text-center">
            <?php echo htmlspecialchars($message['message']); ?>
          </div>
        <?php else: ?>
          <div class="alert alert-danger text-center">
            <?php echo htmlspecialchars($message['message']); ?>
          </div>
        <?php endif; ?>
      <?php endif; ?>

      <form method="POST" action="">
        <div class="mb-3">
          <label for="email" class="form-label">Correo Electrónico</label>
          <input type="email" class="form-control" id="email" name="email" placeholder="Correo electrónico" required>
        </div>

        <div class="d-grid">
          <button type="submit" class="btn btn-primary">
            Enviar Enlace de Restablecimiento
          </button>
        </div>
      </form>

      <!-- Enlaces de navegación -->
      <div class="text-center mt-3">
        <p>
          ¿Ya tienes cuenta?
          <a href="../../index.php" class="link-primary">Iniciar Sesión</a>
        </p>
        <p>
          ¿No tienes cuenta?
          <a href="registroUsuario.php" class="link-primary">Regístrate</a>
        </p>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>