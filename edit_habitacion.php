<?php
require_once __DIR__.'/includes/config.php';

$tituloPagina = 'Editar Detalles Habitación';

$id_host = $app->idUsuario();

$id_habitacion = $app->getAtributoPeticion("id_habitacion");
$app->putAtributoPeticion("id_habitacion", $id_habitacion);

if(es\ucm\fdi\aw\Habitacion::habitacionPerteneceAHost($id_host, $id_habitacion)){
    $formulario = new  es\ucm\fdi\aw\usuarios\FormularioEditDatosHabitacion();
    $url_redireccion = '/edit_habitacion.php';
    $imagenes = es\ucm\fdi\aw\Imagen::getHTMLImagenesPorIdEntidad($id_habitacion, $url_redireccion, true);

    $html_form = $formulario->gestiona();

    $contenidoPrincipal = <<<EOF
        <div class='contenedor-principal'>
            <h1>$tituloPagina</h1>
            $html_form
            <div class="pt-5e">
                $imagenes
            </div>
        </div>
    EOF;
}

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal];
$app->generaVista('/plantillas/plantilla_general.php', $params);



