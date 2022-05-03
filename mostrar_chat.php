<?php

require_once __DIR__.'/includes/config.php';

use es\ucm\fdi\aw\Chat;

$tituloPagina = 'Chat';

$chat = $app->getChat();


$contenidoPrincipal ="";

$contenidoPrincipal .= $formulario_busqueda->gestiona();



$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal];
$app->generaVista('/plantillas/plantilla_general.php', $params);
