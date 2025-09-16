<?php

session_start();

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

    $passwordHash = password_hash($contrasena, PASSWORD_DEFAULT);

    switch ($accion) {
        case "insertar":
            
            //Rutas Validas
            $ruta = '../pages/gestionUsuarios.php';
            $rutaInicio = '../../index.php';
            $rutaGestionUsuario = '../pages/registroUsuario.php';

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
                    "contrasena"=> $contrasena,
                    "estado"    => $estado,
                    "rol"       => $rol
                ];
                header("Location: $rutaGestionUsuario");
                exit();
            }

            // Validar si ya existe un usuario con ese correo
            $usuarios = select("usuarios", "*", "correo='$correo'");
            
            if (count($usuarios) > 0) {
                $_SESSION["error"] = "El correo <span class='font-bold'>" . $correo . "</span> ya existe.";
                $_SESSION["dataTemp"] = [
                    "cedula"    => $cedula,
                    "nombre"    => $nombre,
                    "telefono"  => $telefono,
                    "correo"    => $correo,
                    "contrasena"=> $contrasena,
                    "estado"    => $estado,
                    "rol"       => $rol
                ];
                header("Location: $rutaGestionUsuario");
                exit();
            }

            // Si pasa todas las validaciones, insertar usuario
            insert("usuarios", [
                "cedula"    => $cedula,
                "nombre"    => $nombre,
                "telefono"  => $telefono,
                "correo"    => $correo,
                "contrasena"=> $passwordHash,
                "estado"    => $estado,
                "rol"       => $rol
            ], $rutaInicio);

            unset($_SESSION["dataTemp"]);
            break;

        default:
            echo "No se recibió ninguna acción válida.";
    }
}
