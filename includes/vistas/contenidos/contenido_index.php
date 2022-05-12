<?php

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\usuarios\Usuario;
use es\ucm\fdi\aw\Vista;
use es\ucm\fdi\aw\usuarios\FormularioBusqueda;


$app = Aplicacion::getInstance();
$contenido = "";

if($app->usuarioLogueado()){
    if($app->tieneRol(Usuario::HOST_ROLE) || $app->tieneRol(Usuario::ROOMIE_ROLE)){
        $paths =["vistas/comun/barra_busqueda.php"];
        $vista = Vista::getInstance();
        $vista->carga_contenido($paths);

        $contenido = $vista->get_contenido();
    }
}
else{
    $paths = ["vistas/comun/barra_busqueda.php", "vistas/comun/info_index.php", "vistas/comun/bloque_registro.php"];
    
    $vista = Vista::getInstance();
    $vista->carga_contenido($paths);

    $contenido = $vista->get_contenido();
}