<?php
session_start();
require_once "../php/crud.php";
require_once "../php/getGestionCargos.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion de cargos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- NAV -->
            <?php include 'nav.php'; ?>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Gestión de Cargos</h1>
                </div>
                <!-- Filtros y Crear -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header">

                        <div class="d-flex justify-content-between align-items-center mb-0">
                            <h5 class="mb-0">Búsqueda y Gestión de Cargos</h5>
                        </div>
                    </div>
                    <div class="card-body">

                        <form method="POST" action="" class="row g-2">
                            
                            <!-- Nombre -->
                            <div class="col-md-4">
                                <label for="nombre_cargo" class="form-label fw-bold">Nombre del cargo</label>
                                <input type="text" name="nombre_cargo" id="nombre_cargo" class="form-control"
                                    placeholder="Ingresa una nombre"
                                    value="<?= htmlspecialchars($_POST['nombre_cargo'] ?? '') ?>">
                            </div>
                            <!-- Descripcion de cargos -->
                            <div class="col-md-8">
                                <label for="descripcion_cargo" class="form-label fw-bold">Descripción del cargo</label>
                                <input type="text" name="descripcion_cargo" id="descripcion_cargo" class="form-control"
                                    placeholder="Ingrese descripción del cargo"
                                    value="<?= htmlspecialchars($_POST['descripcion_cargo'] ?? '') ?>">
                            </div>



                            <!-- Botón -->
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <div class="d-flex align-items-end">
                                    <button type="submit" name="filtrar" class="btn btn-primary w-100">Filtrar</button>
                                </div>
                                <div class="d-flex align-items-end">
                                    <button type="submit" name="limpiar" class="btn btn-secondary w-100">Limpiar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Tabla -->
                <div class="card shadow-sm">

                    <div class="card-header d-flex justify-content-between align-items-center mb-0">
                        <h5 class="mb-0">Cargos</h5>
                        <a href=<?= htmlspecialchars($rutaRegistrarCargos) . "?accion=crearCargo"; ?> class="btn btn-primary btn-sm fw-bold">
                            <i class="bi bi-plus-circle"></i> Crear Cargos
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light text-center">
                                    <tr>
                                        <th>Nombre del cargo</th>
                                        <th>Descripción de cargos</th>
                                        <th>Acción</th>
                                    </tr>
                                </thead>
                                <tbody id="tablaCargos">
                                    <?php if (!empty($cargos)): ?>
                                        <?php foreach ($cargos as $cargo): ?>
                                            <tr>
                                                <td class="text-uppercase text-center"><?= htmlspecialchars($cargo['nombre_cargo']) ?></td>
                                                <td class="text-uppercase"><?= htmlspecialchars($cargo['descripcion_cargo']) ?></td>
                                                <td class="text-center">
                                                    <a href="<?= $rutaCargosPHP ?>?accion=modificarCargo&id=<?= $cargo['id'] ?>" class="btn btn-sm btn-outline-primary">
                                                        <i class="bi bi-pencil-square"></i> Editar
                                                    </a>
                                                    <a href="<?= $rutaCargosPHP ?>?accion=deleteCargos&id=<?= $cargo['id'] ?>"
                                                        class="btn btn-sm btn-outline-danger"
                                                        onclick="return confirm('¿Seguro que deseas eliminar este cargo?')">
                                                        <i class="bi bi-trash"></i> Eliminar
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="6" class="text-center py-4 fw-bold text-muted">
                                                No se encontrarón cargos
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

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</body>

</html>