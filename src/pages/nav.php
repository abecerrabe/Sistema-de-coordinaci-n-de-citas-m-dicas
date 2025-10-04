<?php

require_once "../php/rutas.php";

$correo = $_SESSION['usuario'];
$nombre = $_SESSION['nombre_completo'];
$estado = $_SESSION['estado'];

$idUsuario = $_SESSION['id'];
$rol = $_SESSION['tipo_permiso'];

verificarAutenticacion($correo, $rutaInicio, $estado);

// Obtener el nombre del archivo actual
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<!-- Bootstrap CSS (agrega esto en tu layout principal si no lo tienes) -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<nav class="col-md-3 col-lg-2 d-md-block sidebar collapse bg-dark text-white vh-100">
    <div class="position-sticky pt-3">
        <h4 class="text-white text-center">Sistema de Citas</h4>
        <p class="text-white text-center"><?php echo $nombre ?></p>

        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link text-white <?php echo ($currentPage == $paginaDashboard) ? 'active' : ''; ?>" href="dashboard.php">
                    <i class="bi bi-speedometer2"></i>
                    Inicio
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white <?php echo ($currentPage == $paginaCrearCita) ? 'active' : ''; ?>" href=<?php echo $rutaCrearCitas ?>>
                    <i class="bi bi-calendar-plus"></i>
                    Solicitar Cita
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white <?php echo ($currentPage == $paginaConsultarCitas) ? 'active' : ''; ?>" href=<?php echo $rutaConsultarCitas ?>>
                    <i class="bi bi-calendar-check"></i>
                    Mis Citas
                </a>
            </li>
            <?php /* if ($rol === 'admin'):  */?>
                <li class="nav-item">
                    <a class="nav-link text-white <?php echo ($currentPage == $paginaGestionarUsuario) ? 'active' : ''; ?>" href="../pages/gestionUsuarios.php">
                        <i class="bi bi-person-fill"></i>
                        Gestionar usuarios
                    </a>
                </li>
            <?php /* endif; */ ?>
            <li class="nav-item">
                <a class="nav-link text-white" href=<?php echo $rutalogoutPHP ?>>
                    <i class="bi bi-box-arrow-right"></i>
                    Cerrar Sesión
                </a>
            </li>
        </ul>
    </div>
</nav>