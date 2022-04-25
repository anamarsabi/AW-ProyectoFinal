<?php
use es\ucm\fdi\aw\Aplicacion;

$app = Aplicacion::getInstance();


if ($app->usuarioLogueado()){
    switch($app->getContexto()){
        case "mi_perfil.php":
            $app->doinclude("vistas/comun/detalles_perfil.php");
            break;
        case "mis_pisos.php":
            $app->doinclude("vistas/comun/detalles_mis_pisos.php");
            break;
        case "mis_habitaciones.php":
            $app->doinclude("vistas/comun/detalles_mis_habitaciones.php");
            break;
    }   
}

?>


