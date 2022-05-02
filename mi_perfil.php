<?php
require_once __DIR__.'/includes/config.php';

$tituloPagina = 'Mi perfil';

$email = $app->correo();

$paths = ["vistas/contenidos/contenido_perfil.php"];

$vista->carga_contenido($paths);

$contenidoPrincipal = $vista->get_contenido();

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal];
$app->setContexto("mi_perfil.php");
$app->generaVista('/plantillas/plantilla_general.php', $params);

