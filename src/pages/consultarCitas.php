<?php
session_start();
require_once "../php/rutas.php";
require_once "../php/citas.php";

// ✅ Llamamos al listar, pero esta vez no mandamos JSON, sino que lo usamos para la vista
$_GET['accion'] = "listar";
ob_start();
include "../php/citas.php";
$output = ob_get_clean();
$citas = json_decode($output, true);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Citas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- NAV -->
            <?php include 'nav.php'; ?>

            <!-- CONTENIDO -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Mis Citas</h1>
                </div>

                <!-- Alertas -->
                <?php if (isset($_GET['success'])): ?>
                    <div class="alert alert-success">✅ Cita creada exitosamente.</div>
                <?php endif; ?>
                <?php if (isset($_GET['updated'])): ?>
                    <div class="alert alert-info">✏️ Cita actualizada correctamente.</div>
                <?php endif; ?>
                <?php if (isset($_GET['deleted'])): ?>
                    <div class="alert alert-danger">🗑️ Cita eliminada correctamente.</div>
                <?php endif; ?>

                <!-- Filtros -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="bi bi-funnel"></i> Filtros de búsqueda</h5>
                    </div>
                    <div class="card-body">
                        <form method="get" action="consultar_citas.php" class="row g-3">
                            <div class="col-md-4">
                                <label for="filtro_estado" class="form-label">Estado</label>
                                <select class="form-select" id="filtro_estado" name="filtro_estado">
                                    <option value="">Todos</option>
                                    <option value="Pendiente" <?= ($_GET['filtro_estado'] ?? '') == 'Pendiente' ? 'selected' : '' ?>>Pendiente</option>
                                    <option value="Confirmada" <?= ($_GET['filtro_estado'] ?? '') == 'Confirmada' ? 'selected' : '' ?>>Confirmada</option>
                                    <option value="Cancelada" <?= ($_GET['filtro_estado'] ?? '') == 'Cancelada' ? 'selected' : '' ?>>Cancelada</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="filtro_jornada" class="form-label">Jornada</label>
                                <select class="form-select" id="filtro_jornada" name="filtro_jornada">
                                    <opti