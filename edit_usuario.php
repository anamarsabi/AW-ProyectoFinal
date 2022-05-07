<?php
require_once __DIR__.'/includes/config.php';

$tituloPagina = 'Editar Usuario';

$id_usuario = $app->getAtributoPeticion("id_usuario");
$app->putAtributoPeticion("id_usuario", $id_usuario);

    $formulario = new  es\ucm\fdi\aw\usuarios\FormularioEditDatosUsuario();
    $html_form = $formulario->gestiona();
    $url_redireccion = '/edit_usuario.php';

    $datos = [
        'id'=>$id_usuario,
        'url_redireccion'=>$url_redireccion,
        'delForm'=>true,
    ];

    $contenidoPrincipal = <<<EOF
        <div class='contenedor-principal'>
            <h1>$tituloPagina</h1>
            $html_form
            
        </div>
    EOF;

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal];
$app->generaVista('/plantillas/plantilla_general.php', $params);



