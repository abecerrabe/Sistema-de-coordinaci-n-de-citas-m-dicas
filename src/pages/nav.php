<?php

require_once "../php/rutas.php";

// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario']) || !isset($_SESSION['estado']) || $_SESSION['estado'] !== 'activo') {
    header("Location: $rutaInicio");
    exit();
}

$correo = $_SESSION['usuario'] ?? '';
$nombre = $_SESSION['nombre_completo'] ?? '';
$estado = $_SESSION['estado'] ?? '';
$idUsuario = $_SESSION['id'] ?? '';
$rol = $_SESSION['tipo_permiso'] ?? '';

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
            <?php if ($rol === 'paciente'): ?>
            <li class="nav-item">
                <a class="nav-link text-white <?php echo ($currentPage == $paginaCrearCita) ? 'active' : ''; ?>" href=<?php echo $rutaCrearCitas ?>>
                    <i class="bi bi-calendar-plus"></i>
                    Solicitar Cita
                </a>
            </li>            
            <?php endif; ?>
            <li class="nav-item">
                <a class="nav-link text-white <?php echo ($currentPage == $paginaConsultarCitas) ? 'active' : ''; ?>" href=<?php echo $rutaConsultarCitas ?>>
                    <i class="bi bi-calendar-check"></i>
                    Mis Citas
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white <?php echo ($currentPage == $paginaRegistroHistoralMedico) ? 'active' : ''; ?>" href=<?php echo $rutaRegistroHistoralMedico ?>>
                    <i class="bi bi-journal-text"></i>
                    Historial Medico
                </a>
            </li>
            <?php if ($rol === 'medico'): ?>
            <li class="nav-item">
                <a class="nav-link text-white <?php echo ($currentPage == $paginaRegistroDiagnosticoMedico) ? 'active' : ''; ?>" href=<?php echo $rutaRegistroDiagnosticoMedico ?>>
                    <i class="bi bi-pencil-square"></i>
                    Diagnostico Medico
                </a>
            </li>
            <?php endif; ?>
            <li class="nav-item">
                <a class="nav-link text-white" href="<?php echo $rutaUsuarioPHP . '?accion=modificarUsuario&id=' . $idUsuario; ?>" >
                    <i class="bi bi-person-fill"></i>
                    Datos de Usuario
                </a>
            </li>
            
            <?php if ($rol === 'administrador'): ?>
                <li class="nav-item">
                    <a class="nav-link text-white <?php echo ($currentPage == $paginaGestionarUsuario) ? 'active' : ''; ?>" href="../pages/gestionUsuarios.php">
                       <i class="bi bi-people-fill"></i>
                        Gestionar usuarios
                    </a>
                </li>
            <?php endif; ?>
            <li class="nav-item">
                <a class="nav-link text-white" href=<?php echo $rutalogoutPHP ?>>
                    <i class="bi bi-box-arrow-right"></i>
                    Cerrar Sesión
                </a>
            </li>
        </ul>
    </div>
</nav>