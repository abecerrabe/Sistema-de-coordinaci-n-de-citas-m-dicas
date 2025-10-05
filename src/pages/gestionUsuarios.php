<?php
session_start();
require_once "../php/crud.php";
require_once "../php/getGestionUsuario.php";

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- NAV -->
            <?php include 'nav.php'; ?>

            <!-- CONTENIDO -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Gestión de Usuarios</h1>
                </div>

                <!-- Filtros y Crear -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header">

                        <div class="d-flex justify-content-between align-items-center mb-0">
                            <h5 class="mb-0">Búsqueda y Gestión de Usuarios</h5>
                        </div>
                    </div>
                    <div class="card-body">


                        <form method="POST" action="" class="row g-3">
                            <!-- Cedula -->
                            <div class="col-md-4">
                                <label for="cedula" class="form-label fw-bold">Cédula</label>
                                <input type="text" name="cedula" id="cedula" class="form-control"
                                    placeholder="Ingresa una cédula"
                                    value="<?= htmlspecialchars($_POST['cedula'] ?? '') ?>">
                            </div>

                            <!-- Nombre -->
                            <div class="col-md-4">
                                <label for="nombre" class="form-label fw-bold">Nombre</label>
                                <input type="text" name="nombre" id="nombre" class="form-control"
                                    placeholder="Ingresa un nombre"
                                    value="<?= htmlspecialchars($_POST['nombre'] ?? '') ?>">
                            </div>

                            <!-- Rol -->
                            <div class="col-md-4">
                                <label for="rol" class="form-label fw-bold">Rol</label>
                                <select name="rol" id="rol" class="form-select">
                                    <option value="">-- Selecciona un rol --</option>
                                    <option value="medico" <?= (($_POST['rol'] ?? '') == 'medico') ? 'selected' : '' ?>>Médico</option>
                                    <option value="paciente" <?= (($_POST['rol'] ?? '') == 'paciente') ? 'selected' : '' ?>>Paciente</option>
                                    <option value="administrador" <?= (($_POST['rol'] ?? '') == 'administrador') ? 'selected' : '' ?>>Administrador</option>
                                </select>
                            </div>

                            <!-- Botón -->
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary w-100">
                                    Filtrar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Tabla -->
                <div class="card shadow-sm">

                    <div class="card-header d-flex justify-content-between align-items-center mb-0">
                        <h5 class="mb-0">Usuarios</h5>
                        <a href="registroUsuario.php?accion=crear" class="btn btn-primary btn-sm fw-bold">
                            <i class="bi bi-plus-circle"></i> Crear Usuario
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light text-center">
                                    <tr>
                                        <th>Cédula</th>
                                        <th>Nombre</th>
                                        <th>Teléfono</th>
                                        <th>Correo</th>
                                        <th>Rol</th>
                                        <th>Acción</th>
                                    </tr>
                                </thead>
                                <tbody id="tablaUsuario">
                                    <?php if (!empty($usuarios)): ?>
                                        <?php foreach ($usuarios as $u): ?>
                                            <tr>
                                                <td class="text-center"><?= htmlspecialchars($u['numero_cedula']) ?></td>
                                                <td class="text-uppercase"><?= htmlspecialchars($u['nombre_completo']) ?></td>
                                                <td class="text-center"><?= htmlspecialchars($u['telefono']) ?></td>
                                                <td><?= htmlspecialchars($u['correo_electronico']) ?></td>
                                                <td class="text-uppercase text-center"><?= htmlspecialchars($u['tipo_permiso']) ?></td>
                                                <td class="text-center">
                                                    <a href="../php/usuario.php?accion=modificarUsuario&id=<?= $u['id'] ?>" class="btn btn-sm btn-outline-primary">
                                                        <i class="bi bi-pencil-square"></i> Editar
                                                    </a>
                                                    <a href="../php/usuario.php?accion=deleteUsuarios&id=<?= $u['id'] ?>"
                                                        class="btn btn-sm btn-outline-danger"
                                                        onclick="return confirm('¿Seguro que deseas eliminar este usuario?')">
                                                        <i class="bi bi-trash"></i> Eliminar
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="6" class="text-center py-4 fw-bold text-muted">
                                                No se encontraron usuarios
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</body>

</html>