<?php

require_once __DIR__.'/includes/config.php';
use es\ucm\fdi\aw\usuarios\FormularioContacto;

$tituloPagina = 'Contacto piso';

$formulario_contacto = new es\ucm\fdi\aw\usuarios\FormularioContacto();
$html = $formulario_contacto->gestiona();


$contenidoPrincipal ="";
$contenidoPrincipal .= $html;
#$app->delBusqueda();

#$contenidoPrincipal .= ob_get_clean();

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal];
$app->generaVista('/plantillas/plantilla_general.php', $params);
