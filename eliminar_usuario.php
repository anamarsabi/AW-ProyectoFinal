<?php
require_once __DIR__.'/includes/config.php';

$tituloPagina = 'Eliminar Usuario';

$id_usuario = $app->getAtributoPeticion("id_usuario");
$app->putAtributoPeticion("id_usuario", $id_usuario);
$usuario = es\ucm\fdi\aw\usuarios\Usuario::borraPorId($id_usuario);

    $url_redireccion = '/mi_perfil.php';

    $datos = [
        'id'=>$id_usuario,
        'url_redireccion'=>$url_redireccion,
        'delForm'=>true,
    ];

    $contenidoPrincipal = <<<EOF
        <div class='contenedor-principal'>
            <h1>$tituloPagina</h1>
            Se ha eliminado el usuario    
        </div>
    EOF;

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal];
$app->generaVista('/plantillas/plantilla_general.php', $params);



