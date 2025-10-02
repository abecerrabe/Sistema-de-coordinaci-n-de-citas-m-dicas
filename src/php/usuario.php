<?php

session_start();

require_once "rutas.php";
require_once "crud.php";

// Función para validar contraseña
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

// Función para preparar datos temporales de sesión
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

// Función para comprobar duplicados
function comprobarDuplicados($correo, $cedula) {
    return [
        "correo" => select("usuario", "id, correo_electronico", "correo_electronico='$correo' and estado= 'activo'"),
        "cedula" => select("usuario", "id, numero_cedula", "numero_cedula='$cedula' and estado= 'activo'")
    ];
}

// Función para obtener variable POST con valor por defecto
function post($key, $default = "") {
    return isset($_POST[$key]) && $_POST[$key] !== "" ? $_POST[$key] : $default;
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
    
    $passwordHash = !empty($contrasena) ? password_hash($contrasena, PASSWORD_DEFAULT) : "";

    $data = compact('cedula', 'nombre', 'telefono', 'correo', 'contrasena', 'estado', 'rol', 'id_cargo', 'horario_atencion');

    switch ($accion) {
        case "insertar":
            $ruta = empty($accionGestionar) ? $rutaInicio : $rutaGestionUsuario;

            $errores = validarContrasena($contrasena);
            if (!empty($errores)) {
                $_SESSION["error"] = "La contraseña debe contener: " . implode(", ", $errores) . ".";
                setDataTemp($data);
                header("Location: $rutaRegistroUsuario");
                exit();
            }

            $duplicados = comprobarDuplicados($correo, $cedula);
            foreach ($duplicados as $campo => $resultado) {
                if (count($resultado) > 0) {
                    $mensaje = $campo === "correo"
                        ? "El correo <span class='font-bold'>" . $correo . "</span> ya esta registrada."
                        : "La cedula <span class='font-bold'>" . $cedula . "</span> ya esta registrada.";
                    $_SESSION["error"] = $mensaje;
                    setDataTemp($data);
                    header("Location: $rutaRegistroUsuario");
                    exit();
                }
            }

            insert("usuario", [
                "numero_cedula"      => $cedula,
                "nombre_completo"    => $nombre,
                "telefono"           => $telefono,
                "correo_electronico" => $correo,
                "password_usuario"   => $passwordHash,
                "estado"             => $estado,
                "tipo_permiso"       => $rol,
            ], $rol != "medico" ? "$ruta" : "");

            if ($rol == "medico") {
                $usuariosRegistrado = select("usuario", "id", "correo_electronico='$correo'");
                $id_usuario = $usuariosRegistrado[0]["id"];
                insert("medico", [
                    "id_usuario"       => $id_usuario,
                    "id_cargo"         => $id_cargo,
                    "horario_atencion" => $horario_atencion,
                ], $ruta);
            }
            unset($_SESSION["dataTemp"]);
            break;

        case "modificar":
            $id = post('id');
            if (!empty($contrasena)) {
                $errores = validarContrasena($contrasena);
                if (!empty($errores)) {
                    $_SESSION["error"] = "⚠️ La contraseña debe contener: " . implode(", ", $errores) . ".";
                    setDataTemp($data);
                    header("Location: $rutaGestionUsuario");
                    exit();
                }
                $passwordHash = password_hash($contrasena, PASSWORD_DEFAULT);
            }

            $_SESSION["id"] = $id;
            $_SESSION["numero_cedula"] = $cedula;
            $_SESSION["nombre_completo"] = $nombre;
            $_SESSION["tipo_permiso"] = $rol;
            $_SESSION["estado"] = $estado;

            $datosActualizar = [
                "numero_cedula"     => $cedula,
                "nombre_completo"   => $nombre,
                "telefono"          => $telefono,
                "correo_electronico" => $correo,
                "estado"            => $estado,
                "tipo_permiso"      => $rol
            ];
            if (!empty($contrasena)) {
                $datosActualizar["contrasena"] = $passwordHash;
            }

            if ($rol == "medico") {
                $datosMedicosActualizar = [
                    "id_cargo"  => $id_cargo,
                    "horario_atencion " => $horario_atencion,
                ];
                $datosMedicos = select(
                    "usuario 
                    inner join medico on usuario.id = medico.id_usuario
                    inner join cargo on medico.id_cargo = cargo.id",
                    "usuario.*, medico.horario_atencion, cargo.nombre_cargo",
                    "usuario.id='$id' and usuario.estado = 'activo' and usuario.tipo_permiso = 'medico'"
                );
                if (empty($datosMedicos)) {
                    insert("medico", [
                        "id_usuario"       => $id,
                        "id_cargo"        => $id_cargo,
                        "horario_atencion" => $horario_atencion,
                    ]);
                }
                update("medico", $datosMedicosActualizar, "id_usuario ='$id'");
            }

            update("usuario", $datosActualizar, "id='$id'", $rutaGestionUsuario);
            break;

        default:
            echo "No se recibió ninguna acción válida de POST.";
    }
} else {
    $accion = $_GET["accion"];
    $id = $_GET["id"];

    switch ($accion) {
        case 'modificarUsuario':
            $usuarios = select("usuario", "*", "id='$id'");
            $_SESSION["dataTemp"] = $usuarios[0];
            if ($usuarios[0]['tipo_permiso'] == 'medico') {
                $datosMedicos = select(
                    "usuario 
                    inner join medico on usuario.id = medico.id_usuario
                    inner join cargo on medico.id_cargo = cargo.id",
                    "usuario.*, medico.horario_atencion, cargo.id as id_cargo",
                    "usuario.id='$id' and usuario.estado = 'activo' and usuario.tipo_permiso = 'medico'"
                );
                if (empty($datosMedicos)) {
                    insert("medico", [
                        "id_usuario"       => $id,
                        "id_cargo"         => post('id_cargo'),
                        "horario_atencion" => post('horario_atencion'),
                    ], $ruta);
                }
                $_SESSION["dataTemp"] = $datosMedicos[0];
            }
            header("Location: $rutaRegistroUsuario?id=" . $id);
            exit();
            break;
        case 'deleteUsuarios':
            update(
                "usuario",
                ["estado"     => 'inactivo'],
                "id ='$id'",
                $rutaGestionUsuario
            );
            break;
        default:
            echo "No se recibió ninguna acción válida de GET.";
            break;
    }
}