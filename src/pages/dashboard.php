<?php
/* session_start();
require_once 'database.php';

// Verificar credenciales
if ($_POST) {
$usuario = $_POST['usuario'];
$password = md5($_POST['password']); 

$stmt = $pdo->prepare("SELECT * FROM pacientes WHERE usuario = ? AND password = ?");
$stmt->execute([$usuario, $password]);
$paciente = $stmt->fetch();

if ($paciente) {
$_SESSION['paciente_id'] = $paciente['id'];
$_SESSION['paciente_nombre'] = $paciente['nombre'];
} else {
header('Location: index.html?error=1');
exit();
}
}

verificarAutenticacion(); */
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Paciente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="position-sticky pt-3">
                    <h4 class="text-white text-center">Sistema de Citas</h4>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" href="dashboard.php">
                                Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="crear_cita.php">
                                Solicitar Cita
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="consultar_citas.php">
                                Mis Citas
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">
                                Cerrar Sesión
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Contenido principal -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Bienvenido, <?php echo $_SESSION['paciente_nombre']; ?></h1>
                </div>
                
                
                <div class="row">
                    <div class="col-md-4">
                        <div class="card text-white bg-primary mb-3">
                            <div class="card-body">
                                <h5 class="card-title">Citas Pendientes</h5>
                                <?php
                                $stmt = $pdo->prepare("SELECT COUNT(*) FROM citas WHERE paciente_id = ? AND estado = 'Pendiente'");
                                $stmt->execute([$_SESSION['paciente_id']]);
                                $count = $stmt->fetchColumn();
                                ?>
                                <h2 class="card-text"><?php echo $count; ?></h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-white bg-success mb-3">
                            <div class="card-body">
                                <h5 class="card-title">Citas Confirmadas</h5>
                                <?php
                                $stmt = $pdo->prepare("SELECT COUNT(*) FROM citas WHERE paciente_id = ? AND estado = 'Confirmada'");
                                $stmt->execute([$_SESSION['paciente_id']]);
                                $count = $stmt->fetchColumn();
                                ?>
                                <h2 class="card-text"><?php echo $count; ?></h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-white bg-warning mb-3">
                            <div class="card-body">
                                <h5 class="card-title">Total de Citas</h5>
                                <?php
                                $stmt = $pdo->prepare("SELECT COUNT(*) FROM citas WHERE paciente_id = ?");
                                $stmt->execute([$_SESSION['paciente_id']]);
                                $count = $stmt->fetchColumn();
                                ?>
                                <h2 class="card-text"><?php echo $count; ?></h2>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5>Últimas Citas</h5>
                    </div>
                    <div class="card-body">
                        <?php
                        $stmt = $pdo->prepare("SELECT * FROM citas WHERE paciente_id = ? ORDER BY fecha_creacion DESC LIMIT 5");
                        $stmt->execute([$_SESSION['paciente_id']]);
                        $citas = $stmt->fetchAll();
                        
                        if (count($citas) > 0) {
                            echo '<div class="table-responsive">';
                            echo '<table class="table table-striped">';
                            echo '<thead><tr><th>Tipo de Solicitud</th><th>Jornada</th><th>N° Trámite</th><th>Estado</th><th>Fecha</th></tr></thead>';
                            echo '<tbody>';
                            foreach ($citas as $cita) {
                                echo '<tr>';
                                echo '<td>' . htmlspecialchars($cita['tipo_solicitud']) . '</td>';
                                echo '<td>' . $cita['jornada'] . '</td>';
                                echo '<td>' . htmlspecialchars($cita['numero_tramite']) . '</td>';
                                echo '<td><span class="badge bg-' . ($cita['estado'] == 'Confirmada' ? 'success' : ($cita['estado'] == 'Pendiente' ? 'warning' : 'danger')) . '">' . $cita['estado'] . '</span></td>';
                                echo '<td>' . date('d/m/Y H:i', strtotime($cita['fecha_creacion'])) . '</td>';
                                echo '</tr>';
                            }
                            echo '</tbody></table></div>';
                        } else {
                            echo '<p class="text-muted">No tienes citas registradas.</p>';
                        }
                        ?>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>