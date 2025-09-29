<?php
require_once "../php/crud.php";
$where = "estado = 'activo'";
$params = [];

// Si recibimos POST aplicamos filtros
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (!empty($_POST['cedula'])) {
        $cedula = $_POST['cedula'];
        $where .= " AND cedula LIKE '%$cedula%'";
    }
    if (!empty($_POST['nombre'])) {
        $nombre = $_POST['nombre'];
        $where .= " AND nombre LIKE '%$nombre%'";
    }

    if (!empty($_POST['rol'])) {
        $rol = $_POST['rol'];
        $where .= " AND rol = '$rol'";
    }
}

$usuarios = select("usuarios", "*", $where, $params);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios </title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen p-6">

    <!-- NAV -->

    <!-- CONTENIDO -->
    <main class="flex-1 w-full mx-auto pt-6">
        <div class="bg-white p-4 rounded-lg shadow mb-6">
            <h1 class="text-2xl font-bold text-blue-700 mb-6">Gestión de Usuarios</h1>
            <div class="pb-4">

                <a href="registroUsuario.php?accion=crear"
                    class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                    + Crear Usuario
                </a>
            </div>
            <form method="POST" action="" class="grid grid-cols-1 gap-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pb-4">
                    <!-- Cedula -->
                    <div>
                        <label for="cedula" class="block mb-1 font-semibold">Cedula</label>
                        <input name="cedula" id="cedula" placeholder="Ingresa un cedula" value="<?= $_POST['cedula'] ?? '' ?>"
                            class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500" />
                    </div>
                    <!-- Nombre -->
                    <div>
                        <label for="nombre" class="block mb-1 font-semibold">Nombre</label>
                        <input name="nombre" id="nombre" placeholder="Ingresa un nombre" value="<?= $_POST['nombre'] ?? '' ?>"
                            class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500" />
                    </div>

                    <!-- ROLES -->
                    <div>
                        <label for="rol" class="block mb-1 font-semibold">Roles</label>
                        <select name="rol" id="rol"
                            class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                            <option value="">-- Selecciona un rol --</option>
                            <option value="medico" <?= (($_POST['rol'] ?? '') == 'medico') ? 'selected' : '' ?>>Médico</option>
                            <option value="paciente" <?= (($_POST['rol'] ?? '') == 'paciente') ? 'selected' : '' ?>>Paciente</option>
                            <option value="admin" <?= (($_POST['rol'] ?? '') == 'admin') ? 'selected' : '' ?>>Administrador</option>
                        </select>
                    </div>
                </div>

                <!-- Boton -->
                <div>
                    <button type="submit"
                        class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition">
                        Filtrar
                    </button>
                </div>
            </form>
        </div>

        <div class="bg-white p-4 rounded-lg shadow">
            <h2 class="text-lg font-semibold mb-4">Usuarios</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full border">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border px-4 py-2 text-left">Cedula</th>
                            <th class="border px-4 py-2 text-left">Nombre</th>
                            <th class="border px-4 py-2 text-left">Telefono</th>
                            <th class="border px-4 py-2 text-left">Correo</th>
                            <th class="border px-4 py-2 text-left">Rol</th>
                            <th class="border px-4 py-2">Acción</th>
                        </tr>
                    </thead>
                    <tbody id="tablaUsuario">
                        <?php if (!empty($usuarios)): ?>
                            <?php foreach ($usuarios as $u): ?>
                                <tr class="border-b">
                                    <td class="p-3 uppercase"><?= htmlspecialchars($u['cedula']) ?></td>
                                    <td class="p-3 uppercase"><?= htmlspecialchars($u['nombre']) ?></td>
                                    <td class="p-3"><?= htmlspecialchars($u['telefono']) ?></td>
                                    <td class="p-3"><?= htmlspecialchars($u['correo']) ?></td>
                                    <td class="p-3 uppercase"><?= htmlspecialchars($u['rol']) ?></td>
                                    <td class="p-3 flex gap-2 justify-center">
                                        <a href="../php/usuario.php?accion=modificarUsuario&id=<?= $u['id'] ?>"
                                            class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600">Modificar</a>
                                        <a href="../php/usuario.php?accion=deleteUsuarios&id=<?= $u['id'] ?>"
                                            class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700"
                                            onclick="return confirm('¿Seguro que deseas eliminar este usuario?')">Eliminar</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center py-4">No se encontraron usuarios</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </main>
</body>

</html>