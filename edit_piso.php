<?php
require_once __DIR__.'/includes/config.php';

$tituloPagina = 'Editar Detalles Piso';

$id_host = $app->idUsuario();

$id_piso = $app->getAtributoPeticion("id_piso");

if(es\ucm\fdi\aw\Piso::pisoPerteneceAHost($id_host, $id_piso)){
    $formulario = new  es\ucm\fdi\aw\usuarios\FormularioEditDatosPiso();
    $html_form = $formulario->gestiona();
    
    $imagenes = es\ucm\fdi\aw\Imagen::printImagenes_idPiso($id_piso, true);

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



