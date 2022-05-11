<?php
require_once __DIR__.'/includes/config.php';


$formulario_registro = new  es\ucm\fdi\aw\usuarios\FormularioRegistroRoomie();
$html_form_registro = $formulario_registro->gestiona();



$tituloPagina = 'Registro';
$form_name = "form_registro_roomie";


$contenidoPrincipal = <<<EOF
    <div class='contenedor-principal'>
        <h1>$tituloPagina</h1>
        $html_form_registro
EOF;

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal];
$app->generaVista('/plantillas/plantilla_general.php', $params);
