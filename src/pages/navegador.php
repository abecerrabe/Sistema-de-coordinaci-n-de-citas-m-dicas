<?php
session_start();

include_once '../php/rutas.php';
// Protección básica si no existe usuario redirecciona al login
/* if (!isset($_SESSION['usuario']) || (isset($_SESSION['estado']) && $_SESSION['estado'] != "activo" )) {
    header("Location: $rutaInicio");
    exit();
} */

    
$usuarioNombre = $_SESSION['usuario'];
$idUsuario = $_SESSION['id'];
$rol = $_SESSION['rol'];
?>
<nav class="bg-white shadow">
    <div class="max-w-full mx-auto px-4 flex justify-between h-16 items-center">

        <!-- IZQUIERDA: LOGO + MENÚ -->
        <div class="flex items-center space-x-8">
            <div class="text-lg font-bold text-blue-600">
                <a href=<?php echo $rutaDashboard ?>>Sistema de Citas</a>
            </div>

            <!-- CITAS -->
            <div class="relative">
                <button onclick="toggleMenu('menu-citas')" class="text-gray-700 hover:text-blue-600 font-medium focus:outline-none">
                    Citas
                </button>
                <div id="menu-citas" class="absolute hidden bg-white shadow rounded mt-2 w-40 z-10">
                    <?php if ($rol === 'paciente'): ?>
                        <a href=<?php $rutaCrearCitas?> class="block px-4 py-2 hover:bg-gray-100">Crear</a>
                    <?php endif; ?>
                    <?php if ($rol === 'paciente' || $rol === 'administrador'): ?>
                        <a href=<?php echo $rutaDashboard ?> class="block px-4 py-2 hover:bg-gray-100">Visualizar</a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- HISTORIAL -->
            <div class="relative">
                <button onclick="toggleMenu('menu-historial')" class="text-gray-700 hover:text-blue-600 font-medium focus:outline-none">
                    Historial
                </button>
                <div id="menu-historial" class="absolute hidden bg-white shadow rounded mt-2 w-40 z-10">
                    <?php if ($rol === 'medico'): ?>
                        <a href=<?php $rutaDiagnostico ?> class="block px-4 py-2 hover:bg-gray-100">Diagnóstico</a>
                    <?php endif; ?>
                    <a href=<?php $rutaHistorial ?> class="block px-4 py-2 hover:bg-gray-100">Visualizar</a>
                </div>
            </div>
        </div>

        <!-- DERECHA: PERFIL / LOGOUT -->
        <div class="flex items-center space-x-4">
            <div class="relative">
                <button onclick="toggleMenu('menu-perfil')" class="text-gray-700 hover:text-blue-600 font-medium focus:outline-none">
                    <?php echo $usuarioNombre; ?> (<?php echo ucfirst($rol); ?>)
                </button>
                <div id="menu-perfil" class="absolute hidden bg-white shadow rounded mt-2 w-xs right-0 z-10">
                    <a href="<?php echo $rutaUsuarioPHP . '?accion=modificarUsuario&id=' . $idUsuario; ?>" class="block px-4 py-2 hover:bg-gray-100">Configuración</a>
                    <?php if ($rol === 'administrador'): ?>
                        <a href=<?php echo $rutaGestionUsuario?> class="block px-4 py-2 hover:bg-gray-100">Gestionar usuarios</a>
                    <?php endif; ?>
                    <button id="btnLogout"  data-url="<?php echo $rutalogoutPHP; ?>" class="block w-full text-left px-4 py-2 hover:bg-gray-100 text-red-600">Cerrar sesión</button>
                </div>
            </div>
        </div>
    </div>
</nav>
<script src="../js/menu.js" defer></script>