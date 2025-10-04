<?php
//PAGES
$rutaInicio = '../../index.php';
$rutaDashboard = 'dashboard.php';
$rutaGestionUsuario = '../pages/gestionUsuarios.php';
$rutaRegistroUsuario = '../pages/registroUsuario.php';

$rutaCrearCitas = '../pages/crearCita.php';
$rutaConsultarCitas = '../pages/consultarCitas.php';
$rutaDiagnostico = 'diagnostico.php';
$rutaHistorial = 'historial.php';

// PHP
$rutaUsuarioPHP = '../php/usuario.php';
$rutalogoutPHP = '../php/logout.php';
$rutaCitasPHP = '../php/citas.php';

//Nombres Pginas

$paginaDashboard = 'dashboard.php';
$paginaGestionarUsuario = 'gestionUsuarios.php';
$paginaCrearCita = 'crearCita.php';
$paginaConsultarCitas = 'consultarCitas.php';

function verificarAutenticacion($usuario,$estado, $ruta)
{

    if (!isset($usuario) && (!isset($estado) && $estado == 'activo')) {
        header("Location: $ruta");
    }
}
