<?php

require_once __DIR__.'/includes/config.php';


$tituloPagina = 'Resultados de la busqueda';

$paths = ["vistas/contenidos/contenido_mostrar_busqueda.php"];

$vista->carga_contenido($paths);

$contenidoPrincipal = $vista->get_contenido();

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal];
$app->generaVista('/plantillas/plantilla_general.php', $params);

#include 'includes/templates/plantilla_general.php';