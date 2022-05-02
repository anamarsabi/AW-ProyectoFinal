<?php

use es\ucm\fdi\aw\usuarios\FormularioBusqueda;


$formulario_busqueda = new FormularioBusqueda();
$form = $formulario_busqueda->gestiona();

$contenido = <<<EOS
    <div class="index-container input-group rounded">
        <img class ="index-bg" src="img/index_bg.jpg" alt="index_banner_bg_img" />
       
        <div class="search-container">
            
            <div class="align-center  index-banner-bg">
            <h1>Encuentra tu habitación ideal</h1>
                $form
            </div>
        </div>
       
    </div>

EOS;



