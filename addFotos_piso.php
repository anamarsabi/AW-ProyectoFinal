<?php
require_once __DIR__.'/includes/config.php';
use es\ucm\fdi\aw\Imagen;

$tituloPagina = 'Añadir Fotos Piso';

$id_host = $app->idUsuario();

$id_piso = $app->getAtributoPeticion("id_piso");

$html_imagenes_piso = Imagen::getHTMLImagenesPorIdEntidad($id_piso);

if(es\ucm\fdi\aw\Piso::pisoPerteneceAHost($id_host, $id_piso)){
    $formUpload = new es\ucm\fdi\aw\usuarios\FormularioUpload($id_piso);
    $htmlFormUpload = $formUpload->gestiona();
    $contenidoPrincipal = <<<EOF
        <div class='contenedor-principal'>
            <h1>$tituloPagina</h1>
            $htmlFormUpload
            $html_imagenes_piso
        </div>

    EOF;
}

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal];
$app->generaVista('/plantillas/plantilla_general.php', $params);

