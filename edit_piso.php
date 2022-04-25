<?php
require_once __DIR__.'/includes/config.php';

$tituloPagina = 'Editar Detalles Piso';

$id_host = $app->idUsuario();

$id_piso = $app->getAtributoPeticion("id_piso");

if(es\ucm\fdi\aw\Piso::pisoPerteneceAHost($id_host, $id_piso)){
    $formulario = new  es\ucm\fdi\aw\usuarios\FormularioEditDatosPiso();
    $html_form = $formulario->gestiona();
    $contenidoPrincipal = <<<EOF
        <div class='pl-20p pr-20p pt-2e'>
            <h1>$tituloPagina</h1>
            $html_form
        </div>
    EOF;
}

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal];
$app->generaVista('/plantillas/plantilla_general.php', $params);



