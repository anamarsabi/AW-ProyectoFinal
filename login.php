<?php

require_once __DIR__.'/includes/config.php';

$tituloPagina = 'Inicio sesión';

$formulario_login =  new \es\ucm\fdi\aw\usuarios\FormularioLogin();

$contenidoPrincipal = $formulario_login->gestiona();

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal];
$app->generaVista('/plantillas/plantilla_general.php', $params);




