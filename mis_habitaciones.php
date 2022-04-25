<?php

require_once __DIR__.'/includes/config.php';

$tituloPagina = 'Mis habitaciones';

$paths = ["vistas/contenidos/contenido_mis_habitaciones.php"];

$vista->carga_contenido($paths);

$contenidoPrincipal = $vista->get_contenido();

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal];

$app->setContexto("mis_habitaciones.php");

$app->generaVista('/plantillas/plantilla_general.php', $params);


