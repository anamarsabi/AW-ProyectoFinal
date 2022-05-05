<?php
require_once __DIR__.'/includes/config.php';

$tituloPagina = 'Editar Detalles Piso';

$id_host = $app->idUsuario();

$id_piso = $app->getAtributoPeticion("id_piso");
$app->putAtributoPeticion("id_piso", $id_piso);

if(es\ucm\fdi\aw\Piso::pisoPerteneceAHost($id_host, $id_piso)){
    $formulario = new  es\ucm\fdi\aw\usuarios\FormularioEditDatosPiso();
    $html_form = $formulario->gestiona();
    $url_redireccion = '/edit_piso.php';

    $datos = [
        'id'=>$id_piso,
        'url_redireccion'=>$url_redireccion,
        'delForm'=>true,
        'tabla'=>'imagenes_pisos',
        'entidad'=>'id_piso'
    ];

    $imagenes = es\ucm\fdi\aw\Imagen::getHTMLImagenes($datos);

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



