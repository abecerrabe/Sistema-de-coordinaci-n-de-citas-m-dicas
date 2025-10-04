<?php
require_once "conexion.php"; // tu archivo de conexión mysqli $conn

// 🔹 Redirigir de forma segura
function redirect($url) {
    if (!headers_sent()) {
        header("Location: $url");
        exit();
    } else {
        echo "<script>window.location.href='$url';</script>";
        exit();
    }
}

// 🔹 Insertar datos
function insert($tabla, $datos, $redirectTo = null) {
    global $conn;

    $campos = array_keys($datos);
    $placeholders = implode(", ", array_fill(0, count($datos), "?"));
    $sql = "INSERT INTO $tabla (" . implode(", ", $campos) . ") VALUES ($placeholders)";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Error en la preparación (insert): " . $conn->error);
    }

    $tipos = str_repeat("s", count($datos));
    $valores = array_values($datos);

    $stmt->bind_param($tipos, ...$valores);
    $ok = $stmt->execute();

    if (!$ok) {
        echo "Error al insertar: " . $stmt->error;
        return false;
    }

    $stmt->close();

    if ($redirectTo) redirect($redirectTo);
    return true;
}

// 🔹 Actualizar datos
function update($tabla, $datos, $condicion, $redirectTo = null) {
    global $conn;

    $campos = array_keys($datos);
    $set = implode(", ", array_map(fn($c) => "$c = ?", $campos));
    $sql = "UPDATE $tabla SET $set WHERE $condicion";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Error en la preparación (update): " . $conn->error);
    }

    $tipos = str_repeat("s", count($datos));
    $valores = array_values($datos);

    $stmt->bind_param($tipos, ...$valores);
    $ok = $stmt->execute();

    if (!$ok) {
        echo "Error al actualizar: " . $stmt->error;
        return false;
    }

    $stmt->close();

    if ($redirectTo) redirect($redirectTo);
    return true;
}

// 🔹 Eliminar datos (soft delete recomendado, pero dejo físico si lo necesitas)
function delete($tabla, $condicion, $redirectTo = null) {
    global $conn;

    $sql = "DELETE FROM $tabla WHERE $condicion";
    $ok = $conn->query($sql);

    if (!$ok) {
        echo "Error al eliminar: " . $conn->error;
        return false;
    }

    if ($redirectTo) redirect($redirectTo);
    return true;
}

// 🔹 Consultar datos
function select($tabla, $campos="*", $condicion="1", $params = [], $types = "") {
    global $conn;

    $sql = "SELECT $campos FROM $tabla WHERE $condicion";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Error en la preparación (select): " . $conn->error);
    }

    if (!empty($params)) {
        if ($types === "") {
            $types = str_repeat("s", count($params));
        }
        $stmt->bind_param($types, ...$params);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    $datos = [];
    while ($row = $result->fetch_assoc()) {
        $datos[] = $row;
    }

    $stmt->close();
    return $datos;
}
function lastID($tabla, $campo = "id") {
    global $conn;

    // Usamos prepared statements para evitar inyección
    $sql = "SELECT MAX($campo) AS ultimo_id FROM $tabla";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Error al preparar la consulta: " . $conn->error);
    }

    $stmt->execute();
    $resultado = $stmt->get_result();

    $row = $resultado->fetch_assoc();
    $ultimoID = $row['ultimo_id'] ?? 0;

    $stmt->close();

    return $ultimoID;
}


// 🔹 POST helper
function post($key, $default = "") {
    return isset($_POST[$key]) && $_POST[$key] !== "" ? $_POST[$key] : $default;
}
?>

