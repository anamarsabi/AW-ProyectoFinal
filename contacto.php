<?php

require_once __DIR__.'/includes/config.php';
use es\ucm\fdi\aw\usuarios\FormularioContacto;

$tituloPagina = 'Contacto piso | Roomie';

$formulario_contacto = new es\ucm\fdi\aw\usuarios\FormularioContacto();
$html = $formulario_contacto->gestiona();

$contenidoPrincipal = <<<EOS
    <div class="contenedor-principal">
        <img id="contacto-center" src='img/foro_meme.jpg' width="300" alt='https://memecreator.org/static/images/memes/5457775.jpg' />
    </div>
EOS;


$contenidoPrincipal .= $html;


$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal];
$app->generaVista('/plantillas/plantilla_general.php', $params);
