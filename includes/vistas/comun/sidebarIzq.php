<?php
use es\ucm\fdi\aw\Aplicacion;

$app = Aplicacion::getInstance();


if ($app->usuarioLogueado()){
    switch($app->getContexto()){
        case "mi_perfil.php":
            $app->doinclude("vistas/comun/detalles_perfil.php");
            break;
        case "mis_pisos.php":
            if($app->tieneRol(es\ucm\fdi\aw\usuarios\Usuario::ADMIN_ROLE)){
                $app->doinclude("vistas/comun/admin_pisos.php");
            }else{
                $app->doinclude("vistas/comun/detalles_mis_pisos.php");
            }
            break;
        case "mis_habitaciones.php":
            $app->doinclude("vistas/comun/detalles_mis_habitaciones.php");
            break;
    }   
}

?>


