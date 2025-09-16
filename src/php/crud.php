<?php
require_once "conexion.php";  // incluye la conexión

//Función de ayuda para redirigir
function redirect($url) {
    if (!headers_sent()) {
        header("Location: $url");
        exit();
    } else {
        echo "<script>window.location.href='$url';</script>";
        exit();
    }
}

//Insertar datos
function insert($tabla, $datos, $redirectTo = null) {
    global $conn;
    $campos = implode(", ", array_keys($datos));
    $valores = "'" . implode("','", array_values($datos)) . "'";
    $sql = "INSERT INTO $tabla ($campos) VALUES ($valores)";
    
    if ($conn->query($sql) === TRUE) {
        if ($redirectTo) redirect($redirectTo);
        return true;
    } else {
        echo "Error al insertar: " . $conn->error;
        return false;
    }
}

//Actualizar datos
function update($tabla, $datos, $condicion, $redirectTo = null) {
    global $conn;
    $set = [];
    foreach ($datos as $campo => $valor) {
        $set[] = "$campo='$valor'";
    }
    $set = implode(", ", $set);
    $sql = "UPDATE $tabla SET $set WHERE $condicion";
    //echo "<br>".$sql;
    if ($conn->query($sql) === TRUE) {
        if ($redirectTo) redirect($redirectTo);
        return true;
    } else {
        echo "Error al actualizar: " . $conn->error;
        return false;
    }
}

//Eliminar datos
function delete($tabla, $condicion, $redirectTo = null) {
    global $conn;
    $sql = "DELETE FROM $tabla WHERE $condicion";

    if ($conn->query($sql) === TRUE) {
        if ($redirectTo) redirect($redirectTo);
        return true;
    } else {
        echo "Error al eliminar: " . $conn->error;
        return false;
    }
}

//Consultar datos
function select($tabla, $campos="*", $condicion="1") {
    global $conn;
    $sql = "SELECT $campos FROM $tabla WHERE $condicion";
    //echo "<br>".$sql."<br>";
    $result = $conn->query($sql);
    $datos = [];
    if ($result && $result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $datos[] = $row;
        }
    }
    return $datos;
}
?>