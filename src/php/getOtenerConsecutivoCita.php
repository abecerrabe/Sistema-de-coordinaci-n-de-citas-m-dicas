<?php

require_once "crud.php";

function obtenerConsecutivo($id_paciente) {
    $anio_actual = date('Y');
    $like = "TRAM-$anio_actual-%";

    $data = select(
        "cita",
        "numero_tramite",
        "id_paciente = ? AND numero_tramite LIKE ? ORDER BY id DESC LIMIT 1",
        [$id_paciente, $like],
        "is"
    );

    if ($data && !empty($data[0]['numero_tramite'])) {
        $ultimo = $data[0]['numero_tramite'];
        if (preg_match('/TRAM-\d{4}-(\d+)/', $ultimo, $matches)) {
            return intval($matches[1]) + 1;
        }
    }

    return 1;
}

function generarNumeroTramite($consecutivo) {
    $anio_actual = date('Y');
    return sprintf("TRAM-%04d-%03d", $anio_actual, $consecutivo);
}