<?php
session_start();

require_once "rutas.php";
require_once "crud.php";

// 🔹 Validar contraseña
function validarContrasena($contrasena) {
    $errores = [];
    if (strlen($contrasena) < 8) {
        $errores[] = "al menos 8 caracteres";
    }
    if (!preg_match('/[A-Z]/', $contrasena)) {
        $errores[] = "una letra mayúscula";
    }
    if (!preg_match('/[!@#$%^&*(),.?\":{}|<>]/', $contrasena)) {
        $errores[] = "un carácter especial (!@#$%^&*(),.?\":{}|<>)";
    }
    return $errores;
}

// 🔹 Guardar datos temporales en sesión
function setDataTemp($data) {
    $_SESSION["dataTemp"] = [
        "numero_cedula"      => $data['cedula'],
        "nombre_completo"    => $data['nombre'],
        "telefono"           => $data['telefono'],
        "correo_electronico" => $data['correo'],
        "contrasena"         => $data['contrasena'],
        "estado"             => $data['estado'],
        "tipo_permiso"       => $data['rol'],
        "id_cargo"           => $data['id_cargo'],
        "horario_atencion"   => $data['horario_atencion'],
    ];
}

// 🔹 Comprobar duplicados
function comprobarDuplicados($correo, $cedula) {
    return [
        "correo" => select("usuario", "id, correo_electronico", "correo_electronico = ? AND estado = 'activo'", [$correo]),
        "cedula" => select("usuario", "id, numero_cedula", "numero_cedula = ? AND estado = 'activo'", [$cedula])
    ];
}
function actualizarUsuario ($id, $cedula, $nombre, $correo, $rol, $estado){

    $_SESSION["id"] = $id;
    $_SESSION["numero_cedula"] = $cedula;
    $_SESSION["nombre_completo"] = $nombre;
    $_SESSION["usuario"] = $correo;
    $_SESSION["tipo_permiso"] = $rol;
    $_SESSION["estado"] = $estado; 
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $accion = post('accion');
    $cedula = post('cedula');
    $nombre = post('nombre');
    $telefono = post('telefono');
    $correo = post('correo');
    $contrasena = post('contrasena');
    $estado = post('estado', 'activo');
    $rol = post('rol', 'Paciente');
    $id_cargo = post('id_cargo');
    $horario_atencion = post('horario_atencion');
    $accionGestionar = post('accionGestionar');
    $usuario = post('usuario');
    $password = post('password');

    $passwordHash = !empty($contrasena) ? password_hash($contrasena, PASSWORD_DEFAULT) : "";
    $data = compact('cedula', 'nombre', 'telefono', 'correo', 'contrasena', 'estado', 'rol', 'id_cargo', 'horario_atencion');

    switch ($accion) {
        case "insertar":
            $ruta = empty($accionGestionar) ? $rutaInicio : $rutaGestionUsuario;

            // validar contraseña
            $errores = validarContrasena($contrasena);
            if (!empty($errores)) {
                $_SESSION["error"] = "La contraseña debe contener: " . implode(", ", $errores) . ".";
                setDataTemp($data);
                header("Location: $rutaRegistroUsuario");
                exit();
            }

            // validar duplicados
            $duplicados = comprobarDuplicados($correo, $cedula);
            foreach ($duplicados as $campo => $resultado) {
                if (count($resultado) > 0) {
                    $mensaje = $campo === "correo"
                        ? "El correo <span class='font-bold'>" . $correo . "</span> ya está registrado."
                        : "La cédula <span class='font-bold'>" . $cedula . "</span> ya está registrada.";
                    $_SESSION["error"] = $mensaje;
                    setDataTemp($data);
                    header("Location: $rutaRegistroUsuario");
                    exit();
                }
            }

            // insertar usuario
            insert("usuario", [
                "numero_cedula"      => $cedula,
                "nombre_completo"    => $nombre,
                "telefono"           => $telefono,
                "correo_electronico" => $correo,
                "password_usuario"   => $passwordHash,
                "estado"             => $estado,
                "tipo_permiso"       => $rol,
            ], $rol != "medico" ? $ruta : null);

            // si es medico → insertar en tabla medico
            if ($rol == "medico") {
                $usuariosRegistrado = select("usuario", "id", "correo_electronico = ?", [$correo]);
                if (!empty($usuariosRegistrado)) {
                    $id_usuario = $usuariosRegistrado[0]["id"];
                    insert("medico", [
                        "id_usuario"       => $id_usuario,
                        "id_cargo"         => $id_cargo,
                        "horario_atencion" => $horario_atencion,
                    ], $ruta);
                }
            }
            unset($_SESSION["dataTemp"]);
            break;

        case "modificar":
            $id = post('id');

            if (!empty($contrasena)) {
                $errores = validarContrasena($contrasena);
                if (!empty($errores)) {
                    $_SESSION["error"] = "⚠️La contraseña debe contener: " . implode(", ", $errores) . ".";
                    setDataTemp($data);
                    header("Location: $rutaGestionUsuario");
                    exit();
                }
                $passwordHash = password_hash($contrasena, PASSWORD_DEFAULT);
            }

            $datosActualizar = [
                "numero_cedula"      => $cedula,
                "nombre_completo"    => $nombre,
                "telefono"           => $telefono,
                "correo_electronico" => $correo,
                "estado"             => $estado,
                "tipo_permiso"       => $rol
            ];
            if (!empty($contrasena)) {
                $datosActualizar["password_usuario"] = $passwordHash;
            }

            if ($rol == "medico") {
                $datosMedicosActualizar = [
                    "id_cargo"          => $id_cargo,
                    "horario_atencion " => $horario_atencion,
                ];

                $datosMedicos = select(
                    "usuario 
                     INNER JOIN medico ON usuario.id = medico.id_usuario
                     INNER JOIN cargo ON medico.id_cargo = cargo.id",
                    "usuario.*, medico.horario_atencion, cargo.nombre_cargo",
                    "usuario.id = ? AND usuario.estado = 'activo' AND usuario.tipo_permiso = 'medico'",
                    [$id]
                );

                if (empty($datosMedicos)) {
                    insert("medico", [
                        "id_usuario"       => $id,
                        "id_cargo"         => $id_cargo,
                        "horario_atencion" => $horario_atencion,
                    ]);
                } else {
                    update("medico", $datosMedicosActualizar, "id_usuario = '$id'");
                }
            }
            actualizarUsuario($id, $cedula, $nombre,$correo, $rol, $estado);
            update("usuario", $datosActualizar, "id = '$id'", $rutaGestionUsuario);
            break;

        case "login":
            $usuarios = select("usuario", "*", "correo_electronico = ? AND estado = 'activo'", [$usuario]);

            if (count($usuarios) > 0) {
                $u = $usuarios[0];

                if (password_verify($password, $u["password_usuario"])) {
                    actualizarUsuario($u["id"], $u["numero_cedula"], $u["nombre_completo"], $u["correo_electronico"], $u["tipo_permiso"], $u["estado"]);

                    header("Location: ../pages/$rutaDashboard");
                    exit();
                } else {
                    $_SESSION["error"] = "La contraseña es incorrecta";
                    $_SESSION["correoTemp"] = $usuario;
                    header("Location: $rutaInicio");
                    exit();
                }
            } else {
                $_SESSION["error"] = "El correo no existe";
                $_SESSION["correoTemp"] = $usuario;
                header("Location: $rutaInicio");
                exit();
            }
            break;

        default:
            echo "No se recibió ninguna acción válida de POST.";
    }
} else {
    $accion = $_GET["accion"] ?? null;
    $id = $_GET["id"] ?? null;

    switch ($accion) {
        case 'modificarUsuario':
            $usuarios = select("usuario", "*", "id = ?", [$id]);
            if (!empty($usuarios)) {
                $_SESSION["dataTemp"] = $usuarios[0];

                if ($usuarios[0]['tipo_permiso'] == 'medico') {
                    $datosMedicos = select(
                        "usuario 
                         INNER JOIN medico ON usuario.id = medico.id_usuario
                         INNER JOIN cargo ON medico.id_cargo = cargo.id",
                        "usuario.*, medico.horario_atencion, cargo.id as id_cargo",
                        "usuario.id = ? AND usuario.estado = 'activo' AND usuario.tipo_permiso = 'medico'",
                        [$id]
                    );

                    if (empty($datosMedicos)) {
                        insert("medico", [
                            "id_usuario"       => $id,
                            "id_cargo"         => post('id_cargo'),
                            "horario_atencion" => post('horario_atencion'),
                        ]);
                    } else {
                        $_SESSION["dataTemp"] = $datosMedicos[0];
                    }
                }
            }
            header("Location: $rutaRegistroUsuario?id=" . $id);
            exit();
            break;

        case 'deleteUsuarios':
            update(
                "usuario",
                ["estado" => 'inactivo'],
                "id = '$id'",
                $rutaGestionUsuario
            );
            break;

        default:
            echo "No se recibió ninguna acción válida de GET.";
            break;
    }
}
