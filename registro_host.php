<?php
require_once __DIR__.'/includes/config.php';

$tituloPagina = 'Registro';

$formulario_registro = new  es\ucm\fdi\aw\usuarios\FormularioRegistroHost();
$html_form_registro = $formulario_registro->gestiona();

$contenidoPrincipal = <<<EOF
    <div class='pl-20p pr-20p pt-2e'>
        <h1>$tituloPagina</h1>
        $html_form_registro
    </div>
EOF;


$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal];
$app->generaVista('/plantillas/plantilla_general.php', $params);



