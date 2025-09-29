<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="../js/validacionRol.js"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center h-screen">

    <div class="bg-white p-8 rounded-2xl shadow-lg w-full max-w-2xl">
        <h1 class="text-2xl font-bold text-center text-blue-700 mb-6">Registro de Usuario</h1>

        <?php
        if (isset($_SESSION["error"])): ?>
            <p class="text-red-600 font-semibold mb-4"><?php echo $_SESSION["error"]; ?></p>
            <?php unset($_SESSION["error"]); ?>
        <?php endif; ?>

        <form id="formRegistro" action="../php/usuario.php" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <?php if (isset($_GET['accion']) && $_GET['accion'] == 'crear'): ?>
                <input type="hidden" name="accionGestionar" value="crear">
            <?php endif; ?>
            
            <!-- Cedula -->
            <div class="md:col-span-1 col-span-2">
                <label for="cedula" class="block mb-1 font-medium">Cédula:</label>
                <input type="number" id="cedula" name="cedula"
                    placeholder="Cédula del usuario"
                    value="<?php echo isset($_SESSION['dataTemp']['cedula']) ? $_SESSION['dataTemp']['cedula'] : ''; ?>"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400" required>
            </div>
            <!-- Nombre -->
            <div class="md:col-span-1 col-span-2">
                <label for="nombre" class="block mb-1 font-medium">Nombre:</label>
                <input type="text" id="nombre" name="nombre"
                    placeholder="Nombre del usuario"
                    value="<?php echo isset($_SESSION['dataTemp']['nombre']) ? $_SESSION['dataTemp']['nombre'] : ''; ?>"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400" required>
            </div>
            <!-- Teléfono -->
            <div class="md:col-span-1 col-span-2">
                <label for="telefono" class="block mb-1 font-medium">Teléfono:</label>
                <input type="number" id="telefono" name="telefono"
                    placeholder="Teléfono del usuario"
                    value="<?php echo isset($_SESSION['dataTemp']['telefono']) ? $_SESSION['dataTemp']['telefono'] : ''; ?>"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400" required>
            </div>
            <!-- Correo -->
            <div class="md:col-span-1 col-span-2">
                <label for="correo" class="block mb-1 font-medium">Correo:</label>
                <input type="email" id="correo" name="correo"
                    placeholder="Correo electrónico"
                    value="<?php echo isset($_SESSION['dataTemp']['correo']) ? $_SESSION['dataTemp']['correo'] : ''; ?>"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400" required>
            </div>
            <!-- Contraseña -->
            <div class="col-span-2">
                <label for="contrasena" class="block mb-1 font-medium">Contraseña:</label>
                <div class="relative">
                    <?php
                    // Si existe $_GET["id"], no es required
                    $contrasenaRequired = !(isset($_GET["id"])) ? 'required' : '';
                    ?>
                    <input type="password" id="contrasena" name="contrasena"
                        placeholder="Contraseña"
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400 pr-10" <?php echo $contrasenaRequired; ?>>
                    <button type="button" onclick="togglePassword()"
                        class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-600">
                        <i id="toggleIcon" class="fa-solid fa-eye-slash"></i>
                    </button>
                </div>
            </div>
            <!-- Rol -->
            <div class="col-span-2">
                <label for="rol" class="block mb-1 font-medium">Rol:</label>
                <select name="rol" id="rol" required
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400">
                    <option class="text-gray-600" value="">-- Selecciona un rol --</option>
                    <option value="paciente" <?php echo (isset($_SESSION['dataTemp']['rol']) && $_SESSION['dataTemp']['rol'] === 'paciente') ? 'selected' : 'selected'; ?>>Paciente</option>
                    <option value="medico" <?php echo (isset($_SESSION['dataTemp']['rol']) && $_SESSION['dataTemp']['rol'] === 'medico') ? 'selected' : ''; ?>>Médico</option>
                    <option value="admin" <?php echo (isset($_SESSION['dataTemp']['rol']) && $_SESSION['dataTemp']['rol'] === 'admin') ? 'selected' : ''; ?>>Administrador</option>
                </select>
            </div>
            <!-- Estado -->
            <?php if (isset($_GET["id"])): ?>
                <div class="col-span-2">
                    <label for="estado" class="block mb-1 font-medium">Estado:</label>
                    <select name="estado" id="estado" required
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400">
                        <option class="text-gray-600" value="">-- Selecciona un estado --</option>
                        <option value="activo" <?php echo (isset($_SESSION['dataTemp']['estado']) && $_SESSION['dataTemp']['estado'] === 'activo') ? 'selected' : ''; ?>>Activo</option>
                        <option value="inactivo" <?php echo (isset($_SESSION['dataTemp']['estado']) && $_SESSION['dataTemp']['estado'] === 'inactivo') ? 'selected' : ''; ?>>Inactivo</option>
                    </select>
                </div>
            <?php endif; ?>
            <!-- Especialidad (solo médicos) -->
            <div id="especialidad-container" style="display:none;" class="col-span-2 space-y-4">
                <label for="especialidad" class="block mb-1 font-medium">Especialidad:</label>
                <select name="id_especialidad" id="especialidad"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400">
                    <option class="text-gray-600" value="">-- Selecciona una especialidad --</option>
                </select>
                <!-- Horario -->
                <div class="col-span-2 pt-4">
                    <label for="horario_atencion" class="block mb-1 font-medium">Horario de atención:</label>
                    <select name="horario_atencion" id="horario_atencion"
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400">
                        <option class="text-gray-600" value="">-- Selecciona una horario --</option>
                    </select>
                </div>
            </div>
            <!-- Botón -->
            <?php
            // Determinar si es edición o registro
            $esEdicion = (isset($_GET["id"]) && $_GET["id"] !== "Registrar");
            ?>
            <input type="hidden" name="accion" value="<?php echo $esEdicion ? 'modificar' : 'insertar'; ?>">
            <input type="hidden" name="id" value="<?php echo $esEdicion ? (isset($_SESSION['dataTemp']['id']) ? $_SESSION['dataTemp']['id'] : '') : ''; ?>">
            <div class="col-span-2">
                <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-xl hover:bg-blue-700 transition">
                    <?php echo $esEdicion ? 'Actualizar' : 'Regístrate'; ?>
                </button>
            </div>
        </form>
    </div>
</body>

</html>