<?php
require_once __DIR__.'/includes/config.php';

$tituloPagina = 'Roomie - Inicio';

$paths = ["vistas/contenidos/contenido_index.php"];

$vista->carga_contenido($paths);

$contenidoPrincipal = $vista->get_contenido();

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal];

$app->setContexto("index.php");

$app->generaVista('/plantillas/plantilla_general.php', $params);


