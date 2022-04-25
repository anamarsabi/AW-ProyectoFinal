<?php
require_once __DIR__.'/includes/config.php';

$tituloPagina = 'Registro piso';

$formulario_registro = new  es\ucm\fdi\aw\usuarios\FormularioRegistroPiso();

$html_form_registro = $formulario_registro->gestiona();

$contenidoPrincipal = <<<EOF
    <div class='pl-20p pr-20p pt-5e'>
        <h1>$tituloPagina</h1>
        $html_form_registro
    </div>
EOF;


$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal];
$app->generaVista('/plantillas/plantilla_general.php', $params);



