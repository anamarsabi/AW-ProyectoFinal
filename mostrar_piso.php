<?php

require_once __DIR__.'/includes/config.php';
#use es\ucm\fdi\aw\usuarios\FormularioBusqueda;
use es\ucm\fdi\aw\Piso;
#use es\ucm\fdi\aw\Aplicacion;

$tituloPagina = 'Piso';

#$id_piso = $app->getAtributoPeticion("id_piso");

$piso_session = $app->getPiso();
$piso = Piso::buscaPorId($piso_session->getId());


#$app = Aplicacion::getInstance();
#$id_piso = $app->getAtributoPeticion("id_piso");

#$piso = Piso::buscaPorId($id_piso);

$contenidoPrincipal ="";
if ($app->usuarioLogueado()) {
    $contenidoPrincipal .= $piso->imprimirDetalles();
}
else
{
    $loginUrl = $app->resuelve('/login.php');
    $contenidoPrincipal .= <<<EOS
        <div class="centrado align-center">
            <p>Usuario desconocido, por favor inicie sesión: <a href="{$loginUrl}">Login</a> </p>
        </div>
    EOS;
}
#$app->delBusqueda();

#$contenidoPrincipal .= ob_get_clean();

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal];
$app->generaVista('/plantillas/plantilla_general.php', $params);

#include 'includes/templates/plantilla_general.php';