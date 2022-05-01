<?php
require_once __DIR__.'/includes/config.php';

$tituloPagina = 'Editar Detalles Habitación';

$id_host = $app->idUsuario();

$id_habitacion = $app->getAtributoPeticion("id_habitacion");

if(es\ucm\fdi\aw\Habitacion::habitacionPerteneceAHost($id_host, $id_habitacion)){
    $formulario = new  es\ucm\fdi\aw\usuarios\FormularioEditDatosHabitacion();
    $html_form = $formulario->gestiona();
    $contenidoPrincipal = <<<EOF
        <div class='contenedor-principal'>
            <h1>$tituloPagina</h1>
            $html_form
        </div>
    EOF;
}

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal];
$app->generaVista('/plantillas/plantilla_general.php', $params);



