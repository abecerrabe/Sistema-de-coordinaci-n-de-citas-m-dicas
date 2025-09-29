<?php

session_start();

require_once "rutas.php";
require_once "crud.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $accion = isset($_POST['accion']) ? $_POST['accion'] : "";

    $cedula   =  isset($_POST['cedula']) ? $_POST['cedula'] : "";
    $nombre   =  isset($_POST['nombre']) ? $_POST['nombre'] : "";
    $telefono = isset($_POST['telefono']) ? $_POST['telefono'] : "";
    $correo   = isset($_POST['correo']) ? $_POST['correo'] : "";
    $contrasena = isset($_POST['contrasena']) ? $_POST['contrasena'] : "";
    $estado   = isset($_POST['estado']) ? $_POST['estado'] : "activo";
    $rol      = isset($_POST['rol']) ? $_POST['rol'] : "Paciente";
    $accionGestionar   = isset($_POST['accionGestionar']) ? $_POST['accionGestionar'] : "";

    $passwordHash = password_hash($contrasena, PASSWORD_DEFAULT);

    switch ($accion) {
        case "insertar":

            //Rutas Validas
            $ruta = empty($accionGestionar) ? $rutaInicio : $rutaGestionUsuario;
            
            

            // Validar contraseña con regex: mínimo 8 caracteres, 1 mayúscula y 1 carácter especial
            $errores = [];

            if (strlen($contrasena) < 8) {
                $errores[] = "al menos 8 caracteres";
            }
            if (!preg_match('/[A-Z]/', $contrasena)) {
                $errores[] = "una letra mayúscula";
            }
            if (!preg_match('/[!@#$%^&*(),.?":{}|<>]/', $contrasena)) {
                $errores[] = "un carácter especial (!@#$%^&*(),.?\":{}|<>)";
            }

            if (!empty($errores)) {
                $_SESSION["error"] = "La contraseña debe contener: " . implode(", ", $errores) . ".";
                $_SESSION["dataTemp"] = [
                    "cedula"    => $cedula,
                    "nombre"    => $nombre,
                    "telefono"  => $telefono,
                    "correo"    => $correo,
                    "contrasena" => $contrasena,
                    "estado"    => $estado,
                    "rol"       => $rol
                ];
                header("Location: $rutaRegistroUsuario");
                exit();
            }

            // Validar si ya existe un usuario con ese correo o cedula
            $duplicados = [
                "correo" => select("usuarios", "id, correo", "correo='$correo' and estado= 'activo'"),
                "cedula" => select("usuarios", "id, cedula", "cedula='$cedula' and estado= 'activo'")
            ];

            foreach ($duplicados as $campo => $resultado) {

                if (count($resultado) > 0) {
                    $mensaje = $campo === "correo"
                        ? "El correo <span class='font-bold'>" . $correo . "</span> ya esta registrada."
                        : "La cedula <span class='font-bold'>" . $cedula . "</span> ya esta registrada.";

                    $_SESSION["error"] = $mensaje;
                    $_SESSION["dataTemp"] = [
                        "cedula"    => $cedula,
                        "nombre"    => $nombre,
                        "telefono"  => $telefono,
                        "correo"    => $correo,
                        "contrasena"=> $contrasena,
                        "estado"    => $estado,
                        "rol"       => $rol
                    ];
                    header("Location: $rutaRegistroUsuario");
                    exit();
                }
            }

            // Si pasa todas las validaciones, insertar usuario
            insert("usuarios", [
                "cedula"    => $cedula,
                "nombre"    => $nombre,
                "telefono"  => $telefono,
                "correo"    => $correo,
                "contrasena" => $passwordHash,
                "estado"    => $estado,
                "rol"       => $rol
            ], $rol != "medico" ? "$ruta" : "");

            unset($_SESSION["dataTemp"]);
            break;

        case "modificar":
            // Recoger el id del usuario a modificar
            $id = isset($_POST['id']) ? $_POST['id'] : "";

            // Si se envía una nueva contraseña, validar y hashear
            if (!empty($contrasena)) {
                $errores = [];
                if (strlen($contrasena) < 8) {
                    $errores[] = "al menos 8 caracteres";
                }
                if (!preg_match('/[A-Z]/', $contrasena)) {
                    $errores[] = "una letra mayúscula";
                }
                if (!preg_match('/[!@#$%^&*(),.?":{}|<>]/', $contrasena)) {
                    $errores[] = "un carácter especial (!@#$%^&*(),.?\":{}|<>)";
                }
                if (!empty($errores)) {
                    $_SESSION["error"] = "⚠️ La contraseña debe contener: " . implode(", ", $errores) . ".";
                    $_SESSION["dataTemp"] = [
                        "cedula"     => $cedula,
                        "nombre"     => $nombre,
                        "telefono"   => $telefono,
                        "correo"     => $correo,
                        "contrasena" => $contrasena,
                        "estado"     => $estado,
                        "rol"        => $rol
                    ];
                    header("Location: $rutaGestionUsuario");
                    exit();
                }
                $passwordHash = password_hash($contrasena, PASSWORD_DEFAULT);
            }

            //Esto debe ir cuando el usuario se logeo
            $_SESSION["id"] = $id;
            $_SESSION["cedula"] = $cedula;
            $_SESSION["usuario"] = $nombre;
            $_SESSION["rol"] = $rol;
            $_SESSION["estado"] = $estado;

            // Preparar los datos a actualizar
            $datosActualizar = [
                "cedula"     => $cedula,
                "nombre"     => $nombre,
                "telefono"   => $telefono,
                "correo"     => $correo,
                "estado"     => $estado,
                "rol"        => $rol
            ];
            $datosMedicosActualizar = [
                "id_especialidad "  => $id_especialidad,
                "horario_atencion " => $horario_atencion,
            ];
            if (!empty($contrasena)) {
                $datosActualizar["contrasena"] = $passwordHash;
            }

            if ($rol == "medico") {
                update("medico", $datosMedicosActualizar, "id_usuario ='$id'", $rutaGestionUsuario);
            }
            update("usuarios", $datosActualizar, "id='$id'", $rutaGestionUsuario);
            break;
        default:
            echo "No se recibió ninguna acción válida de POST.";
    }
} else {
    //Parametros
    $accion = $_GET["accion"];
    $id = $_GET["id"];

    switch ($accion) {
        case 'modificarUsuario':
            //Consulta de usuario para modificar 
            $usuarios = select("usuarios", "*", "id='$id'");

            $_SESSION["dataTemp"] = $usuarios[0];
            /* if ($usuarios[0]['rol'] == 'medico') {
                $datosMedicos = select(
                    "usuarios inner join medicos on usuarios.id = medicos.id_usuario",
                    "usuarios.*, medicos.horario_atencion as turnos, medicos.id_especialidad",
                    "usuarios.id='$id'and usuarios.estado = 'activo'"
                );

                $_SESSION["dataTemp"] = $datosMedicos[0];
            } */
            header("Location: $rutaRegistroUsuario?id=" . $id);
            exit();
            break;
        case 'deleteUsuarios':
            update(
                "usuarios",
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
